<?php require_once 'views/admin/layout_header.php'; ?>

<style>
    .admin-wrapper {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f9;
        padding: 40px 20px;
        min-height: 80vh;
        display: flex;
        justify-content: center;
    }

    .form-card {
        background: #ffffff;
        width: 100%;
        max-width: 700px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #eaeaea;
    }

    .form-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        padding: 20px 30px;
        border-bottom: 1px solid #0a58ca;
    }

    .form-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .form-body { padding: 30px; }
    .form-group { margin-bottom: 25px; }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 8px;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .row-group { display: flex; gap: 20px; }
    .col-half { flex: 1; }

    .btn-action {
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        border: none;
        text-decoration: none;
        display: inline-block;
    }

    .btn-save { background-color: #198754; color: white; }
    .btn-back { background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6; margin-right: 15px; }
    
    .alert-error {
        padding: 15px;
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    optgroup { font-weight: 700; color: #0d6efd; background: #f0f4ff; }
</style>

<div class="admin-wrapper">
    <div class="form-card">
        <div class="form-header">
            <h3><i class="fas fa-calendar-plus"></i> Tạo Lịch Chiếu Mới</h3>
        </div>

        <div class="form-body">
            
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=admin&action=showtimes_add" method="POST">
                
                <div class="form-group">
                    <label class="form-label">Chọn Phim</label>
                    <select name="movie_id" class="form-control" required>
                        <option value="">-- Vui lòng chọn phim --</option>
                        <?php if (!empty($movies)) { 
                            foreach ($movies as $m) { ?>
                                <option value="<?php echo $m['movie_id']; ?>">
                                    <?php echo htmlspecialchars($m['title']); ?> 
                                    (<?php echo $m['duration_minutes']; ?> phút)
                                </option>
                            <?php } 
                        } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Chọn Rạp & Phòng Chiếu</label>
                    <select name="screen_id" class="form-control" required>
                        <option value="">-- Vui lòng chọn phòng --</option>
                        
                        <?php 
                        if (!empty($screens)) {
                            $currentCinema = null;
                            
                            foreach ($screens as $s) {
                                // Lấy tên rạp
                                $cinemaName = isset($s['cinema_name']) ? $s['cinema_name'] : 'Rạp chưa đặt tên';
                                
                                // Logic gom nhóm
                                if ($cinemaName != $currentCinema) {
                                    if ($currentCinema !== null) {
                                        echo '</optgroup>';
                                    }
                                    echo '<optgroup label="' . htmlspecialchars($cinemaName) . '">';
                                    $currentCinema = $cinemaName;
                                }
                        ?>
                                <option value="<?php echo $s['screen_id']; ?>">
                                    &nbsp;&nbsp;📍 <?php echo htmlspecialchars($s['screen_name']); ?> 
                                    (<?php echo $s['total_seats']; ?> ghế)
                                </option>
                        <?php 
                            } // Kết thúc foreach
                            
                            if ($currentCinema !== null) {
                                echo '</optgroup>';
                            }
                        } else { 
                        ?>
                            <option value="" disabled>⚠️ Không có dữ liệu phòng</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="row-group">
                    <div class="col-half form-group">
                        <label class="form-label">Thời gian bắt đầu</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>

                    <div class="col-half form-group">
                        <label class="form-label">Giá vé cơ bản (VNĐ)</label>
                        <input type="number" name="price" value="75000" min="0" step="1000" class="form-control" required>
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                <div style="text-align: right;">
                    <a href="index.php?page=admin&action=showtimes" class="btn-action btn-back">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn-action btn-save">
                        <i class="fas fa-save"></i> LƯU LỊCH CHIẾU
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>