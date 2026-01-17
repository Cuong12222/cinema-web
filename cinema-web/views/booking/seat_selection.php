<?php require_once 'views/layouts/header.php'; ?>

<style>
    /* CSS MỚI - CHUẨN HÓA */
    body { background-color: #000; color: white; }
    
    .screen-container { 
        perspective: 1000px; 
        margin: 30px auto; 
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .screen {
        background: linear-gradient(to bottom, #fff, rgba(255,255,255,0));
        height: 60px; 
        width: 300px; 
        margin-bottom: 15px;
        transform: rotateX(-45deg); 
        box-shadow: 0 10px 30px rgba(255,255,255,0.3);
        border-radius: 10px;
    }

    .seats-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
    }

    .seat-row { 
        display: flex; 
        justify-content: center; 
        gap: 8px; /* Khoảng cách giữa các ghế */
    }

    /* GIAO DIỆN GHẾ */
    .seat-label {
        position: relative;
        width: 35px; 
        height: 35px;
        background-color: #444451; 
        border-radius: 6px;
        cursor: pointer; 
        font-size: 11px; 
        color: #aaa; 
        display: flex;
        align-items: center; 
        justify-content: center;
        transition: 0.2s all;
        user-select: none;
    }

    /* Ẩn cái ô checkbox xấu xí đi */
    .seat-label input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Hiệu ứng khi rê chuột */
    .seat-label:hover:not(.occupied) { 
        background-color: #666; 
        transform: scale(1.1); 
    }

    /* KHI GHẾ ĐƯỢC CHỌN (Màu đỏ) */
    .seat-label.selected { 
        background-color: #e50914; 
        color: white; 
        box-shadow: 0 0 10px rgba(229, 9, 20, 0.6);
        border: 1px solid #ff4d4d;
    }

    /* KHI GHẾ ĐÃ CÓ NGƯỜI MUA (Màu trắng) */
    .seat-label.occupied { 
        background-color: #fff; 
        color: #000; 
        cursor: not-allowed; 
        font-weight: bold;
    }

    /* Chú thích */
    .legend { display: flex; justify-content: center; gap: 20px; margin-top: 30px; color: #ccc; font-size: 14px; }
    .legend span { display: flex; align-items: center; gap: 8px; }
    .box { width: 20px; height: 20px; border-radius: 4px; display: inline-block; }

    /* Thanh thanh toán phía dưới */
    .booking-summary {
        background: #111;
        padding: 20px;
        border-top: 1px solid #333;
        margin-top: 40px;
        text-align: center;
    }
</style>

<div class="container" style="padding-bottom: 50px;">
    
    <div style="text-align: center; margin-top: 30px;">
        <h2 style="color: #e50914; font-weight: bold; text-transform: uppercase;">CHỌN GHẾ</h2>
        <p style="font-size: 16px;">
            Phim: <strong style="color: white;"><?php echo htmlspecialchars($showtime['title']); ?></strong>
        </p>
        <p style="color: #aaa; font-size: 14px;">
            <?php echo date('H:i | d/m/Y', strtotime($showtime['start_time'])); ?> 
            - <?php echo htmlspecialchars($showtime['cinema_name']); ?>
        </p>
    </div>

    <div class="screen-container">
        <div class="screen"></div>
        <small style="color: #777; letter-spacing: 2px;">MÀN HÌNH</small>
    </div>

    <form action="index.php?page=booking&action=payment" method="POST" id="bookingForm">
        
        <input type="hidden" name="showtime_id" value="<?php echo $showtime['showtime_id']; ?>">
        <input type="hidden" name="price" id="ticketPrice" value="<?php echo $showtime['base_price']; ?>">

        <div class="seats-container">
            <?php 
            $rows = ['A', 'B', 'C', 'D', 'E', 'F']; // Thêm hàng F cho nhiều ghế hơn
            foreach ($rows as $row) {
                echo '<div class="seat-row">';
                for ($i = 1; $i <= 8; $i++) {
                    $seatName = $row . $i;
                    
                    // Kiểm tra ghế đã đặt (giả sử $bookedSeats là mảng các ghế đã đặt)
                    $isBooked = in_array($seatName, $bookedSeats ?? []);
                    
                    if ($isBooked) {
                        // Ghế đã bán: Không có input, class occupied
                        echo "<div class='seat-label occupied'>$seatName</div>";
                    } else {
                        // Ghế trống: Có checkbox bên trong
                        // QUAN TRỌNG: Thẻ input nằm trong label
                        echo "
                        <label class='seat-label' id='label-$seatName'>
                            $seatName
                            <input type='checkbox' name='seats[]' value='$seatName' class='seat-checkbox'>
                        </label>";
                    }
                }
                echo '</div>';
            }
            ?>
        </div>

        <div class="legend">
            <span><div class="box" style="background:#444451"></div> Trống</span>
            <span><div class="box" style="background:#e50914"></div> Đang chọn</span>
            <span><div class="box" style="background:#fff"></div> Đã bán</span>
        </div>

        <div class="booking-summary">
            <p style="margin-bottom: 5px;">Ghế đang chọn:</p>
            <div id="selectedSeatsList" style="font-weight: bold; color: #e50914; font-size: 18px; min-height: 27px;">
                Chưa chọn ghế nào
            </div>
            
            <h3 style="margin: 15px 0;">
                Tổng tiền: <span id="totalPrice" style="color: #e50914;">0</span> VNĐ
            </h3>
            
            <button type="submit" class="btn btn-danger btn-lg" style="padding: 12px 60px; font-weight: bold; font-size: 18px; box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4);">
                THANH TOÁN NGAY
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Lấy tất cả checkbox ghế
    const checkboxes = document.querySelectorAll('.seat-checkbox');
    const pricePerTicket = parseFloat(document.getElementById('ticketPrice').value);
    
    // 2. Lắng nghe sự kiện thay đổi (Click vào ghế)
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            
            // Tìm thẻ label cha của nó
            const label = this.closest('.seat-label');
            
            // Nếu được check -> Thêm class selected (đổi màu đỏ)
            // Nếu bỏ check -> Xóa class selected
            if (this.checked) {
                label.classList.add('selected');
            } else {
                label.classList.remove('selected');
            }

            // Cập nhật lại tổng tiền
            updateSummary();
        });
    });

    // 3. Hàm tính toán tiền và hiển thị danh sách ghế
    function updateSummary() {
        // Lấy danh sách các checkbox ĐANG ĐƯỢC CHỌN
        const selected = document.querySelectorAll('.seat-checkbox:checked');
        
        // Lấy tên ghế (value của checkbox)
        const seatNames = Array.from(selected).map(cb => cb.value);
        
        // Cập nhật giao diện chữ
        const listElement = document.getElementById('selectedSeatsList');
        if (seatNames.length > 0) {
            listElement.innerText = seatNames.join(', ');
        } else {
            listElement.innerText = 'Chưa chọn ghế nào';
        }
        
        // Tính tiền
        const total = seatNames.length * pricePerTicket;
        
        // Format tiền kiểu Việt Nam (100.000)
        document.getElementById('totalPrice').innerText = new Intl.NumberFormat('vi-VN').format(total);
    }

    // 4. Chặn submit nếu chưa chọn ghế
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const selected = document.querySelectorAll('.seat-checkbox:checked');
        if (selected.length === 0) {
            e.preventDefault(); // Ngăn không cho gửi form
            alert('⚠️ Bạn ơi, vui lòng chọn ít nhất 1 ghế nhé!');
        }
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>