<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Lấy sản phẩm mới nhất
$new_products_query = "SELECT p.*, c.name as category_name FROM Products p 
                      LEFT JOIN Categories c ON p.category_id = c.id 
                      WHERE p.is_new = 1 
                      ORDER BY p.created_at DESC LIMIT 8";
$stmt = $pdo->query($new_products_query);
$new_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy sản phẩm bán chạy
$top_products_query = "SELECT p.*, c.name as category_name FROM Products p 
                      LEFT JOIN Categories c ON p.category_id = c.id 
                      WHERE p.is_top = 1 
                      ORDER BY p.reviews_count DESC LIMIT 8";
$stmt = $pdo->query($top_products_query);
$top_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<!-- Banner Hero -->
<section class="hero-banner">
    <div class="hero-content">
        <h1>KHÁM PHÁ VẺ ĐẸP CỦA BẠN VỚI<br>TOO BEAUTY</h1>
        <p>Chăm sóc bản thân, lan tỏa vẻ thương - bắt đầu từ những điều nhỏ bé</p>
        <a href="products.php" class="btn btn-primary">MUA NGAY</a>
    </div>
    <div class="hero-image">
        <img src="assets/images/hero-banner.jpg" alt="Too Beauty">
    </div>
</section>

<!-- Sản phẩm mới -->
<section class="product-section">
    <div class="section-header">
        <h2 class="section-title">SẢN PHẨM MỚI</h2>
        <a href="products.php?sort=newest" class="view-all">Xem Tất Cả</a>
    </div>
    
    <div class="product-slider">
        <button class="slider-arrow prev" id="new-prev"><i class="fas fa-chevron-left"></i></button>
        
        <div class="product-carousel" id="new-products-carousel">
            <?php foreach ($new_products as $product): ?>
            <div class="product-card">
                <?php if ($product['is_sale']): ?>
                <span class="sale-tag">Sale</span>
                <?php endif; ?>
                <div class="product-image">
                    <a href="product-detail.php?id=<?= $product['id'] ?>">
                        <img src="assets/images/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                    </a>
                    <button class="wishlist-btn" data-product-id="<?= $product['id'] ?>">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <div class="product-info">
                    <h3 class="product-name">
                        <a href="product-detail.php?id=<?= $product['id'] ?>"><?= $product['name'] ?></a>
                    </h3>
                    <div class="product-category">
                        <?= $product['category_name'] ?>
                    </div>
                    <div class="product-rating">
                        <?php for($i = 0; $i < 5; $i++): ?>
                            <?php if($i < floor($product['rating'])): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif($i < $product['rating'] && $product['rating'] - floor($product['rating']) >= 0.5): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span class="rating-count">(<?= $product['reviews_count'] ?>)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price"><?= number_format($product['price'], 0, ',', '.') ?> ₫</span>
                        <?php if ($product['sale_price']): ?>
                        <span class="old-price"><?= number_format($product['sale_price'], 0, ',', '.') ?> ₫</span>
                        <?php endif; ?>
                    </div>
                    <a href="#" class="add-to-cart-btn" data-product-id="<?= $product['id'] ?>">Thêm Vào Giỏ Hàng</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <button class="slider-arrow next" id="new-next"><i class="fas fa-chevron-right"></i></button>
    </div>
    
    <div class="slider-dots" id="new-dots">
        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>
</section>

<!-- Sản phẩm bán chạy -->
<section class="product-section">
    <div class="section-header">
        <h2 class="section-title">SẢN PHẨM BÁN CHẠY</h2>
        <a href="products.php?sort=popular" class="view-all">Xem Tất Cả</a>
    </div>
    
    <div class="product-slider">
        <button class="slider-arrow prev" id="top-prev"><i class="fas fa-chevron-left"></i></button>
        
        <div class="product-carousel" id="top-products-carousel">
            <?php foreach ($top_products as $product): ?>
            <div class="product-card">
                <?php if ($product['is_top']): ?>
                <span class="top-tag">Top Seller</span>
                <?php endif; ?>
                <div class="product-image">
                    <a href="product-detail.php?id=<?= $product['id'] ?>">
                        <img src="assets/images/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                    </a>
                    <button class="wishlist-btn" data-product-id="<?= $product['id'] ?>">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <div class="product-info">
                    <h3 class="product-name">
                        <a href="product-detail.php?id=<?= $product['id'] ?>"><?= $product['name'] ?></a>
                    </h3>
                    <div class="product-category">
                        <?= $product['category_name'] ?>
                    </div>
                    <div class="product-rating">
                        <?php for($i = 0; $i < 5; $i++): ?>
                            <?php if($i < floor($product['rating'])): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif($i < $product['rating'] && $product['rating'] - floor($product['rating']) >= 0.5): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span class="rating-count">(<?= $product['reviews_count'] ?>)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price"><?= number_format($product['price'], 0, ',', '.') ?> ₫</span>
                        <?php if ($product['sale_price']): ?>
                        <span class="old-price"><?= number_format($product['sale_price'], 0, ',', '.') ?> ₫</span>
                        <?php endif; ?>
                    </div>
                    <a href="#" class="add-to-cart-btn" data-product-id="<?= $product['id'] ?>">Thêm Vào Giỏ Hàng</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <button class="slider-arrow next" id="top-next"><i class="fas fa-chevron-right"></i></button>
    </div>
    
    <div class="slider-dots" id="top-dots">
        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>
</section>

<!-- About Section -->
<section class="about-home-section">
    <div class="about-home-content">
        <h2 class="section-title">Too Beauty</h2>
        <p>TooBeauty là đối với sứ mệnh giúp mọi người tỏa sáng theo cách riêng của mình. Chúng tôi tin rằng vẻ đẹp không chỉ đến từ bên ngoài, mà còn từ sự tự tin và tình yêu với bản thân trong những gì tự nhiên mang lại.</p>
        <a href="about.php" class="btn btn-secondary">Về Chúng Tôi</a>
    </div>
    <div class="about-home-image">
        <img src="assets/images/about-home.jpg" alt="Too Beauty Products">
    </div>
</section>

<!-- Feature Section -->
<section class="feature-section">
    <div class="feature-image">
        <img src="assets/images/feature-image.jpg" alt="Too Beauty Skincare">
    </div>
    <div class="feature-content">
        <h2 class="section-title">Nâng niu làn da</h2>
        <p>TooBeauty không chỉ mang đến sản phẩm dưỡng da, mà còn là những trải nghiệm thư giãn, giúp bạn yêu thương chính mình hơn mỗi ngày.</p>
        <a href="products.php" class="btn btn-secondary">Sản Phẩm</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>