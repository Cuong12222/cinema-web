<?php
require_once 'config/db.php';

class ShowTimeModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
// File: models/ShowTime.php

    public function getAllShowtimes() {
        // ĐÃ XÓA DÒNG: WHERE st.start_time >= GETDATE()
        // Để Admin nhìn thấy được tất cả lịch chiếu vừa thêm
        $query = "SELECT st.*, 
                        m.title as movie_title, 
                        c.name as cinema_name, 
                        sc.name as screen_name
                FROM ShowTimes st
                JOIN Movies m ON st.movie_id = m.movie_id
                JOIN Screens sc ON st.screen_id = sc.screen_id
                JOIN Cinemas c ON sc.cinema_id = c.cinema_id
                ORDER BY st.start_time DESC"; // Sắp xếp cái mới nhất lên đầu

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // 2. Chi tiết lịch chiếu (Giữ nguyên logic cũ)
    public function getShowtimeDetail($id) {
        $query = "SELECT st.showtime_id, st.start_time, st.base_price, 
                          m.title, m.poster_url, 
                          c.name as cinema_name, s.name as screen_name 
                  FROM ShowTimes st
                  JOIN Movies m ON st.movie_id = m.movie_id
                  JOIN Screens s ON st.screen_id = s.screen_id
                  JOIN Cinemas c ON s.cinema_id = c.cinema_id
                  WHERE st.showtime_id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Lấy lịch chiếu theo ID PHIM (Giữ nguyên logic cũ)
    public function getShowtimesByMovie($movieId) {
        $query = "SELECT st.*, c.name as cinema_name 
                FROM ShowTimes st
                JOIN Screens sc ON st.screen_id = sc.screen_id
                JOIN Cinemas c ON sc.cinema_id = c.cinema_id
                WHERE st.movie_id = :movie_id 
                -- ĐÃ BỎ DÒNG: AND st.start_time >= GETDATE() để hiện mọi lịch chiếu test
                ORDER BY st.start_time ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':movie_id', $movieId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Lấy danh sách phòng
    public function getAllScreens() {
        $sql = "SELECT s.screen_id, s.name AS screen_name, s.total_seats, c.name AS cinema_name 
                FROM Screens s
                JOIN Cinemas c ON s.cinema_id = c.cinema_id
                ORDER BY c.name ASC, s.name ASC";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Thêm suất chiếu mới (ĐÃ FIX LỖI NGÀY THÁNG TẠI ĐÂY)
// Trong file models/ShowTime.php

    public function createShowtime($movie_id, $screen_id, $start_time, $price) {
    // 1. Xử lý ngày tháng (Fix lỗi convert string cũ)
    $timestamp = strtotime($start_time);
    $formatted_start_time = date('Y-m-d H:i:s', $timestamp);

    // 2. Lấy thời lượng phim
    $sqlMovie = "SELECT duration_minutes FROM Movies WHERE movie_id = :movie_id";
    $stmtMovie = $this->conn->prepare($sqlMovie);
    $stmtMovie->bindParam(':movie_id', $movie_id);
    $stmtMovie->execute();
    $movie = $stmtMovie->fetch(PDO::FETCH_ASSOC);

    // KIỂM TRA QUAN TRỌNG: Nếu phim không tìm thấy hoặc không có thời lượng
    if (!$movie) {
        die("❌ LỖI: Không tìm thấy ID phim này trong Database.");
    }
    
    // Nếu duration là NULL hoặc 0 thì báo lỗi ngay
    if (empty($movie['duration_minutes'])) {
        die("❌ LỖI: Phim này chưa cập nhật 'Thời lượng' (duration_minutes). Vui lòng vào Quản Lý Phim để điền thời lượng (ví dụ: 120 phút) trước khi tạo lịch chiếu.");
    }

    $duration = (int)$movie['duration_minutes'];
    $endTimeStr = date('Y-m-d H:i:s', $timestamp + ($duration * 60));

    // 3. Thực hiện Insert và BẮT LỖI SQL
    try {
        $sql = "INSERT INTO ShowTimes (movie_id, screen_id, start_time, end_time, base_price) 
                VALUES (:movie_id, :screen_id, :start_time, :end_time, :price)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':movie_id', $movie_id);
        $stmt->bindParam(':screen_id', $screen_id);
        $stmt->bindParam(':start_time', $formatted_start_time);
        $stmt->bindParam(':end_time', $endTimeStr);
        $stmt->bindParam(':price', $price);
        
        if ($stmt->execute()) {
            return true;
        } else {
            // In lỗi SQL cụ thể ra màn hình
            echo "<pre>";
            print_r($stmt->errorInfo());
            echo "</pre>";
            die("❌ Lỗi SQL khi Execute");
        }
    } catch (PDOException $e) {
        die("❌ Lỗi Exception: " . $e->getMessage());
    }
}

    // 6. Xóa suất chiếu
    public function deleteShowtime($id) {
        $sql = "DELETE FROM ShowTimes WHERE showtime_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>