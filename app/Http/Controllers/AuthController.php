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

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

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
            $userInfo = $response['User Info'] ?? ($response['data']['User Info'] ?? null);

            Session::put('access_token', $token);
            if ($userInfo) {
                Session::put('user_info', $userInfo);
            }

            return redirect()->route('profile.index')->with('success', 'Đăng nhập thành công.');
        }

        // Handle error
        $errorMessage = $response['message'] ?? 'Tài khoản hoặc mật khẩu không đúng.';
        $debugInfo = json_encode($response);
        return back()->with('error', "{$errorMessage} | Debug: {$debugInfo}")->withInput($request->only('username'));
    }

    public function logout()
    {
        Session::forget('access_token');
        Session::forget('user_info');
        
        return redirect()->route('welcome')->with('success', 'Đăng xuất thành công.');
    }
}
