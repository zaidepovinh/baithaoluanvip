// JavaScript cho trang giới thiệu
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý chuyển đổi giữa các tab chính sách
    const policyItems = document.querySelectorAll('.policy-item');
    const policySections = document.querySelectorAll('.policy-section');
    
    policyItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Lấy target section
            const targetId = this.getAttribute('data-target');
            
            // Xóa class active khỏi tất cả các mục
            policyItems.forEach(item => item.classList.remove('active'));
            policySections.forEach(section => section.classList.remove('active'));
            
            // Thêm class active cho mục được chọn
            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
            
            // Cập nhật URL với hash
            window.location.hash = targetId;
            
            // Nếu ở màn hình nhỏ, cuộn xuống nơi có nội dung
            if (window.innerWidth < 992) {
                document.getElementById(targetId).scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
            
            // Nếu có hash trong URL, cuộn xuống section tương ứng
            if (window.location.hash) {
                const hash = window.location.hash.substring(1);
                const section = document.getElementById(hash);
                
                if (section) {
                    // Tìm item tương ứng với section và thêm active
                    policyItems.forEach(item => {
                        if (item.getAttribute('data-target') === hash) {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    });
                    
                    // Hiển thị section được chọn
                    policySections.forEach(section => {
                        if (section.id === hash) {
                            section.classList.add('active');
                        } else {
                            section.classList.remove('active');
                        }
                    });
                }
            }
        });
    });
    
    // Xử lý các mục thu gọn (collapsible)
    const collapsibleSections = document.querySelectorAll('.collapsible-section');
    
    collapsibleSections.forEach(section => {
        const header = section.querySelector('.collapsible-header');
        
        header.addEventListener('click', function() {
            section.classList.toggle('active');
            
            // Nếu section đang active, mở rộng nội dung
            if (section.classList.contains('active')) {
                const content = section.querySelector('.collapsible-content');
                content.style.maxHeight = content.scrollHeight + 'px';
                section.querySelector('.toggle-icon').textContent = '×';
            } else {
                section.querySelector('.collapsible-content').style.maxHeight = '0';
                section.querySelector('.toggle-icon').textContent = '+';
            }
        });
    });
    
    // Kiểm tra URL khi trang load để hiển thị section phù hợp
    window.addEventListener('load', function() {
        if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            const targetItem = document.querySelector(`.policy-item[data-target="${hash}"]`);
            
            if (targetItem) {
                targetItem.click();
            } else {
                // Mặc định hiển thị tab đầu tiên nếu không tìm thấy hash
                document.querySelector('.policy-item').click();
            }
        } else {
            // Mặc định hiển thị tab đầu tiên nếu không có hash
            document.querySelector('.policy-item').click();
        }
    });
    
    // Tự động mở rộng section đầu tiên trong mỗi tab
    policySections.forEach(section => {
        const firstCollapsible = section.querySelector('.collapsible-section');
        if (firstCollapsible) {
            setTimeout(() => {
                firstCollapsible.querySelector('.collapsible-header').click();
            }, 500);
        }
    });
});