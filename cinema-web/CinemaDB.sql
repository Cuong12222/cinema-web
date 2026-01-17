CREATE DATABASE CinemaDB;
GO
USE CinemaDB;
GO

-- 1. Bảng Users (Người dùng & Admin)
CREATE TABLE Users (
    user_id INT PRIMARY KEY IDENTITY(1,1),
    full_name NVARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    role VARCHAR(20) DEFAULT 'user', -- 'user' hoặc 'admin'
    avatar_url NVARCHAR(255),
    created_at DATETIME2 DEFAULT GETDATE()
);
GO

-- 2. Bảng Genres (Thể loại phim)
CREATE TABLE Genres (
    genre_id INT PRIMARY KEY IDENTITY(1,1),
    name NVARCHAR(50) NOT NULL
);
GO

-- 3. Bảng Movies (Phim)
CREATE TABLE Movies (
    movie_id INT PRIMARY KEY IDENTITY(1,1),
    title NVARCHAR(200) NOT NULL,
    description NVARCHAR(MAX),
    director NVARCHAR(100),
    cast NVARCHAR(MAX),
    duration_minutes INT NOT NULL,
    release_date DATE,
    poster_url NVARCHAR(255),
    trailer_url NVARCHAR(255),
    rating DECIMAL(3, 1) DEFAULT 0.0, -- Rating trung bình
    country NVARCHAR(50),
    status VARCHAR(20) DEFAULT 'now_showing', -- 'now_showing', 'coming_soon', 'ended'
    created_at DATETIME2 DEFAULT GETDATE()
);
GO

