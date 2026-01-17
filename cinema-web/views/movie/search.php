<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    
    <div style="border-bottom: 1px solid #333; margin-bottom: 30px; padding-bottom: 10px;">
        <h2 style="color: white; margin: 0;">
            Kết quả tìm kiếm cho: <span style="color: #e50914;">"<?php echo htmlspecialchars($keyword); ?>"</span>
        </h2>
        <p style="color: #777; margin-top: 5px;">Tìm thấy <?php echo count($movies); ?> bộ phim.</p>
    </div>

    <div class="movie-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
        
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="movie-card" style="background: #1f1f1f; border-radius: 8px; overflow: hidden; transition: transform 0.3s;">
                    <a href="index.php?page=movie&action=detail&id=<?php echo $movie['movie_id']; ?>">
                        <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" 
                             alt="<?php echo htmlspecialchars($movie['title']); ?>" 
                             style="width: 100%; height: 300px; object-fit: cover;">
                    </a>
                    
                    <div class="card-body" style="padding: 15px;">
                        <h3 style="margin: 0 0 10px 0; font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <a href="index.php?page=movie&action=detail&id=<?php echo $movie['movie_id']; ?>" style="color: white; text-decoration: none;">
                                <?php echo htmlspecialchars($movie['title']); ?>
                            </a>
                        </h3>
                        
                        <div style="font-size: 0.9rem; color: #aaa; margin-bottom: 15px;">
                            <span>⏱ <?php echo $movie['duration_minutes']; ?> phút</span>
                            <span style="float: right;">⭐ <?php echo $movie['rating']; ?></span>
                        </div>

                        <a href="index.php?page=movie&action=detail&id=<?php echo $movie['movie_id']; ?>" 
                           style="display: block; text-align: center; background: #333; color: white; padding: 8px; text-decoration: none; border-radius: 4px; border: 1px solid #444;">
                           XEM CHI TIẾT
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px; color: #777;">
                <i class="fa-solid fa-film" style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;"></i>
                <h3>Không tìm thấy phim nào khớp với từ khóa.</h3>
                <p>Hãy thử tìm bằng từ khóa khác.</p>
                <a href="index.php" style="color: #e50914;">Quay về trang chủ</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>