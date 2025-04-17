<?php
// Xác định trang hiện tại dựa trên URL
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Too Beauty - Mỹ phẩm chính hãng</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/home.css"> <!-- Thêm CSS cho trang chủ -->
    <link rel="stylesheet" href="assets/css/product.css"> <!-- Thêm CSS cho trang sản phẩm -->
    <link rel="stylesheet" href="assets/css/cart.css"> <!-- Thêm CSS cho trang giỏ hàng -->
    <link rel="stylesheet" href="assets/css/checkout.css"> <!-- Thêm CSS cho trang thanh toán -->
    <link rel="stylesheet" href="assets/css/about.css"> <!-- Thêm CSS cho trang giới thiệu -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <a href="index.php">
                    <span class="logo-text"><span class="logo-pink">Too</span> Beauty</span>
                </a>
            </div>
            
            <nav class="main-nav">
                <ul class="nav-links">
                    <li class="<?= $current_page === 'index.php' ? 'active' : '' ?>">
                        <a href="index.php">TRANG CHỦ</a>
                    </li>
                    <li class="<?= $current_page === 'products.php' ? 'active' : '' ?>">
                        <a href="products.php">SẢN PHẨM</a>
                    </li>
                    <li class="<?= $current_page === 'about.php' ? 'active' : '' ?>">
                        <a href="about.php">GIỚI THIỆU</a>
                    </li>
                    <li class="<?= $current_page === 'contact.php' ? 'active' : '' ?>">
                        <a href="#">LIÊN HỆ</a>
                    </li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <div class="search-box">
                    <form action="search.php" method="get">
                        <input type="text" name="keyword" placeholder="TÌM KIẾM" required>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <div class="user-actions">
                    <a href="#" class="action-link"> <!-- link điền vào thẻ a:< ? = isLoggedIn() ? 'account.php' : 'login.php' ?>-->
                        <div class="action-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="action-text">
                            TÀI KHOẢN
                        </div>
                    </a>
                    
                    <a href="cart.php" class="action-link">
                        <div class="action-icon cart-icon">
                            <i class="fas fa-shopping-bag"></i>
                            <?php
                            $cart_count = 0;
                            if (isLoggedIn()) {
                                // Đếm số lượng sản phẩm trong giỏ hàng từ database
                                $user_id = $_SESSION['user_id'];
                                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM Cart WHERE user_id = ?");
                                $stmt->execute([$user_id]);
                                $result = $stmt->fetch();
                                $cart_count = $result['count'];
                            } else {
                                // Đếm số lượng sản phẩm trong giỏ hàng từ session
                                $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                            }
                            ?>
                            <span class="cart-count"><?= $cart_count ?></span>
                        </div>
                        <div class="action-text">
                            GIỎ HÀNG
                        </div>
                    </a>
                </div>
            </div>
            
            <button class="mobile-menu-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>
    </header>
    
    <main>