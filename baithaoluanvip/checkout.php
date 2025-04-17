<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Kiểm tra xem giỏ hàng có trống không
$cart_items = [];
$subtotal = 0;
$has_items = false;

// Lấy thông tin giỏ hàng
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
    
    // Lấy thông tin người dùng để điền sẵn form
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $has_items = !empty($cart_items);
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
        $has_items = !empty($cart_items);
    }
    
    // Khởi tạo dữ liệu người dùng trống
    $user = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'city' => '',
        'state' => '',
        'postal_code' => '',
        'country' => ''
    ];
}

// Xử lý form đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Validate form
    $errors = [];
    
    $first_name = clean($_POST['first_name']);
    $last_name = clean($_POST['last_name']);
    $email = clean($_POST['email']);
    $phone = clean($_POST['phone']);
    $address = clean($_POST['address']);
    $city = clean($_POST['city']);
    $province = clean($_POST['province']);
    $payment_method = isset($_POST['payment_method']) ? clean($_POST['payment_method']) : '';
    
    // Kiểm tra các trường bắt buộc
    if (empty($first_name)) {
        $errors[] = 'Vui lòng nhập họ của bạn.';
    }
    
    if (empty($last_name)) {
        $errors[] = 'Vui lòng nhập tên của bạn.';
    }
    
    if (empty($email)) {
        $errors[] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ.';
    }
    
    if (empty($phone)) {
        $errors[] = 'Vui lòng nhập số điện thoại.';
    }
    
    if (empty($address)) {
        $errors[] = 'Vui lòng nhập địa chỉ.';
    }
    
    if (empty($city)) {
        $errors[] = 'Vui lòng nhập thành phố/quận/huyện.';
    }
    
    if (empty($province)) {
        $errors[] = 'Vui lòng chọn tỉnh/thành phố.';
    }
    
    if (empty($payment_method)) {
        $errors[] = 'Vui lòng chọn phương thức thanh toán.';
    }
    
    if (empty($errors) && $has_items) {
        try {
            // Bắt đầu transaction
            $pdo->beginTransaction();
            
            // Tạo mã đơn hàng
            $order_number = 'ORD' . time() . rand(1000, 9999);
            
            // Tạo đơn hàng mới
            $stmt = $pdo->prepare("INSERT INTO Orders (user_id, order_number, payment_method, subtotal, shipping_cost, tax, total, shipping_first_name, shipping_last_name, shipping_email, shipping_phone, shipping_address, shipping_city, shipping_state, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
            $shipping_cost = 0; // Miễn phí vận chuyển
            $tax = 0; // Chưa tính thuế
            $total = $subtotal + $shipping_cost + $tax;
            $notes = isset($_POST['additional_info']) ? clean($_POST['additional_info']) : '';
            
            $stmt->execute([
                $user_id,
                $order_number,
                $payment_method,
                $subtotal,
                $shipping_cost,
                $tax,
                $total,
                $first_name,
                $last_name,
                $email,
                $phone,
                $address,
                $city,
                $province,
                $notes
            ]);
            
            $order_id = $pdo->lastInsertId();
            
            // Thêm các mặt hàng vào chi tiết đơn hàng
            if (isLoggedIn()) {
                foreach ($cart_items as $item) {
                    $stmt = $pdo->prepare("INSERT INTO OrderItems (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
                }
                
                // Xóa giỏ hàng
                $stmt = $pdo->prepare("DELETE FROM Cart WHERE user_id = ?");
                $stmt->execute([$user_id]);
            } else {
                foreach ($cart_items as $item) {
                    $stmt = $pdo->prepare("INSERT INTO OrderItems (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
                }
                
                // Xóa giỏ hàng trong session
                unset($_SESSION['cart']);
            }
            
            // Commit transaction
            $pdo->commit();
            
            // Tạo thông báo thành công
            setFlashMessage('success', 'Đặt hàng thành công! Mã đơn hàng của bạn là ' . $order_number);
            
            // Chuyển hướng về trang chủ
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            // Rollback transaction nếu có lỗi
            $pdo->rollBack();
            $errors[] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }
    }
}

// Tính phí vận chuyển và tổng tiền
$shipping_cost = 0; // Miễn phí vận chuyển
$total = $subtotal + $shipping_cost;

// Lấy danh sách tỉnh/thành phố
$provinces = [
    'Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
    'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu',
    'Bắc Ninh', 'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước',
    'Bình Thuận', 'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông',
    'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai', 'Hà Giang',
    'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang', 'Hòa Bình',
    'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu',
    'Lâm Đồng', 'Lạng Sơn', 'Lào Cai', 'Long An', 'Nam Định',
    'Nghệ An', 'Ninh Bình', 'Ninh Thuận', 'Phú Thọ', 'Phú Yên',
    'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị',
    'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình', 'Thái Nguyên',
    'Thanh Hóa', 'Thừa Thiên Huế', 'Tiền Giang', 'Trà Vinh', 'Tuyên Quang',
    'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái'
];

require_once 'includes/header.php';
?>

<div class="checkout-container">
    <?php if (!$has_items): ?>
    <div class="empty-checkout">
        <h2>Giỏ hàng của bạn đang trống</h2>
        <p>Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.</p>
        <a href="products.php" class="continue-shopping-btn">Tiếp tục mua sắm</a>
    </div>
    <?php else: ?>
    
    <?php if (!empty($errors)): ?>
    <div class="error-container">
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div class="checkout-content">
        <div class="checkout-form">
            <h1>Billing details</h1>
            
            <form method="post" action="checkout.php" id="checkout-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?= $user['first_name'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?= $user['last_name'] ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?= $user['phone'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Street address</label>
                    <input type="text" id="address" name="address" value="<?= $user['address'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="city">Town / City</label>
                    <input type="text" id="city" name="city" value="<?= $user['city'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="province">Province</label>
                    <select id="province" name="province" required>
                        <option value="">Chọn tỉnh/thành phố</option>
                        <?php foreach ($provinces as $province): ?>
                        <option value="<?= $province ?>" <?= $user['state'] == $province ? 'selected' : '' ?>><?= $province ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="additional_info">Additional information</label>
                    <textarea id="additional_info" name="additional_info" rows="4"></textarea>
                </div>
            </form>
        </div>
        
        <div class="checkout-summary">
            <div class="order-summary">
                <h2>Product</h2>
                <div class="order-items">
                    <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">
                        <div class="product-info">
                            <?= $item['name'] ?>
                            <span class="quantity">× <?= $item['quantity'] ?></span>
                        </div>
                        <div class="product-price">
                            <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> vnđ
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-total">
                    <div class="subtotal">
                        <span>Subtotal</span>
                        <span><?= number_format($subtotal, 0, ',', '.') ?> vnđ</span>
                    </div>
                    <div class="total">
                        <span>Total</span>
                        <span class="total-price"><?= number_format($total, 0, ',', '.') ?> vnđ</span>
                    </div>
                </div>
            </div>
            
            <div class="payment-methods">
                <h3>Lựa chọn phương thức thanh toán</h3>
                
                <div class="payment-method">
                    <input type="radio" id="payment_bank" name="payment_method" value="bank_transfer" form="checkout-form" checked>
                    <label for="payment_bank">
                        <span class="radio-button"></span>
                        <div class="payment-info">
                            <strong>Thanh toán trực tiếp với tài khoản ngân hàng của chúng tôi.</strong>
                            <p>Vui lòng sử dụng Mã đơn hàng của bạn làm tham chiếu thanh toán. Đơn hàng của bạn sẽ không được giao cho đến khi tiền được chuyển vào tài khoản của chúng tôi.</p>
                        </div>
                    </label>
                </div>
                
                <div class="payment-method">
                    <input type="radio" id="payment_online" name="payment_method" value="online" form="checkout-form">
                    <label for="payment_online">
                        <span class="radio-button"></span>
                        <div class="payment-info">
                            <strong>Thanh toán Online</strong>
                        </div>
                    </label>
                </div>
                
                <div class="payment-method">
                    <input type="radio" id="payment_cod" name="payment_method" value="cod" form="checkout-form">
                    <label for="payment_cod">
                        <span class="radio-button"></span>
                        <div class="payment-info">
                            <strong>Thanh toán trực tiếp</strong>
                        </div>
                    </label>
                </div>
                
                <div class="privacy-notice">
                    <p>Dữ liệu cá nhân của bạn sẽ được sử dụng để hỗ trợ trải nghiệm của bạn trên toàn bộ trang web này, để quản lý quyền truy cập vào tài khoản của bạn và cho các mục đích khác được mô tả trong <a href="#">chính sách bảo mật</a> của chúng tôi.</p>
                </div>
                
                <button type="submit" form="checkout-form" name="place_order" class="place-order-btn">Đặt Hàng</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>