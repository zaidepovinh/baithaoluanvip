// JavaScript cho trang sản phẩm
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý hiệu ứng khi hover vào sản phẩm
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('product-hover');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('product-hover');
        });
    });
    
    // Xử lý khi thay đổi sắp xếp
    const sortDropdown = document.getElementById('sort');
    if (sortDropdown) {
        sortDropdown.addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', this.value);
            window.location.href = currentUrl.toString();
        });
    }
    
    // Xử lý Price Range slider
    const minPriceSlider = document.getElementById('min-price');
    const maxPriceSlider = document.getElementById('max-price');
    const minPriceValue = document.getElementById('min-price-value');
    const maxPriceValue = document.getElementById('max-price-value');
    
    if (minPriceSlider && maxPriceSlider) {
        minPriceSlider.addEventListener('input', function() {
            const value = parseInt(this.value);
            minPriceValue.textContent = '$' + value;
            
            // Đảm bảo giá trị tối thiểu không vượt quá giá trị tối đa
            if (value >= parseInt(maxPriceSlider.value)) {
                maxPriceSlider.value = value + 5;
                maxPriceValue.textContent = '$' + maxPriceSlider.value;
            }
        });
        
        maxPriceSlider.addEventListener('input', function() {
            const value = parseInt(this.value);
            maxPriceValue.textContent = '$' + value;
            
            // Đảm bảo giá trị tối đa không nhỏ hơn giá trị tối thiểu
            if (value <= parseInt(minPriceSlider.value)) {
                minPriceSlider.value = value - 5;
                minPriceValue.textContent = '$' + minPriceSlider.value;
            }
        });
    }
    
    // Xử lý nút Apply Filter
    const applyFilterBtn = document.querySelector('.apply-filter-btn');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
            const currentUrl = new URL(window.location.href);
            
            // Lấy giá trị từ các filter
            const minPrice = document.getElementById('min-price').value;
            const maxPrice = document.getElementById('max-price').value;
            
            // Lấy loại da được chọn
            const skinTypes = [];
            document.querySelectorAll('input[name="skin_type[]"]:checked').forEach(checkbox => {
                skinTypes.push(checkbox.value);
            });
            
            // Cập nhật URL với các tham số lọc
            currentUrl.searchParams.set('price_min', minPrice);
            currentUrl.searchParams.set('price_max', maxPrice);
            
            if (skinTypes.length > 0) {
                currentUrl.searchParams.set('skin_types', skinTypes.join(','));
            } else {
                currentUrl.searchParams.delete('skin_types');
            }
            
            // Chuyển hướng tới URL mới với các tham số lọc
            window.location.href = currentUrl.toString();
        });
    }
    
    // Xử lý nút Add To Bag
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            addToCart(productId);
        });
    });
    
    // Hàm thêm sản phẩm vào giỏ hàng
    function addToCart(productId) {
        // Gửi AJAX request để thêm sản phẩm vào giỏ hàng
        fetch('includes/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật số lượng sản phẩm trong giỏ hàng
                document.querySelector('.cart-count').textContent = data.cart_count;
                
                // Hiển thị thông báo
                showNotification('Sản phẩm đã được thêm vào giỏ hàng');
            } else {
                showNotification('Đã xảy ra lỗi! Vui lòng thử lại sau.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Đã xảy ra lỗi! Vui lòng thử lại sau.');
        });
    }
    
    // Hiển thị thông báo
    function showNotification(message) {
        // Tạo phần tử thông báo
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = message;
        
        // Thêm vào body
        document.body.appendChild(notification);
        
        // Hiển thị thông báo
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Ẩn và xóa thông báo sau 1 giây
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 1000);
    }
});