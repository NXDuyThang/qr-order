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
        if (Session::has('access_token') || \Illuminate\Support\Facades\Auth::check()) {
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

        // 1. Thử đăng nhập bằng local database (bằng email hoặc name)
        $credentialsEmail = ['email' => $request->username, 'password' => $request->password];
        $credentialsName = ['name' => $request->username, 'password' => $request->password];

        if (\Illuminate\Support\Facades\Auth::attempt($credentialsEmail, $request->has('remember')) ||
            \Illuminate\Support\Facades\Auth::attempt($credentialsName, $request->has('remember'))) {
            
            $localUser = \Illuminate\Support\Facades\Auth::user();
            
            if ($localUser->is_admin || in_array($localUser->role, ['manager', 'admin'])) {
                return redirect()->route('filament.admin.pages.dashboard');
            } elseif ($localUser->role === 'chef') {
                return redirect()->route('chef.dashboard');
            } elseif ($localUser->role === 'waiter') {
                return redirect()->route('waiter.dashboard');
            } else {
                // Khách hàng / User thường đăng nhập thành công qua local DB
                Session::put('user_info', [
                    'id' => $localUser->id,
                    'name' => $localUser->name,
                    'email' => $localUser->email,
                    'avatar' => $localUser->avatar_url,
                    'phone' => $localUser->phone,
                ]);
                if ($localUser->avatar_url) {
                    Session::put('user_avatar', $localUser->avatar_url);
                }

                $targetUrl = session()->get('url.intended');
                if (!$targetUrl && session('table_id')) {
                    $targetUrl = route('order_at_table');
                }
                if (!$targetUrl) {
                    $targetUrl = route('profile.index');
                }
                return redirect()->intended($targetUrl)->with('success', 'Đăng nhập thành công.');
            }
        }

        // 2. Thử đăng nhập qua API NKS nếu local DB không khớp
        try {
            $response = $this->apiService->login(
                $request->username,
                $request->password
            );

            if (isset($response['access_token']) || (isset($response['data']) && isset($response['data']['access_token']))) {
                $token = $response['access_token'] ?? $response['data']['access_token'];
                $userInfo = $response['User Info'] ?? ($response['data']['User Info'] ?? ($response['data'] ?? null));

                Session::put('access_token', $token);
                if ($userInfo) {
                    $filteredUserInfo = [
                        'id' => $userInfo['id'] ?? null,
                        'name' => $userInfo['name'] ?? null,
                        'email' => $userInfo['email'] ?? null,
                        'avatar' => $userInfo['avatar'] ?? null,
                        'phone' => $userInfo['phone'] ?? null,
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
                        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                    ]
                );
                
                // Cập nhật lại mật khẩu và thông tin mới từ API
                $localUser->name = $name;
                $localUser->password = \Illuminate\Support\Facades\Hash::make($request->password);
                if (isset($userInfo['avatar'])) {
                    $localUser->avatar_url = $userInfo['avatar'];
                }
                if (isset($userInfo['phone']) && !empty($userInfo['phone'])) {
                    $localUser->phone = $userInfo['phone'];
                }
                $localUser->save();

                // Đăng nhập bằng Laravel Auth
                \Illuminate\Support\Facades\Auth::login($localUser);

                if ($localUser->is_admin || in_array($localUser->role, ['manager', 'admin'])) {
                    return redirect()->route('filament.admin.pages.dashboard');
                } elseif ($localUser->role === 'chef') {
                    return redirect()->route('chef.dashboard');
                } elseif ($localUser->role === 'waiter') {
                    return redirect()->route('waiter.dashboard');
                }

                $targetUrl = session()->get('url.intended');
                if (!$targetUrl && session('table_id')) {
                    $targetUrl = route('order_at_table');
                }
                if (!$targetUrl) {
                    $targetUrl = route('profile.index');
                }
                return redirect()->intended($targetUrl)->with('success', 'Đăng nhập thành công.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Login Exception: ' . $e->getMessage());
        }

        // Đăng nhập thất bại
        return back()->with('error', 'Đăng nhập không thành công. Vui lòng kiểm tra lại tài khoản hoặc mật khẩu!')->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        $tableId = session('table_id');
        \Illuminate\Support\Facades\Auth::logout();
        Session::forget('access_token');
        Session::forget('user_info');
        Session::forget('user_avatar');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($tableId) {
            session(['table_id' => $tableId]);
        }
        
        return redirect()->route('welcome')->with('success', 'Đăng xuất thành công.');
    }
}
