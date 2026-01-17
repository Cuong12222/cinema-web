<?php
require_once 'models/Community.php';

class CommunityController {
    private $model;

    public function __construct() {
        $this->model = new CommunityModel();
    }

    // --- SỬA HÀM INDEX CŨ ---
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user_id = $_SESSION['user']['user_id'] ?? 0;

        $posts = $this->model->getAllPosts();

        // Lấy thêm danh sách comment và trạng thái like cho từng bài viết
        foreach ($posts as &$post) {
            $post['comments'] = $this->model->getCommentsByPost($post['post_id']);
            $post['is_liked'] = $this->model->hasLiked($user_id, $post['post_id']);
        }
        
        require_once 'views/community/index.php';
    }
    // -------------------------

    // --- THÊM HÀM CREATE (GIỮ NGUYÊN NHƯ CŨ) ---
    public function create() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header("Location: index.php?page=auth&action=login"); exit; }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $content = $_POST['content'];
            $user_id = $_SESSION['user']['user_id'];
            if (!empty($content)) {
                $this->model->createPost($user_id, $content);
            }
        }
        header("Location: index.php?page=community&action=index");
    }

    // --- THÊM HÀM XỬ LÝ LIKE (MỚI) ---
    public function like() {
        session_start();
        if (!isset($_SESSION['user'])) { header("Location: index.php?page=auth&action=login"); exit; }

        $post_id = $_GET['id'] ?? null;
        if ($post_id) {
            $this->model->toggleLike($_SESSION['user']['user_id'], $post_id);
        }
        // Quay lại đúng trang cũ
        header("Location: index.php?page=community&action=index");
    }

    // --- THÊM HÀM XỬ LÝ COMMENT (MỚI) ---
    public function comment() {
        session_start();
        if (!isset($_SESSION['user'])) { header("Location: index.php?page=auth&action=login"); exit; }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_id = $_POST['post_id'];
            $content = $_POST['content'];
            if (!empty($content)) {
                $this->model->addComment($_SESSION['user']['user_id'], $post_id, $content);
            }
        }
        header("Location: index.php?page=community&action=index");
    }
}
?>