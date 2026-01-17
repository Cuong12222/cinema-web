<?php
require_once 'models/Movie.php';
require_once 'models/ShowTime.php';

class MovieController {
    public function detail() {
        if (!isset($_GET['id'])) {
            // Nếu không có ID thì về trang chủ
            header("Location: index.php");
            exit();
        }

        $id = $_GET['id'];
        
        // 1. Lấy thông tin phim
        $movieModel = new MovieModel();
        $movie = $movieModel->getMovieById($id);

        // 2. Lấy lịch chiếu
        $showTimeModel = new ShowTimeModel();
        $showtimes = $showTimeModel->getShowtimesByMovie($id);

        if (!$movie) {
            echo "Phim không tồn tại!";
            return;
        }

        // 3. Hiển thị View
        require_once 'views/movie/detail.php';
    }
    // ... code cũ ...

// --- HÀM TÌM KIẾM ---
    public function search() {
        // Lấy từ khóa, nếu không có thì để rỗng
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        
        $movieModel = new MovieModel();
        
        if ($keyword == '') {
            $movies = [];
        } else {
            $movies = $movieModel->searchMovies($keyword);
        }

        // Gọi View hiển thị kết quả
        // Đảm bảo bạn đã tạo file views/movie/search.php
        require_once 'views/movie/search.php';
    }
    // Hiển thị trang danh sách tất cả phim
    public function list() {
        require_once 'models/Movie.php';
        $movieModel = new MovieModel();
        
        // Lấy 2 danh sách phim
        $nowShowing = $movieModel->getMoviesByStatus('now_showing');
        $comingSoon = $movieModel->getMoviesByStatus('coming_soon'); // Bạn cần đảm bảo Model có hàm này hoặc dùng chung getMoviesByStatus
        
        // Nếu Model chưa có hàm getMoviesByStatus, hãy dùng tạm hàm getNowShowing cho cả 2 hoặc bổ sung Model sau.
        // Ở bài trước mình đã hướng dẫn hàm getNowShowing, giờ mình giả định bạn dùng lại nó hoặc cập nhật Model.
        
        require_once 'views/movie/list.php';
    }
}
?>