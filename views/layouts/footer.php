<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* --- CSS Cấu trúc Layout --- */
    .footer-section {
        background-color: #fdfcf0; /* Màu nền kem nhạt */
        border-top: 3px solid #d0d0d0; /* Đường kẻ trên cùng */
        padding-top: 40px;
        padding-bottom: 20px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        color: #636363;
    }

    .footer-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Flexbox để dàn ngang 4 cột */
    .footer-row {
        display: flex;            
        justify-content: space-between; 
        flex-wrap: wrap;          /* Xuống dòng trên màn hình nhỏ */
        gap: 20px;                
    }

    .footer-col {
        flex: 1;                  /* Các cột rộng bằng nhau */
        min-width: 200px;         /* Chiều rộng tối thiểu để không bị quá bé */
    }

    /* --- CSS Trang trí nội dung --- */
    .footer-title {
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 15px;
        color: #222;
        font-size: 15px;
        position: relative;
    }
    
    /* Thêm gạch chân đỏ dưới tiêu đề cho đẹp mắt */
    .footer-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 40px;
        height: 2px;
        background-color: #e71a0f;
    }

    .footer-list {
        list-style: none; 
        padding: 0;
        margin: 0;
    }

    .footer-list li {
        margin-bottom: 8px;
    }

    .footer-list a {
        color: #636363;
        text-decoration: none; 
        transition: color 0.2s;
    }

    .footer-list a:hover {
        color: #e71a0f; /* Màu đỏ khi di chuột vào */
    }

    /* Icon Mạng xã hội */
    .social-icons a {
        display: inline-block;
        width: 32px;
        height: 32px;
        line-height: 32px;
        text-align: center;
        background-color: #ccc;
        color: white;
        border-radius: 4px;
        margin-right: 5px;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .social-icons a.fb:hover { background-color: #3b5998; }
    .social-icons a.yt:hover { background-color: #c4302b; }
    .social-icons a.insta:hover { background-color: #C13584; }
    .social-icons a.zalo:hover { background-color: #0068ff; }

    /* Logo bộ công thương (Dùng link ảnh chung của Bộ, không phải của rạp khác) */
    .bocongthuong-img {
        margin-top: 15px;
        width: 130px;
    }

    /* Phần chân trang dưới cùng */
    .footer-bottom {
        border-top: 1px solid #d0d0d0;
        margin-top: 30px;
        padding-top: 20px;
        text-align: center;
    }

    /* Logo chữ thay cho logo ảnh */
    .text-logo-footer {
        font-size: 24px;
        font-weight: 900;
        color: #e71a0f; /* Màu đỏ thương hiệu */
        text-transform: uppercase;
        margin-bottom: 10px;
        display: inline-block;
    }

    .footer-bottom h4 {
        margin-bottom: 10px;
        font-size: 14px;
        font-weight: bold;
        color: #222;
    }
    
    .footer-bottom p {
        margin-bottom: 5px;
    }
</style>

<footer class="footer-section">
    <div class="footer-container">
        <div class="footer-row">
            
            <div class="footer-col">
                <h3 class="footer-title">Về Chúng Tôi</h3>
                <ul class="footer-list">
                    <li><a href="#">Giới Thiệu Rạp</a></li>
                    <li><a href="#">Tiện Ích Online</a></li>
                    <li><a href="#">Thẻ Quà Tặng</a></li>
                    <li><a href="#">Tuyển Dụng</a></li>
                    <li><a href="#">Liên Hệ Quảng Cáo</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3 class="footer-title">Điều khoản sử dụng</h3>
                <ul class="footer-list">
                    <li><a href="#">Điều Khoản Chung</a></li>
                    <li><a href="#">Chính Sách Thanh Toán</a></li>
                    <li><a href="#">Chính Sách Bảo Mật</a></li>
                    <li><a href="#">Câu Hỏi Thường Gặp</a></li>
                    <li><a href="#">Góp Ý & Khiếu Nại</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3 class="footer-title">Kết nối mạng xã hội</h3>
                <div class="social-icons">
                    <a href="#" class="fb"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="yt"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="insta"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="zalo"><i class="fas fa-comment-dots"></i></a>
                </div>
                <a href="#">
                    <img src="http://online.gov.vn/Content/EndUser/LogoCCDVSaleNoti/logoSaleNoti.png" alt="Đã thông báo bộ công thương" class="bocongthuong-img">
                </a>
            </div>

            <div class="footer-col">
                <h3 class="footer-title">Chăm sóc khách hàng</h3>
                <p><strong>Hotline:</strong> 1900 xxxx</p>
                <p>Giờ làm việc: 8:00 - 22:00<br>(Tất cả các ngày bao gồm cả Lễ Tết)</p>
                <p><strong>Email:</strong> support@cinemapro.vn</p>
            </div>

        </div> <div class="footer-bottom">
            <div class="text-logo-footer">CINEMAPRO</div>
            
            <h4>CÔNG TY TNHH CINEMAPRO VIỆT NAM</h4>
            <p>Giấy CNĐKDN: 0123456789, được cấp bởi Sở Kế hoạch và Đầu tư.</p>
            <p>Địa chỉ: [Địa chỉ của bạn tại đây]</p>
            <p>&copy; 2025 CINEMAPRO. All rights reserved.</p>
        </div>
    </div>
</footer>