<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    
    <div style="border-bottom: 2px solid #333; margin-bottom: 30px; padding-bottom: 10px;">
        <h2 style="color: #e50914; text-transform: uppercase; margin: 0;">Kho Phim</h2>
    </div>

    <h3 style="color: white; margin-bottom: 20px;">🔥 Phim Đang Chiếu</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px; margin-bottom: 50px;">
        <?php foreach ($nowShowing as $movie): ?>
            <div class="movie-card" style="background: #1f1f1f; border-radius: 8px; overflow: hidden; transition: 0.3s;">
                <a href="index.php?page=movie&action=detail&id=<?php echo $movie['movie_id']; ?>">
                    <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" style="width: 100%; height: 300px; object-fit: cover;">
                </a>
                <div style="padding: 15px;">
                    <h4 style="margin: 0 0 10px; font-size: 16px;">
                        <a href="index.php?page=movie&action=detail&id=<?php echo $movie['movie_id']; ?>" style="color: white; text-decoration: none;">
                            <?php echo htmlspecialchars($movie['title']); ?>
                        </a>
                    </h4>
                    <a href="index.php?page=movie&action=detail&id=<?php echo $movie['movie_id']; ?>" 
                       style="display: block; text-align: center; background: #e50914; color: white; padding: 8px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                       MUA VÉ
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h3 style="color: white; margin-bottom: 20px;">🔜 Phim Sắp Chiếu</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px;">
        <?php if(empty($comingSoon)): ?>
            <p style="color: #777;">Chưa có phim sắp chiếu.</p>
        <?php else: ?>
            <?php foreach ($comingSoon as $movie): ?>
                <div class="movie-card" style="background: #1f1f1f; border-radius: 8px; overflow: hidden; opacity: 0.8;">
                    <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" style="width: 100%; height: 300px; object-fit: cover;">
                    <div style="padding: 15px;">
                        <h4 style="margin: 0 0 10px; font-size: 16px; color: #ccc;">
                            <?php echo htmlspecialchars($movie['title']); ?>
                        </h4>
                        <span style="display: block; text-align: center; border: 1px solid #555; color: #aaa; padding: 8px; border-radius: 4px;">
                           Sắp ra mắt
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php require_once 'views/layouts/footer.php'; ?>