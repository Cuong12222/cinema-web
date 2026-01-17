<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CinemaPro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: sans-serif; display: flex; height: 100vh; background: #f4f4f4; }
        .sidebar { width: 250px; background: #2c3e50; color: white; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; font-size: 1.5rem; font-weight: bold; text-align: center; background: #1a252f; }
        .sidebar a { padding: 15px 20px; color: #adb5bd; text-decoration: none; border-bottom: 1px solid #34495e; display: block; }
        .sidebar a:hover { background: #34495e; color: white; }
        .sidebar a i { margin-right: 10px; width: 20px; text-align: center; }
        .main { flex: 1; padding: 20px; overflow-y: auto; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">ADMIN PANEL</div>
    <a href="index.php?page=admin&action=dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="index.php?page=admin&action=movies"><i class="fa-solid fa-film"></i> Quản lý Phim</a>
    <a href="index.php?page=admin&action=showtimes"><i class="fa-regular fa-calendar-alt"></i> Lịch Chiếu</a>
    <a href="index.php" target="_blank"><i class="fa-solid fa-home"></i> Xem Trang Chủ</a>
    <a href="index.php?page=auth&action=logout" style="margin-top: auto; background: #c0392b; color: white;"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</a>
</div>

<div class="main">