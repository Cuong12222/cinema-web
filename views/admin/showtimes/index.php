<?php require_once 'views/admin/layout_header.php'; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Quản lý Lịch Chiếu</h2>
    <a href="index.php?page=admin&action=showtimes_add" style="background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">+ Thêm Suất Chiếu</a>
</div>

<table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <thead>
        <tr style="background: #2c3e50; color: white; text-align: left;">
            <th style="padding: 12px;">ID</th>
            <th style="padding: 12px;">Tên Phim</th>
            <th style="padding: 12px;">Phòng Chiếu</th>
            <th style="padding: 12px;">Thời gian chiếu</th>
            <th style="padding: 12px;">Giá vé</th>
            <th style="padding: 12px;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($showtimes as $st): ?>
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px;">#<?php echo $st['showtime_id']; ?></td>
            <td style="padding: 12px; font-weight: bold; color: #2c3e50;"><?php echo htmlspecialchars($st['movie_title']); ?></td>
            <td style="padding: 12px;">
                <?php echo $st['cinema_name']; ?> - <strong><?php echo $st['screen_name']; ?></strong>
            </td>
            <td style="padding: 12px; color: #e67e22; font-weight: bold;">
                <?php echo date('H:i - d/m/Y', strtotime($st['start_time'])); ?>
            </td>
            <td style="padding: 12px;"><?php echo number_format($st['base_price']); ?> đ</td>
            <td style="padding: 12px;">
                <a href="index.php?page=admin&action=showtimes_delete&id=<?php echo $st['showtime_id']; ?>" 
                   onclick="return confirm('Bạn có chắc muốn xóa suất chiếu này?');"
                   style="color: red; text-decoration: none;">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>