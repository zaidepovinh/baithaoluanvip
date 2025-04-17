<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Hiển thị nội dung file log
if (file_exists('cart_debug.log')) {
    echo '<h1>Cart Debug Log</h1>';
    echo '<pre>' . htmlspecialchars(file_get_contents('cart_debug.log')) . '</pre>';
} else {
    echo '<p>Debug log không tồn tại.</p>';
}

// Đường dẫn đến các file liên quan đến giỏ hàng
$files = [
    'includes/add_to_cart.php',
    'includes/remove_from_cart.php',
    'cart.php',
    'assets/js/cart.js',
    'assets/js/main.js',
    'assets/js/home.js'
];

// Hiển thị nội dung các file
foreach ($files as $file) {
    if (file_exists($file)) {
        echo '<h1>' . $file . '</h1>';
        echo '<pre>' . htmlspecialchars(file_get_contents($file)) . '</pre>';
    } else {
        echo '<p>File ' . $file . ' không tồn tại.</p>';
    }
}
?>