// JavaScript cho trang thanh toán
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý khi chọn phương thức thanh toán
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Hiển thị thông tin chi tiết phù hợp với phương thức thanh toán
            const paymentMethod = this.value;
            
            // Ẩn tất cả các thông tin chi tiết thanh toán
            document.querySelectorAll('.payment-details').forEach(details => {
                details.style.display = 'none';
            });
            
            // Hiển thị thông tin chi tiết của phương thức được chọn
            const selectedDetails = document.getElementById(`${paymentMethod}_details`);
            if (selectedDetails) {
                selectedDetails.style.display = 'block';
            }
        });
    });
    
    // Xác thực form trước khi gửi
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = checkoutForm.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            // Kiểm tra email hợp lệ
            const emailField = document.getElementById('email');
            if (emailField && emailField.value.trim()) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailField.value.trim())) {
                    isValid = false;
                    emailField.classList.add('error');
                }
            }
            
            // Kiểm tra số điện thoại
            const phoneField = document.getElementById('phone');
            if (phoneField && phoneField.value.trim()) {
                // Cho phép số điện thoại có độ dài từ 10-11 số, có thể bắt đầu với dấu + hoặc số 0
                const phonePattern = /^(\+?\d{1,3}|\d{1,4})?\s?\d{9,10}$/;
                if (!phonePattern.test(phoneField.value.trim().replace(/\s/g, ''))) {
                    isValid = false;
                    phoneField.classList.add('error');
                }
            }
            
            // Kiểm tra phương thức thanh toán đã được chọn chưa
            const paymentSelected = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentSelected) {
                isValid = false;
                document.querySelector('.payment-methods').classList.add('error-section');
            } else {
                document.querySelector('.payment-methods').classList.remove('error-section');
            }
            
            if (!isValid) {
                e.preventDefault();
                // Hiển thị thông báo lỗi
                showNotification('Vui lòng điền đầy đủ thông tin thanh toán!', 'error');
                
                // Cuộn lên trên để hiển thị trường có lỗi đầu tiên
                const firstError = checkoutForm.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    
    // Hiệu ứng khi focus vào input
    const formInputs = document.querySelectorAll('.form-group input, .form-group select, .form-group textarea');
    
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focus');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focus');
            
            // Kiểm tra và hiển thị lỗi khi người dùng rời khỏi trường required
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });
    });
});

// Hàm hiển thị thông báo
function showNotification(message, type = 'success') {
    // Kiểm tra xem đã có hàm hiển thị thông báo chưa
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
    } else {
        // Nếu chưa có, tạo thông báo mới
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
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
}