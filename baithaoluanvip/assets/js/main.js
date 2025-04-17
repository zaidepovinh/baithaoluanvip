// Thêm hoặc chỉnh sửa hàm xử lý thêm vào giỏ hàng
function addToCart(productId, quantity = 1) {
    // Thêm log để debug
    console.log('Adding product to cart:', productId, quantity);
    // Đảm bảo quantity là 1 nếu không được chỉ định
    quantity = quantity || 1; // Sửa ở đây nếu có vấn đề

    fetch('includes/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=' + quantity
    })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Cập nhật số lượng sản phẩm trong giỏ hàng
                updateCartCount(data.cart_count);

                // Hiển thị thông báo
                showNotification('Sản phẩm đã được thêm vào giỏ hàng');

                // Hiệu ứng giỏ hàng để người dùng chú ý
                animateCartIcon();
            } else {
                showNotification('Đã xảy ra lỗi: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng!', 'error');
        });
}

// Hàm cập nhật số lượng sản phẩm trong icon giỏ hàng
function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Hiệu ứng animation cho icon giỏ hàng
function animateCartIcon() {
    const cartIcon = document.querySelector('.cart-icon');
    if (cartIcon) {
        cartIcon.classList.add('shake');

        setTimeout(() => {
            cartIcon.classList.remove('shake');
        }, 500);
    }
}

// Hàm hiển thị thông báo
function showNotification(message, type = 'success') {
    // Tạo element thông báo
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span>${message}</span>
            <button class="close-notification">&times;</button>
        </div>
    `;

    // Thêm vào body
    document.body.appendChild(notification);

    // Hiển thị thông báo
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // Thêm sự kiện đóng thông báo
    const closeBtn = notification.querySelector('.close-notification');
    closeBtn.addEventListener('click', function () {
        notification.classList.remove('show');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    });

    // Tự động ẩn sau 2 giây
    setTimeout(() => {
        if (document.body.contains(notification)) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }
    }, 2000);
}

// Thêm sự kiện cho nút thêm vào giỏ hàng khi trang đã load
document.addEventListener('DOMContentLoaded', function () {
    // Xử lý các nút "Thêm vào giỏ hàng"
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            addToCart(productId, 1);
        });
    });
});