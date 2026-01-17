<?php
// 1. Bật chế độ báo lỗi mạnh nhất
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>BẮT ĐẦU KIỂM TRA HỆ THỐNG...</h1>";

// 2. Kiểm tra kết nối Database
try {
    require_once 'config/db.php';
    echo "<p style='color:green'>✅ File config/db.php: OK</p>";
} catch (Throwable $t) {
    die("<h2 style='color:red'>❌ Lỗi ở config/db.php: " . $t->getMessage() . "</h2>");
}

// 3. Kiểm tra Model ShowTime
try {
    require_once 'models/ShowTime.php';
    echo "<p style='color:green'>✅ File models/ShowTime.php: OK</p>";
} catch (Throwable $t) {
    die("<h2 style='color:red'>❌ Lỗi CÚ PHÁP ở models/ShowTime.php: " . $t->getMessage() . "</h2>");
}

// 4. Kiểm tra Model Booking
try {
    require_once 'models/Booking.php';
    echo "<p style='color:green'>✅ File models/Booking.php: OK</p>";
} catch (Throwable $t) {
    die("<h2 style='color:red'>❌ Lỗi CÚ PHÁP ở models/Booking.php: " . $t->getMessage() . "</h2>");
}

// 5. Kiểm tra Controller
try {
    require_once 'controllers/BookingController.php';
    echo "<p style='color:green'>✅ File controllers/BookingController.php: OK</p>";
} catch (Throwable $t) {
    die("<h2 style='color:red'>❌ Lỗi CÚ PHÁP ở controllers/BookingController.php: " . $t->getMessage() . "</h2>");
}

// 6. Giả lập chạy thử hàm Seat
echo "<h3>Đang thử khởi tạo Controller...</h3>";
try {
    // Giả lập tham số URL
    $_GET['showtime_id'] = 1; // Giả sử ID là 1
    // Giả lập đăng nhập user
    session_start();
    $_SESSION['user'] = ['id' => 1, 'name' => 'Test User'];

    $controller = new BookingController();
    echo "<p style='color:green'>✅ Khởi tạo Controller thành công</p>";
    
    // Gọi thử hàm seat (Nó sẽ cố load view, nếu lỗi sẽ hiện ra ngay)
    $controller->seat(); 

} catch (Throwable $t) {
    die("<h2 style='color:red'>❌ Lỗi khi chạy hàm: " . $t->getMessage() . "</h2><pre>" . $t->getTraceAsString() . "</pre>");
}
?>