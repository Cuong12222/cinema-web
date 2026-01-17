<?php
require_once 'models/User.php';
require_once 'models/Booking.php'; // <--- QUAN TRỌNG: Phải có dòng này để lấy lịch sử vé

class AuthController {
    
    // --- ĐĂNG KÝ ---
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullName = $_POST['full_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            if ($userModel->register($fullName, $email, $password)) {
                // Đăng ký thành công -> Chuyển qua login
                header("Location: index.php?page=auth&action=login&msg=Đăng ký thành công");
            } else {
                $error = "Email đã tồn tại hoặc có lỗi xảy ra.";
                require_once 'views/auth/register.php';
            }
        } else {
            require_once 'views/auth/register.php';
        }
    }

    // --- ĐĂNG NHẬP ---
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->login($email, $password);

            if ($user) {
                // Lưu session User (Dạng mảng chứa full thông tin)
                $_SESSION['user'] = $user;
                
                // Nếu trước đó đang đặt vé dở (bị bắt login) thì quay lại đó
                if (isset($_SESSION['redirect_after_login'])) {
                    $url = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header("Location: " . $url);
                } else {
                    header("Location: index.php"); // Về trang chủ
                }
            } else {
                $error = "Email hoặc mật khẩu không đúng!";
                require_once 'views/auth/login.php';
            }
        } else {
            require_once 'views/auth/login.php';
        }
    }

    // --- ĐĂNG XUẤT ---
    public function logout() {
        session_destroy(); // Xóa sạch session
        header("Location: index.php?page=auth&action=login");
    }

    // --- HỒ SƠ & LỊCH SỬ VÉ (Cái bạn đang thiếu) ---
    public function profile() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=auth&action=login");
            exit();
        }

        $userId = $_SESSION['user']['user_id'];
        
        // Gọi Model lấy danh sách vé
        $bookingModel = new BookingModel();
        $myTickets = $bookingModel->getBookingHistory($userId);

        // Hiển thị View
        require_once 'views/user/profile.php';
    }
}
?>