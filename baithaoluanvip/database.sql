-- Tạo cơ sở dữ liệu
CREATE DATABASE TooBeauty;
GO

USE TooBeauty;
GO

-- Tạo bảng Categories (Danh mục)
CREATE TABLE Categories (
    id INT PRIMARY KEY IDENTITY(1,1),
    name NVARCHAR(100) NOT NULL,
    slug NVARCHAR(100) NOT NULL,
    description NVARCHAR(500),
    image_url NVARCHAR(255),
    parent_id INT,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (parent_id) REFERENCES Categories(id)
);
GO

-- Tạo bảng Brands (Thương hiệu)
CREATE TABLE Brands (
    id INT PRIMARY KEY IDENTITY(1,1),
    name NVARCHAR(100) NOT NULL,
    slug NVARCHAR(100) NOT NULL,
    description NVARCHAR(500),
    logo_url NVARCHAR(255),
    website NVARCHAR(255),
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE()
);
GO

-- Tạo bảng Products (Sản phẩm)
CREATE TABLE Products (
    id INT PRIMARY KEY IDENTITY(1,1),
    name NVARCHAR(255) NOT NULL,
    slug NVARCHAR(255) NOT NULL,
    sku VARCHAR(50),
    price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2),
    quantity INT DEFAULT 0,
    short_description NVARCHAR(500),
    description NVARCHAR(MAX),
    specifications NVARCHAR(MAX),
    image VARCHAR(255),
    brand_id INT,
    category_id INT,
    is_featured BIT DEFAULT 0,
    is_new BIT DEFAULT 0,
    is_sale BIT DEFAULT 0,
    is_top BIT DEFAULT 0,
    rating DECIMAL(3, 2) DEFAULT 0,
    reviews_count INT DEFAULT 0,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (brand_id) REFERENCES Brands(id),
    FOREIGN KEY (category_id) REFERENCES Categories(id)
);
GO

-- Tạo bảng ProductImages (Hình ảnh sản phẩm)
CREATE TABLE ProductImages (
    id INT PRIMARY KEY IDENTITY(1,1),
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);
GO

-- Tạo bảng Users (Người dùng)
CREATE TABLE Users (
    id INT PRIMARY KEY IDENTITY(1,1),
    first_name NVARCHAR(50) NOT NULL,
    last_name NVARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address NVARCHAR(255),
    city NVARCHAR(100),
    state NVARCHAR(100),
    postal_code VARCHAR(20),
    country NVARCHAR(100),
    is_admin BIT DEFAULT 0,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    last_login DATETIME
);
GO

