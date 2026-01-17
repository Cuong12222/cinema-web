<?php require_once 'views/layouts/header.php'; ?>

<style>
    /* Bố cục chính */
    .profile-container {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
        max-width: 1200px;
        margin: 50px auto;
        padding: 0 20px;
        color: #e0e0e0;
        min-height: 80vh;
    }

    /* --- SIDEBAR USER --- */
    .profile-sidebar {
        background: #181818;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        border: 1px solid #333;
        height: fit-content;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    .avatar-circle {
        width: 100px; height: 100px;
        background: #1f1f1f; border-radius: 50%;
        margin: 0 auto 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 36px; font-weight: bold; color: #e50914; border: 3px solid #e50914;
        text-transform: uppercase;
    }
    .user-stats {
        margin-top: 20px; border-top: 1px solid #333; padding-top: 20px;
        text-align: left; color: #aaa; font-size: 14px;
    }
    .user-stats p { margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }

    /* --- MAIN CONTENT --- */
    .profile-content {
        background: #181818; border-radius: 12px; border: 1px solid #333;
        overflow: hidden; min-height: 500px;
    }
    
    /* TABS */
    .profile-tabs {
        display: flex; border-bottom: 1px solid #333; background: #222;
    }
    .tab-btn {
        padding: 15px 25px; background: none; border: none;
        color: #888; font-weight: 600; cursor: pointer; font-size: 15px;
        border-bottom: 3px solid transparent; transition: 0.3s;
    }
    .tab-btn:hover { color: #fff; }
    .tab-btn.active {
        color: #e50914; border-bottom: 3px solid #e50914; background: #2a2a2a;
    }
    .tab-pane { display: none; padding: 30px; animation: fadeIn 0.5s; }
    .tab-pane.active { display: block; }

    /* --- TICKET CARD 3D HOLOGRAM (ĐẶC BIỆT) --- */
    .ticket-wrapper {
        perspective: 1000px; /* Tạo chiều sâu 3D */
        margin-bottom: 25px;
    }

    .ticket-card {
        display: flex;
        background: linear-gradient(135deg, #2b2b2b 0%, #1a1a1a 100%);
        border-radius: 12px;
        border: 1px solid #444;
        overflow: hidden;
        position: relative;
        transition: transform 0.1s ease, box-shadow 0.3s ease;
        transform-style: preserve-3d; /* Giữ các phần tử con ở dạng 3D */
        box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        cursor: default;
    }

    /* Hiệu ứng bóng sáng (Glare) lướt qua */
    .ticket-card::after {
        content: "";
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(125deg, transparent 20%, rgba(255,255,255,0.1) 30%, transparent 40%);
        z-index: 2; pointer-events: none; opacity: 0; transition: opacity 0.3s;
    }
    .ticket-card:hover::after { opacity: 1; }
    .ticket-card:hover {
        border-color: #e50914; /* Viền đỏ khi hover */
        box-shadow: 0 20px 40px rgba(0,0,0,0.6), 0 0 15px rgba(229, 9, 20, 0.3);
    }

    /* Các phần tử bên trong nổi lên (3D Pop-up) */
    .ticket-poster, .ticket-info, .ticket-price-box {
        transform: translateZ(20px); /* Nổi lên 20px so với nền */
        z-index: 5;
    }

    .ticket-poster {
        width: 100px; object-fit: cover; border-right: 2px dashed #444;
        box-shadow: 5px 0 15px rgba(0,0,0,0.3);
    }
    .ticket-info {
        flex: 1; padding: 20px;
        display: flex; flex-direction: column; justify-content: center;
        position: relative;
    }
    /* Chấm tròn trang trí vé */
    .ticket-info::before, .ticket-info::after {
        content: ''; position: absolute; right: -8px; width: 16px; height: 16px;
        background: #181818; border-radius: 50%; z-index: 0;
    }
    .ticket-info::before { top: -8px; }
    .ticket-info::after { bottom: -8px; }

    .ticket-price-box {
        width: 140px; display: flex; flex-direction: column;
        align-items: center; justify-content: center; padding: 15px;
        background: rgba(0,0,0,0.2);
        border-left: 2px dashed #444;
    }

    /* --- RESPONSIVE MOBILE --- */
    @media (max-width: 768px) {
        .profile-container { grid-template-columns: 1fr; }
        .ticket-wrapper { perspective: none; } /* Tắt 3D trên mobile */
        .ticket-card { flex-direction: column; transform: none !important; }
        .ticket-poster { width: 100%; height: 160px; border-right: none; border-bottom: 2px dashed #444; }
        .ticket-info { border-right: none; border-bottom: 2px dashed #444; padding: 15px; }
        .ticket-price-box { width: 100%; flex-direction: row; justify-content: space-between; border-left: none; }
        .ticket-info::before, .ticket-info::after { display: none; }
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="profile-container">
    
    <aside class="profile-sidebar" data-aos="fade-right">
        <?php 
            // Lấy dữ liệu user an toàn
            $uName = $_SESSION['user']['username'] ?? $_SESSION['user']['full_name'] ?? 'Khách';
            $uFullname = $_SESSION['user']['full_name'] ?? $uName;
            $uEmail = $_SESSION['user']['email'] ?? 'Chưa cập nhật email';
            $uPhone = $_SESSION['user']['phone_number'] ?? $_SESSION['user']['phone'] ?? 'Chưa cập nhật số';
        ?>
        <div class="avatar-circle">
            <?php echo substr($uName, 0, 1); ?>
        </div>
        <h3 style="color: white; margin-bottom: 5px;"><?php echo htmlspecialchars($uFullname); ?></h3>
        <p style="font-size: 13px; color: #888;">ID: <?php echo $_SESSION['user']['user_id'] ?? '#'; ?></p>

        <div class="user-stats">
            <p><i class="fas fa-envelope" style="color: #e50914;"></i> <?php echo htmlspecialchars($uEmail); ?></p>
            <p><i class="fas fa-phone" style="color: #e50914;"></i> <?php echo htmlspecialchars($uPhone); ?></p>
            <p><i class="fas fa-star" style="color: #e50914;"></i> Hạng: Thành viên</p>
        </div>

        <button style="width: 100%; margin-top: 20px; padding: 12px; background: #333; color: white; border: none; border-radius: 6px; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#444'" onmouseout="this.style.background='#333'">
            <i class="fas fa-cog"></i> Cài đặt tài khoản
        </button>
    </aside>

    <main class="profile-content" data-aos="fade-left">
        <div class="profile-tabs">
            <button class="tab-btn active" onclick="openTab(event, 'historyTab')"><i class="fas fa-ticket-alt"></i> Bộ sưu tập vé</button>
            <button class="tab-btn" onclick="openTab(event, 'postsTab')"><i class="fas fa-pen-fancy"></i> Bài viết của tôi</button>
        </div>

        <div id="historyTab" class="tab-pane active">
            <h4 style="margin-bottom: 25px; color: #fff; text-transform: uppercase; letter-spacing: 1px;">Vé đã mua gần đây</h4>

            <?php if (!empty($history)): ?>
                <?php foreach ($history as $ticket): ?>
                    <?php 
                        // 1. Phim & Ảnh
                        $tTitle = $ticket['title'] ?? 'Tên phim đang cập nhật';
                        $tPoster = $ticket['poster_url'] ?? 'https://via.placeholder.com/100x150?text=Cinema';
                        
                        // 2. Thời gian (Xử lý chuỗi DateTime từ SQL)
                        $rawTime = $ticket['start_time'] ?? 'now';
                        $tDate = date('d/m/Y', strtotime($rawTime));
                        $tTime = date('H:i', strtotime($rawTime));
                        
                        // 3. Rạp & Phòng
                        $tCinema = $ticket['cinema_name'] ?? 'Rạp trung tâm';
                        $tScreen = $ticket['screen_name'] ?? 'P.Chiếu';

                        // 4. Ghế (Chuỗi gộp từ SQL STRING_AGG)
                        $tSeats = $ticket['seat_numbers'] ?? '...';
                        
                        // 5. Giá & Trạng thái
                        $tPrice = $ticket['total_amount'] ?? 0;
                        $tStatus = $ticket['status'] ?? 'pending';
                        $statusText = ($tStatus == 'confirmed') ? 'ĐÃ THANH TOÁN' : 'ĐANG XỬ LÝ';
                        $statusColor = ($tStatus == 'confirmed') ? '#2ecc71' : '#f1c40f';
                    ?>

                    <div class="ticket-wrapper">
                        <div class="ticket-card js-tilt">
                            <img src="<?php echo htmlspecialchars($tPoster); ?>" class="ticket-poster" alt="Poster">
                            
                            <div class="ticket-info">
                                <h3 style="color: #e50914; margin: 0 0 10px 0; font-size: 20px; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">
                                    <?php echo htmlspecialchars($tTitle); ?>
                                </h3>
                                <div style="font-size: 14px; color: #ccc; line-height: 1.7;">
                                    <div><i class="far fa-calendar-alt" style="width: 20px; color: #888;"></i> <b style="color: white;"><?php echo $tDate; ?></b> lúc <?php echo $tTime; ?></div>
                                    <div><i class="fas fa-map-marker-alt" style="width: 20px; color: #888;"></i> <?php echo htmlspecialchars($tCinema); ?> - <?php echo htmlspecialchars($tScreen); ?></div>
                                    <div><i class="fas fa-couch" style="width: 20px; color: #888;"></i> Ghế: <b style="color: #e50914; font-size: 16px;"><?php echo htmlspecialchars($tSeats); ?></b></div>
                                </div>
                            </div>

                            <div class="ticket-price-box">
                                <span style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 8px;">
                                    <?php echo number_format($tPrice, 0, ',', '.'); ?>đ
                                </span>
                                <span style="background: <?php echo $statusColor; ?>; color: #000; font-size: 11px; font-weight: 700; padding: 5px 10px; border-radius: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                    <?php echo $statusText; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 60px; color: #666; border: 2px dashed #333; border-radius: 12px;">
                    <i class="fas fa-ticket-alt" style="font-size: 50px; margin-bottom: 20px; color: #444;"></i>
                    <p>Bạn chưa có vé nào trong bộ sưu tập.</p>
                    <a href="index.php?page=movie" style="color: #e50914; font-weight: bold;">Mua vé ngay</a>
                </div>
            <?php endif; ?>
        </div>

        <div id="postsTab" class="tab-pane">
            <h4 style="margin-bottom: 20px; color: #fff;">Hoạt động cộng đồng</h4>
            <?php if (!empty($myPosts)): ?>
                <?php foreach ($myPosts as $post): ?>
                    <div style="background: #222; padding: 20px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #e50914;">
                        <div style="display: flex; justify-content: space-between; font-size: 12px; color: #888; margin-bottom: 10px;">
                            <span><?php echo date('d/m/Y H:i', strtotime($post['created_at'] ?? 'now')); ?></span>
                            <span><i class="fas fa-heart" style="color: #e50914;"></i> <?php echo $post['like_count'] ?? 0; ?></span>
                        </div>
                        <p style="color: #eee; font-size: 15px; line-height: 1.5;">
                            <?php echo nl2br(htmlspecialchars($post['content'] ?? '')); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; color: #666; padding: 40px;">
                    <i class="fas fa-comment-slash" style="font-size: 40px; margin-bottom: 10px;"></i>
                    <p>Chưa có bài viết nào.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // 1. Khởi tạo AOS
    AOS.init({ duration: 800, once: true });

    // 2. Logic chuyển Tab
    function openTab(evt, tabName) {
        var i, tabContent, tabLinks;
        tabContent = document.getElementsByClassName("tab-pane");
        for (i = 0; i < tabContent.length; i++) {
            tabContent[i].style.display = "none";
            tabContent[i].classList.remove("active");
        }
        tabLinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tabLinks.length; i++) {
            tabLinks[i].className = tabLinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.className += " active";
    }

    // 3. LOGIC 3D TILT EFFECT (Hiệu ứng nghiêng vé theo chuột)
    document.addEventListener("DOMContentLoaded", function() {
        const cards = document.querySelectorAll('.js-tilt');

        cards.forEach(card => {
            card.addEventListener('mousemove', function(e) {
                // Tắt hiệu ứng trên điện thoại
                if(window.innerWidth < 768) return;

                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left; // Tọa độ X trong thẻ
                const y = e.clientY - rect.top;  // Tọa độ Y trong thẻ
                
                // Tính tâm thẻ
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                // Tính góc xoay (chia số càng lớn -> xoay càng nhẹ)
                const rotateX = (centerY - y) / 15; 
                const rotateY = (x - centerX) / 15;

                // Áp dụng xoay + Scale nhẹ
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
                
                // Di chuyển ánh sáng (background) ngược chiều chuột một chút để tạo độ sâu
                card.style.backgroundPosition = `${x/5}% ${y/5}%`;
            });

            // Khi chuột rời khỏi -> Reset về bình thường
            card.addEventListener('mouseleave', function() {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
                card.style.transition = 'transform 0.5s ease';
            });
            
            // Xóa transition khi chuột vào để di chuyển mượt mà không bị trễ
            card.addEventListener('mouseenter', function() {
                card.style.transition = 'none';
            });
        });
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>