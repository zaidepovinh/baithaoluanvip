<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<div class="about-container">
    <div class="about-sidebar">
        <ul class="policy-nav">
            <li class="policy-item active" data-target="about-us">
                <a href="#about-us">Về Chúng Tôi</a>
            </li>
            <li class="policy-item" data-target="warranty-policy">
                <a href="#warranty-policy">Chính Sách Bảo Hành</a>
            </li>
            <li class="policy-item" data-target="shipping-policy">
                <a href="#shipping-policy">Chính Sách Vận Chuyển</a>
            </li>
            <li class="policy-item" data-target="payment-policy">
                <a href="#payment-policy">Chính Sách Thanh Toán</a>
            </li>
            <li class="policy-item" data-target="privacy-policy">
                <a href="#privacy-policy">Chính Sách Bảo Mật</a>
            </li>
        </ul>
    </div>
    
    <div class="about-content">
        <!-- Về Chúng Tôi -->
        <div id="about-us" class="policy-section active">
            <div class="policy-header">
                <h1 class="policy-title">VỀ CHÚNG TÔI</h1>
            </div>
            
            <div class="policy-body">
                <p><strong>Toobeauty</strong> là nền tảng thương mại điện tử chuyên cung cấp các sản phẩm chăm sóc da, mỹ phẩm và làm đẹp chính hãng từ các thương hiệu uy tín trong và ngoài nước. Với sứ mệnh lan tỏa vẻ đẹp tự nhiên và giúp mỗi người tự tin hơn trong cuộc sống, Too Beauty luôn đặt chất lượng sản phẩm và trải nghiệm khách hàng lên hàng đầu.</p>
                
                <p>Tại Too Beauty, bạn có thể dễ dàng tìm thấy hàng ngàn sản phẩm từ các thương hiệu nổi tiếng như La Roche-Posay, Paula's Choice, Some By Mi, Klairs, Laneige, Vichy, The Ordinary... từ dưỡng da, trang điểm, chăm sóc tóc, có thể đến các dòng sản phẩm dành riêng cho nam giới. Tất cả đều có nguồn gốc rõ ràng, được nhập khẩu chính hãng và kiểm định chất lượng chặt chẽ.</p>
                
                <p>Không chỉ đơn thuần là nơi mua sắm mỹ phẩm, Toobeauty còn là không gian chia sẻ kiến thức làm đẹp – nơi bạn có thể tìm thấy các bài viết chuyên sâu, mẹo chăm sóc da, xu hướng trang điểm mới nhất cùng những bí quyết làm đẹp hiệu quả và an toàn. Đội ngũ của chúng tôi luôn sẵn sàng hỗ trợ, tư vấn để bạn lựa chọn sản phẩm phù hợp nhất với làn da và phong cách sống của mình.</p>
                
                <p>Với giao diện thân thiện, quy trình đặt hàng đơn giản, phương thức thanh toán linh hoạt và dịch vụ giao hàng nhanh chóng toàn quốc, Too Beauty cam kết mang đến cho bạn một trải nghiệm mua sắm mỹ phẩm trực tuyến hiện đại, tiện lợi và đáng tin cậy.</p>
            </div>
        </div>
        
        <!-- Chính Sách Bảo Hành -->
        <div id="warranty-policy" class="policy-section">
            <div class="policy-header">
                <h1 class="policy-title">CHÍNH SÁCH BẢO HÀNH</h1>
            </div>
            
            <div class="policy-body">
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>1. Điều kiện đổi trả</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Too Beauty cam kết đổi trả sản phẩm trong các trường hợp sau:</p>
                        <ul>
                            <li>Sản phẩm bị lỗi do nhà sản xuất</li>
                            <li>Sản phẩm không đúng với mô tả trên website</li>
                            <li>Sản phẩm còn nguyên vẹn, chưa qua sử dụng</li>
                            <li>Sản phẩm còn trong thời hạn đổi trả (7 ngày kể từ ngày nhận hàng)</li>
                        </ul>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>2. Quy trình đổi trả</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Để đổi trả sản phẩm, khách hàng vui lòng thực hiện theo các bước sau:</p>
                        <ol>
                            <li>Liên hệ với bộ phận Chăm sóc Khách hàng qua hotline: <strong>+84 3050 1605</strong> hoặc email: <strong>TooBeauty@gmail.com</strong></li>
                            <li>Cung cấp thông tin đơn hàng, lý do đổi trả</li>
                            <li>Chụp ảnh sản phẩm cần đổi trả</li>
                            <li>Đóng gói sản phẩm cần đổi trả kèm theo hóa đơn mua hàng</li>
                            <li>Gửi sản phẩm về địa chỉ được hướng dẫn</li>
                        </ol>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>3. Chính sách hoàn tiền</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Sau khi nhận được sản phẩm đổi trả và xác nhận đủ điều kiện, Too Beauty sẽ tiến hành hoàn tiền cho khách hàng theo hình thức:</p>
                        <ul>
                            <li>Chuyển khoản ngân hàng: 3-5 ngày làm việc</li>
                            <li>Ví điện tử: 1-2 ngày làm việc</li>
                            <li>Hoàn tiền mặt: khi khách hàng đến trực tiếp cửa hàng</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chính Sách Vận Chuyển -->
        <div id="shipping-policy" class="policy-section">
            <div class="policy-header">
                <h1 class="policy-title">CHÍNH SÁCH VẬN CHUYỂN</h1>
            </div>
            
            <div class="policy-body">
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>1. Thời gian giao hàng</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Too Beauty cam kết giao hàng nhanh chóng trong thời gian như sau:</p>
                        <ul>
                            <li>Nội thành Hà Nội và Hồ Chí Minh: 1-2 ngày làm việc</li>
                            <li>Các tỉnh thành khác: 2-5 ngày làm việc</li>
                            <li>Khu vực miền núi, hải đảo: 5-7 ngày làm việc</li>
                        </ul>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>2. Phương thức vận chuyển</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Too Beauty hợp tác với các đơn vị vận chuyển uy tín để đảm bảo đơn hàng của bạn được giao đến tay an toàn:</p>
                        <ul>
                            <li>Giao hàng tiết kiệm</li>
                            <li>J&T Express</li>
                            <li>Viettel Post</li>
                            <li>Ninja Van</li>
                            <li>Giao hàng nhanh</li>
                        </ul>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>3. Phí vận chuyển</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Phí vận chuyển được tính dựa trên vị trí giao hàng và giá trị đơn hàng:</p>
                        <ul>
                            <li>Miễn phí vận chuyển cho đơn hàng từ 500,000đ</li>
                            <li>Nội thành Hà Nội và Hồ Chí Minh: 20,000đ</li>
                            <li>Các tỉnh thành khác: 30,000đ - 40,000đ</li>
                            <li>Khu vực miền núi, hải đảo: 50,000đ - 70,000đ</li>
                        </ul>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>4. Theo dõi đơn hàng</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Khách hàng có thể theo dõi trạng thái đơn hàng của mình thông qua:</p>
                        <ul>
                            <li>Đăng nhập vào tài khoản Too Beauty</li>
                            <li>Nhập mã vận đơn được gửi qua email/SMS vào trang theo dõi đơn hàng</li>
                            <li>Liên hệ với bộ phận Chăm sóc Khách hàng qua hotline: +84 3050 1605</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chính Sách Thanh Toán -->
        <div id="payment-policy" class="policy-section">
            <div class="policy-header">
                <h1 class="policy-title">CHÍNH SÁCH THANH TOÁN</h1>
            </div>
            
            <div class="policy-body">
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>1. Phương thức thanh toán</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Too Beauty chấp nhận các phương thức thanh toán sau:</p>
                        <ul>
                            <li>Thanh toán khi nhận hàng (COD)</li>
                            <li>Chuyển khoản ngân hàng</li>
                            <li>Thẻ tín dụng/ghi nợ (Visa, Mastercard, JCB)</li>
                            <li>Ví điện tử (Momo, ZaloPay, VNPay)</li>
                            <li>Trả góp qua thẻ tín dụng</li>
                        </ul>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>2. Quy trình xử lý thanh toán</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Quy trình xử lý thanh toán tại Too Beauty diễn ra như sau:</p>
                        <ol>
                            <li>Đặt hàng và chọn phương thức thanh toán</li>
                            <li>Nhận xác nhận đơn hàng qua email/SMS</li>
                            <li>Thực hiện thanh toán theo phương thức đã chọn</li>
                            <li>Nhận xác nhận thanh toán thành công</li>
                            <li>Đơn hàng được xử lý và giao hàng</li>
                        </ol>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>3. Bảo mật thông tin thanh toán</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Too Beauty cam kết bảo mật tuyệt đối thông tin thanh toán của khách hàng:</p>
                        <ul>
                            <li>Sử dụng công nghệ mã hóa SSL 256-bit để bảo vệ thông tin</li>
                            <li>Không lưu trữ thông tin thẻ tín dụng của khách hàng</li>
                            <li>Tuân thủ các tiêu chuẩn bảo mật PCI DSS</li>
                            <li>Hợp tác với các cổng thanh toán uy tín và an toàn</li>
                        </ul>
                        <p>Mọi giao dịch thanh toán đều được mã hóa và bảo vệ để đảm bảo an toàn tuyệt đối cho khách hàng.</p>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>4. Xử lý lỗi thanh toán</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Trong trường hợp gặp lỗi khi thanh toán, khách hàng có thể:</p>
                        <ul>
                            <li>Kiểm tra lại thông tin thẻ/tài khoản</li>
                            <li>Thử lại sau vài phút</li>
                            <li>Chọn phương thức thanh toán khác</li>
                            <li>Liên hệ với bộ phận Chăm sóc Khách hàng qua hotline: +84 3050 1605</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chính Sách Bảo Mật -->
        <div id="privacy-policy" class="policy-section">
            <div class="policy-header">
                <h1 class="policy-title">CHÍNH SÁCH BẢO MẬT</h1>
            </div>
            
            <div class="policy-body">
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>1. Thu thập thông tin cá nhân</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Too Beauty thu thập các thông tin cá nhân sau đây từ khách hàng:</p>
                        <ul>
                            <li>Họ tên</li>
                            <li>Địa chỉ email</li>
                            <li>Số điện thoại</li>
                            <li>Địa chỉ giao hàng</li>
                            <li>Thông tin thanh toán (không lưu trữ dữ liệu thẻ)</li>
                            <li>Thông tin khác: ngày sinh, giới tính, sở thích mua sắm (nếu khách hàng cung cấp)</li>
                        </ul>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>2. Mục đích sử dụng thông tin</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Too Beauty sử dụng thông tin cá nhân của khách hàng cho các mục đích sau:</p>
                        <ul>
                            <li>Xử lý đơn hàng và giao hàng</li>
                            <li>Cung cấp dịch vụ chăm sóc khách hàng</li>
                            <li>Gửi thông báo về đơn hàng và cập nhật tài khoản</li>
                            <li>Gửi thông tin về chương trình khuyến mãi, sản phẩm mới (nếu khách hàng đồng ý)</li>
                            <li>Phân tích và cải thiện dịch vụ, sản phẩm</li>
                            <li>Ngăn chặn các hoạt động gian lận</li>
                        </ul>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>3. Bảo vệ thông tin cá nhân</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Too Beauty áp dụng các biện pháp bảo vệ thông tin cá nhân của khách hàng:</p>
                        <ul>
                            <li>Sử dụng công nghệ mã hóa SSL để bảo vệ thông tin truyền tải</li>
                            <li>Giới hạn quyền truy cập vào thông tin cá nhân</li>
                            <li>Đào tạo nhân viên về bảo mật thông tin</li>
                            <li>Lưu trữ dữ liệu trong hệ thống bảo mật</li>
                            <li>Thường xuyên kiểm tra, đánh giá và cập nhật hệ thống bảo mật</li>
                        </ul>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>4. Chia sẻ thông tin với bên thứ ba</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Too Beauty có thể chia sẻ thông tin của khách hàng với các bên thứ ba sau:</p>
                        <ul>
                            <li>Đối tác vận chuyển để giao hàng</li>
                            <li>Đối tác thanh toán để xử lý giao dịch</li>
                            <li>Nhà cung cấp dịch vụ lưu trữ, phân tích dữ liệu</li>
                            <li>Cơ quan nhà nước có thẩm quyền khi có yêu cầu</li>
                        </ul>
                        <p>Too Beauty cam kết chỉ chia sẻ thông tin cần thiết và yêu cầu các bên thứ ba tuân thủ chính sách bảo mật của chúng tôi.</p>
                    </div>
                </div>
                
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h3>5. Quyền của khách hàng</h3>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="collapsible-content">
                        <p>Khách hàng có các quyền sau đối với thông tin cá nhân của mình:</p>
                        <ul>
                            <li>Truy cập và xem thông tin cá nhân</li>
                            <li>Yêu cầu chỉnh sửa, cập nhật thông tin</li>
                            <li>Yêu cầu xóa thông tin</li>
                            <li>Từ chối nhận thông tin tiếp thị</li>
                            <li>Yêu cầu giải thích về việc sử dụng thông tin</li>
                        </ul>
                        <p>Để thực hiện các quyền trên, khách hàng vui lòng liên hệ: TooBeauty@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>