<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Lấy danh mục sản phẩm
$categories_query = "SELECT * FROM categories";
$stmt = $pdo->query($categories_query);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý bộ lọc
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'relevance';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 9;

// Tạo câu truy vấn
$query = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($category_filter)) {
    $query .= " AND category_id = ?";
    $params[] = $category_filter;
}

if (!empty($price_min)) {
    $query .= " AND price >= ?";
    $params[] = $price_min;
}

if (!empty($price_max)) {
    $query .= " AND price <= ?";
    $params[] = $price_max;
}

// Sắp xếp theo điều kiện
switch ($sort_by) {
    case 'price_asc':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY price DESC";
        break;
    case 'newest':
        $query .= " ORDER BY created_at DESC";
        break;
    default:
        $query .= " ORDER BY id DESC"; // Thay relevance bằng id hoặc trường khác thực tế có trong bảng
        break;
}

// Phân trang - CÁCH GIẢI QUYẾT: Sử dụng LIMIT với các giá trị tính toán trực tiếp
$start_from = ($page - 1) * $items_per_page;

// CÁCH 1: Dùng truy vấn chuẩn bị trước, nhưng thêm limit trực tiếp vào chuỗi truy vấn
$query .= " LIMIT " . $start_from . ", " . $items_per_page;

// Thực thi truy vấn
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tính tổng số sản phẩm và số trang
$count_query = "SELECT COUNT(*) as total FROM products WHERE 1=1";
$count_params = [];

if (!empty($category_filter)) {
    $count_query .= " AND category_id = ?";
    $count_params[] = $category_filter;
}
if (!empty($price_min)) {
    $count_query .= " AND price >= ?";
    $count_params[] = $price_min;
}
if (!empty($price_max)) {
    $count_query .= " AND price <= ?";
    $count_params[] = $price_max;
}

$count_stmt = $pdo->prepare($count_query);
$count_stmt->execute($count_params);
$count_row = $count_stmt->fetch(PDO::FETCH_ASSOC);
$total_products = $count_row['total'];
$total_pages = ceil($total_products / $items_per_page);

require_once 'includes/header.php';
?>

<!-- Nội dung trang sản phẩm -->
<div class="product-container">
    <div class="sidebar">
        <h2>Double-Cleanse</h2>
        <ul class="sidebar-menu">
            <li><a href="#">Cleansing Balms</a></li>
            <li><a href="#">Oil Cleansers</a></li>
            <li><a href="#">Water Cleansers</a></li>
        </ul>
        
        <h2>BỘ LỌC</h2>
        <div class="filter-section">
            <h3>LOẠI SẢN PHẨM</h3>
            <ul>
                <?php foreach($categories as $category): ?>
                    <li>
                        <a href="?category=<?= $category['id'] ?>">
                            <?= $category['name'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <h3>THƯƠNG HIỆU</h3>
            <ul>
                <li><a href="#">Innisfree</a></li>
                <li><a href="#">Cosrx</a></li>
                <li><a href="#">Some By Mi</a></li>
            </ul>
            
            <h3>LOẠI DA</h3>
            <form class="filter-form">
                <div class="checkbox-item">
                    <input type="checkbox" id="da-dau" name="skin_type[]" value="oily">
                    <label for="da-dau">Da dầu</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="da-kho" name="skin_type[]" value="dry">
                    <label for="da-kho">Da khô</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="da-nhay-cam" name="skin_type[]" value="sensitive">
                    <label for="da-nhay-cam">Da nhạy cảm</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="da-mun" name="skin_type[]" value="acne-prone">
                    <label for="da-mun">Da mụn</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="da-binh-thuong" name="skin_type[]" value="normal">
                    <label for="da-binh-thuong">Da bình thường</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="da-hhy-hop" name="skin_type[]" value="combination">
                    <label for="da-hhy-hop">Da hỗn hợp</label>
                </div>
            </form>
            
            <h3>Price Range</h3>
            <div class="price-range-filter">
                <div class="price-range">
                    <a href="?price_max=25" class="<?= $price_max == '25' ? 'active' : '' ?>">Under $25</a>
                </div>
                <div class="price-range">
                    <a href="?price_min=25&price_max=50" class="<?= $price_min == '25' && $price_max == '50' ? 'active' : '' ?>">$25 - $50</a>
                </div>
                <div class="price-range">
                    <a href="?price_min=50&price_max=100" class="<?= $price_min == '50' && $price_max == '100' ? 'active' : '' ?>">$50 - $100</a>
                </div>
            </div>
            
            <div class="price-slider">
                <input type="range" min="0" max="100" value="25" class="slider" id="min-price">
                <input type="range" min="0" max="100" value="75" class="slider" id="max-price">
                <div class="price-values">
                    <span id="min-price-value">$25</span>
                    <span id="max-price-value">$75</span>
                </div>
            </div>
            
            <button class="apply-filter-btn">Áp dụng</button>
        </div>
    </div>
    
    <div class="product-content">
        <div class="product-header">
            <h1 class="collection-title"><?= $total_products ?> PRODUCTS</h1>
            <div class="product-sort">
                <label for="sort">SẮP XẾP THEO</label>
                <select id="sort" class="sort-dropdown">
                    <option value="relevance" <?= $sort_by == 'relevance' ? 'selected' : '' ?>>Relevance</option>
                    <option value="price_asc" <?= $sort_by == 'price_asc' ? 'selected' : '' ?>>Giá: Tăng dần</option>
                    <option value="price_desc" <?= $sort_by == 'price_desc' ? 'selected' : '' ?>>Giá: Giảm dần</option>
                    <option value="newest" <?= $sort_by == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                </select>
            </div>
        </div>
        
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <?php if ($product['is_sale']): ?>
                <span class="sale-tag">Sale</span>
                <?php endif; ?>
                <?php if ($product['is_top']): ?>
                <span class="top-tag">Top Seller</span>
                <?php endif; ?>
                <div class="product-image">
                    <img src="assets/images/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                </div>
                <h3 class="product-name"><?= $product['name'] ?></h3>
                <div class="product-rating">
                    <?php for($i = 0; $i < 5; $i++): ?>
                        <?php if($i < $product['rating']): ?>
                            <span class="star filled">★</span>
                        <?php else: ?>
                            <span class="star">★</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <span class="rating-count">(<?= $product['reviews_count'] ?>)</span>
                </div>
                <p class="product-description"><?= $product['short_description'] ?></p>
                <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                <button class="add-to-cart-btn" data-product-id="<?= $product['id'] ?>" data-quantity="1">Thêm Vào Giỏ Hàng</button>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?page=<?= $page-1 ?>" class="page-link">&lt;</a>
            <?php endif; ?>
            
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            
            <?php if($page < $total_pages): ?>
                <a href="?page=<?= $page+1 ?>" class="page-link">&gt;</a>
            <?php endif; ?>
            
            <div class="show-more">
                <button class="show-more-btn">Show More ↓</button>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>