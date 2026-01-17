<?php
// models/User.php
require_once 'config/db.php';

class UserModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($fullname, $email, $password) {
        // 1. Mã hóa mật khẩu trước khi lưu (Bắt buộc để bảo mật)
        // Dùng thuật toán BCRYPT mặc định của PHP
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 2. Câu lệnh SQL chèn dữ liệu
        // LƯU Ý: Ở đây tôi dùng tên cột là 'password_hash' để khớp với lỗi bạn đang gặp
        $query = "INSERT INTO Users (full_name, email, password_hash, role) VALUES (:fullname, :email, :pass, 'user')";
        
        // Nếu database của bạn tên cột là 'password' thì sửa dòng trên thành:
        // ... (full_name, email, password, role) ...

        try {
            $stmt = $this->conn->prepare($query);

            // 3. Gán giá trị vào các tham số
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            
            // Quan trọng: Gán mật khẩu ĐÃ MÃ HÓA vào database
            $stmt->bindParam(':pass', $hashed_password);

            if ($stmt->execute()) {
                return true;
            }
            return false;

        } catch(PDOException $e) {
            // Nếu lỗi là do chưa có cột 'password_hash' mà là 'password'
            if (strpos($e->getMessage(), "Invalid column name 'password_hash'") !== false) {
                 // Thử lại với tên cột là 'password' (phòng trường hợp bạn có cả 2 phiên bản DB)
                 // Đây là cơ chế tự sửa lỗi thông minh
                 $query_fallback = "INSERT INTO Users (full_name, email, password, role) VALUES (:fullname, :email, :pass, 'user')";
                 $stmt = $this->conn->prepare($query_fallback);
                 $stmt->bindParam(':fullname', $fullname);
                 $stmt->bindParam(':email', $email);
                 $stmt->bindParam(':pass', $hashed_password);
                 return $stmt->execute();
            }
            
            // Ném lỗi ra ngoài để Debug Mode bắt được
            throw $e;
        }
    }

    public function login($email, $password) {
        // 1. Tìm user theo email
        $query = "SELECT * FROM Users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Nếu tìm thấy user, kiểm tra mật khẩu
        if ($user) {
            // Lấy mật khẩu từ DB (Ưu tiên cột password_hash, nếu không có thì lấy password)
            $db_pass = !empty($user['password_hash']) ? $user['password_hash'] : $user['password'];

            // SO SÁNH: Mật khẩu nhập vào vs Mật khẩu trong DB
            // Nếu password trong DB dài (đã mã hóa) -> dùng password_verify
            // Nếu password trong DB ngắn (cũ) -> so sánh trực tiếp
            if (strlen($db_pass) > 50) {
                if (password_verify($password, $db_pass)) return $user;
            } else {
                if ($password == $db_pass) return $user;
            }
        }
        
        return false; // Sai email hoặc sai mật khẩu
    }
}
?>