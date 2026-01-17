<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinemaPro - Đặt vé xem phim</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body {
            background-color: #000; /* Màu nền đen chủ đạo */
            color: white;
            font-family: Arial, sans-serif;
        }

        /* --- MENU NAVIGATION --- */
        nav a {
            position: relative;
            color: #ddd; /* Màu chữ hơi xám nhẹ */
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            font-size: 15px;
            transition: color 0.3s ease;
            padding-bottom: 5px;
        }
        nav a:hover, nav a.active {
            color: #fff;
        }
        /* Hiệu ứng gạch chân chạy từ trái sang */
        nav a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #e50914; /* Màu đỏ Netflix */
            transition: width 0.3s ease;
        }
        nav a:hover::after {
            width: 100%;
        }

        /* --- USER DROPDOWN (ĐÃ SỬA: DỄ BẤM HƠN) --- */
        .user-menu { 
            position: relative; 
            display: inline-block; 
            height: 100%; /* Chiều cao full để bắt chuột tốt hơn */
            padding: 10px 0; 
        }

        /* Nút bấm tên người dùng */
        .user-trigger {
            display: flex; 
            align-items: center; 
            gap: 8px;
            color: white; 
            text-decoration: none; 
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .user-trigger:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .user-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%; /* Dính liền đáy header */
            background-color: #1f1f1f;
            min-width: 200px; /* Rộng hơn chút cho đẹp */
            box-shadow: 0px 8px 16px rgba(0,0,0,0.8);
            z-index: 1000;
            border-radius: 8px;
            border: 1px solid #333;
            margin-top: 10px; /* Cách ra 1 chút */
            overflow: visible; /* Để hiển thị mũi tên tam giác */
        }

        /* Mũi tên tam giác nhỏ chỉ lên trên */
        .user-dropdown::after {
            content: '';
            position: absolute;
            top: -6px;
            right: 20px;
            width: 12px;
            height: 12px;
            background: #1f1f1f;
            border-left: 1px solid #333;
            border-top: 1px solid #333;
            transform: rotate(45deg);
        }

        /* LỚP CẦU NỐI VÔ HÌNH (QUAN TRỌNG: Giúp chuột không bị trượt) */
        .user-dropdown::before {
            content: "";
            position: absolute;
            top: -20px; /* Lấp đầy khoảng trống giữa tên và menu */
            left: 0;
            width: 100%;
            height: 20px;
            background: transparent;
        }

        .user-dropdown a {
            color: #ddd;
            padding: 14px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            border-bottom: 1px solid #2a2a2a;
            transition: all 0.2s;
        }
        .user-dropdown a:last-child { border-bottom: none; }

        .user-dropdown a:hover { 
            background-color: #e50914; 
            color: white;
            padding-left: 25px; /* Hiệu ứng đẩy chữ sang phải */
        }
        
        /* Hiển thị menu khi hover */
        .user-menu:hover .user-dropdown { 
            display: block; 
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- BUTTON GLOW EFFECT (Nút phát sáng) --- */
        .btn-glow {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(229, 9, 20, 0.4);
        }

        /* --- MOVIE CARD HOVER (Hiệu ứng Poster) --- */
        .movie-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
            background: #1f1f1f;
        }
        .movie-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
            z-index: 10;
        }
        .movie-card img {
            transition: transform 0.5s ease;
        }
        .movie-card:hover img {
            transform: scale(1.1);
        }

        /* --- SLIDER STYLES (Cho trang chủ) --- */
        .hero-slider {
            position: relative;
            width: 100%;
            height: 600px;
            overflow: hidden;
            margin-top: -20px; /* Đẩy lên đè mép header nếu cần */
        }
        .slide-item {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center top;
        }
        .slide-item.active { opacity: 1; }
        .slide-content {
            position: absolute; bottom: 100px; left: 50px;
            color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            max-width: 600px; z-index: 2;
        }
        .overlay-gradient {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to top, #000 0%, transparent 60%);
            z-index: 1;
        }
    </style>
</head>
<body>

<header style="background-color: rgba(0,0,0,0.9); padding: 10px 0; border-bottom: 1px solid #333; position: sticky; top: 0; z-index: 1000;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        
        <a href="index.php" style="text-decoration: none;">
            <h1 style="color: #e50914; margin: 0; font-size: 30px; font-weight: bold; letter-spacing: 2px;">CINEMAPRO</h1>
        </a>

        <nav>
            <a href="index.php">TRANG CHỦ</a>
            <a href="index.php?page=movie&action=list">PHIM</a>
            <a href="index.php?page=showtime&action=list">LỊCH CHIẾU</a>
            <a href="index.php?page=community&action=index" style="color: #e50914;">CỘNG ĐỒNG</a>
        </nav>

        <div style="display: flex; align-items: center; gap: 20px;">
            
            <form action="index.php" method="GET" style="display: flex;">
                <input type="hidden" name="page" value="movie">
                <input type="hidden" name="action" value="search">
                <input type="text" name="keyword" placeholder="Tìm tên phim..." 
                       style="padding: 6px 12px; border-radius: 20px 0 0 20px; border: 1px solid #333; background: #222; color: white; outline: none;">
                <button type="submit" style="background: #e50914; color: white; border: none; padding: 6px 12px; border-radius: 0 20px 20px 0; cursor: pointer;">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <?php if (isset($_SESSION['user'])): ?>
                
                <div class="user-menu">
                    <a href="index.php?page=user&action=profile" class="user-trigger">
                        <div style="width: 30px; height: 30px; background: #e50914; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; margin-right: 5px;">
                            <?php echo strtoupper(substr($_SESSION['user']['username'] ?? 'U', 0, 1)); ?>
                        </div>
                        
                        <span style="font-weight: bold; max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo htmlspecialchars($_SESSION['user']['username'] ?? $_SESSION['user']['full_name'] ?? 'Bạn'); ?>
                        </span>
                        <i class="fas fa-caret-down" style="font-size: 12px; opacity: 0.7;"></i>
                    </a>
                    
                    <div class="user-dropdown">
                        <div style="padding: 15px 20px; border-bottom: 1px solid #333; color: #888; font-size: 12px;">
                            Xin chào, <br>
                            <strong style="color: white; font-size: 15px;"><?php echo htmlspecialchars($_SESSION['user']['full_name'] ?? $_SESSION['user']['username']); ?></strong>
                        </div>

                        <a href="index.php?page=user&action=profile">
                            <i class="fas fa-id-card"></i> Hồ sơ & Bài viết
                        </a>
    
                        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'admin'): ?>
                            <a href="index.php?page=admin&action=dashboard" style="color: #e50914;">
                                <i class="fas fa-cogs"></i> Trang Admin
                            </a>
                        <?php endif; ?>
    
                        <a href="index.php?page=auth&action=logout">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </div>

            <?php else: ?>
                
                <div>
                    <a href="index.php?page=auth&action=login" style="color: white; text-decoration: none; margin-right: 15px; font-weight: bold;">Đăng Nhập</a>
                    <a href="index.php?page=auth&action=register" class="btn-glow" style="background: #e50914; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold;">Đăng Ký</a>
                </div>

            <?php endif; ?>
            
        </div>
    </div>
</header>