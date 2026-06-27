<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ChatbotController extends Controller
{
    // Cấu hình URL và model của Gemini
    private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent';

    public function index()
    {
        // Khởi tạo lịch sử chat nếu chưa có
        if (!Session::has('chat_history')) {
            Session::put('chat_history', []);
        }
        
        return view('chatbot.index', [
            'chatHistory' => Session::get('chat_history')
        ]);
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = $request->input('message');
        
        // Lấy lịch sử chat
        $chatHistory = Session::get('chat_history', []);
        
        // Thêm tin nhắn của user vào lịch sử để hiển thị
        $chatHistory[] = ['role' => 'user', 'content' => $userMessage];
        
        // Gọi API Gemini
        $apiKey = config('services.gemini.key');
        
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Hệ thống chưa được cấu hình API Key. Vui lòng liên hệ quản trị viên.'
            ]);
        }

        // Chuẩn bị payload theo chuẩn Gemini API (v1beta)
        $contents = [];
        foreach ($chatHistory as $msg) {
            $contents[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg['content']]]
            ];
        }

        // Lấy danh sách món ăn từ database
        $foods = \App\Models\Food::where('is_available', true)->get(['name', 'price', 'description']);
        $menuList = "Danh sách thực đơn của nhà hàng:\n";
        foreach ($foods as $food) {
            $priceVND = number_format($food->price * 1000, 0, ',', '.');
            $menuList .= "- {$food->name} ({$priceVND} VNĐ): {$food->description}\n";
        }

        // Lấy thông tin khách hàng nếu đã đăng nhập
        $userInfo = "";
        if (auth()->check()) {
            $user = auth()->user();
            // Đảm bảo lấy tên (name) hoặc fallback sang thông tin khác, tránh gọi bằng email
            $displayName = trim($user->name);
            if (empty($displayName)) {
                $displayName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''));
            }
            if (empty($displayName)) {
                $displayName = 'Khách hàng';
            }
            
            // Nếu displayName là một email (có chứa @), cắt lấy phần trước @ làm tên gọi
            if (str_contains($displayName, '@')) {
                $displayName = explode('@', $displayName)[0];
            }

            $userInfo = "Tên của khách hàng đang trò chuyện là: '$displayName'. Tuyệt đối gọi khách hàng bằng tên này, KHÔNG được gọi bằng email hay số điện thoại.";
        }

        $systemPrompt = "Bạn là một chuyên gia dinh dưỡng làm việc cho 'Nhà Hàng Ẩm Thực Việt'. Nhiệm vụ của bạn là tư vấn sức khỏe và gợi ý các món ăn dinh dưỡng dựa trên chỉ số BMI của khách hàng. Hãy hỏi chiều cao và cân nặng của họ nếu chưa biết để tính BMI. Công thức BMI = Cân nặng (kg) / (Chiều cao (m) * Chiều cao (m)). Đưa ra đánh giá (Gầy, Bình thường, Thừa cân, Béo phì) và sau đó đề xuất các món ăn ẩm thực Việt Nam phù hợp với thể trạng của họ. Chỉ trả lời bằng tiếng Việt, thân thiện và nhiệt tình.\n\n$userInfo\n\n$menuList\nLƯU Ý: CHỈ đề xuất các món ăn có trong 'Danh sách thực đơn của nhà hàng' ở trên, tuyệt đối không tự bịa ra món khác.";

        $payload = [
            'system_instruction' => [
                'parts' => [
                    [
                        'text' => $systemPrompt
                    ]
                ]
            ],
            'contents' => $contents
        ];

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '?key=' . $apiKey, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Trích xuất văn bản trả về
                if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    $botMessage = $responseData['candidates'][0]['content']['parts'][0]['text'];
                    
                    // Thêm phản hồi của bot vào lịch sử
                    $chatHistory[] = ['role' => 'bot', 'content' => $botMessage];
                    Session::put('chat_history', $chatHistory);
                    
                    return response()->json([
                        'success' => true,
                        'message' => $botMessage
                    ]);
                }
            }
            
            // Xử lý lỗi API
            return response()->json([
                'success' => false,
                'message' => 'Lỗi từ Gemini API: ' . $response->body()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi hệ thống xảy ra: ' . $e->getMessage()
            ]);
        }
    }
}
