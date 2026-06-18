<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NksApiService
{
    protected string $onlineBaseUrl = 'https://online.nks.vn/api';
    protected string $accountBaseUrl = 'https://account.nks.vn/api';

    /**
     * POST Request wrapper
     */
    protected function post($baseUrl, $endpoint, $data = [], $asForm = false)
    {
        try {
            $request = Http::withoutVerifying();
            if ($asForm) {
                $request = $request->asForm();
            }
            $response = $request->post("{$baseUrl}/{$endpoint}", $data);
            
            if ($response->successful()) {
                return $response->json();
            }

            // You may want to throw a custom exception or log this
            Log::error("NKS API Error [{$endpoint}]: " . $response->body());
            return [
                'status' => 'error',
                'message' => 'API Request failed',
                'data' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error("NKS API Exception [{$endpoint}]: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getProvinces($countryId = 237)
    {
        return $this->post($this->onlineBaseUrl, 'nks/provinces', [
            'country_id' => $countryId,
            'slcBox' => 1
        ]);
    }

    public function getAdministratives($provinceId)
    {
        return $this->post($this->onlineBaseUrl, 'nks/administratives', [
            'province_id' => $provinceId,
            'slcBox' => 1
        ]);
    }

    public function login($username, $password, $fbtoken = '', $system = 'NKS', $device = 'Web Browser')
    {
        return $this->post($this->accountBaseUrl, 'nks/user/login', [
            'username' => $username,
            'password' => $password,
            'fbtoken'  => $fbtoken,
            'system'   => $system,
            'device'   => $device,
            'ip_address' => request()->ip(),
            'location' => ''
        ]);
    }

    public function getUser($accessToken)
    {
        return $this->post($this->accountBaseUrl, 'nks/user', [
            'access_token' => $accessToken
        ]);
    }

    public function updateInfo($accessToken, $data)
    {
        $data['access_token'] = $accessToken;
        return $this->post($this->accountBaseUrl, 'nks/user/updateInfo', $data);
    }

    public function updatePassword($accessToken, $oldPassword, $newPassword)
    {
        return $this->post($this->accountBaseUrl, 'nks/user/updatePass', [
            'access_token' => $accessToken,
            'old_password' => $oldPassword,
            'password' => $newPassword
        ]);
    }

    public function updateAvatar($accessToken, $avatarBase64)
    {
        return $this->post($this->accountBaseUrl, 'nks/user/updateAvatar', [
            'access_token' => $accessToken,
            'avatar' => $avatarBase64
        ], true);
    }

    public function updateCccd($accessToken, $frontBase64, $backBase64, $number, $date, $place)
    {
        return $this->post($this->accountBaseUrl, 'nks/user/updateCccd', [
            'access_token' => $accessToken,
            'front' => $frontBase64,
            'back' => $backBase64,
            'number' => $number,
            'date' => $date,
            'place' => $place
        ]);
    }
}
