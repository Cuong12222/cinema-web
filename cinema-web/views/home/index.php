<?php require_once 'views/layouts/header.php'; ?>

<div class="hero-slider" id="heroSlider">
    <div class="slide-item active" style="background-image: url('https://image.tmdb.org/t/p/original/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg');">
        <div class="overlay-gradient"></div>
        <div class="slide-content" data-aos="fade-up" data-aos-duration="1000">
            <h1 style="font-size: 3.5rem; font-weight: bold; margin-bottom: 10px; color: #fff;">DUNE: PART TWO</h1>
            <p style="font-size: 1.2rem; margin-bottom: 20px; color: #ddd;">Siêu phẩm hành động viễn tưởng định dạng IMAX. Cuộc chiến giành lấy vận mệnh thiên hà.</p>
            <a href="#" class="btn-glow" style="background: #e50914; color: white; padding: 12px 30px; text-decoration: none; border-radius: 30px; font-weight: bold; font-size: 1.1rem; border: none;">
                <i class="fas fa-ticket-alt"></i> ĐẶT VÉ NGAY
            </a>
        </div>
    </div>
    
    <div class="slide-item" style="background-image: url('https://image.tmdb.org/t/p/original/kYgQzzjNis5jJalYtIHgromRGgE.jpg');">
        <div class="overlay-gradient"></div>
        <div class="slide-content">
            <h1 style="font-size: 3.5rem; font-weight: bold; margin-bottom: 10px; color: #fff;">KUNG FU PANDA 4</h1>
            <p style="font-size: 1.2rem; margin-bottom: 20px; color: #ddd;">Gấu Po trở lại! Chuyến phiêu lưu hài hước và kịch tính nhất từ trước đến nay.</p>
            <a href="#" class="btn-glow" style="background: #e50914; color: white; padding: 12px 30px; text-decoration: none; border-radius: 30px; font-weight: bold; font-size: 1.1rem; border: none;">
                <i class="fas fa-ticket-alt"></i> MUA VÉ NGAY
            </a>
        </div>
    </div>

    <div class="slide-item" style="background-image: url('https://image.tmdb.org/t/p/original/j3Z3XktmWB1VqkS8iAoIn1nMRRU.jpg');">
        <div class="overlay-gradient"></div>
        <div class="slide-content">
            <h1 style="font-size: 3.5rem; font-weight: bold; margin-bottom: 10px; color: #fff;">GODZILLA x KONG</h1>
            <p style="font-size: 1.2rem; margin-bottom: 20px; color: #ddd;">Đế chế mới trỗi dậy. Hai vị vua phải hợp sức để chống lại mối đe dọa chung.</p>
            <a href="#" class="btn-glow" style="background: #e50914; color: white; padding: 12px 30px; text-decoration: none; border-radius: 30px; font-weight: bold; font-size: 1.1rem; border: none;">
                <i class="fas fa-info-circle"></i> XEM CHI TIẾT
            </a>
        </div>
    </div>
</div>

<div class="container" style="padding: 50px 20px; max-width: 1200px; margin: 0 auto;">
    
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;" data-aos="fade-right">
        <h2 style="color: #e50914; border-left: 5px solid #e50914; padding-left: 15px; margin: 0; text-transform: uppercase;">
            PHIM ĐANG CHIẾU
        </h2>
        <a href="index.php?page=movie&action=list" style="color: #888; text-decoration: none; font-size: 14px;">Xem tất cả <i class="fas fa-arrow-right"></i></a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px;">
        
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $index => $movie): ?>
                <div class="movie-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    
                    <a href="index.php?page=movie&action=detail&id=<?php echo $movie['movie_id']; ?>" style="display: block; position: relative; padding-top: 150%;">
                        <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" 
                             alt="<?php echo htmlspecialchars($movie['title']); ?>" 
                             style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                        
                        <span style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.7); color: #ffc107; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                            <i class="fas fa-star"></i> <?php echo $movie['rating']; ?>
                        </span>
                    </a>
                    
                    <div style="padding: 15px; background: #1f1f1f;">
                        <h3 style="margin: 0 0 8px 0; font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <a href="index.php?page=movie&action=detail&id=<?php echo $movie['movie_id']; ?>" style="color: white; text-decoration: none;">
                                <?php echo htmlspecialchars($movie['title']); ?>
                            </a>
                        </h3>
                        
                        <div style="font-size: 13px; color: #aaa; margin-bottom: 15px; display: flex; justify-content: space-between;">
                            <span><i class="far fa-clock"></i> <?php echo $movie['duration_minutes']; ?>'</span>
                            <span>2D Phụ đề</span>
                        </div>

                        <?php if (!empty($movie['showtime_id']) && $movie['showtime_id'] > 0): ?>
                            <a href="index.php?page=booking&action=select_seats&showtime_id=<?php echo $movie['showtime_id']; ?>" class="btn btn-danger w-100">
                                <i class="fas fa-ticket-alt"></i> Mua vé
                            </a>
                        <?php else: ?>
                            <a href="index.php?page=movie&action=detail&id=<?php echo $movie['movie_id']; ?>" class="btn btn-secondary w-100">
                                <i class="fas fa-info-circle"></i> Chi tiết
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: white; grid-column: 1/-1; text-align: center;">Hiện chưa có phim nào trong hệ thống.</p>
        <?php endif; ?>

    </div>
</div>

<div style="height: 50px;"></div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // 1. Khởi tạo thư viện Animation
    AOS.init({
        duration: 800, // Tốc độ hiệu ứng (ms)
        once: true,    // Chỉ chạy 1 lần khi cuộn tới
        offset: 100    // Cách mép dưới 100px thì bắt đầu chạy
    });

    // 2. Logic xử lý Slider (Carousel)
    document.addEventListener("DOMContentLoaded", function() {
        let slides = document.querySelectorAll('.slide-item');
        let currentSlide = 0;
        const slideInterval = 5000; // 5 giây đổi ảnh 1 lần

        function nextSlide() {
            // Bỏ class active ở slide hiện tại
            slides[currentSlide].classList.remove('active');
            
            // Tính toán slide tiếp theo
            currentSlide = (currentSlide + 1) % slides.length;
            
            // Thêm class active cho slide mới
            slides[currentSlide].classList.add('active');
        }

        // Cài đặt bộ đếm thời gian
        if(slides.length > 0) {
            setInterval(nextSlide, slideInterval);
        }
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>