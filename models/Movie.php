<?php
// models/Movie.php
require_once 'config/db.php';

class MovieModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy phim đang chiếu
    public function getNowShowing($limit = 8) {
        $query = "SELECT TOP " . (int)$limit . " * FROM Movies WHERE status = 'now_showing' ORDER BY release_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy phim sắp chiếu
    public function getComingSoon($limit = 8) {
        $query = "SELECT TOP " . (int)$limit . " * FROM Movies WHERE status = 'coming_soon' ORDER BY release_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

// Lấy chi tiết 1 phim theo ID
    public function getMovieById($id) {
        $query = "SELECT * FROM Movies WHERE movie_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ... trong class MovieModel ...

    // Lấy tất cả phim (cho admin)
    public function getAllMovies() {
        $query = "SELECT * FROM Movies ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ... code cũ ...

    // ADMIN: Thêm phim mới
    public function addMovie($title, $description, $director, $cast, $duration, $release_date, $poster_url, $trailer_url) {
        $query = "INSERT INTO Movies (title, description, director, cast, duration_minutes, release_date, poster_url, trailer_url, status) 
                  VALUES (:title, :description, :director, :cast, :duration, :release_date, :poster_url, :trailer_url, 'now_showing')";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':director', $director);
        $stmt->bindParam(':cast', $cast);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':release_date', $release_date);
        $stmt->bindParam(':poster_url', $poster_url);
        $stmt->bindParam(':trailer_url', $trailer_url);

        return $stmt->execute();
    }

    // ADMIN: Xóa phim
    public function deleteMovie($id) {
        $query = "DELETE FROM Movies WHERE movie_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    // ... code cũ ...

    // Tìm kiếm phim theo tên hoặc mô tả
    public function searchMovies($keyword) {
        $keyword = "%{$keyword}%"; // Thêm dấu % để tìm kiếm gần đúng
        $query = "SELECT * FROM Movies 
                  WHERE title LIKE :k1 OR description LIKE :k2 
                  ORDER BY release_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':k1', $keyword);
        $stmt->bindParam(':k2', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
// Thêm vào models/Movie.php
    public function getMoviesByStatus($status) {
        // status: 'now_showing' hoặc 'coming_soon'
        $query = "SELECT * FROM Movies WHERE status = :status ORDER BY release_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 
?>