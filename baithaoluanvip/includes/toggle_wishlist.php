<?php
require_once 'config.php';
require_once 'db.php';

// Thiết lập header để trả về JSON
header('Content-Type: application/json');

// Kiểm tra phương thức request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không được hỗ trợ'
    ]);
    exit;
}

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    echo json_encode([
        'success' => false,
        'message' => 'login_required'
    ]);
    exit;
}

// Lấy thông tin sản phẩm từ request
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$user_id = $_SESSION['user_id'];

// Kiểm tra dữ liệu đầu vào
if ($product_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Dữ liệu không hợp lệ'
    ]);
    exit;
}

// Kiểm tra xem sản phẩm đã có trong wishlist chưa
$stmt = $pdo->prepare("SELECT id FROM Wishlist WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$wishlist_item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($wishlist_item) {
    // Xóa khỏi wishlist
    $stmt = $pdo->prepare("DELETE FROM Wishlist WHERE id = ?");
    $result = $stmt->execute([$wishlist_item['id']]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi danh sách yêu thích',
            'action' => 'removed'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi xóa sản phẩm'
        ]);
    }
} else {
    // Thêm vào wishlist
    $stmt = $pdo->prepare("INSERT INTO Wishlist (user_id, product_id) VALUES (?, ?)");
    $result = $stmt->execute([$user_id, $product_id]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào danh sách yêu thích',
            'action' => 'added'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi thêm sản phẩm'
        ]);
    }
}
?>