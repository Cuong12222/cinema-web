<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="max-width: 1000px; margin: 40px auto; padding: 20px; color: white;">
    <h2 style="border-left: 5px solid #e50914; padding-left: 15px; margin-bottom: 30px;">LỊCH CHIẾU PHIM</h2>

    <?php if (empty($showtimes)): ?>
        <p style="text-align: center; color: #999;">Hiện chưa có suất chiếu nào đang mở.</p>
    <?php else: ?>

    <table style="width: 100%; border-collapse: collapse; background: #1f1f1f; border-radius: 8px; overflow: hidden;">
        <thead>
            <tr style="background: #333; text-align: left;">
                <th style="padding: 15px;">Thời gian</th>
                <th style="padding: 15px;">Phim</th>
                <th style="padding: 15px;">Rạp</th>
                <th style="padding: 15px; text-align: right;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($showtimes as $st): ?>
            <tr style="border-bottom: 1px solid #333;">
                <td style="padding: 15px;">
                    <span style="color: #e50914; font-weight: bold; font-size: 1.2rem;">
                        <?php echo date('H:i', strtotime($st['start_time'])); ?>
                    </span>
                    <br>
                    <small style="color: #888;">
                        <?php echo date('d/m/Y', strtotime($st['start_time'])); ?>
                    </small>
                </td>
                
                <td style="padding: 15px; font-weight: bold; font-size: 1.1rem;">
                    <?php echo htmlspecialchars($st['movie_title'] ?? $st['title'] ?? 'Phim chưa rõ'); ?>
                </td>
                
                <td style="padding: 15px; color: #ccc;">
                    <?php echo htmlspecialchars($st['cinema_name'] ?? 'Rạp chính'); ?> 
                    - <?php echo htmlspecialchars($st['screen_name'] ?? 'Phòng 1'); ?>
                </td>
                
                <td style="padding: 15px; text-align: right;">
                    <a href="index.php?page=booking&action=seat&showtime_id=<?php echo $st['showtime_id']; ?>" 
                        style="background: #e50914; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                        ĐẶT VÉ
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>