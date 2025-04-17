// JavaScript cho trang chủ
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo slider cho sản phẩm mới
    initProductSlider('new-products-carousel', 'new-prev', 'new-next', 'new-dots');
    
    // Khởi tạo slider cho sản phẩm bán chạy
    initProductSlider('top-products-carousel', 'top-prev', 'top-next', 'top-dots');
    
    // Thêm sự kiện cho nút Add to Cart
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            addToCart(productId, 1);
        });
    });
    
    // Thêm sự kiện cho nút Wishlist
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            toggleWishlist(productId);
            
            // Chuyển đổi icon trái tim
            const heartIcon = this.querySelector('i');
            heartIcon.classList.toggle('far');
            heartIcon.classList.toggle('fas');
            heartIcon.classList.toggle('text-danger');
            
            // Thêm hiệu ứng nhảy
            this.classList.add('bounce');
            setTimeout(() => {
                this.classList.remove('bounce');
            }, 1000);
        });
    });
    
    // Hiệu ứng Scroll Reveal
    const sections = document.querySelectorAll('.product-section, .about-home-section, .feature-section');
    sections.forEach(section => {
        addScrollEffect(section);
    });
    
    // Hiệu ứng hover cho sản phẩm
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const addToCartBtn = this.querySelector('.add-to-cart-btn');
            addToCartBtn.classList.add('pulse');
        });
        
        card.addEventListener('mouseleave', function() {
            const addToCartBtn = this.querySelector('.add-to-cart-btn');
            addToCartBtn.classList.remove('pulse');
        });
    });
});

/**
 * Khởi tạo slider cho danh sách sản phẩm
 */
function initProductSlider(carouselId, prevBtnId, nextBtnId, dotsId) {
    const carousel = document.getElementById(carouselId);
    const prevBtn = document.getElementById(prevBtnId);
    const nextBtn = document.getElementById(nextBtnId);
    const dotsContainer = document.getElementById(dotsId);
    
    if (!carousel || !prevBtn || !nextBtn || !dotsContainer) return;
    
    const cardWidth = carousel.querySelector('.product-card').offsetWidth + 20; // width + margin
    const visibleCards = Math.floor(carousel.offsetWidth / cardWidth);
    const totalCards = carousel.querySelectorAll('.product-card').length;
    const maxScrollPosition = (totalCards - visibleCards) * cardWidth;
    
    // Khởi tạo dots
    dotsContainer.innerHTML = '';
    const totalDots = Math.ceil(totalCards / visibleCards);
    
    for (let i = 0; i < totalDots; i++) {
        const dot = document.createElement('span');
        dot.className = i === 0 ? 'dot active' : 'dot';
        dot.dataset.index = i;
        dot.addEventListener('click', function() {
            scrollToPosition(i * visibleCards * cardWidth);
            updateActiveDot(i);
        });
        dotsContainer.appendChild(dot);
    }
    
    // Xử lý nút prev
    prevBtn.addEventListener('click', function() {
        const currentScroll = carousel.scrollLeft;
        const targetScroll = Math.max(currentScroll - (visibleCards * cardWidth), 0);
        scrollToPosition(targetScroll);
        
        const currentDotIndex = Math.floor(targetScroll / (visibleCards * cardWidth));
        updateActiveDot(currentDotIndex);
    });
    
    // Xử lý nút next
    nextBtn.addEventListener('click', function() {
        const currentScroll = carousel.scrollLeft;
        const targetScroll = Math.min(currentScroll + (visibleCards * cardWidth), maxScrollPosition);
        scrollToPosition(targetScroll);
        
        const currentDotIndex = Math.floor(targetScroll / (visibleCards * cardWidth));
        updateActiveDot(currentDotIndex);
    });
    
    // Scroll to position với animation
    function scrollToPosition(position) {
        carousel.scrollTo({
            left: position,
            behavior: 'smooth'
        });
    }
    
    // Cập nhật active dot
    function updateActiveDot(index) {
        const dots = dotsContainer.querySelectorAll('.dot');
        dots.forEach(dot => dot.classList.remove('active'));
        if (dots[index]) {
            dots[index].classList.add('active');
        }
    }
    
    // Xử lý scroll event để cập nhật active dot
    carousel.addEventListener('scroll', function() {
        const currentScroll = carousel.scrollLeft;
        const currentDotIndex = Math.round(currentScroll / (visibleCards * cardWidth));
        updateActiveDot(currentDotIndex);
    });
    
    // Auto play slider
    let slideInterval;
    
    function startAutoPlay() {
        slideInterval = setInterval(() => {
            const currentScroll = carousel.scrollLeft;
            
            // Kiểm tra xem đã ở cuối chưa
            if (currentScroll >= maxScrollPosition) {
                scrollToPosition(0); // Quay lại đầu
                updateActiveDot(0);
            } else {
                const targetScroll = Math.min(currentScroll + (visibleCards * cardWidth), maxScrollPosition);
                scrollToPosition(targetScroll);
                
                const currentDotIndex = Math.floor(targetScroll / (visibleCards * cardWidth));
                updateActiveDot(currentDotIndex);
            }
        }, 5000);
    }
    
    function stopAutoPlay() {
        clearInterval(slideInterval);
    }
    
    startAutoPlay();
    
    // Dừng auto play khi hover vào carousel
    carousel.addEventListener('mouseenter', stopAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);
    
    // Dừng auto play khi người dùng tương tác với nút prev/next
    prevBtn.addEventListener('mouseenter', stopAutoPlay);
    nextBtn.addEventListener('mouseenter', stopAutoPlay);
    prevBtn.addEventListener('mouseleave', startAutoPlay);
    nextBtn.addEventListener('mouseleave', startAutoPlay);
    
    // Cũng dừng khi tương tác với dots
    dotsContainer.addEventListener('mouseenter', stopAutoPlay);
    dotsContainer.addEventListener('mouseleave', startAutoPlay);
}

/**
 * Thêm vào giỏ hàng
 */
function addToCart(productId, quantity) {
    // Gửi AJAX request để thêm sản phẩm vào giỏ hàng
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
            const cartIcon = document.querySelector('.cart-icon');
            cartIcon.classList.add('shake');
            
            setTimeout(() => {
                cartIcon.classList.remove('shake');
            }, 500);
        } else {
            showNotification('Đã xảy ra lỗi! Vui lòng thử lại sau.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Đã xảy ra lỗi! Vui lòng thử lại sau.', 'error');
    });
}

/**
 * Thêm/xóa khỏi danh sách yêu thích
 */
function toggleWishlist(productId) {
    fetch('includes/toggle_wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.action === 'added') {
                showNotification('Sản phẩm đã được thêm vào danh sách yêu thích');
            } else {
                showNotification('Sản phẩm đã được xóa khỏi danh sách yêu thích');
            }
        } else {
            // Nếu người dùng chưa đăng nhập, hiển thị thông báo
            if (data.message === 'login_required') {
                showNotification('Vui lòng đăng nhập để sử dụng tính năng này', 'warning');
            } else {
                showNotification('Đã xảy ra lỗi! Vui lòng thử lại sau.', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Đã xảy ra lỗi! Vui lòng thử lại sau.', 'error');
    });
}

/**
 * Thêm hiệu ứng scroll cho các phần tử
 */
function addScrollEffect(element) {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    observer.observe(element);
}

/**
 * Hiển thị thông báo
 */
function showNotification(message, type = 'success') {
    // Kiểm tra xem đã có hàm hiển thị thông báo chưa
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
    } else {
        // Nếu chưa có, tạo thông báo mới
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
        closeBtn.addEventListener('click', function() {
            notification.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(notification);
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
}