<?php require_once 'views/layouts/header.php'; ?>

<style>
    body { background-color: #000; color: #fff; }
    
    .payment-container {
        max-width: 1000px;
        margin: 50px auto;
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    /* KHUNG THÔNG TIN VÉ (Bên Trái) */
    .ticket-info {
        flex: 1;
        background: #1f1f1f;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #333;
    }
    
    .movie-summary { display: flex; gap: 15px; border-bottom: 1px dashed #555; padding-bottom: 20px; margin-bottom: 20px; }
    .movie-poster { width: 100px; border-radius: 5px; }
    
    .row-item { display: flex; justify-content: space-between; margin-bottom: 10px; color: #ccc; }
    .row-item strong { color: white; }
    .total-price { font-size: 24px; color: #e50914; font-weight: bold; text-align: right; margin-top: 20px; }

    /* KHUNG THANH TOÁN (Bên Phải) */
    .payment-method {
        flex: 1;
        background: #fff; /* Nền trắng cho dễ nhìn QR */
        color: #333;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
    }

    .qr-placeholder {
        width: 200px;
        height: 200px;
        background-color: #f0f0f0;
        margin: 20px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #333;
    }
    
    .btn-confirm {
        background-color: #e50914; color: white; 
        width: 100%; padding: 15px; 
        border: none; border-radius: 5px; 
        font-size: 18px; font-weight: bold; cursor: pointer;
        margin-top: 20px; transition: 0.3s;
    }
    .btn-confirm:hover { background-color: #b20710; }
</style>

<div class="container">
    <h2 class="text-center" style="margin-top: 30px; border-bottom: 2px solid #e50914; display: inline-block;">
        XÁC NHẬN THANH TOÁN
    </h2>

    <div class="payment-container">
        
        <div class="ticket-info">
            <h4 style="color: #e50914; margin-bottom: 20px;">THÔNG TIN VÉ</h4>
            
            <div class="movie-summary">
                <img src="<?php echo htmlspecialchars($showtime['poster_url']); ?>" class="movie-poster">
                <div>
                    <h3 style="margin: 0; font-size: 22px;"><?php echo htmlspecialchars($showtime['title']); ?></h3>
                    <p style="color: #aaa; margin-top: 5px;"><?php echo htmlspecialchars($showtime['cinema_name']); ?></p>
                    <span style="background: #e50914; padding: 2px 8px; font-size: 12px; border-radius: 3px;">2D Phụ Đề</span>
                </div>
            </div>

            <div class="row-item">
                <span>Suất chiếu:</span>
                <strong><?php echo date('H:i - d/m/Y', strtotime($showtime['start_time'])); ?></strong>
            </div>
            <div class="row-item">
                <span>Phòng chiếu:</span>
                <strong><?php echo htmlspecialchars($showtime['screen_name']); ?></strong>
            </div>
            <div class="row-item">
                <span>Ghế đã chọn:</span>
                <strong style="color: #e50914; font-size: 18px;">
                    <?php echo implode(', ', $seats); ?>
                </strong>
            </div>
            
            <hr style="border-color: #444;">
            
            <div class="row-item">
                <span>Đơn giá:</span>
                <span><?php echo number_format($price_per_ticket); ?> đ</span>
            </div>
            <div class="row-item">
                <span>Số lượng:</span>
                <span><?php echo count($seats); ?> vé</span>
            </div>

            <div class="total-price">
                TỔNG TIỀN: <?php echo number_format($total_amount); ?> VNĐ
            </div>
        </div>

        <div class="payment-method">
            <h3>THANH TOÁN QR CODE</h3>
            <p>Vui lòng quét mã bên dưới để thanh toán</p>

            <?php 
                $qrContent = "Thanh toan ve xem phim: " . $showtime['title'] . " - Tong: " . $total_amount;
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrContent);
            ?>
            <img src="<?php echo $qrUrl; ?>" alt="QR Code" style="width: 200px; height: 200px; border: 1px solid #ddd;">
            
            <div style="margin-top: 15px; font-size: 14px; color: #555;">
                Nội dung chuyển khoản:<br>
                <strong style="font-size: 16px; color: #000;">VEPHIM <?php echo $showtime_id; ?> <?php echo rand(1000,9999); ?></strong>
            </div>

            <form action="index.php?page=booking&action=process" method="POST">
                <input type="hidden" name="showtime_id" value="<?php echo $showtime_id; ?>">
                <input type="hidden" name="total_price" value="<?php echo $total_amount; ?>">
                <input type="hidden" name="seats_list" value="<?php echo implode(',', $seats); ?>">

                <button type="submit" class="btn-confirm">
                    ✅ XÁC NHẬN ĐÃ CHUYỂN KHOẢN
                </button>
            </form>
            <a href="index.php?page=booking&action=seat&showtime_id=<?php echo $showtime_id; ?>" 
               style="display: block; margin-top: 15px; color: #666; text-decoration: underline;">
               Quay lại chọn ghế
            </a>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>