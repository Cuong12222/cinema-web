<?php 
require_once 'views/layouts/header.php'; 

// 1. KIỂM TRA DỮ LIỆU
if (!$movie) {
    echo '<div class="container" style="padding: 50px; text-align: center; color: white;">
            <h2>🚫 Không tìm thấy phim!</h2>
            <a href="index.php" style="color: #e50914;">Quay lại trang chủ</a>
          </div>';
    require_once 'views/layouts/footer.php';
    exit;
}

// Hàm hỗ trợ in dữ liệu an toàn (tránh lỗi NULL và lỗi Undefined Key)
function safe($value, $default = 'Đang cập nhật') {
    return htmlspecialchars($value ?? $default);
}
?>

<div style="background-color: #000; color: white; min-height: 100vh; padding-bottom: 50px;">
    
    <div class="movie-banner" style="display: flex; gap: 40px; max-width: 1100px; margin: 0 auto; padding: 40px 20px; flex-wrap: wrap;">
        
        <div style="flex-shrink: 0; width: 300px; margin: 0 auto;">
            <img src="<?php echo safe($movie['poster_url'] ?? null, 'assets/images/no-poster.jpg'); ?>" 
                 alt="Poster" 
                 style="width: 100%; border-radius: 10px; box-shadow: 0 0 20px rgba(229, 9, 20, 0.5);">
        </div>

        <div style="flex-grow: 1;">
            <h1 style="font-size: 40px; color: #e50914; margin-top: 0;">
                <?php echo safe($movie['title'] ?? null); ?>
            </h1>
            
            <p><strong>Đạo diễn:</strong> <?php echo safe($movie['director'] ?? null); ?></p>
            <p><strong>Diễn viên:</strong> <?php echo safe($movie['actor'] ?? null); ?></p>
            <p><strong>Thời lượng:</strong> <?php echo safe($movie['duration_minutes'] ?? null, 0); ?> phút</p>
            <p><strong>Khởi chiếu:</strong> 
                <?php echo !empty($movie['release_date']) ? date('d/m/Y', strtotime($movie['release_date'])) : 'Đang cập nhật'; ?>
            </p>
            
            <div style="margin: 20px 0;">
                <span style="background: #e50914; padding: 5px 10px; border-radius: 4px; font-weight: bold;">
                    ⭐ 8.8
                </span>
                <span style="border: 1px solid #777; padding: 5px 10px; border-radius: 4px; margin-left: 10px; text-transform: uppercase;">
                    <?php echo (isset($movie['status']) && $movie['status'] == 'now_showing') ? 'Đang chiếu' : 'Sắp chiếu'; ?>
                </span>
            </div>

            <h3 style="border-bottom: 1px solid #333; padding-bottom: 10px; margin-top: 30px;">NỘI DUNG PHIM</h3>
            <p style="line-height: 1.6; color: #ccc;">
                <?php echo nl2br(safe($movie['description'] ?? null, 'Chưa có mô tả.')); ?>
            </p>

            <a href="#lich-chieu" 
               style="display: inline-block; margin-top: 25px; background: #e50914; color: white; padding: 15px 40px; text-decoration: none; font-weight: bold; font-size: 18px; border-radius: 5px; text-transform: uppercase; transition: 0.3s;">
                🎟️ ĐẶT VÉ NGAY
            </a>
        </div>
    </div>

    <div id="lich-chieu" class="container" style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
        <h3 style="color: #e50914; border-bottom: 2px solid #e50914; display: inline-block; padding-bottom: 5px;">
            LỊCH CHIẾU
        </h3>
        
        <div style="margin-top: 20px;">
            <?php if (empty($showtimes)): ?>
                <div style="background: #1f1f1f; padding: 30px; border-radius: 8px; text-align: center; border: 1px dashed #555;">
                    <p style="color: #bbb; margin-bottom: 10px; font-size: 16px;">
                        🚫 Hiện chưa có lịch chiếu cho phim này.
                    </p>
                    <small style="color: #777;">(Vui lòng quay lại sau hoặc chọn phim khác)</small>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px;">
                    <?php foreach ($showtimes as $st): ?>
                        
                        <a href="index.php?page=booking&action=seat&showtime_id=<?php echo $st['showtime_id']; ?>" 
                           style="background: #1f1f1f; color: white; padding: 15px; text-align: center; text-decoration: none; border-radius: 8px; border: 1px solid #333; transition: 0.3s; display: block;">
                            
                            <div style="font-size: 20px; font-weight: bold; color: #e50914;">
                                <?php echo date('H:i', strtotime($st['start_time'])); ?>
                            </div>
                            
                            <div style="font-size: 13px; color: #aaa; margin-top: 5px; border-top: 1px solid #333; padding-top: 5px;">
                                📅 <?php echo date('d/m/Y', strtotime($st['start_time'])); ?>
                            </div>
                            
                            <div style="font-size: 12px; color: #ccc; margin-top: 5px;">
                                📍 <?php echo htmlspecialchars($st['cinema_name'] ?? 'Rạp chiếu'); ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once 'views/layouts/footer.php'; ?>