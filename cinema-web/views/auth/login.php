<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="display:flex; justify-content:center; align-items:center; min-height:60vh;">
    <div style="background: #1f1f1f; padding: 40px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        <h2 style="text-align: center; margin-bottom: 20px; color: var(--primary-color);">ĐĂNG NHẬP</h2>
        
        <?php if(isset($error)): ?>
            <div style="background: rgba(229, 9, 20, 0.2); color: #ff4d4d; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php?page=auth&action=login" method="POST">
            <div style="margin-bottom: 15px;">
                <label style="color: #ccc;">Email</label>
                <input type="email" name="email" required placeholder="Nhập email..." 
                       style="width: 100%; padding: 12px; margin-top:5px; background: #333; border: 1px solid #444; color:white; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="color: #ccc;">Mật khẩu</label>
                <input type="password" name="password" required placeholder="Nhập mật khẩu..." 
                       style="width: 100%; padding: 12px; margin-top:5px; background: #333; border: 1px solid #444; color:white; border-radius: 4px;">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1.1rem; cursor: pointer; background-color: #e50914; color: white; border: none; border-radius: 4px;">Đăng Nhập</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #777;">
            Chưa có tài khoản? <a href="index.php?page=auth&action=register" style="color: white; text-decoration: underline;">Đăng ký ngay</a>
        </p>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>