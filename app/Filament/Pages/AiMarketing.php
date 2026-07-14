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
    public $platform = 'facebook';
    public $style = 'hấp dẫn, sinh động';
    public $length = 'vừa phải (khoảng 150-200 từ)';
    public $targetAudience = 'chung';
    public $promotion = '';
    
    public $generatedTitle = '';
    public $generatedContent = '';
    public $generatedHashtags = '';
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
        $this->generatedHashtags = '';
        $this->generatedImageUrl = '';

        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            Notification::make()->title('Chưa cấu hình API Key')->danger()->send();
            return;
        }

        $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

        // 1. Sinh nội dung bài đăng
        $prompt = "Viết nội dung quảng cáo cho món ăn/dịch vụ: '{$this->dishName}'. Yêu cầu chi tiết:\n";
        $prompt .= "- Nền tảng đăng tải: {$this->platform}.\n";
        $prompt .= "- Phong cách viết: {$this->style}.\n";
        $prompt .= "- Độ dài bài viết: {$this->length}.\n";
        if ($this->targetAudience !== 'chung') {
            $prompt .= "- Khách hàng mục tiêu: {$this->targetAudience}.\n";
        }
        if (!empty($this->promotion)) {
            $prompt .= "- Thông tin khuyến mãi/Ưu đãi cần nhấn mạnh: {$this->promotion}.\n";
        }
        $prompt .= "Quan trọng: Hãy trả kết quả DUY NHẤT ở định dạng JSON hợp lệ (không chứa markdown nào khác). Ví dụ định dạng:\n";
        $prompt .= "{\n";
        $prompt .= '  "title": "Tiêu đề hấp dẫn, ngắn gọn",'."\n";
        $prompt .= '  "content": "Nội dung quảng cáo thu hút, dùng emoji phù hợp, xuống dòng rõ ràng",'."\n";
        $prompt .= '  "hashtags": "#hashtag1 #hashtag2"'."\n";
        $prompt .= "}";
        
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
                    $text = trim(str_replace(['```json', '```'], '', $text));
                    $decoded = json_decode($text, true);
                    
                    if ($decoded && is_array($decoded)) {
                        $this->generatedTitle = str_replace(['#', '**', '*'], '', $decoded['title'] ?? '');
                        $this->generatedContent = trim($decoded['content'] ?? '');
                        $this->generatedHashtags = trim($decoded['hashtags'] ?? '');
                    } else {
                        // Fallback
                        $this->generatedTitle = "Quảng cáo " . $this->dishName;
                        $this->generatedContent = $text;
                        $this->generatedHashtags = '';
                    }
                } else {
                    $this->generatedTitle = "Quảng cáo " . $this->dishName;
                    $this->generatedContent = "Mời bạn thưởng thức " . $this->dishName . " ngon tuyệt!";
                    $this->generatedHashtags = '';
                }
                
            } else {
                \Illuminate\Support\Facades\Log::error('AI Marketing Gemini Error: ' . $response->body());
                Notification::make()->title('Lỗi API Gemini: ' . $response->status())->body($response->body())->danger()->send();
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

            if ($response->successful()) {
                Notification::make()->title('Tạo nội dung thành công!')->success()->send();
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AI Marketing Exception: ' . $e->getMessage());
            Notification::make()->title('Lỗi hệ thống: ' . $e->getMessage())->danger()->send();
        }
    }
}
