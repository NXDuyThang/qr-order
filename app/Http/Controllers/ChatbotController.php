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

        // Kiểm tra trạng thái đăng nhập
        $isLoggedIn = auth()->check();
        $userInfo = "";
        $displayName = "Khách hàng";

        if ($isLoggedIn) {
            $user = auth()->user();
            $displayName = trim($user->name);
            if (empty($displayName)) {
                $displayName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''));
            }
            if (empty($displayName)) {
                $displayName = 'Khách hàng';
            }
            if (str_contains($displayName, '@')) {
                $displayName = explode('@', $displayName)[0];
            }
            $userInfo = "Tên của khách hàng đang trò chuyện là: '$displayName'. KHÔNG cần hỏi tên khách nữa. (Coi như thông tin [Tên] = '$displayName' đã có).";
        } else {
            $userInfo = "Tình trạng: KHÁCH HÀNG CHƯA ĐĂNG NHẬP.";
        }

        $tableId = Session::get('table_id');
        if ($tableId) {
            $systemPrompt = "Bạn là một AI Waiter (nhân viên phục vụ AI) tại 'Nhà Hàng Ẩm Thực Việt', đang phục vụ khách tại bàn số $tableId. Nhiệm vụ của bạn là chào đón khách, giới thiệu thực đơn, trả lời các câu hỏi về giá cả hoặc thành phần món ăn. Ví dụ nếu khách hỏi món nào dưới 300.000 thì bạn phải liệt kê ra. Hãy tỏ ra thân thiện, chuyên nghiệp và nhiệt tình. Chỉ trả lời bằng tiếng Việt.\n\n$userInfo\n\n$menuList\nLƯU Ý: CHỈ đề xuất các món ăn có trong 'Danh sách thực đơn của nhà hàng' ở trên, tuyệt đối không tự bịa ra món khác.";
        } else {
            $bookingInstructions = "";
            if ($isLoggedIn) {
                $bookingInstructions = <<<EOT
- ĐỂ ĐẶT BÀN, cần ĐỦ 5 thông tin: [Số điện thoại], [Số người], [Ngày], [Giờ], [Khu vực (trong nhà/ngoài trời)]. (Tên đã có sẵn, không cần hỏi tên).
- Nếu còn thiếu: Chỉ hỏi những thông tin chưa có.
- NẾU ĐÃ ĐỦ TẤT CẢ THÔNG TIN: DỪNG VIỆC CHAT LẠI. BẠN PHẢI TRẢ VỀ DUY NHẤT 1 DÒNG ĐỊNH DẠNG JSON. KHÔNG KÈM THEO BẤT KỲ CÂU CHÀO HAY VĂN BẢN NÀO KHÁC.

Mẫu JSON TRẢ VỀ (khi đã đủ thông tin):
{"action":"book_table","name":"$displayName","phone":"Số ĐT","guests":2,"date":"YYYY-MM-DD","time":"HH:MM","notes":"Khu vực"}
EOT;
            } else {
                $bookingInstructions = <<<EOT
- ĐỂ ĐẶT BÀN: Khách hàng BẮT BUỘC phải đăng nhập.
- Bạn PHẢI TỪ CHỐI việc đặt bàn và thông báo lịch sự rằng khách cần phải đăng nhập tài khoản vào hệ thống trước thì mới có thể đặt bàn. Tuyệt đối không tiến hành thu thập thông tin đặt bàn.
EOT;
            }

            $systemPrompt = <<<EOT
Bạn là một AI Lễ tân làm việc cho 'Nhà Hàng Ẩm Thực Việt'.

*** QUY TẮC CỐT LÕI: ***
- Bạn PHẢI đọc toàn bộ lịch sử trò chuyện để biết khách đã cung cấp thông tin gì.
- KHÔNG BAO GIỜ hỏi lại thông tin mà khách đã cung cấp trong các tin nhắn trước.
$bookingInstructions

Thông tin khách hàng:
$userInfo

$menuList
EOT;
        }

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
                    
                    // Kiểm tra xem có JSON action đặt bàn không
                    if (preg_match('/\{.*?"action"\s*:\s*"book_table".*?\}/s', $botMessage, $matches)) {
                        try {
                            $bookingData = json_decode($matches[0], true);
                            if ($bookingData && isset($bookingData['action']) && $bookingData['action'] === 'book_table') {
                                // Xóa đoạn JSON khỏi botMessage
                                $botMessage = str_replace($matches[0], "", $botMessage);
                                
                                // Tạo Booking record
                                $booking = \App\Models\Booking::create([
                                    'name' => $bookingData['name'] ?? 'Khách',
                                    'phone' => $bookingData['phone'] ?? '',
                                    'guests' => $bookingData['guests'] ?? 1,
                                    'date' => $bookingData['date'] ?? date('Y-m-d'),
                                    'time' => $bookingData['time'] ?? date('H:i'),
                                    'notes' => $bookingData['notes'] ?? '',
                                    'status' => 'pending'
                                ]);

                                // Tạo link QR code
                                $qrData = "Booking ID: " . $booking->id . " | Tên: " . $booking->name . " | Giờ: " . $booking->time . " " . $booking->date;
                                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);
                                
                                $successMessage = "<br><br><b>🎉 Tôi đã đặt bàn thành công cho bạn!</b><br>";
                                $successMessage .= "Mã đặt bàn: <b>#" . $booking->id . "</b><br>";
                                $successMessage .= "Tên: " . $booking->name . "<br>";
                                $successMessage .= "Thời gian: " . $booking->time . " " . $booking->date . "<br>";
                                $successMessage .= "Số người: " . $booking->guests . "<br>";
                                $successMessage .= "Khu vực: " . ($booking->notes ?: 'Không') . "<br><br>";
                                $successMessage .= "Đây là mã QR của bạn:<br><img src='{$qrUrl}' alt='QR Code' class='mt-2 rounded-lg border border-white/10 shadow-lg' style='width: 200px;'><br><br>";
                                $successMessage .= "<span class='text-primary'><i>🔔 Hệ thống đã lưu nhắc lịch cho bạn.</i></span>";

                                $botMessage .= $successMessage;
                            }
                        } catch (\Exception $e) {
                            // Bỏ qua nếu lỗi parse
                        }
                    }

                    // Đảm bảo tin nhắn không bị trống
                    if (trim(strip_tags($botMessage)) === '') {
                        $botMessage = "Tuyệt vời! Tôi đã ghi nhận thông tin đặt bàn của bạn.";
                    }

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
