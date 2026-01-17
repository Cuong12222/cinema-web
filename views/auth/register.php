<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="padding: 50px 0;">
    <div style="max-width: 400px; margin: 0 auto; background: #1f1f1f; padding: 30px; border-radius: 8px;">
        <h2 style="text-align: center; margin-bottom: 20px; color: #e50914;">ĐĂNG KÝ TÀI KHOẢN</h2>
        
        <?php if(isset($error)): ?>
            <div style="background: #e50914; color: white; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=auth&action=register" method="POST">
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: #ddd;">Họ và tên</label>
                <input type="text" name="full_name" required placeholder="Nhập họ tên đầy đủ" 
                       style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #333; background: #333; color: white;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: #ddd;">Tên đăng nhập</label>
                <input type="text" name="username" required placeholder="Nhập tên đăng nhập (viết liền)" 
                       style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #333; background: #333; color: white;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: #ddd;">Email</label>
                <input type="email" name="email" required placeholder="Nhập email" 
                       style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #333; background: #333; color: white;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; color: #ddd;">Mật khẩu</label>
                <input type="password" name="password" required placeholder="Nhập mật khẩu" 
                       style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #333; background: #333; color: white;">
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background: #e50914; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                ĐĂNG KÝ NGAY
            </button>
        </form>

        <p style="text-align: center; margin-top: 15px; color: #777;">
            Đã có tài khoản? <a href="index.php?page=auth&action=login" style="color: #e50914; text-decoration: none;">Đăng nhập</a>
        </p>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>