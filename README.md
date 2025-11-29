# Website Quản Lý Tour

Dự án PHP cơ bản mô phỏng cấu trúc MVC tối giản phục vụ học tập.

## Tổng quan cấu trúc

- `index.php`: điểm vào, định tuyến request bằng `match`.
- `config/config.php`: cấu hình chung và thông tin kết nối DB.
- `src/helpers/`: các hàm tiện ích (`render`, `asset`...).
- `src/models/`: các lớp đại diện dữ liệu ví dụ (`User`).
- `src/controllers/`: nghiệp vụ mẫu (`HomeController` với nhiều action).
- `views/`: giao diện tương ứng mỗi action (trang chủ, giỏ hàng, thanh toán...).
- `public/`: tài nguyên tĩnh (css/js/images).
- `.htaccess`: Dùng Rewrite URL Chuyển từ dạng "index.php?act=home" thành "/home"

## Thiết lập và chạy

1. Tạo database MySQL (ví dụ `website_ql_tour`) và cập nhật thông tin trong `config/config.php`.
2. Tạo bảng `users` bằng tập lệnh SQL trong `database/schema.sql` (nếu chưa có):

```sql
-- Từ thư mục dự án, dùng MySQL client hoặc phpMyAdmin để chạy
CREATE TABLE IF NOT EXISTS `users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL UNIQUE,
	`password` varchar(255) NOT NULL,
	`role` varchar(50) NOT NULL DEFAULT 'huong_dan_vien',
	`status` tinyint(1) NOT NULL DEFAULT 1,
	`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

3. Chạy ứng dụng thông qua Apache/nginx (Laragon) và mở:
	 - `http://localhost/website_quan_ly_tour/` để xem trang chào mừng.
	 - `http://localhost/website_quan_ly_tour/register` để tạo tài khoản mới.
	 - `http://localhost/website_quan_ly_tour/login` để đăng nhập.

	## Kiểm tra chức năng đăng ký/đăng nhập (curl)

	Để test nhanh bằng curl (dành cho developer):

	Đăng ký:
	```bash
	curl -X POST \
		-d "name=Test User" \
		-d "email=test@example.com" \
		-d "password=123456" \
		-d "password_confirm=123456" \
		-c cookies.txt \
		http://localhost/website_quan_ly_tour/check-register
	```

	Đăng nhập:
	```bash
	curl -X POST \
		-d "email=test@example.com" \
		-d "password=123456" \
		-c cookies.txt \
		http://localhost/website_quan_ly_tour/check-login
	```


## Lưu ý bảo mật
- Đây là ví dụ mẫu cho mục đích học tập: chưa có CSRF token, rate-limiting, hoặc xác thực email.
- Nên bật HTTPS trong môi trường thực để bảo vệ thông tin đăng nhập.
