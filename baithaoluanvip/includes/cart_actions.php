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

// Xác định hành động
$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'update':
        updateCartItem();
        break;
    case 'remove':
        removeCartItem();
        break;
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Hành động không hợp lệ'
        ]);
}

// Hàm cập nhật số lượng sản phẩm
function updateCartItem() {
    global $pdo;
    
    $cart_id = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    if ($cart_id <= 0 || $quantity < 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ'
        ]);
        return;
    }
    
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        
        if ($quantity > 0) {
            // Cập nhật số lượng
            $stmt = $pdo->prepare("UPDATE Cart SET quantity = ?, updated_at = GETDATE() WHERE id = ? AND user_id = ?");
            $result = $stmt->execute([$quantity, $cart_id, $user_id]);
        } else {
            // Xóa sản phẩm khỏi giỏ hàng
            $stmt = $pdo->prepare("DELETE FROM Cart WHERE id = ? AND user_id = ?");
            $result = $stmt->execute([$cart_id, $user_id]);
        }
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Giỏ hàng đã được cập nhật'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật giỏ hàng'
            ]);
        }
    } else {
        // Cập nhật giỏ hàng trong session
        $product_id = $cart_id; // Trong trường hợp này, cart_id là product_id
        
        if (isset($_SESSION['cart'])) {
            if ($quantity > 0) {
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['product_id'] == $product_id) {
                        $item['quantity'] = $quantity;
                        break;
                    }
                }
            } else {
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['product_id'] == $product_id) {
                        unset($_SESSION['cart'][$key]);
                        break;
                    }
                }
                // Tái tổ chức mảng
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Giỏ hàng đã được cập nhật'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại'
            ]);
        }
    }
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeCartItem() {
    global $pdo;
    
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    if ($product_id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ'
        ]);
        return;
    }
    
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        
        // Xóa sản phẩm khỏi giỏ hàng
        $stmt = $pdo->prepare("DELETE FROM Cart WHERE product_id = ? AND user_id = ?");
        $result = $stmt->execute([$product_id, $user_id]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa sản phẩm'
            ]);
        }
    } else {
        // Xóa sản phẩm khỏi giỏ hàng trong session
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['product_id'] == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            // Tái tổ chức mảng
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại'
            ]);
        }
    }
}
?>