-- Bảng trung gian Phim - Thể loại
CREATE TABLE MovieGenres (
    movie_id INT,
    genre_id INT,
    PRIMARY KEY (movie_id, genre_id),
    FOREIGN KEY (movie_id) REFERENCES Movies(movie_id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES Genres(genre_id) ON DELETE CASCADE
);
GO

-- 4. Bảng Cinemas (Rạp)
CREATE TABLE Cinemas (
    cinema_id INT PRIMARY KEY IDENTITY(1,1),
    name NVARCHAR(100) NOT NULL,
    address NVARCHAR(255) NOT NULL,
    city NVARCHAR(50) NOT NULL
);
GO

-- 5. Bảng Screens (Phòng chiếu)
CREATE TABLE Screens (
    screen_id INT PRIMARY KEY IDENTITY(1,1),
    cinema_id INT,
    name NVARCHAR(50) NOT NULL, -- Ví dụ: Phòng 01, IMAX 01
    total_seats INT DEFAULT 0,
    FOREIGN KEY (cinema_id) REFERENCES Cinemas(cinema_id) ON DELETE CASCADE
);
GO

-- 6. Bảng Seats (Ghế ngồi - Ma trận ghế cố định của phòng)
CREATE TABLE Seats (
    seat_id INT PRIMARY KEY IDENTITY(1,1),
    screen_id INT,
    row_name CHAR(2) NOT NULL, -- A, B, C...
    seat_number INT NOT NULL, -- 1, 2, 3...
    type VARCHAR(20) DEFAULT 'standard', -- 'standard', 'vip', 'couple'
    is_active BIT DEFAULT 1,
    FOREIGN KEY (screen_id) REFERENCES Screens(screen_id) ON DELETE CASCADE
);
GO

-- 7. Bảng ShowTimes (Suất chiếu)
CREATE TABLE ShowTimes (
    showtime_id INT PRIMARY KEY IDENTITY(1,1),
    movie_id INT,
    screen_id INT,
    start_time DATETIME2 NOT NULL,
    end_time DATETIME2 NOT NULL,
    base_price DECIMAL(18, 0) NOT NULL, -- Giá vé cơ bản VND
    FOREIGN KEY (movie_id) REFERENCES Movies(movie_id) ON DELETE CASCADE,
    FOREIGN KEY (screen_id) REFERENCES Screens(screen_id) ON DELETE CASCADE
);
GO

-- 8. Bảng Combos (Bắp nước)
CREATE TABLE Combos (
    combo_id INT PRIMARY KEY IDENTITY(1,1),
    name NVARCHAR(100) NOT NULL,
    description NVARCHAR(255),
    price DECIMAL(18, 0) NOT NULL,
    image_url NVARCHAR(255)
);
GO

-- 9. Bảng Bookings (Đơn đặt vé)
CREATE TABLE Bookings (
    booking_id INT PRIMARY KEY IDENTITY(1,1),
    user_id INT,
    showtime_id INT,
    total_amount DECIMAL(18, 0) NOT NULL,
    booking_date DATETIME2 DEFAULT GETDATE(),
    status VARCHAR(20) DEFAULT 'pending', -- 'pending', 'confirmed', 'cancelled'
    payment_method VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (showtime_id) REFERENCES ShowTimes(showtime_id)
);
GO

-- 10. Bảng BookingTickets (Chi tiết vé ghế đã đặt)
CREATE TABLE BookingTickets (
    ticket_id INT PRIMARY KEY IDENTITY(1,1),
    booking_id INT,
    seat_id INT,
    price DECIMAL(18, 0) NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES Bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (seat_id) REFERENCES Seats(seat_id)
);
GO

-- 11. Bảng BookingCombos (Chi tiết bắp nước đã đặt)
CREATE TABLE BookingCombos (
    booking_combo_id INT PRIMARY KEY IDENTITY(1,1),
    booking_id INT,
    combo_id INT,
    quantity INT DEFAULT 1,
    price DECIMAL(18, 0) NOT NULL, -- Giá tại thời điểm mua
    FOREIGN KEY (booking_id) REFERENCES Bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (combo_id) REFERENCES Combos(combo_id)
);
GO

-- 12. Cộng đồng: Groups (Nhóm phim)
CREATE TABLE CommunityGroups (
    group_id INT PRIMARY KEY IDENTITY(1,1),
    name NVARCHAR(100) NOT NULL,
    description NVARCHAR(MAX),
    creator_id INT,
    created_at DATETIME2 DEFAULT GETDATE(),
    FOREIGN KEY (creator_id) REFERENCES Users(user_id)
);
GO

-- 13. Cộng đồng: Posts (Bài viết)
CREATE TABLE Posts (
    post_id INT PRIMARY KEY IDENTITY(1,1),
    user_id INT,
    group_id INT NULL, -- Có thể post vào group hoặc trang cá nhân
    content NVARCHAR(MAX),
    image_url NVARCHAR(255),
    created_at DATETIME2 DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (group_id) REFERENCES CommunityGroups(group_id)
);
GO

-- 14. Cộng đồng: Comments & Likes
CREATE TABLE Comments (
    comment_id INT PRIMARY KEY IDENTITY(1,1),
    post_id INT,
    user_id INT,
    content NVARCHAR(MAX),
    created_at DATETIME2 DEFAULT GETDATE(),
    FOREIGN KEY (post_id) REFERENCES Posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Likes (
    user_id INT,
    post_id INT,
    created_at DATETIME2 DEFAULT GETDATE(),
    PRIMARY KEY (user_id, post_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (post_id) REFERENCES Posts(post_id) ON DELETE CASCADE
);
GO

-- INDEXING ĐỂ TỐI ƯU HIỆU SUẤT TÌM KIẾM
CREATE INDEX IDX_Movies_Title ON Movies(title);
CREATE INDEX IDX_ShowTimes_Movie ON ShowTimes(movie_id);
CREATE INDEX IDX_ShowTimes_Start ON ShowTimes(start_time);
CREATE INDEX IDX_Bookings_User ON Bookings(user_id);


INSERT INTO Movies (title, description, duration_minutes, release_date, poster_url, trailer_url, rating, status)
VALUES 
(N'Đào, Phở và Piano', N'Phim lịch sử Việt Nam...', 100, '2024-02-10', 'https://upload.wikimedia.org/wikipedia/vi/9/91/Dao_pho_va_piano_poster.jpg', '', 9.5, 'now_showing'),
(N'Mai', N'Phim tâm lý Trấn Thành...', 131, '2024-02-10', 'https://upload.wikimedia.org/wikipedia/vi/2/23/Mai_2024_poster.jpg', '', 8.0, 'now_showing'),
(N'Dune: Part Two', N'Hành tinh cát...', 166, '2024-03-01', 'https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg', '', 8.8, 'now_showing'),
(N'Kung Fu Panda 4', N'Gấu trúc võ thuật...', 94, '2024-03-08', 'https://image.tmdb.org/t/p/w500/kDp1vUBnMpe8ak4rjgl3cLELqjU.jpg', '', 7.5, 'now_showing'),
(N'Godzilla x Kong', N'Đế chế mới...', 115, '2024-04-12', 'https://image.tmdb.org/t/p/w500/bQ2ywkchIiaKLSEaMrcT6e29f91.jpg', '', 9.0, 'coming_soon');
GO



-- 1. Tạo Rạp và Phòng chiếu
INSERT INTO Cinemas (name, address, city) VALUES (N'CGV Vincom Bà Triệu', N'191 Bà Triệu, Hai Bà Trưng', N'Hà Nội');
DECLARE @cinemaId INT = SCOPE_IDENTITY();

-- Tạo phòng chiếu số 1
INSERT INTO Screens (cinema_id, name, total_seats) VALUES (@cinemaId, N'Cinema 01', 50);
DECLARE @screenId INT = SCOPE_IDENTITY();

-- 2. Tự động sinh 50 ghế (5 hàng: A, B, C, D, E - mỗi hàng 10 ghế)
-- Logic: A1-A10, B1-B10...
DECLARE @row CHAR(1);
DECLARE @i INT;
DECLARE @j INT;
SET @i = 0;

WHILE @i < 5
BEGIN
    SET @row = CHAR(65 + @i); -- 65 là mã ASCII của 'A'
    SET @j = 1;
    WHILE @j <= 10
    BEGIN
        INSERT INTO Seats (screen_id, row_name, seat_number, type)
        VALUES (@screenId, @row, @j, 'standard');
        SET @j = @j + 1;
    END
    SET @i = @i + 1;
END
GO

-- 3. Tạo Suất chiếu cho phim "Mai" (ID phim check trong DB, giả sử là 2)
-- Giả sử ID phim Mai là 2, Phòng chiếu là 1
INSERT INTO ShowTimes (movie_id, screen_id, start_time, end_time, base_price) 
VALUES 
(2, 1, '2025-12-25 18:00:00', '2025-12-25 20:15:00', 100000),
(2, 1, '2025-12-25 21:00:00', '2025-12-25 23:15:00', 110000);
GO

UPDATE Users SET role = 'admin' WHERE email = 'email_cua_ban@example.com';

-- 1. Nếu bảng Users chưa có cột password thì thêm vào
IF NOT EXISTS(SELECT * FROM sys.columns WHERE Name = N'password' AND Object_ID = Object_ID(N'Users'))
BEGIN
    -- Thêm cột password, mặc định là '123456' cho các user cũ (để không bị lỗi dữ liệu trống)
    ALTER TABLE Users ADD password VARCHAR(255) NOT NULL DEFAULT '123456';
    PRINT 'Da them cot password thanh cong!';
END
ELSE
BEGIN
    PRINT 'Cot password da ton tai, khong can them nua.';
END
GO

select * FROM Users 
select * FROM Seats 

-- Chạy lệnh này trong SQL Server để tạo bảng lưu vé đơn giản
CREATE TABLE Tickets (
    ticket_id INT IDENTITY(1,1) PRIMARY KEY,
    booking_id INT NOT NULL,
    seat_number VARCHAR(10) NOT NULL, -- Lưu A1, B2...
    price DECIMAL(18, 2),
    FOREIGN KEY (booking_id) REFERENCES Bookings(booking_id)
);
USE CinemaDB; -- Đảm bảo đang chọn đúng Database
GO

-- --- BƯỚC 1: NHẬP ID CỦA NGƯỜI DÙNG MUỐN TEST TẠI ĐÂY ---
DECLARE @UserId INT = 1;  -- <--- Thay số 1 bằng ID User bạn muốn xem (VD: 2, 3...)

-- --- BƯỚC 2: CHẠY CÂU LỆNH ---
SELECT 
    b.booking_id,
    b.total_amount,
    b.status,
    b.booking_date,
    m.title,
    m.poster_url,
    st.start_time,
    c.name as cinema_name,
    s.name as screen_name,
    -- Hàm này gom các ghế (A1, A2) thành 1 dòng. Yêu cầu SQL Server 2017 trở lên.
    STRING_AGG(CONCAT(seat.row_name, seat.seat_number), ', ') WITHIN GROUP (ORDER BY seat.row_name, seat.seat_number) as seat_numbers
FROM Bookings b
JOIN ShowTimes st ON b.showtime_id = st.showtime_id
JOIN Movies m ON st.movie_id = m.movie_id
JOIN Screens s ON st.screen_id = s.screen_id
JOIN Cinemas c ON s.cinema_id = c.cinema_id
LEFT JOIN BookingTickets bt ON b.booking_id = bt.booking_id
LEFT JOIN Seats seat ON bt.seat_id = seat.seat_id
WHERE b.user_id = @UserId -- Dùng biến @UserId thay cho dấu ?
GROUP BY 
    b.booking_id, b.total_amount, b.status, b.booking_date, 
    m.title, m.poster_url, st.start_time, c.name, s.name
ORDER BY b.booking_date DESC;

USE CinemaDB;
GO

UPDATE Users
SET role = 'admin' -- Đổi thành từ khóa quy định admin trong code của bạn
WHERE user_id = 13; -- Thay số 1 bằng ID tài khoản của bạn