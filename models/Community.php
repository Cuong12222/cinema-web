<?php
class CommunityModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả bài viết (kèm tên người đăng)
    public function getAllPosts() {
        $sql = "SELECT p.*, u.full_name, u.avatar_url,
                       (SELECT COUNT(*) FROM Likes WHERE post_id = p.post_id) as like_count,
                       (SELECT COUNT(*) FROM Comments WHERE post_id = p.post_id) as comment_count
                FROM Posts p
                JOIN Users u ON p.user_id = u.user_id
                ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bài viết của riêng 1 user (cho trang cá nhân)
    public function getPostsByUserId($user_id) {
        $sql = "SELECT p.*, 
                       (SELECT COUNT(*) FROM Likes WHERE post_id = p.post_id) as like_count
                FROM Posts p
                WHERE p.user_id = :user_id
                ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đăng bài mới
    public function createPost($user_id, $content) {
        $sql = "INSERT INTO Posts (user_id, content, created_at) VALUES (:uid, :content, GETDATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':uid', $user_id);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }
    // ... (Giữ nguyên các hàm cũ)

    // 1. Lấy danh sách bình luận của một bài viết
    public function getCommentsByPost($post_id) {
        $sql = "SELECT c.*, u.full_name, u.avatar_url 
                FROM Comments c
                JOIN Users u ON c.user_id = u.user_id
                WHERE c.post_id = :post_id
                ORDER BY c.created_at ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Thêm bình luận mới
    public function addComment($user_id, $post_id, $content) {
        $sql = "INSERT INTO Comments (user_id, post_id, content) VALUES (:uid, :pid, :content)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid' => $user_id, ':pid' => $post_id, ':content' => $content]);
    }

    // 3. Xử lý Like (Nếu like rồi thì bỏ like, chưa like thì thêm like)
    public function toggleLike($user_id, $post_id) {
        // Kiểm tra xem đã like chưa
        $checkSql = "SELECT * FROM Likes WHERE user_id = :uid AND post_id = :pid";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->execute([':uid' => $user_id, ':pid' => $post_id]);
        
        if ($stmt->fetch()) {
            // Đã like -> Xóa (Unlike)
            $sql = "DELETE FROM Likes WHERE user_id = :uid AND post_id = :pid";
        } else {
            // Chưa like -> Thêm (Like)
            $sql = "INSERT INTO Likes (user_id, post_id) VALUES (:uid, :pid)";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid' => $user_id, ':pid' => $post_id]);
    }

    // 4. Kiểm tra xem user hiện tại đã like bài này chưa (để tô đỏ trái tim)
    public function hasLiked($user_id, $post_id) {
        $sql = "SELECT 1 FROM Likes WHERE user_id = :uid AND post_id = :pid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid' => $user_id, ':pid' => $post_id]);
        return $stmt->fetchColumn(); // Trả về true nếu đã like
    }
}
?>