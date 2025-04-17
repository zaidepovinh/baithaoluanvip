<?php
require_once 'config.php';
require_once 'db.php';

// Thiết lập header để trả về JSON
header('Content-Type: application/json');

// Khởi tạo biến đếm giỏ hàng
$cart_count = 0;

// Kiểm tra nếu người dùng đã đăng nhập
if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    
    // Lấy số lượng sản phẩm từ cơ sở dữ liệu
    $stmt = $pdo->prepare("SELECT SUM(quantity) as cart_count FROM Cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $cart_count = $result['cart_count'] ?? 0;
} else {
    // Lấy số lượng sản phẩm từ session
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $cart_count += $item['quantity'];
        }
    }
}

// Trả về kết quả
echo json_encode([
    'success' => true,
    'cart_count' => (int)$cart_count
]);
?>