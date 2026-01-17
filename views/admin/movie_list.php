<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý phim</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background: #f8f9fa; color: #333; }
        .admin-container { 
            max-width: 1200px; 
            margin: 30px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 0 15px rgba(0,0,0,0.1); 
        }
        .table img {
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-group-action .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container admin-container">
    
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="text-primary m-0"><i class="fas fa-film"></i> QUẢN LÝ PHIM</h2>
        
        <div>
            <a href="index.php?page=admin&action=dashboard" class="btn btn-secondary me-2">
                <i class="fas fa-tachometer-alt"></i> Panel
            </a>

            <a href="index.php" class="btn btn-outline-primary" target="_blank">
                <i class="fas fa-home"></i> Website
            </a>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="index.php?page=admin&action=movies_add" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Thêm Phim Mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" width="5%">ID</th>
                    <th class="text-center" width="10%">Poster</th>
                    <th width="30%">Tên Phim</th>
                    <th class="text-center" width="15%">Thời lượng</th>
                    <th class="text-center" width="15%">Ngày phát hành</th>
                    <th class="text-center" width="15%">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($movies)): ?>
                    <?php foreach ($movies as $movie): ?>
                    <tr>
                        <td class="text-center"><?php echo $movie['movie_id']; ?></td>
                        <td class="text-center">
                            <img src="<?php echo !empty($movie['poster_url']) ? $movie['poster_url'] : 'assets/images/no-image.png'; ?>" 
                                 width="60" height="90" style="object-fit: cover;" 
                                 alt="Poster">
                        </td>
                        <td>
                            <strong class="text-primary"><?php echo $movie['title']; ?></strong>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info text-dark"><?php echo $movie['duration_minutes']; ?> phút</span>
                        </td>
                        <td class="text-center">
                            <?php echo date('d/m/Y', strtotime($movie['release_date'])); ?>
                        </td>
                        <td class="text-center">
                            <a href="index.php?page=admin&action=movies_delete&id=<?php echo $movie['movie_id']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa phim này?');">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Chưa có phim nào trong cơ sở dữ liệu.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>