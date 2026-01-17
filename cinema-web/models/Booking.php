<?php
require_once 'config/db.php';

class BookingModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getShowtimeDetail($id) {
        // Sửa st.price -> st.base_price để khớp với Database của bạn
        $sql = "SELECT st.showtime_id, st.start_time, st.base_price, 
                       m.title, m.poster_url, 
                       s.name as screen_name, s.screen_id,
                       c.name as cinema_name
                FROM ShowTimes st
                JOIN Movies m ON st.movie_id = m.movie_id
                JOIN Screens s ON st.screen_id = s.screen_id
                JOIN Cinemas c ON s.cinema_id = c.cinema_id
                WHERE st.showtime_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getBookedSeats($showtime_id) {
    // Sử dụng INNER JOIN để đảm bảo chỉ lấy những ghế thực sự đã được lưu vào vé
        $sql = "SELECT s.row_name + CAST(s.seat_number AS VARCHAR) as seat_name
            FROM BookingTickets bt
            INNER JOIN Bookings b ON bt.booking_id = b.booking_id
            INNER JOIN Seats s ON bt.seat_id = s.seat_id
            WHERE b.showtime_id = :showtime_id 
            AND b.status IN ('confirmed', 'success')"; 
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':showtime_id' => $showtime_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

// 3. Lưu đặt vé vào SSMS (Đã sửa lỗi khoảng trắng tên ghế)
    public function createBooking($user_id, $showtime_id, $total_amount, $seatList, $screen_id) {
        try {
            $this->conn->beginTransaction();

            // A. Chèn vào bảng Bookings
            $sql1 = "INSERT INTO Bookings (user_id, showtime_id, total_amount, status, booking_date, payment_method) 
                     VALUES (:user_id, :showtime_id, :total_amount, 'confirmed', GETDATE(), 'QR Code')";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([
                ':user_id' => $user_id,
                ':showtime_id' => $showtime_id,
                ':total_amount' => $total_amount
            ]);

            $booking_id = $this->conn->lastInsertId();

            // B. Chèn từng ghế vào BookingTickets
            foreach ($seatList as $seatName) {
                // QUAN TRỌNG: Cắt bỏ khoảng trắng thừa (ví dụ " A1" -> "A1")
                $seatName = trim($seatName); 
                
                if (empty($seatName)) continue; // Bỏ qua nếu chuỗi rỗng

                // Tách số ghế (A1 -> row: A, number: 1)
                // Logic: Lấy ký tự đầu làm hàng, phần còn lại làm số
                $row = substr($seatName, 0, 1);
                $num = (int)substr($seatName, 1);

                // Tìm seat_id trong DB
                $sqlSeat = "SELECT seat_id FROM Seats 
                            WHERE screen_id = :screen_id 
                            AND row_name = :row 
                            AND seat_number = :num";
                
                $stmtS = $this->conn->prepare($sqlSeat);
                $stmtS->execute([
                    ':screen_id' => $screen_id, 
                    ':row' => $row, 
                    ':num' => $num
                ]);
                
                $seatData = $stmtS->fetch(PDO::FETCH_ASSOC);

                if ($seatData) {
                    $sql2 = "INSERT INTO BookingTickets (booking_id, seat_id, price) 
                             VALUES (:booking_id, :seat_id, :price)";
                    $stmt2 = $this->conn->prepare($sql2);
                    $stmt2->execute([
                        ':booking_id' => $booking_id,
                        ':seat_id' => $seatData['seat_id'],
                        ':price' => ($total_amount / max(count($seatList), 1)) // Tránh chia cho 0
                    ]);
                } else {
                    // (Tùy chọn) Ghi log nếu không tìm thấy ghế để debug
                    error_log("Khong tim thay ghe: Row $row, Num $num, Screen $screen_id");
                }
            }

            $this->conn->commit();
            return $booking_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Loi dat ve: " . $e->getMessage());
            return false;
        }
    }

    public function getBookingHistory($user_id) {
    $sql = "SELECT 
                b.booking_id, 
                b.total_amount, 
                b.booking_date, 
                b.status,
                m.title, 
                s.start_time, 
                c.name as cinema_name, -- Lấy tên rạp từ bảng Cinemas
                STUFF((
                    SELECT ', ' + s2.row_name + CAST(s2.seat_number AS VARCHAR)
                    FROM BookingTickets bt2
                    JOIN Seats s2 ON bt2.seat_id = s2.seat_id
                    WHERE bt2.booking_id = b.booking_id
                    FOR XML PATH('')), 1, 2, '') AS seats_list
            FROM Bookings b
            JOIN ShowTimes s ON b.showtime_id = s.showtime_id
            JOIN Movies m ON s.movie_id = m.movie_id
            JOIN Screens sc ON s.screen_id = sc.screen_id -- Phải qua bảng Screens mới tới được Cinema
            JOIN Cinemas c ON sc.cinema_id = c.cinema_id -- Lấy Cinema từ Screen
            WHERE b.user_id = :user_id
            ORDER BY b.booking_date DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // --- CÁC HÀM THỐNG KÊ CHO ADMIN (ĐÃ SỬA CỘT CHO KHỚP) ---

    public function getTotalRevenue() {
        // Lưu ý: Đổi total_price thành total_amount vì SSMS bạn dùng total_amount
        $query = "SELECT SUM(total_amount) as total FROM Bookings";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getTotalTickets() {
        $query = "SELECT COUNT(*) as total FROM Bookings";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getRevenueByMovie() {
        // Đổi total_price -> total_amount
        $query = "SELECT m.title, COUNT(b.booking_id) as ticket_count, SUM(b.total_amount) as revenue
                  FROM Bookings b
                  JOIN ShowTimes st ON b.showtime_id = st.showtime_id
                  JOIN Movies m ON st.movie_id = m.movie_id
                  GROUP BY m.title, m.movie_id
                  ORDER BY revenue DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>