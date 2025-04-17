<?php
// Thêm vào đầu file add_to_cart.php sau dòng require
file_put_contents('cart_debug.log', date('Y-m-d H:i:s') . ' - Request received: ' . print_r($_POST, true) . PHP_EOL, FILE_APPEND);
require_once 'config.php';
require_once 'db.php';

// Thêm vào đầu file add_to_cart.php sau dòng require
file_put_contents('cart_debug.log', date('Y-m-d H:i:s') . ' - Request received: ' . 
    'product_id=' . (isset($_POST['product_id']) ? $_POST['product_id'] : 'not set') . 
    ', quantity=' . (isset($_POST['quantity']) ? $_POST['quantity'] : 'not set') . PHP_EOL, FILE_APPEND);

// Thiết lập header để trả về JSON
header('Content-Type: application/json');

// Khởi tạo session nếu chưa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra phương thức request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không được hỗ trợ'
    ]);
    exit;
}

// Lấy thông tin sản phẩm từ request và đảm bảo mặc định là 1
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Mặc định là 1

// Đảm bảo số lượng tối thiểu là 1
if ($quantity < 1) {
    $quantity = 1;
}

// Debug để xác định giá trị thực tế đang được sử dụng
error_log("Product ID: $product_id, Quantity: $quantity");


// Kiểm tra dữ liệu đầu vào
if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Dữ liệu không hợp lệ'
    ]);
    exit;
}

// Kiểm tra sản phẩm có tồn tại không
$stmt = $pdo->prepare("SELECT id, price FROM Products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode([
        'success' => false,
        'message' => 'Sản phẩm không tồn tại'
    ]);
    exit;
}

try {
    // Nếu người dùng đã đăng nhập
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        
        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $stmt = $pdo->prepare("SELECT id, quantity FROM Cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $cart_item = $stmt->fetch();
        
        if ($cart_item) {
            // Cập nhật số lượng
            $new_quantity = $cart_item['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE Cart SET quantity = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$new_quantity, $cart_item['id']]);
        } else {
            // Thêm mới vào giỏ hàng
            $stmt = $pdo->prepare("INSERT INTO Cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }
        
        // Lấy số lượng sản phẩm trong giỏ hàng
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM Cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        $cart_count = $result['count'];
    } else {
        // Nếu chưa đăng nhập, lưu vào session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'quantity' => $quantity
            ];
        }
        
        $cart_count = count($_SESSION['cart']);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Sản phẩm đã được thêm vào giỏ hàng',
        'cart_count' => $cart_count
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
    ]);
}
?>