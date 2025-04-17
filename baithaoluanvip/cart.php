<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Lấy thông tin giỏ hàng
$cart_items = [];
$subtotal = 0;

// Kiểm tra nếu người dùng đã đăng nhập
if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    
    $query = "SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image 
              FROM Cart c 
              JOIN Products p ON c.product_id = p.id 
              WHERE c.user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Tính tổng tiền
    foreach ($cart_items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
} else {
    // Lấy giỏ hàng từ session
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $item) {
            // Lấy thông tin sản phẩm
            $stmt = $pdo->prepare("SELECT id, name, price, image FROM Products WHERE id = ?");
            $stmt->execute([$item['product_id']]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                $cart_item = [
                    'id' => $item['product_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image']
                ];
                $cart_items[] = $cart_item;
                
                // Tính tổng tiền
                $subtotal += $product['price'] * $item['quantity'];
            }
        }
    }
}

// Xử lý khi người dùng cập nhật giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        
        foreach ($_POST['quantity'] as $cart_id => $quantity) {
            if ($quantity > 0) {
                // Cập nhật số lượng
                $stmt = $pdo->prepare("UPDATE Cart SET quantity = ?, updated_at = GETDATE() WHERE id = ? AND user_id = ?");
                $stmt->execute([$quantity, $cart_id, $user_id]);
            } else {
                // Xóa sản phẩm khỏi giỏ hàng
                $stmt = $pdo->prepare("DELETE FROM Cart WHERE id = ? AND user_id = ?");
                $stmt->execute([$cart_id, $user_id]);
            }
        }
    } else {
        // Cập nhật giỏ hàng trong session
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            if ($quantity > 0) {
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['product_id'] == $product_id) {
                        $item['quantity'] = $quantity;
                        break;
                    }
                }
            } else {
                // Xóa sản phẩm khỏi giỏ hàng
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['product_id'] == $product_id) {
                        unset($_SESSION['cart'][$key]);
                        break;
                    }
                }
                // Tái tổ chức mảng để đảm bảo các key là liên tục
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
        }
    }
    
    // Chuyển hướng để tránh gửi lại form khi refresh trang
    header("Location: cart.php");
    exit;
}

// Xử lý khi người dùng xóa sản phẩm
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $product_id = $_GET['remove'];
    
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        
        // Xóa sản phẩm khỏi giỏ hàng
        $stmt = $pdo->prepare("DELETE FROM Cart WHERE product_id = ? AND user_id = ?");
        $stmt->execute([$product_id, $user_id]);
    } else {
        // Xóa sản phẩm khỏi giỏ hàng trong session
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['product_id'] == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            // Tái tổ chức mảng để đảm bảo các key là liên tục
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
    
    // Chuyển hướng
    header("Location: cart.php");
    exit;
}

// Tính phí vận chuyển và tổng tiền
$shipping_cost = 0; // Miễn phí vận chuyển
$total = $subtotal + $shipping_cost;

require_once 'includes/header.php';
?>

<div class="cart-container">
    <h1 class="cart-title">Giỏ hàng</h1>
    
    <?php if (empty($cart_items)): ?>
    <div class="empty-cart">
        <p>Giỏ hàng của bạn đang trống.</p>
        <a href="products.php" class="continue-shopping-btn">Tiếp tục mua sắm</a>
    </div>
    <?php else: ?>
    
    <div class="cart-content">
        <div class="cart-items">
            <div class="cart-header">
                <div class="cart-header-product">Sản phẩm</div>
                <div class="cart-header-price">Giá</div>
                <div class="cart-header-quantity">Số lượng</div>
                <div class="cart-header-subtotal">Thành tiền</div>
            </div>
            
            <form method="post" action="cart.php" id="update-cart-form">
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <div class="cart-item-product">
                        <div class="cart-item-image">
                            <img src="assets/images/products/<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                        </div>
                        <div class="cart-item-info">
                            <h3><?= $item['name'] ?></h3>
                        </div>
                    </div>
                    <div class="cart-item-price">
                        <?= number_format($item['price'], 0, ',', '.') ?> vnđ
                    </div>
                    <div class="cart-item-quantity">
                        <div class="quantity-control">
                            <button type="button" class="quantity-btn decrease">-</button>
                            <input type="number" name="quantity[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="0" class="quantity-input">
                            <button type="button" class="quantity-btn increase">+</button>
                        </div>
                    </div>
                    <div class="cart-item-subtotal">
                        <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> vnđ
                    </div>
                    <div class="cart-item-remove">
                        <a href="cart.php?remove=<?= $item['product_id'] ?>" class="remove-btn">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="cart-actions">
                    <button type="submit" name="update_cart" class="update-cart-btn">Cập nhật giỏ hàng</button>
                    <a href="products.php" class="continue-shopping-btn">Tiếp tục mua sắm</a>
                </div>
            </form>
        </div>
        
        <div class="cart-summary">
            <h2 class="summary-title">Cart Totals</h2>
            
            <div class="summary-row">
                <span>Subtotal</span>
                <span><?= number_format($subtotal, 0, ',', '.') ?> vnđ</span>
            </div>
            
            <div class="summary-row total">
                <span>Total</span>
                <span><?= number_format($total, 0, ',', '.') ?> vnđ</span>
            </div>
            
            <a href="checkout.php" class="checkout-btn">Check Out</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>