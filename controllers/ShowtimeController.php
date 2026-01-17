<?php
require_once 'models/ShowTime.php';

class ShowtimeController {
    public function index() {
        // Kiểm tra xem class có tồn tại không để tránh lỗi trang trắng
        if (!class_exists('ShowTimeModel')) {
            echo "Lỗi: Không tìm thấy class ShowTimeModel. Kiểm tra file models/ShowTime.php";
            return;
        }

        $stModel = new ShowTimeModel();
        
        // Gọi đúng hàm getAllShowtimes vừa tạo ở Bước 1
        $showtimes = $stModel->getAllShowtimes();
        
        // Gọi View
        require_once 'views/showtime/index.php';
    }
}
?>