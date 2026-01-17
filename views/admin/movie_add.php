<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Phim Mới</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .form-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 1rem; }
        button:hover { background: #218838; }
    </style>
</head>
<body>

<div class="form-container">
    <h2 style="text-align: center; color: #333;">THÊM PHIM MỚI</h2>
    
    <form action="index.php?page=admin&action=movies_add" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Tên phim:</label>
            <input type="text" name="title" required placeholder="Ví dụ: Lật Mặt 7">
        </div>
        
        <div class="form-group">
            <label>Đạo diễn:</label>
            <input type="text" name="director" placeholder="Ví dụ: Lý Hải">
        </div>

        <div class="form-group">
            <label>Diễn viên:</label>
            <input type="text" name="cast" placeholder="Ví dụ: Lý Hải, Thanh Thức...">
        </div>

        <div style="display: flex; gap: 10px;">
            <div class="form-group" style="flex: 1;">
                <label>Thời lượng (phút):</label>
                <input type="number" name="duration" required placeholder="120">
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Ngày khởi chiếu:</label>
                <input type="date" name="release_date" required>
            </div>
        </div>

        <div class="form-group">
            <label>Link Poster (Ảnh):</label>
            <input type="text" name="poster_url" required placeholder="https://...">
        </div>

        <div class="form-group">
            <label>Link Trailer (Youtube):</label>
            <input type="text" name="trailer_url" placeholder="https://youtube.com/...">
        </div>

        <div class="form-group">
            <label>Mô tả nội dung:</label>
            <textarea name="description" rows="5" placeholder="Nội dung phim..."></textarea>
        </div>

        <div style="text-align: center;">
            <button type="submit">LƯU PHIM</button>
            <a href="index.php?page=admin&action=index" style="margin-left: 20px; color: #666; text-decoration: none;">Hủy</a>
        </div>
    </form>
</div>

</body>
</html>