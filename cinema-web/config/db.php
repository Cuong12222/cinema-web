<?php
// config/db.php
require_once 'config.php';

class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Chuỗi kết nối dành cho SQL Server
            $dsn = "sqlsrv:Server=" . $this->host . ";Database=" . $this->db_name;
            
            // Cấu hình options cho PDO
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Báo lỗi dạng Exception
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Lấy dữ liệu dạng mảng kết hợp
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8 // Hỗ trợ Tiếng Việt
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // Trong môi trường Production, nên log lỗi ra file thay vì echo
            echo "Lỗi kết nối Database: " . $exception->getMessage();
            die();
        }

        return $this->conn;
    }
}
?>