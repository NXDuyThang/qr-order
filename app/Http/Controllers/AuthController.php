<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NksApiService;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $apiService;

    public function __construct(NksApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function showLoginForm()
    {
        // If already logged in, redirect to profile
        if (Session::has('access_token')) {
            return redirect()->route('profile.index');
        }

        // Lưu lại trang trước đó nếu người dùng đang ở trang đặt món (quét QR)
        $previous = url()->previous();
        if (str_contains($previous, '/order') || str_contains($previous, '/checkout')) {
            session()->put('url.intended', $previous);
        } else {
            // Đảm bảo xóa url intended cũ nếu vào trực tiếp trang login
            session()->forget('url.intended');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Thử đăng nhập bằng local database cho admin
        if (\Illuminate\Support\Facades\Auth::attempt(['email' => $request->username, 'password' => $request->password], $request->has('remember'))) {
            $localUser = \Illuminate\Support\Facades\Auth::user();
            if ($localUser->is_admin) {
                return redirect()->route('filament.admin.pages.dashboard');
            } else {
                \Illuminate\Support\Facades\Auth::logout();
            }
        }

        $response = $this->apiService->login(
            $request->username,
            $request->password
        );

        // Assuming the API returns something like:
        // { "User Info": { ... }, "access_token": "..." } 
        // or { "status": "success", "data": { "access_token": "..." } }
        // We will check for access_token or an error.
        
        // Let's inspect the response to find the token.
        // Based on the docs, output has: "User Info", "access_token"
        if (isset($response['access_token']) || (isset($response['data']) && isset($response['data']['access_token']))) {
            $token = $response['access_token'] ?? $response['data']['access_token'];
            $userInfo = $response['User Info'] ?? ($response['data']['User Info'] ?? ($response['data'] ?? null));

            Session::put('access_token', $token);
            if ($userInfo) {
                // Prevent large data from bloating the session cookie
                // Chỉ lưu những thông tin thật sự cần thiết (tránh lỗi vượt quá giới hạn 4KB của cookie)
                $filteredUserInfo = [
                    'id' => $userInfo['id'] ?? null,
                    'name' => $userInfo['name'] ?? null,
                    'email' => $userInfo['email'] ?? null,
                    'avatar' => $userInfo['avatar'] ?? null,
                ];
                Session::put('user_info', $filteredUserInfo);
                if (isset($userInfo['avatar'])) {
                    Session::put('user_avatar', $userInfo['avatar']);
                }
            }

            // Sync user to local database so Laravel Auth works
            $email = $userInfo['email'] ?? ($request->username . '@nks.local');
            $name = $userInfo['name'] ?? trim(($userInfo['firstname'] ?? '') . ' ' . ($userInfo['lastname'] ?? ''));
            if (empty($name)) {
                $name = $request->username;
            }

            $localUser = \App\Models\User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                ]
            );
            
            // Cập nhật lại tên nếu lúc trước bị lưu nhầm thành email (hoặc API có tên mới)
            if (!empty($name) && $name !== $request->username && str_contains($localUser->name, '@')) {
                $localUser->name = $name;
            }
            // Hoặc luôn luôn update theo thông tin API mới nhất
            $localUser->name = $name;

            if (isset($userInfo['avatar'])) {
                $localUser->avatar_url = $userInfo['avatar'];
            }
            $localUser->save();

            // Đăng nhập bằng Laravel Auth
            \Illuminate\Support\Facades\Auth::login($localUser);

            if ($localUser->is_admin) {
                return redirect()->route('filament.admin.pages.dashboard');
            }

            return redirect()->intended(route('profile.index'))->with('success', 'Đăng nhập thành công.');
        }

        // Handle error
        return back()->with('error', 'Đăng nhập không thành công')->withInput($request->only('username'));
    }

    public function logout()
    {
        Session::forget('access_token');
        Session::forget('user_info');
        
        return redirect()->route('welcome')->with('success', 'Đăng xuất thành công.');
    }
}
