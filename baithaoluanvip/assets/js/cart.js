// JavaScript để xử lý giỏ hàng
document.addEventListener('DOMContentLoaded', function() {
    // Lấy số lượng sản phẩm trong giỏ hàng khi trang load
    fetchCartCount();
    
    // Hàm lấy số lượng sản phẩm trong giỏ hàng
    function fetchCartCount() {
        fetch('includes/get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật số lượng hiển thị
                    document.querySelector('.cart-count').textContent = data.cart_count;
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // Hàm thêm sản phẩm vào giỏ hàng (được gọi từ các trang khác)
    window.addToCart = function(productId, quantity = 1) {
        fetch('includes/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId + '&quantity=' + quantity
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật số lượng sản phẩm trong giỏ hàng
                document.querySelector('.cart-count').textContent = data.cart_count;
                
                // Hiển thị thông báo
                showNotification('Sản phẩm đã được thêm vào giỏ hàng');
                
                // Hiệu ứng giỏ hàng để người dùng chú ý
                animateCartIcon();
            } else {
                showNotification('Đã xảy ra lỗi! Vui lòng thử lại sau.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Đã xảy ra lỗi! Vui lòng thử lại sau.');
        });
    };
    
    // Hiệu ứng giỏ hàng
    function animateCartIcon() {
        const cartIcon = document.querySelector('.cart-icon');
        cartIcon.classList.add('shake');
        
        setTimeout(() => {
            cartIcon.classList.remove('shake');
        }, 500);
    }
    
    // Hiển thị thông báo
    function showNotification(message) {
        // Kiểm tra xem đã có thông báo nào chưa
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            document.body.removeChild(existingNotification);
        }
        
        // Tạo phần tử thông báo mới
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

// JavaScript cho trang giỏ hàng
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút tăng/giảm số lượng
    const decreaseButtons = document.querySelectorAll('.quantity-btn.decrease');
    const increaseButtons = document.querySelectorAll('.quantity-btn.increase');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.nextElementSibling;
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
                updateCartItemSubtotal(input);
                updateCartTotals();
            }
        });
    });
    
    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            let value = parseInt(input.value);
            input.value = value + 1;
            updateCartItemSubtotal(input);
            updateCartTotals();
        });
    });
    
    // Cập nhật thành tiền khi thay đổi số lượng
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (parseInt(this.value) < 0) {
                this.value = 0;
            }
            updateCartItemSubtotal(this);
            updateCartTotals();
        });
    });
    
    // Cập nhật thành tiền của một sản phẩm
    function updateCartItemSubtotal(input) {
        const cartItem = input.closest('.cart-item');
        const priceText = cartItem.querySelector('.cart-item-price').textContent;
        const price = extractPrice(priceText);
        const quantity = parseInt(input.value);
        const subtotal = price * quantity;
        
        cartItem.querySelector('.cart-item-subtotal').textContent = formatPrice(subtotal);
    }
    
    // Cập nhật tổng giỏ hàng
    function updateCartTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.cart-item').forEach(item => {
            const subtotalText = item.querySelector('.cart-item-subtotal').textContent;
            subtotal += extractPrice(subtotalText);
        });
        
        // Tạm thời không tính phí vận chuyển
        const total = subtotal;
        
        // Cập nhật hiển thị
        document.querySelector('.summary-row:not(.total) span:last-child').textContent = formatPrice(subtotal);
        document.querySelector('.summary-row.total span:last-child').textContent = formatPrice(total);
    }
    
    // Hàm trích xuất giá từ chuỗi
    function extractPrice(priceString) {
        return parseInt(priceString.replace(/\D/g, ''));
    }
    
    // Hàm định dạng giá
    function formatPrice(price) {
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' vnđ';
    }
    
    // Nút xóa sản phẩm
    const removeButtons = document.querySelectorAll('.remove-btn');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                e.preventDefault();
            }
        });
    });
    
    // Nút cập nhật giỏ hàng
    const updateCartBtn = document.querySelector('.update-cart-btn');
    if (updateCartBtn) {
        updateCartBtn.addEventListener('click', function() {
            document.getElementById('update-cart-form').submit();
        });
    }
});
