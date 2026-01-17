<?php require_once 'views/admin/layout_header.php'; ?>

<h2 style="margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Tổng Quan Hệ Thống</h2>

<div style="display: flex; gap: 20px; margin-bottom: 40px;">
    
    <div style="flex: 1; background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin: 0; font-size: 1rem; opacity: 0.9;">TỔNG DOANH THU</h3>
        <p style="margin: 10px 0 0 0; font-size: 2rem; font-weight: bold;">
            <?php echo number_format($totalRevenue); ?> VNĐ
        </p>
    </div>

    <div style="flex: 1; background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin: 0; font-size: 1rem; opacity: 0.9;">VÉ ĐÃ BÁN</h3>
        <p style="margin: 10px 0 0 0; font-size: 2rem; font-weight: bold;">
            <?php echo number_format($totalTickets); ?>
        </p>
        <span style="font-size: 0.9rem;">vé</span>
    </div>

    <div style="flex: 1; background: linear-gradient(135deg, #9b59b6, #8e44ad); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin: 0; font-size: 1rem; opacity: 0.9;">PHIM ĐANG HOẠT ĐỘNG</h3>
        <p style="margin: 10px 0 0 0; font-size: 2rem; font-weight: bold;">
            <?php echo isset($totalMovies) ? $totalMovies : 0; ?>
        </p>
        <span style="font-size: 0.9rem;">phim</span>
    </div>
</div>

<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
    <h3 style="color: #2c3e50; margin-top: 0;">📊 Doanh thu theo phim</h3>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead style="background: #f8f9fa; color: #555;">
            <tr>
                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Tên Phim</th>
                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd;">Số vé bán</th>
                <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($movieStats)): ?>
                <?php foreach ($movieStats as $movie): ?>
                    <tr>
                        <td style="padding: 12px; text-align: left; border-bottom: 1px solid #eee; color: #2980b9; font-weight: 600;">
                            <?php echo htmlspecialchars($movie['title']); ?>
                        </td>
                        
                        <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">
                            <span style="background-color: #3498db; color: white; padding: 5px 10px; border-radius: 12px; font-size: 0.9rem;">
                                <?php echo $movie['total_sold']; ?> vé
                            </span>
                        </td>
                        
                        <td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee; font-weight: bold; color: #27ae60;">
                            <?php echo number_format($movie['total_revenue']); ?> đ
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px; color: #7f8c8d;">
                        Chưa có dữ liệu bán hàng.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 100px;"></div>