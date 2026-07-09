<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class AiMarketing extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Quản lý';
    protected static ?string $title = 'AI Marketing';
    
    protected static string $view = 'filament.pages.ai-marketing';

    public $dishName = '';
    public $generatedTitle = '';
    public $generatedContent = '';
    public $generatedImageUrl = '';

    public static function canAccess(): bool
    {
        return auth()->user()->is_admin || auth()->user()->role === 'manager';
    }

    public function generateContent()
    {
        $this->validate([
            'dishName' => 'required|string|max:255',
        ]);

        $this->generatedTitle = '';
        $this->generatedContent = '';
        $this->generatedImageUrl = '';

        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            Notification::make()->title('Chưa cấu hình API Key')->danger()->send();
            return;
        }

        $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

        // 1. Sinh nội dung bài đăng
        $prompt = "Viết một bài đăng mạng xã hội (Facebook/Instagram) quảng cáo món ăn '{$this->dishName}'. Yêu cầu bao gồm: Một tiêu đề hấp dẫn, nội dung quảng cáo thật ngon miệng và hấp dẫn, có sử dụng emoji phù hợp, kèm theo các hashtag. Tách riêng tiêu đề ở dòng đầu tiên.";
        
        $payload = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ];

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($apiUrl . '?key=' . $apiKey, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = data_get($data, 'candidates.0.content.parts.0.text', '');
                
                if (!empty($text)) {
                    // Tách dòng đầu làm tiêu đề
                    $lines = explode("\n", trim($text));
                    $this->generatedTitle = str_replace(['#', '**', '*'], '', array_shift($lines));
                    $this->generatedContent = trim(implode("\n", $lines));
                } else {
                    $this->generatedTitle = "Quảng cáo " . $this->dishName;
                    $this->generatedContent = "Mời bạn thưởng thức " . $this->dishName . " ngon tuyệt!";
                }
                
            } else {
                Notification::make()->title('Lỗi sinh nội dung')->danger()->send();
            }

            // 2. Sinh prompt tiếng Anh để tạo hình ảnh
            $imgPromptReq = "Tạo một mô tả tiếng Anh (image generation prompt) ngắn gọn, chi tiết dưới 200 ký tự để tạo hình ảnh cực kỳ hấp dẫn về món ăn '{$this->dishName}' chụp theo phong cách food photography, high quality. Chỉ trả lời bằng mô tả tiếng Anh, không thêm chữ nào khác.";
            
            $payload2 = [
                'contents' => [
                    ['parts' => [['text' => $imgPromptReq]]]
                ]
            ];

            $response2 = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($apiUrl . '?key=' . $apiKey, $payload2);

            if ($response2->successful()) {
                $data2 = $response2->json();
                $englishPrompt = trim(data_get($data2, 'candidates.0.content.parts.0.text', ''));
                if (!empty($englishPrompt)) {
                    $this->generatedImageUrl = 'https://image.pollinations.ai/prompt/' . urlencode($englishPrompt) . '?width=1080&height=1080&nologo=true';
                } else {
                    $this->generatedImageUrl = 'https://image.pollinations.ai/prompt/' . urlencode("delicious " . $this->dishName . " food photography high quality") . '?width=1080&height=1080&nologo=true';
                }
            } else {
                $this->generatedImageUrl = 'https://image.pollinations.ai/prompt/' . urlencode("delicious " . $this->dishName . " food photography high quality") . '?width=1080&height=1080&nologo=true';
            }

            Notification::make()->title('Tạo nội dung thành công!')->success()->send();

        } catch (\Exception $e) {
            Notification::make()->title('Đã có lỗi xảy ra')->danger()->send();
        }
    }
}
