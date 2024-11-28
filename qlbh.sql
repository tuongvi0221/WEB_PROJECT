-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS qlbh;

-- Sử dụng cơ sở dữ liệu vừa tạo
USE qlbh;

-- Tạo bảng categories để lưu trữ thông tin danh mục sản phẩm
CREATE TABLE IF NOT EXISTS danhmucsp (
    madm INT AUTO_INCREMENT PRIMARY KEY,   -- Khóa chính
    tendm VARCHAR(100) NOT NULL,         -- Tên danh mục
    xuatsu VARCHAR(100)                   -- Xuất xứ
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng products để lưu trữ thông tin sản phẩm
CREATE TABLE IF NOT EXISTS sanpham (
    masp INT AUTO_INCREMENT PRIMARY KEY,    -- Khóa chính
    tensp VARCHAR(255) NOT NULL,          -- Tên sản phẩm
    gia DECIMAL(10, 2) NOT NULL,          -- Giá sản phẩm
    baohanh VARCHAR(100),                  -- Thời gian bảo hành
    trongluong VARCHAR(100),               -- Trọng lượng
    chatlieu VARCHAR(100),                 -- Chất liệu
    chongnuoc VARCHAR(100),                -- Chống nước
    nangluong VARCHAR(100),                -- Nguồn năng lượng
    loaibh VARCHAR(100),                   -- Loại hàng
    kichthuoc VARCHAR(100),                -- Kích thước
    mau VARCHAR(50),                       -- Màu sắc
    danhcho VARCHAR(100),                  -- Dành cho
    phukien VARCHAR(255),                  -- Phụ kiện
    khuyenmai VARCHAR(255),                -- Khuyến mãi
    tinhtrang VARCHAR(50),                 -- Tình trạng sản phẩm
    madm INT,                              -- ID danh mục (khóa ngoại)
    anhchinh VARCHAR(255),                 -- Ảnh chính sản phẩm
    luotmua INT DEFAULT 0,                -- Số lượt mua
    luotxem INT DEFAULT 0,                 -- Số lượt xem
    ngaytao TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày tạo
    ngay_nhap TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày nhập sản phẩm
    FOREIGN KEY (madm) REFERENCES danhmucsp(madm) ON DELETE CASCADE -- Liên kết với bảng danhmuc
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng giao dịch để lưu trữ thông tin giao dịch
CREATE TABLE IF NOT EXISTS giaodich (
    magd INT AUTO_INCREMENT PRIMARY KEY,        -- Khóa chính
    user_id INT,                               -- ID của người dùng
    user_name VARCHAR(255) NOT NULL,            -- Tên người dùng
    user_dst VARCHAR(100),                       -- Quận
    user_addr VARCHAR(255),                      -- Địa chỉ
    user_phone VARCHAR(15),                      -- Số điện thoại
    tongtien DECIMAL(10, 2) NOT NULL,           -- Tổng tiền
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    -- Ngày giao dịch
    tinhtrang TINYINT DEFAULT 0                  -- Tình trạng giao dịch (0: chưa giao, 1: đã giao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng chitietgiaodich để lưu trữ thông tin chi tiết giao dịch
CREATE TABLE IF NOT EXISTS chitietgiaodich (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Khóa chính
    magd INT NOT NULL,                           -- ID giao dịch (khóa ngoại liên kết với bảng giaodich)
    masp INT NOT NULL,                           -- Mã sản phẩm
    soluong INT NOT NULL,                        -- Số lượng sản phẩm
    FOREIGN KEY (magd) REFERENCES giaodich(magd) ON DELETE CASCADE,
    FOREIGN KEY (masp) REFERENCES sanpham(masp) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng thanhvien để lưu trữ thông tin thành viên
CREATE TABLE IF NOT EXISTS thanhvien (
    id INT AUTO_INCREMENT PRIMARY KEY,            -- Khóa chính
    ten VARCHAR(255) NOT NULL,                    -- Tên thành viên
    tentaikhoan VARCHAR(100) NOT NULL UNIQUE,     -- Tên tài khoản (độc nhất)
    matkhau VARCHAR(255) NOT NULL,                 -- Mật khẩu
    diachi TEXT,                                   -- Địa chỉ (tùy chọn)
    sodt VARCHAR(15),                              -- Số điện thoại (tùy chọn)
    email VARCHAR(100),                            -- Email (tùy chọn)
    ngaytao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,   -- Ngày tạo tài khoản
    quyen INT DEFAULT 0                           -- Quyền hạn (0: người dùng bình thường, 1: admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng giohang để lưu trữ thông tin giỏ hàng
CREATE TABLE IF NOT EXISTS giohang (
    id INT AUTO_INCREMENT PRIMARY KEY,            -- Khóa chính
    user_id INT NOT NULL,                          -- ID của người dùng
    masp INT NOT NULL,                             -- Mã sản phẩm
    soluong INT NOT NULL DEFAULT 1,                -- Số lượng sản phẩm
    FOREIGN KEY (user_id) REFERENCES thanhvien(id) ON DELETE CASCADE,  -- Ràng buộc khóa ngoại
    FOREIGN KEY (masp) REFERENCES sanpham(masp) ON DELETE CASCADE          -- Ràng buộc khóa ngoại
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng sanphamyeuthich để lưu trữ thông tin sản phẩm yêu thích
CREATE TABLE IF NOT EXISTS sanphamyeuthich (
    id INT AUTO_INCREMENT PRIMARY KEY,            -- Khóa chính
    user_id INT NOT NULL,                          -- ID của người dùng
    masp INT NOT NULL,                             -- Mã sản phẩm
    FOREIGN KEY (user_id) REFERENCES thanhvien(id) ON DELETE CASCADE,  -- Ràng buộc khóa ngoại
    FOREIGN KEY (masp) REFERENCES sanpham(masp) ON DELETE CASCADE          -- Ràng buộc khóa ngoại
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO danhmucsp (tendm, xuatsu) VALUES
('Áo Khoác', 'Việt Nam'),
('Áo Thun', 'Việt Nam'),
('Áo Sơ Mi', 'Việt Nam'),
('Áo Hoodie', 'Việt Nam'),
('Quần', 'Việt Nam'),
('Đầm', 'Việt Nam'),
('Phụ Kiện', 'Việt Nam')
ON DUPLICATE KEY UPDATE madm = madm; -- Không thêm nếu đã có

INSERT INTO sanpham (tensp, gia, baohanh, trongluong, chatlieu, chongnuoc, nangluong, loaibh, kichthuoc, mau, danhcho, phukien, khuyenmai, tinhtrang, madm, anhchinh) VALUES
-- Áo Khoác
('Áo Khoác Bomber', 750000, '6 tháng', '450g', 'Nylon', 'Có', 'N/A', 'Nam', 'M', 'Đen', 'Nam', 'Nón', 'Giảm 10%', 'Mới', 1, 'images/ao_khoac/ao_khoac_bomber.jpg'),
('Áo Khoác Dạ Nữ', 900000, '1 năm', '800g', 'Dạ', 'Không', 'N/A', 'Nữ', 'M', 'Đen', 'Nữ', 'Nón', 'Giảm 20%', 'Mới', 1, 'images/ao_khoac/ao_khoac_da_nu.jpg'),
('Áo Khoác Denim', 600000, '6 tháng', '300g', 'Denim', 'Không', 'N/A', 'Nam', 'M', 'Xanh dương', 'Nam', 'Nón', 'Giảm 10%', 'Mới', 1, 'images/ao_khoac/ao_khoac_denim.jpg'),
('Áo Khoác Gió', 800000, '1 năm', '450g', 'Polyester', 'Có', 'N/A', 'Nam', 'L', 'Xanh dương', 'Nam', 'Nón', 'Giảm 5%', 'Mới', 1, 'images/ao_khoac/ao_khoac_gio.jpg'),
('Áo Khoác Mùa Đông', 650000, '6 tháng', '400g', 'Polyester', 'Có', 'N/A', 'Nam', 'L', 'Xanh dương', 'Nam', 'Nón', 'Giảm 10%', 'Mới', 1, 'images/ao_khoac/ao_khoac_mua_dong.jpg'),
('Áo Khoác Phao', 700000, '1 năm', '500g', 'Polyester', 'Có', 'N/A', 'Nam', 'L', 'Xám', 'Nam', 'Nón', 'Giảm 5%', 'Mới', 1, 'images/ao_khoac/ao_khoac_phao.jpeg'),

-- Áo Thun
('Áo Polo Thể Thao', 350000, '6 tháng', '250g', 'Cotton', 'Không', 'N/A', 'Nữ', 'M', 'Vàng', 'Nữ', 'Nón', 'Giảm 5%', 'Mới', 2, 'images/ao_thun/ao_polo_the_thao.jpg'),
('Áo Thun Cổ Tròn', 250000, '1 năm', '200g', 'Cotton', 'Không', 'N/A', 'Unisex', 'L', 'Vàng', 'Nam, Nữ', 'Nón', 'Giảm 5%', 'Mới', 2, 'images/ao_thun/ao_thun_co_tron.jpg'),
('Áo Thun Fit', 500000, '1 năm', '200g', 'Cotton', 'Không', 'N/A', 'Nam', 'L', 'Xám', 'Nam', 'Nón', 'Giảm 5%', 'Mới', 2, 'images/ao_thun/ao_thun_fit.jpeg'),
('Áo Thun Nữ Cổ V', 300000, '1 năm', '150g', 'Cotton', 'Không', 'N/A', 'Nữ', 'S', 'Đen', 'Nữ', 'Nón', 'Giảm 5%', 'Mới', 2, 'images/ao_thun/ao_thun_nu_co_v.jpg'),
('Áo Thun Dài Tay', 400000, '1 năm', '250g', 'Cotton', 'Không', 'N/A', 'Nam', 'M', 'Xanh rêu', 'Nam', 'Nón', 'Giảm 5%', 'Mới', 2, 'images/ao_thun/ao_thun_tay_dai.jpg'),
('Áo Thun Unisex', 350000, '6 tháng', '200g', 'Cotton', 'Không', 'N/A', 'Nữ', 'L', 'Vàng', 'Nữ', 'Nón', 'Giảm 10%', 'Mới', 2, 'images/ao_thun/ao_tshirt_nu.jpg'),

-- Áo Sơ Mi
('Áo Sơ Mi Caro', 400000, '6 tháng', '300g', 'Cotton', 'Không', 'N/A', 'Nam', 'L', 'Nhiều màu', 'Nam', 'Nón', 'Giảm 10%', 'Mới', 3, 'images/ao_so_mi/ao_so_mi_caro.jpg'),
('Áo Sơ Mi Cọc Tay', 500000, '6 tháng', '300g', 'Cotton', 'Không', 'N/A', 'Nam', 'L', 'Đen', 'Nam', 'Nón', 'Giảm 10%', 'Mới', 3, 'images/ao_so_mi/ao_so_mi_coc_tay.jpeg'),
('Áo Sơ Mi Dài Tay', 550000, '6 tháng', '300g', 'Cotton', 'Không', 'N/A', 'Nam', 'L', 'Trắng', 'Nam', 'Nón', 'Giảm 10%', 'Mới', 3, 'images/ao_so_mi/ao_so_mi_dai_tay.jpeg'),
('Áo Sơ Mi Kẻ Sọc', 400000, '6 tháng', '300g', 'Cotton', 'Không', 'N/A', 'Nam', 'L', 'Xanh dương', 'Nam', 'Không nón', 'Giảm 10%', 'Mới', 3, 'images/ao_so_mi/ao_so_mi_ke_soc.jpg'),
('Áo Sơ Mi Nữ', 350000, '1 năm', '200g', 'Cotton', 'Không', 'N/A', 'Nữ', 'M', 'Trắng', 'Nữ', 'Nón', 'Giảm 10%', 'Mới', 3, 'images/ao_so_mi/ao_so_mi_nu.jpg'),
('Áo Sơ Mi Trắng', 450000, '1 năm', '250g', 'Cotton', 'Không', 'N/A', 'Nữ', 'M', 'Trắng', 'Nữ', 'Nón', 'Giảm 20%', 'Mới', 3, 'images/ao_so_mi/ao_so_mi_trang.jpg'),

-- Áo Hoodie
('Áo Hoodie Da Lộn', 450000, '6 tháng', '250g', 'Da Lộn', 'Không', 'N/A', 'Nam', 'M', 'Nâu', 'Nam', 'Nón', 'Giảm 5%', 'Mới', 4, 'images/ao_hoodie/ao_hoodie_da_lon.jpeg'),
('Áo Hoodie Jack Lane Mix', 600000, '1 năm', '350g', 'Nỉ', 'Không', 'N/A', 'Nam', 'M', 'Trắng đen', 'Nam', 'Nón', 'Giảm 5%', 'Mới', 4, 'images/ao_hoodie/ao_hoodie_jack_lane_mix.jpg'),
('Áo Hoodie Just Odin', 500000, '1 năm', '350g', 'Nỉ', 'Không', 'N/A', 'Nam', 'M', 'Trắng', 'Nam', 'Nón', 'Giảm 5%', 'Mới', 4, 'images/ao_hoodie/ao_hoodie_just_odin.jpg'),
('Áo Hoodie Lông Cừu', 500000, '1 năm', '500g', 'Lông cừu', 'Không', 'N/A', 'Nữ', 'M', 'Nâu', 'Nữ', 'Nón', 'Giảm 5%', 'Mới', 4, 'images/ao_hoodie/ao_hoodie_long_cuu.jpeg'),
('Áo Hoodie Nỉ', 700000, '1 năm', '500g', 'Nỉ', 'Không', 'N/A', 'Nam', 'L', 'Trắng', 'Nam', 'Nón', 'Giảm 15%', 'Mới', 4, 'images/ao_khoac/ao_hoodie_ni.jpg'),
('Áo Hoodie OD Bestle', 650000, '6 tháng', '350g', 'Nỉ', 'Không', 'N/A', 'Nam', 'M', 'Đen', 'Nam', 'Nón', 'Giảm 5%', 'Mới', 4, 'images/ao_hoodie/ao_hoodie_od_bestle.jpg'),

-- Quần
('Quần Baggy Nữ', 250000, '1 năm', '150g', 'Polyester', 'Không', 'N/A', 'Nữ', 'M', 'Xanh dương', 'Nữ', 'Nón', 'Giảm 5%', 'Mới', 5, 'images/quan/quan_baggy_nu.jpg'),
('Quần Jeans Rách', 500000, '1 năm', '400g', 'Jean', 'Không', 'N/A', 'Nữ', 'L', 'Xanh dương', 'Nữ', 'Thắt lưng', 'Giảm 15%', 'Mới', 5, 'images/quan/quan_jeans_rach.jpg'),
('Quần Jeans Skinny', 550000, '1 năm', '350g', 'Jean', 'Không', 'N/A', 'Nam', 'L', 'Xanh nhạt', 'Nam', 'Thắt lưng', 'Giảm 15%', 'Mới', 5, 'images/quan/quan_jeans_skinny.jpg'),
('Quần Jogger Thể Thao', 400000, '1 năm', '350g', 'Cotton', 'Không', 'N/A', 'Nam', 'L', 'Trắng, Đen', 'Nam', 'Thắt lưng', 'Giảm 15%', 'Mới', 5, 'images/quan/quan_jogger_the_thao.jpg'),
('Quần Kaki Nữ', 500000, '1 năm', '300g', 'Kaki', 'Không', 'N/A', 'Nữ', 'L', 'Trắng', 'Nữ', 'Thắt lưng', 'Giảm 15%', 'Mới', 5, 'images/quan/quan_kaki_nu.jpg'),
('Quần Legging Nữ ', 250000, '1 năm', '150g', 'Polyester', 'Không', 'N/A', 'Nữ', 'M', 'Xám', 'Nữ', 'Nón', 'Giảm 5%', 'Mới', 5, 'images/quan/quan_legging_nu.jpg'),
('Quần Short Thể Thao', 400000, '1 năm', '350g', 'Cotton', 'Không', 'N/A', 'Nam', 'M', 'Xanh Dương', 'Nam', 'Thắt lưng', 'Giảm 15%', 'Mới', 5, 'images/quan/quan_short_the_thao.jpg'),
('Quần Tây Nam', 700000, '1 năm', '350g', 'Kaki', 'Không', 'N/A', 'Nam', 'L', 'Đen', 'Nam', 'Thắt lưng', 'Giảm 15%', 'Mới', 5, 'images/quan/quan_tay_nam.jpg'),

-- Đầm
('Đầm Dự Tiệc', 900000, '1 năm', '400g', 'Lụa', 'Không', 'N/A', 'Nữ', 'L', 'Be', 'Nữ', 'Nón', 'Giảm 10%', 'Mới', 6, 'images/dam/dam_da_hoi.jpg'),
('Đầm Maxi Hoa', 800000, '1 năm', '200g', 'Lụa', 'Không', 'N/A', 'Nữ', 'M', 'Be', 'Nữ', 'Nón', 'Giảm 20%', 'Mới', 6, 'images/dam/dam_maxi_hoa.jpg'),
('Đầm Maxi Trễ Vai', 600000, '6 tháng', '350g', 'Cotton', 'Không', 'N/A', 'Nữ', 'M', 'Nhiều màu', 'Nữ', 'Nón', 'Giảm 15%', 'Mới', 6, 'images/dam/dam_maxi_tre_vai.jpg'),
('Đầm Suông Trẻ Trung', 600000, '6 tháng', '350g', 'Cotton', 'Không', 'N/A', 'Nữ', 'M', 'Xanh lá nhạt', 'Nữ', 'Nón', 'Giảm 15%', 'Mới', 6, 'images/dam/dam_suong_tre_trung.jpg'),
('Đầm Xòe Công Sở', 900000, '1 năm', '200g', 'Lụa', 'Không', 'N/A', 'Nữ', 'M', 'Hồng', 'Nữ', 'Nón', 'Giảm 20%', 'Mới', 6, 'images/dam/dam_xoe_cong_so.webp'),
('Đầm Xòe Vinatge', 800000, '1 năm', '200g', 'Lụa', 'Không', 'N/A', 'Nữ', 'M', 'Nhiều màu', 'Nữ', 'Nón', 'Giảm 20%', 'Mới', 6, 'images/dam/dam_xoe_vintage.jpg'),

-- Phụ Kiện
('Dây Chuyền Pandora', 800000, '6 tháng', '200g', 'Bạc', 'Không', 'N/A', 'Nữ', 'N/A', 'Trắng', 'Nữ', 'N/A', 'Giảm 10%', 'Mới', 7, 'images/phu_kien/day_chuyen_pandora.webp'),
('Kính Mát Thời Trang', 300000, '1 năm', '50g', 'Nhựa', 'Không', 'N/A', 'Unisex', 'N/A', 'Đen', 'Nam, Nữ', 'N/A', 'Giảm 15%', 'Mới', 7, 'images/phu_kien/kinh_mat_thoi_trang.jpg'),
('Lắc Tay Bạc', 700000, '6 tháng', '150g', 'Bạc', 'Không', 'N/A', 'Nữ', 'N/A', 'Trắng', 'Nữ', 'N/A', 'Giảm 10%', 'Mới', 7, 'images/phu_kien/lac_tay_bac.jpg'),
('Nón Lưỡi Trai', 200000, '6 tháng', '100g', 'Cotton', 'Không', 'N/A', 'Unisex', 'N/A', 'Đen', 'Nam, Nữ', 'N/A', 'Giảm 10%', 'Mới', 7, 'images/phu_kien/non_luoi_trai.jpg'),
('Thắt Lưng Da', 250000, '1 năm', '150g', 'Da', 'Không', 'N/A', 'Unisex', 'N/A', 'Đen', 'Nam, Nữ', 'N/A', 'Giảm 5%', 'Mới', 7, 'images/phu_kien/that_lung_da.jpg'),
('Vòng Tay Thời Trang', 150000, '6 tháng', '50g', 'Kim loại', 'Không', 'N/A', 'Nữ', 'N/A', 'Vàng', 'Nữ', 'N/A', 'Giảm 10%', 'Mới', 7, 'images/phu_kien/vong_tay_thoi_trang.png')
ON DUPLICATE KEY UPDATE masp=masp; -- Không thêm nếu đã có

INSERT INTO giaodich (user_id, user_name, user_dst, user_addr, user_phone, tongtien, tinhtrang) VALUES
(1, 'Nguyễn Văn A', 'Hà Nội', '123 Đường ABC', '0901234567', 25000000.00, 1),  
(2, 'Trần Thị B', 'Hồ Chí Minh', '456 Đường DEF', '0912345678', 1500000.00, 1),  
(3, 'Lê Văn C', 'Đà Nẵng', '789 Đường GHI', '0923456789', 10000000.00, 1),      
(1, 'Nguyễn Văn A', 'Hà Nội', '123 Đường ABC', '0901234567', 3000000.00, 0),   
(2, 'Trần Thị B', 'Hồ Chí Minh', '456 Đường DEF', '0912345678', 4000000.00, 0),  
(3, 'Lê Văn C', 'Đà Nẵng', '789 Đường GHI', '0923456789', 3500000.00, 1)        
ON DUPLICATE KEY UPDATE magd=magd;

INSERT INTO thanhvien (ten, tentaikhoan, matkhau, diachi, sodt, email, quyen) VALUES
('Nguyễn Văn A', 'nguyenvana', 'password123', '123 Đường ABC, Hà Nội', '0901234567', 'a@example.com', 0),
('Trần Thị B', 'tranthib', 'password456', '456 Đường DEF, Hồ Chí Minh', '0912345678', 'b@example.com', 0),
('Lê Văn C', 'levanc', 'password789', '789 Đường GHI, Đà Nẵng', '0923456789', 'c@example.com', 1),
('Phạm Minh D', 'phaminhd', 'password101', '321 Đường JKL, Hà Nội', '0934567890', 'd@example.com', 0),
('Nguyễn Thị E', 'nguyenthie', 'password202', '654 Đường MNO, Hồ Chí Minh', '0945678901', 'e@example.com', 1),
('Trần Văn F', 'tranvanf', 'password303', '987 Đường PQR, Đà Nẵng', '0956789012', 'f@example.com', 0)
ON DUPLICATE KEY UPDATE id=id; -- Không thêm nếu đã có

INSERT INTO giohang (user_id, masp, soluong) VALUES
(1, 1, 2),  
(1, 3, 1),  
(2, 4, 1),  
(3, 5, 3),  
(2, 1, 1),  
(3, 6, 1)  
ON DUPLICATE KEY UPDATE id=id; -- Không thêm nếu đã có

INSERT INTO chitietgiaodich (magd, masp, soluong) VALUES
(1, 1, 1), 
(1, 2, 1),  
(2, 3, 1),  
(3, 4, 1), 
(3, 5, 1),  
(4, 1, 1),  
(5, 2, 1)   
ON DUPLICATE KEY UPDATE id=id; -- Không thêm nếu đã có

INSERT INTO sanphamyeuthich (user_id, masp) VALUES
(1, 1),  
(1, 2), 
(2, 3), 
(3, 1),  
(3, 4),  
(2, 5)  
ON DUPLICATE KEY UPDATE id=id; -- Không thêm nếu đã có
