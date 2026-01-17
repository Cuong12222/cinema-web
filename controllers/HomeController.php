<?php
require_once 'models/Movie.php';

class HomeController {
    public function index() {
        $movieModel = new MovieModel();
        
        // Lấy danh sách phim từ Model
        $movies = $movieModel->getAllMovies();

        // Gửi dữ liệu $movies sang View để hiển thị
        require_once 'views/home/index.php';
    }
    public function promo() {
        require_once 'views/home/promo.php';
    }
}
?>