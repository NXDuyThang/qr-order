<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use App\Services\NksApiService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar_url')
                    ->label('Ảnh đại diện (Avatar)')
                    ->image()
                    ->avatar()
                    ->directory('avatars')
                    ->alignCenter(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function afterSave(): void
    {
        $user = auth()->user();
        $token = Session::get('access_token');

        if ($token && $user->avatar_url) {
            $path = Storage::disk('public')->path($user->avatar_url);
            if (file_exists($path)) {
                $mime = mime_content_type($path);
                $base64 = base64_encode(file_get_contents($path));
                $base64String = "data:{$mime};base64,{$base64}";
                
                $apiService = app(NksApiService::class);
                $apiService->updateAvatar($token, $base64String);
            }
        }
    }
}
