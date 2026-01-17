<?php require_once 'views/layouts/header.php'; ?>

<div class="container text-center" style="padding: 100px 20px; color: white;">
    <div style="font-size: 100px; color: #28a745;">✔</div>
    <h1 style="color: #e50914; font-weight: bold;">ĐẶT VÉ THÀNH CÔNG!</h1>
    <p style="font-size: 18px;">Cảm ơn bạn đã tin tưởng dịch vụ của chúng tôi.</p>
    
    <div class="booking-info" style="background: #1f1f1f; padding: 20px; border-radius: 10px; display: inline-block; margin-top: 20px;">
        <p>Mã hóa đơn của bạn:</p>
        <h2 style="color: #ffc107;">#<?php echo htmlspecialchars($_GET['id'] ?? 'N/A'); ?></h2>
        <p class="text-muted small">Vui lòng kiểm tra lại thông tin trong lịch sử đặt vé.</p>
    </div>

    <div style="margin-top: 40px;">
        <a href="index.php?page=user&action=profile" class="btn btn-danger" style="padding: 10px 30px; margin-right: 15px;">
            XEM LỊCH SỬ VÉ
        </a>
        <a href="index.php" class="btn btn-outline-light" style="padding: 10px 30px;">
            VỀ TRANG CHỦ
        </a>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>