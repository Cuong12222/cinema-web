<?php
// Kiểm tra xem file model có tồn tại không
if (!file_exists('models/Booking.php')) {
    die("Lỗi: Thiếu file models/Booking.php");
}
require_once 'models/Booking.php';

class BookingController {
    private $bookingModel;

    public function __construct() {
        // Khởi tạo Model 1 lần dùng chung cho cả class
        $this->bookingModel = new BookingModel();
    }

    // 1. CHỌN GHẾ
    public function seat() {
        // A. Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            // Lưu URL để sau khi login thì quay lại đúng chỗ này
            $_SESSION['redirect_url'] = "index.php?page=booking&action=seat&id=" . ($_GET['id'] ?? '');
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        // B. Lấy ID suất chiếu
        // Lưu ý: View của bạn dùng ?id=... hay ?showtime_id=...? 
        // Code này hỗ trợ cả 2 trường hợp để tránh lỗi
        $showtime_id = $_GET['id'] ?? $_GET['showtime_id'] ?? 0;
        
        // C. Lấy thông tin suất chiếu (Dùng hàm trong BookingModel đã viết)
        $showtime = $this->bookingModel->getShowtimeDetail($showtime_id);

        if (!$showtime) {
            die("Lỗi: Không tìm thấy suất chiếu (ID: $showtime_id)."); 
        }

        // D. Lấy danh sách ghế ĐÃ BÁN (Quan trọng để tô màu trắng)
        $bookedSeats = $this->bookingModel->getBookedSeats($showtime_id);

        // E. Gọi View
        require_once 'views/booking/seat_selection.php';
    }

    // 2. TRANG THANH TOÁN
    public function payment() {
        // 1. Kiểm tra request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php");
            exit;
        }

        // 2. Lấy dữ liệu
        $showtime_id = $_POST['showtime_id'] ?? null;
        
        // --- [SỬA LỖI TẠI ĐÂY] ---
        // Vì bên View dùng name="seats[]" nên $_POST['seats'] đã là Mảng rồi.
        // Không dùng explode() nữa.
        $seats = $_POST['seats'] ?? []; 
        
        // Kiểm tra nếu biến $seats không phải là mảng (phòng hờ lỗi) thì ép kiểu
        if (!is_array($seats)) {
            $seats = explode(',', $seats);
        }
        // -------------------------

        // 3. Lấy thông tin phim
        // Lưu ý: Đảm bảo bạn đã dùng code models/Booking.php đã sửa (st.base_price)
        $showtime = $this->bookingModel->getShowtimeDetail($showtime_id);

        if (!$showtime || empty($seats)) {
            die("Lỗi: Bạn chưa chọn ghế hoặc dữ liệu vé không hợp lệ.");
        }

        // 4. Tính toán tiền
        // Sử dụng base_price từ database
        $price_per_ticket = $showtime['base_price']; 
        $total_amount = count($seats) * $price_per_ticket;

        // 5. Gọi View hiển thị
        require_once 'views/booking/payment.php';
    }
    // 3. XỬ LÝ LƯU VÉ (PROCESS)
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy thông tin User
            $user_id = $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'];
            
            // Lấy thông tin Form
            $showtime_id = $_POST['showtime_id'];
            $seats_str = $_POST['seats_list']; // Danh sách ghế: "A1, A2"
            
            // Tách chuỗi thành mảng
            $seatList = explode(',', $seats_str);

            // Lấy screen_id từ suất chiếu để tìm đúng ghế trong bảng Seats
            $showtime = $this->bookingModel->getShowtimeDetail($showtime_id);
            
            if (!$showtime) die("Lỗi: Suất chiếu không tồn tại");
            
            $screen_id = $showtime['screen_id'];
            
            // Tính lại tổng tiền lần cuối (Server side calculation)
            // (Hoặc lấy từ POST nếu bạn chấp nhận tin tưởng client)
            $total_amount = $_POST['total_price']; 

            // Gọi Model lưu vào DB
            $result = $this->bookingModel->createBooking($user_id, $showtime_id, $total_amount, $seatList, $screen_id);

            if ($result) {
                // Thành công -> Chuyển hướng
                header("Location: index.php?page=booking&action=success&id=" . $result);
                exit;
            } else {
                die("Lỗi: Không thể lưu đơn hàng. Vui lòng thử lại.");
            }
        }
    }

    // 4. TRANG THÔNG BÁO THÀNH CÔNG
    public function success() {
        $booking_id = isset($_GET['id']) ? $_GET['id'] : '';
        require_once 'views/booking/success.php';
    }
}
?>