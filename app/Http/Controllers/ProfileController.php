<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NksApiService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $apiService;

    public function __construct(NksApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    protected function checkAuth()
    {
        if (!Session::has('access_token')) {
            abort(redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để tiếp tục.'));
        }
    }

    public function index()
    {
        $this->checkAuth();
        $token = Session::get('access_token');
        
        // Fetch User Info to get latest data
        $userResponse = $this->apiService->getUser($token);
        
        // Ensure we handle different possible response structures
        $userInfo = $userResponse['User Info'] ?? ($userResponse['data'] ?? []);
        
        // If API doesn't return properly, we might fall back to session
        if (empty($userInfo) && Session::has('user_info')) {
            $userInfo = Session::get('user_info');
        } elseif (!empty($userInfo)) {
            // Update session with the latest user info (including avatar)
            Session::put('user_info', $userInfo);
        }

        $provincesResp = $this->apiService->getProvinces(237);
        $provinces = array_filter($provincesResp['data'] ?? [], function($prov) {
            $title = $prov['title'] ?? $prov['name'] ?? '';
            return strpos($title, 'Tỉnh ') === 0 || strpos($title, 'Thành phố ') === 0 || strpos($title, 'Thủ đô ') === 0;
        });

        $userAdministratives = [];
        $userProvinceId = $userInfo['province'] ?? $userInfo['add_province'] ?? null;
        if ($userProvinceId) {
            $adminResp = $this->apiService->getAdministratives($userProvinceId);
            $userAdministratives = $adminResp['data'] ?? [];
        }

        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $wishlists = \App\Models\Wishlist::with('food.category')->where('user_id', $user->id)->get();
            $foods = $wishlists->map(function ($wishlist) {
                return $wishlist->food;
            })->filter();
        } else {
            $foods = collect();
        }

        return view('profile.index', compact('userInfo', 'provinces', 'userAdministratives', 'foods'));
    }

    public function updateInfo(Request $request)
    {
        $this->checkAuth();
        $token = Session::get('access_token');

        $data = $request->only([
            'firstname', 'lastname', 'intro', 'phone', 'gender', 
            'website', 'dob', 'pob', 'id_number', 'id_date', 'id_place', 'province'
        ]);

        $response = $this->apiService->updateInfo($token, $data);

        if (isset($response['User Info']) || (isset($response['status']) && $response['status'] !== 'error')) {
            return back()->with('success', 'Cập nhật thông tin thành công.');
        }

        return back()->with('error', $response['message'] ?? 'Cập nhật thông tin thất bại.');
    }

    public function updatePassword(Request $request)
    {
        $this->checkAuth();
        $token = Session::get('access_token');

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $response = $this->apiService->updatePassword($token, $request->old_password, $request->password);

        if (isset($response['User Info']) || (isset($response['status']) && $response['status'] !== 'error')) {
            return back()->with('success', 'Cập nhật mật khẩu thành công.');
        }

        return back()->with('error', $response['message'] ?? 'Cập nhật mật khẩu thất bại.');
    }

    public function updateAvatar(Request $request)
    {
        $this->checkAuth();
        $token = Session::get('access_token');

        $request->validate([
            'avatar' => 'required|string' // Expecting Base64 string from frontend
        ]);

        $response = $this->apiService->updateAvatar($token, $request->avatar);

        if ((isset($response['success']) && $response['success'] === true) || isset($response['User Info']) || (isset($response['status']) && $response['status'] !== 'error')) {
            return back()->with('success', 'Cập nhật ảnh đại diện thành công.');
        }

        return back()->with('error', $response['message'] ?? 'Cập nhật ảnh đại diện thất bại.');
    }

    public function updateCccd(Request $request)
    {
        $this->checkAuth();
        $token = Session::get('access_token');

        $request->validate([
            'front' => 'required|string', // Base64
            'back' => 'required|string',  // Base64
            'number' => 'nullable|string',
            'date' => 'nullable|string',
            'place' => 'nullable|string'
        ]);

        $response = $this->apiService->updateCccd(
            $token, 
            $request->front, 
            $request->back, 
            $request->number, 
            $request->date, 
            $request->place
        );

        if (isset($response['User Info']) || (isset($response['status']) && $response['status'] !== 'error')) {
            return back()->with('success', 'Cập nhật CCCD thành công.');
        }

        return back()->with('error', $response['message'] ?? 'Cập nhật CCCD thất bại.');
    }

    public function getAdministratives(Request $request)
    {
        $provinceId = $request->input('province_id');
        $response = $this->apiService->getAdministratives($provinceId);
        
        return response()->json($response);
    }
}
