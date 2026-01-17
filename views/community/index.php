<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="margin-top: 20px; max-width: 800px;">
    
    <?php if (isset($_SESSION['user'])): ?>
        <div style="background: #1f1f1f; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3 style="color: #e50914; margin-bottom: 15px;">Bạn đang nghĩ gì về phim hôm nay?</h3>
            <form action="index.php?page=community&action=create" method="POST">
                <textarea name="content" rows="3" placeholder="Chia sẻ cảm nghĩ của bạn..." style="width: 100%; padding: 10px; border-radius: 5px; border: none; background: #333; color: white;"></textarea>
                <div style="text-align: right; margin-top: 10px;">
                    <button type="submit" style="background: #e50914; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">Đăng bài</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div style="background: #333; padding: 15px; text-align: center; border-radius: 8px; margin-bottom: 30px;">
            <p style="color: white;">Vui lòng <a href="index.php?page=auth&action=login" style="color: #e50914;">Đăng nhập</a> để tham gia thảo luận.</p>
        </div>
    <?php endif; ?>

    <h2 style="color: white; border-bottom: 2px solid #e50914; padding-bottom: 10px; margin-bottom: 20px;">Bài viết mới nhất</h2>
    
    <?php foreach ($posts as $post): ?>
        <div style="background: #1f1f1f; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #333;">
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <div style="width: 40px; height: 40px; background: #555; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; margin-right: 10px;">
                    <?php echo strtoupper(substr($post['full_name'], 0, 1)); ?>
                </div>
                <div>
                    <strong style="color: white; font-size: 16px;"><?php echo htmlspecialchars($post['full_name']); ?></strong>
                    <div style="color: #888; font-size: 12px;"><?php echo date('d/m H:i', strtotime($post['created_at'])); ?></div>
                </div>
            </div>
            
            <div style="color: #ddd; font-size: 15px; line-height: 1.5; margin-bottom: 15px;">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>

            <div style="border-top: 1px solid #333; padding-top: 10px; display: flex; align-items: center; gap: 20px;">
                <?php 
                    $heartColor = $post['is_liked'] ? '#e50914' : '#888'; 
                    $heartIcon = $post['is_liked'] ? 'fas' : 'far';
                ?>
                <a href="index.php?page=community&action=like&id=<?php echo $post['post_id']; ?>" style="color: <?php echo $heartColor; ?>; text-decoration: none; cursor: pointer;">
                    <i class="<?php echo $heartIcon; ?> fa-heart"></i> <?php echo $post['like_count']; ?> Thích
                </a>

                <span style="color: #888;"><i class="fas fa-comment"></i> <?php echo $post['comment_count']; ?> Bình luận</span>
            </div>

            <?php if (!empty($post['comments'])): ?>
                <div style="background: #2a2a2a; margin-top: 15px; padding: 10px; border-radius: 5px;">
                    <?php foreach ($post['comments'] as $cmt): ?>
                        <div style="margin-bottom: 8px; font-size: 14px;">
                            <strong style="color: #e50914;"><?php echo htmlspecialchars($cmt['full_name']); ?>:</strong>
                            <span style="color: #ccc;"><?php echo htmlspecialchars($cmt['content']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div style="margin-top: 15px; display: flex; gap: 10px;">
                <div style="width: 30px; height: 30px; background: #444; border-radius: 50%; flex-shrink: 0;"></div>
                
                <form action="index.php?page=community&action=comment" method="POST" style="flex-grow: 1; display: flex;">
                    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                    <input type="text" name="content" placeholder="Viết bình luận..." required
                           style="width: 100%; background: #333; border: none; padding: 8px 15px; color: white; border-radius: 20px; outline: none;">
                    <button type="submit" style="background: none; border: none; color: #e50914; margin-left: 10px; cursor: pointer;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>