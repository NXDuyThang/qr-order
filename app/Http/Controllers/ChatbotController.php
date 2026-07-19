<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ChatbotController extends Controller
{
    // Cấu hình URL và model của Gemini
    private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

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
        $foods = \App\Models\Food::where('is_available', 'true')->get(['name', 'price', 'description']);
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
- NHIỆM VỤ CỦA BẠN: Thu thập ĐỦ 5 thông tin để đặt bàn:
  1. Số điện thoại
  2. Số lượng người
  3. Ngày (ví dụ: hôm nay, ngày mai, 20/11...)
  4. Giờ (ví dụ: 11 giờ tối, 19:00...)
  5. Khu vực (trong nhà / ngoài trời) hoặc Số bàn cụ thể.
- LƯU Ý ĐẶC BIỆT: BẠN PHẢI ĐỌC VÀ GHI NHỚ TOÀN BỘ LỊCH SỬ TRÒ CHUYỆN. Khách hàng có thể đã cung cấp các thông tin này ở những tin nhắn trước đó. BẠN TUYỆT ĐỐI KHÔNG ĐƯỢC HỎI LẠI NHỮNG THÔNG TIN ĐÃ CUNG CẤP.
- NẾU CHƯA ĐỦ THÔNG TIN: Hãy liệt kê lại những gì bạn đã ghi nhận được từ đầu đến giờ, và LỊCH SỰ HỎI THÊM CÁC THÔNG TIN CÒN THIẾU.
- NẾU ĐÃ ĐỦ TẤT CẢ 5 THÔNG TIN: BẠN BẮT BUỘC PHẢI GỌI HÀM `book_table` ĐỂ HOÀN TẤT ĐẶT BÀN.
EOT;
            } else {
                $bookingInstructions = <<<EOT
- ĐỂ ĐẶT BÀN: Khách hàng BẮT BUỘC phải đăng nhập.
- Bạn PHẢI TỪ CHỐI việc đặt bàn và thông báo lịch sự rằng khách cần phải đăng nhập tài khoản vào hệ thống trước thì mới có thể đặt bàn. Tuyệt đối không tiến hành thu thập thông tin đặt bàn.
EOT;
            }

            $currentDate = date('Y-m-d');
            $currentTime = date('H:i');
            $systemPrompt = <<<EOT
Bạn là AI Lễ tân làm việc cho 'Nhà Hàng Ẩm Thực Việt'.

*** THÔNG TIN HỆ THỐNG: ***
- Ngày hôm nay: $currentDate
- Giờ hiện tại: $currentTime

*** QUY TẮC CỐT LÕI: ***
1. Bạn là máy đọc dữ liệu. Hãy ghép nối TẤT CẢ thông tin khách đã nhắn từ trước tới nay.
2. KHÔNG BAO GIỜ yêu cầu khách cung cấp lại những gì họ đã từng nhắn (kể cả ở các câu chat trước).
3. ĐỐI VỚI ĐẶT BÀN:
$bookingInstructions

Thông tin khách hàng:
$userInfo

