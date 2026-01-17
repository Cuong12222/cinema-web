<?php
// 1. HIỂN THỊ LỖI (Quan trọng để debug khi phát triển)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. LOAD FILE CẤU HÌNH & DB
if (file_exists('config/db.php')) {
    require_once 'config/db.php';
} else {
    die("<h1 style='color:red'>LỖI: Không tìm thấy file config/db.php</h1>");
}

// 3. LOAD MODEL CẦN THIẾT
if (file_exists('models/User.php')) {
    require_once 'models/User.php'; 
}

// 4. LẤY THAM SỐ URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// 5. ĐIỀU HƯỚNG (ROUTER)
switch ($page) {

    // --- TRANG CHỦ ---
    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        if ($action == 'promo') $controller->promo();
        else $controller->index();
        break;

    // --- TÀI KHOẢN ---
    case 'auth':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        if ($action == 'login') $controller->login();
        elseif ($action == 'register') $controller->register();
        elseif ($action == 'logout') $controller->logout();
        break;

    // --- ĐẶT VÉ (BOOKING) ---
    case 'booking':
        if (!file_exists('controllers/BookingController.php')) {
            die("<h1 style='color:red'>LỖI: Thiếu file controllers/BookingController.php</h1>");
        }

        require_once 'controllers/BookingController.php';

        if (!class_exists('BookingController')) {
            die("<h1 style='color:red'>LỖI: Class BookingController không tồn tại.</h1>");
        }

        $controller = new BookingController();
        
        if ($action == 'seat' || $action == 'select_seats') {
            $controller->seat();
        } elseif ($action == 'payment') {
            $controller->payment();
        } elseif ($action == 'process') {
            $controller->process();
        } elseif ($action == 'success') {
            $controller->success();
        } else {
            die("<h1 style='color:red'>LỖI: Action '$action' không hợp lệ!</h1>");
        }
        break;

    // --- PHIM ---
    case 'movie':
        require_once 'controllers/MovieController.php';
        $controller = new MovieController();
        if ($action == 'detail') $controller->detail();
        elseif ($action == 'search') $controller->search();
        elseif ($action == 'list') $controller->list();
        else $controller->list(); 
        break;

    // --- LỊCH CHIẾU ---
    case 'showtime':
        require_once 'controllers/ShowtimeController.php';
        $controller = new ShowtimeController();
        $controller->index();
        break;

    // --- CỘNG ĐỒNG ---
    case 'community':
        require_once 'controllers/CommunityController.php';
        $controller = new CommunityController();
        if ($action == 'index') $controller->index();
        elseif ($action == 'create') $controller->create();
        elseif ($action == 'like') $controller->like();
        elseif ($action == 'comment') $controller->comment();
        break;
    
    // --- NGƯỜI DÙNG (PROFILE & LỊCH SỬ) ---
    case 'user':
        // Kiểm tra đăng nhập trước khi vào profile
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=auth&action=login");
            exit();
        }
        require_once 'controllers/UserController.php';
        $controller = new UserController();
        if ($action == 'profile') {
            $controller->profile();
        } else {
            $controller->profile();
        }
        break;
        
    // --- QUẢN TRỊ (ADMIN) - ĐÃ SỬA LẠI LOGIC ---
    case 'admin':
        // 1. Kiểm tra quyền Admin (Dùng trim để tránh lỗi khoảng trắng)
        if (empty($_SESSION['user']) || trim($_SESSION['user']['role']) !== 'admin') {
            // Chuyển hướng về trang đăng nhập nếu không phải admin
            header("Location: index.php?page=auth&action=login");
            exit();
        }

        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        
        // 2. Phân luồng chức năng (Router)
        switch ($action) {
            case 'dashboard':
                $controller->dashboard();
                break;
            
            // Quản lý Phim
            case 'movies':
                $controller->movies(); // Cần có hàm này trong AdminController
                break;
            case 'movies_add':
                $controller->addMovie(); // Cần có hàm này trong AdminController
                break;

            // Quản lý Lịch chiếu
            case 'showtimes':
                $controller->indexShowtimes(); // Cần có hàm này trong AdminController
                break;
            case 'showtimes_add':
                $controller->addShowtime(); // Cần có hàm này trong AdminController
                break;
            case 'movies_delete': 
            // Gọi hàm xóa trong Controller
            $controller->deleteMovie(); 
            break;

            // Mặc định về Dashboard
            default:
                $controller->dashboard();
                break;
        }
        break;

    // --- MẶC ĐỊNH ---
    default:
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
}
?>