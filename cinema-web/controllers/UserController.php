<?php
// controllers/UserController.php

require_once 'models/User.php';
require_once 'models/Booking.php';    // Thêm dòng này
require_once 'models/Community.php';  // Thêm dòng này

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // Hàm xử lý trang Hồ sơ (Bao gồm Lịch sử vé + Bài viết)
    public function profile() {
        // 1. Kiểm tra đăng nhập
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        // 2. Khởi tạo Model
        $bookingModel = new BookingModel();
        $communityModel = new CommunityModel();

        $userId = $_SESSION['user']['user_id'];

        // 3. Lấy dữ liệu
        // Lấy lịch sử vé
        $history = $bookingModel->getBookingHistory($userId);
        
        // Lấy bài viết của user
        $myPosts = $communityModel->getPostsByUserId($userId);

        // 4. Gọi View hiển thị
        require_once 'views/user/profile.php';
    }

    // ... (Giữ nguyên các hàm login, register, logout khác của bạn ở dưới nếu có) ...
}
?>