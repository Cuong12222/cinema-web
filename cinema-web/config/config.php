<?php
// config/config.php

// Tên Server (thường là tên máy tính của bạn hoặc localhost)
// Nếu dùng SQL Server Express, thường là: TÊN_MÁY\SQLEXPRESS
define('DB_HOST', 'LAPTOP-1VG0BVKV'); // Hoặc 'DESKTOP-XXXX\SQLEXPRESS'

// Tên Database bạn đã tạo trong SQL Server
define('DB_NAME', 'CinemaDB');

// Tên đăng nhập SQL (mặc định 'sa')
define('DB_USER', 'sa');

// Mật khẩu SQL (bạn đặt lúc cài SQL Server)
define('DB_PASS', '123456'); // <--- Sửa lại cho đúng pass của bạn

// Đường dẫn gốc của website (quan trọng để load ảnh/css)
define('BASE_URL', 'http://localhost/cinema-web');
?>