-- Tạo bảng Orders (Đơn hàng)
CREATE TABLE Orders (
    id INT PRIMARY KEY IDENTITY(1,1),
    user_id INT,
    order_number VARCHAR(50) NOT NULL,
    payment_method VARCHAR(50),
    shipping_method VARCHAR(50),
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping_cost DECIMAL(10, 2) NOT NULL,
    tax DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    discount DECIMAL(10, 2) DEFAULT 0,
    status VARCHAR(50) DEFAULT 'pending',
    shipping_first_name NVARCHAR(50),
    shipping_last_name NVARCHAR(50),
    shipping_email VARCHAR(100),
    shipping_phone VARCHAR(20),
    shipping_address NVARCHAR(255),
    shipping_city NVARCHAR(100),
    shipping_state NVARCHAR(100),
    shipping_postal_code VARCHAR(20),
    shipping_country NVARCHAR(100),
    notes NVARCHAR(500),
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
GO

-- Tạo bảng OrderItems (Chi tiết đơn hàng)
CREATE TABLE OrderItems (
    id INT PRIMARY KEY IDENTITY(1,1),
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (order_id) REFERENCES Orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(id)
);
GO

-- Tạo bảng Reviews (Đánh giá)
CREATE TABLE Reviews (
    id INT PRIMARY KEY IDENTITY(1,1),
    product_id INT NOT NULL,
    user_id INT,
    name NVARCHAR(100),
    email VARCHAR(100),
    rating INT NOT NULL,
    comment NVARCHAR(MAX),
    status VARCHAR(20) DEFAULT 'pending',
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
GO

-- Tạo bảng Cart (Giỏ hàng)
CREATE TABLE Cart (
    id INT PRIMARY KEY IDENTITY(1,1),
    user_id INT,
    session_id VARCHAR(100),
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (product_id) REFERENCES Products(id)
);
GO

-- Tạo bảng Wishlist (Danh sách yêu thích)
CREATE TABLE Wishlist (
    id INT PRIMARY KEY IDENTITY(1,1),
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);
GO

-- Chèn dữ liệu mẫu cho Categories
INSERT INTO Categories (name, slug, description) VALUES
(N'Cleansing Balms', 'cleansing-balms', N'Sản phẩm tẩy trang dạng sáp'),
(N'Oil Cleansers', 'oil-cleansers', N'Sản phẩm tẩy trang dạng dầu'),
(N'Water Cleansers', 'water-cleansers', N'Sản phẩm tẩy trang dạng nước'),
(N'Masks', 'masks', N'Mặt nạ dưỡng da'),
(N'Sunscreen', 'sunscreen', N'Kem chống nắng');
GO

-- Chèn dữ liệu mẫu cho Brands
INSERT INTO Brands (name, slug, website) VALUES
('Innisfree', 'innisfree', 'https://www.innisfree.com'),
('Cosrx', 'cosrx', 'https://www.cosrx.com'),
('Some By Mi', 'some-by-mi', 'https://www.somebymi.com'),
('Mizon', 'mizon', 'https://www.mizon.co.kr'),
('e.l.f', 'elf', 'https://www.elfcosmetics.com');
GO

-- Chèn dữ liệu mẫu cho Products
INSERT INTO Products (name, slug, price, sale_price, short_description, image, brand_id, category_id, is_featured, is_new, is_sale, is_top, rating, reviews_count) VALUES
(N'Dewy Glow Jelly Cream', 'dewy-glow-jelly-cream', 32, NULL, N'With Jeju Cherry Blossom', 'product1.jpg', 1, 1, 1, 1, 0, 0, 4.5, 8),
(N'Fermented Soybean Bio Cellulose Mask', 'fermented-soybean-bio-cellulose-mask', 18, NULL, N'6-in-1 facial mask made with advanced cellulose', 'product2.jpg', 2, 4, 0, 1, 0, 0, 4.8, 12),
(N'Soft Finish All-Around Safe Block Sun Milk SPF50+/PA+++', 'soft-finish-all-around-safe-block-sun-milk', 32, NULL, N'SPF50+/PA+++', 'product3.jpg', 1, 5, 1, 0, 0, 1, 4.9, 15),
(N'Matte Priming UV Shield Sunscreen', 'matte-priming-uv-shield-sunscreen', 32, NULL, N'Broad Spectrum SPF 37', 'product4.jpg', 3, 5, 0, 0, 0, 0, 4.7, 9),
(N'Clarifying Emulsion', 'clarifying-emulsion', 25, NULL, N'with BHA Seed Oil', 'product5.jpg', 2, 3, 0, 0, 1, 0, 4.6, 7),
(N'Silk-Feel Cotton Puff', 'silk-feel-cotton-puff', 12, NULL, N'Soft and gentle cotton pads that will not irritate the skin', 'product6.jpg', 4, 3, 0, 0, 0, 0, 4.8, 5),
(N'Pore Clearing Clay Mask 2X', 'pore-clearing-clay-mask-2x', 32, NULL, N'With Super Volcanic Clusters', 'product7.jpg', 1, 4, 0, 0, 0, 0, 4.9, 10),
(N'All-Around Safe Block Essence Sun SPF45+', 'all-around-safe-block-essence-sun', 35, NULL, N'SPF45+/PA+++', 'product8.jpg', 1, 5, 0, 0, 0, 0, 4.7, 8),
(N'Super Aqua Snail Cream', 'super-aqua-snail-cream', 25, NULL, N'Skin Refinerment Gel Type Cream', 'product9.jpg', 5, 1, 0, 0, 0, 0, 4.5, 9);
GO

-- Thêm người dùng mẫu
INSERT INTO Users (first_name, last_name, email, password, phone, is_admin) VALUES
(N'Admin', N'User', 'admin@toobeauty.com', '$2y$10$HXw9Bx.j.L6K2fX6Ilt1ueqbG4sOLMoWl5DQ5UXel6ZZYnGj/jmsW', '0987654321', 1), -- password: admin123
(N'Test', N'User', 'test@example.com', '$2y$10$vPtZH0bSPBlzUVrOx1q0S.4.6yfQFrNcfmbLZ/MhONbpQLuQA5Ufa', '0123456789', 0); -- password: test123
GO

-- Thêm các trường cần thiết vào bảng Orders để hỗ trợ thanh toán
ALTER TABLE Orders
ADD 
    payment_status VARCHAR(50) DEFAULT 'pending',
    transaction_id VARCHAR(100),
    payment_date DATETIME,
    payment_method_details NVARCHAR(255);

-- Tạo bảng lưu lịch sử thanh toán
CREATE TABLE PaymentHistory (
    id INT PRIMARY KEY IDENTITY(1,1),
    order_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(100),
    status VARCHAR(50) DEFAULT 'pending',
    payment_date DATETIME DEFAULT GETDATE(),
    additional_info NVARCHAR(MAX),
    FOREIGN KEY (order_id) REFERENCES Orders(id) ON DELETE CASCADE
);

-- Tạo bảng theo dõi trạng thái đơn hàng
CREATE TABLE OrderStatus (
    id INT PRIMARY KEY IDENTITY(1,1),
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    notes NVARCHAR(500),
    created_at DATETIME DEFAULT GETDATE(),
    created_by INT,
    FOREIGN KEY (order_id) REFERENCES Orders(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES Users(id)
);

-- Tạo bảng thông báo cho người dùng
CREATE TABLE Notifications (
    id INT PRIMARY KEY IDENTITY(1,1),
    user_id INT NOT NULL,
    title NVARCHAR(100) NOT NULL,
    message NVARCHAR(500) NOT NULL,
    is_read BIT DEFAULT 0,
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Tạo các trigger để cập nhật trạng thái đơn hàng
CREATE TRIGGER trg_OrderStatusInsert
ON Orders
AFTER INSERT
AS
BEGIN
    INSERT INTO OrderStatus (order_id, status, notes)
    SELECT id, 'pending', 'Đơn hàng mới được tạo'
    FROM inserted;
END;

-- Tạo các stored procedure để xử lý thanh toán
CREATE PROCEDURE sp_ProcessPayment
    @OrderId INT,
    @PaymentMethod VARCHAR(50),
    @TransactionId VARCHAR(100),
    @Amount DECIMAL(10,2)
AS
BEGIN
    BEGIN TRY
        BEGIN TRANSACTION;
        
        -- Cập nhật trạng thái thanh toán
        UPDATE Orders
        SET payment_status = 'completed',
            payment_date = GETDATE(),
            transaction_id = @TransactionId,
            status = 'processing'
        WHERE id = @OrderId;
        
        -- Thêm vào lịch sử thanh toán
        INSERT INTO PaymentHistory (order_id, amount, payment_method, transaction_id, status)
        VALUES (@OrderId, @Amount, @PaymentMethod, @TransactionId, 'completed');
        
        -- Thêm vào trạng thái đơn hàng
        INSERT INTO OrderStatus (order_id, status, notes)
        VALUES (@OrderId, 'processing', 'Thanh toán đã hoàn tất, đơn hàng đang được xử lý');
        
        -- Thông báo cho người dùng
        INSERT INTO Notifications (user_id, title, message)
        SELECT user_id, 'Thanh toán thành công', 'Đơn hàng của bạn đã được thanh toán và đang được xử lý'
        FROM Orders
        WHERE id = @OrderId AND user_id IS NOT NULL;
        
        COMMIT;
    END TRY
    BEGIN CATCH
        ROLLBACK;
        THROW;
    END CATCH
END;