$menuList
EOT;
        }

        $tools = [];
        if (!$tableId && $isLoggedIn) {
            $tools = [
                [
                    'function_declarations' => [
                        [
                            'name' => 'book_table',
                            'description' => 'Hoàn tất đặt bàn khi đã thu thập đủ thông tin.',
                            'parameters' => [
                                'type' => 'OBJECT',
                                'properties' => [
                                    'name' => ['type' => 'STRING', 'description' => 'Tên khách hàng'],
                                    'phone' => ['type' => 'STRING', 'description' => 'Số điện thoại của khách'],
                                    'guests' => ['type' => 'INTEGER', 'description' => 'Số lượng người'],
                                    'date' => ['type' => 'STRING', 'description' => 'Ngày khách muốn đặt, định dạng YYYY-MM-DD'],
                                    'time' => ['type' => 'STRING', 'description' => 'Giờ khách muốn đặt, định dạng HH:MM'],
                                    'notes' => ['type' => 'STRING', 'description' => 'Khu vực muốn ngồi (trong nhà, ngoài trời)'],
                                    'table_id' => ['type' => 'INTEGER', 'description' => 'Số bàn yêu cầu cụ thể (nếu có, nếu không thì truyền null)']
                                ],
                                'required' => ['name', 'phone', 'guests', 'date', 'time', 'notes']
                            ]
                        ]
                    ]
                ]
            ];
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

        if (!empty($tools)) {
            $payload['tools'] = $tools;
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '?key=' . $apiKey, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                $botMessage = "";
                $isBookingSuccess = false;

                // Kiểm tra xem model có gọi function đặt bàn không
                if (isset($responseData['candidates'][0]['content']['parts'][0]['functionCall'])) {
                    $functionCall = $responseData['candidates'][0]['content']['parts'][0]['functionCall'];
                    
                    if ($functionCall['name'] === 'book_table') {
                        $bookingData = $functionCall['args'];
                        $requestedDate = $bookingData['date'] ?? date('Y-m-d');
                        $requestedTime = $bookingData['time'] ?? date('H:i');
                        $requestedTableId = $bookingData['table_id'] ?? null;
                        
                        // Tìm các bàn đã được đặt trong khoảng +/- 2 tiếng
                        try {
                            $requestedDateTime = \Carbon\Carbon::parse($requestedDate . ' ' . $requestedTime);
                            $startTime = $requestedDateTime->copy()->subHours(2)->format('H:i:s');
                            $endTime = $requestedDateTime->copy()->addHours(2)->format('H:i:s');
                            
                            $bookedTableIds = \App\Models\Booking::where('date', $requestedDate)
                                ->whereBetween('time', [$startTime, $endTime])
                                ->pluck('table_id')
                                ->filter()
                                ->toArray();
                                
                            if ($requestedTableId && is_numeric($requestedTableId)) {
                                // Khách yêu cầu bàn cụ thể
                                if (in_array($requestedTableId, $bookedTableIds)) {
                                    $table = null; // Bàn này đã bị đặt
                                    $botMessage = "Rất xin lỗi bạn, **Bàn số $requestedTableId** đã có người đặt vào lúc **$requestedTime ngày $requestedDate**. Bạn có thể đổi sang khung giờ khác, hoặc chọn bàn khác được không ạ?";
                                } else {
                                    $table = \App\Models\Table::find($requestedTableId);
                                    if (!$table) {
                                        $botMessage = "Xin lỗi bạn, nhà hàng không có Bàn số $requestedTableId. Vui lòng chọn bàn khác.";
                                    }
                                }
                            } else {
                                // Khách không yêu cầu bàn cụ thể, tự tìm bàn trống
                                $table = \App\Models\Table::whereNotIn('id', $bookedTableIds)->first();
                                if (!$table) {
                                    $botMessage = "Rất xin lỗi bạn, nhà hàng chúng tôi đã **kín bàn** vào lúc **$requestedTime ngày $requestedDate**. Bạn có thể vui lòng chọn một khung giờ khác được không?";
                                }
                            }
                        } catch (\Exception $e) {
                            $table = \App\Models\Table::first(); // Fallback nếu lỗi parse time
                        }

                        if ($table) {
                            $assignedTableId = $table->id;

                            // Tạo Booking record
                            $booking = \App\Models\Booking::create([
                                'name' => $bookingData['name'] ?? 'Khách',
                                'phone' => $bookingData['phone'] ?? '',
                                'guests' => $bookingData['guests'] ?? 1,
                                'date' => $requestedDate,
                                'time' => $requestedTime,
                                'notes' => $bookingData['notes'] ?? '',
                                'table_id' => $assignedTableId,
                                'status' => 'pending'
                            ]);

                            // Tạo link QR code trỏ thẳng tới trang order của bàn
                            $qrData = url('/order?table_id=' . $assignedTableId);
                            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);
                            
                            $successMessage = "<br><br><b>🎉 Tôi đã đặt bàn thành công cho bạn!</b><br>";
                            $successMessage .= "Mã đặt bàn: <b>#" . $booking->id . "</b><br>";
                            $successMessage .= "Tên: " . $booking->name . "<br>";
                            $successMessage .= "Thời gian: " . $booking->time . " " . $booking->date . "<br>";
                            $successMessage .= "Số người: " . $booking->guests . "<br>";
                            $successMessage .= "Khu vực: " . ($booking->notes ?: 'Không') . "<br>";
                            $successMessage .= "Đã xếp bàn số: <b>" . $assignedTableId . "</b><br><br>";
                            $successMessage .= "Bạn có thể quét mã QR dưới đây để vào thẳng trang gọi món cho bàn của mình:<br>";
                            $successMessage .= "<img src='{$qrUrl}' alt='QR Code' class='mt-2 rounded-lg border border-white/10 shadow-lg' style='width: 200px;'><br><br>";
                            $successMessage .= "<span class='text-primary'><i>🔔 Hệ thống đã lưu nhắc lịch cho bạn.</i></span>";

                            $botMessage = $successMessage;
                            $isBookingSuccess = true;
                        }
                    }
                } 
                // Nếu model chỉ trả về văn bản thông thường
                elseif (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    $botMessage = $responseData['candidates'][0]['content']['parts'][0]['text'];
                }

                if (!empty($botMessage)) {
                    // Đảm bảo tin nhắn không bị trống
                    if (trim(strip_tags($botMessage)) === '') {
                        $botMessage = "Tuyệt vời! Tôi đã ghi nhận thông tin đặt bàn của bạn.";
                    }

                    if ($isBookingSuccess) {
                        // Đặt bàn thành công thì xóa lịch sử để bắt đầu luồng chat mới nếu cần
                        Session::put('chat_history', []);
                    } else {
                        // Thêm phản hồi của bot vào lịch sử
                        $chatHistory[] = ['role' => 'bot', 'content' => $botMessage];
                        Session::put('chat_history', $chatHistory);
                    }
                    
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
