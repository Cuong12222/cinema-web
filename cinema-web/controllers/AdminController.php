<?php
require_once 'models/ShowTime.php';
require_once 'models/Movie.php'; 
// require_once 'models/User.php'; // Mở nếu cần quản lý User

class AdminController {
    private $showTimeModel;
    private $movieModel;
    private $conn; 

    public function __construct() {
        $this->showTimeModel = new ShowTimeModel();
        $this->movieModel = new MovieModel();
        
        // Kết nối DB trực tiếp để chạy báo cáo thống kê
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // --- 1. DASHBOARD (SỬA ĐỂ KHỚP VỚI DATABASE CỦA BẠN) ---
// --- FILE: controllers/AdminController.php ---

    public function dashboard() {
        $totalRevenue = 0;
        $totalTickets = 0;
        $totalMovies = 0;
        $movieStats = [];

        try {
            // 1. SỬA: Đếm phim dựa trên trạng thái 'now_showing' (Chính xác hơn theo thời gian thực)
            $sqlMovies = "SELECT COUNT(*) FROM Movies WHERE status = 'now_showing'";
            $stmt = $this->conn->prepare($sqlMovies);
            $stmt->execute();
            $totalMovies = $stmt->fetchColumn();

            // 2. Tổng doanh thu (Lấy từ bảng Bookings - Tổng tiền thực tế đơn hàng)
            $sqlRevenue = "SELECT SUM(total_amount) FROM Bookings WHERE status = 'confirmed'"; 
            $stmt = $this->conn->prepare($sqlRevenue);
            $stmt->execute();
            $revenue = $stmt->fetchColumn();
            $totalRevenue = $revenue ? $revenue : 0;

            // 3. Tổng số vé (Lấy từ BookingTickets)
            $sqlTickets = "SELECT COUNT(*) FROM BookingTickets";
            $stmt = $this->conn->prepare($sqlTickets);
            $stmt->execute();
            $totalTickets = $stmt->fetchColumn();

            // 4. Top Phim (Logic giữ nguyên nhưng đảm bảo GROUP BY đúng)
            $sqlStats = "SELECT TOP 5 
                            m.title, 
                            COUNT(bt.ticket_id) as total_sold, 
                            SUM(bt.price) as total_revenue
                        FROM BookingTickets bt
                        JOIN Bookings b ON bt.booking_id = b.booking_id
                        JOIN ShowTimes st ON b.showtime_id = st.showtime_id
                        JOIN Movies m ON st.movie_id = m.movie_id
                        GROUP BY m.title, m.movie_id
                        ORDER BY total_sold DESC";
            
            $stmt = $this->conn->prepare($sqlStats);
            $stmt->execute();
            $movieStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            // Log lỗi nếu cần
        }

        require_once 'views/admin/dashboard.php';
    }

    // --- 2. QUẢN LÝ PHIM ---
    public function movies() {
        $movies = $this->movieModel->getAllMovies();
        // Đường dẫn file view (Dựa vào ảnh bạn gửi lần trước)
        require_once 'views/admin/movie_list.php'; 
    }

    // --- 3. QUẢN LÝ LỊCH CHIẾU ---
    public function indexShowtimes() {
        $showtimes = $this->showTimeModel->getAllShowtimes();
        require_once 'views/admin/showtimes/index.php';
    }

    public function addShowtime() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $movie_id = $_POST['movie_id'];
            $screen_id = $_POST['screen_id'];
            $start_time = $_POST['start_time'];
            $price = $_POST['price']; // Base price cho ShowTimes

            $result = $this->showTimeModel->createShowtime($movie_id, $screen_id, $start_time, $price);

            if ($result) {
                header('Location: index.php?page=admin&action=showtimes&msg=Thêm thành công');
                exit();
            } else {
                echo "<h3 style='color:red'>LỖI SQL: Không lưu được.</h3>";
                die();
            }
        } else {
            $movies = $this->movieModel->getAllMovies();
            $screens = $this->showTimeModel->getAllScreens();
            require_once 'views/admin/showtimes/add.php';
        }
    }

    public function deleteShowtime() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->showTimeModel->deleteShowtime($id);
        }
        header('Location: index.php?page=admin&action=showtimes');
        exit();
    }
}
?>