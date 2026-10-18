# Portfolio — PHP + MySQL + phpMyAdmin

## Đáp ứng yêu cầu
- Backend PHP 8.1+, PDO MySQL; Docker dùng PHP 8.3 và MySQL 8.4. Không dùng SQL Server/MSSQL.
- config.php chỉ dùng getenv() để đọc DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD. Không có giá trị mặc định hardcode. Thiếu hoặc rỗng biến bắt buộc: kết nối dừng và báo lỗi cấu hình chung, không lộ mật khẩu.
- `.env.example` là mẫu cấu hình triển khai, không phải giá trị nhúng trong PHP. Docker Compose đọc `.env` và truyền biến vào container. PHP không tự đọc file .env.
- SQL có cấu trúc và dữ liệu mẫu: hồ sơ, 3 dự án (1 bản nháp), 4 kỹ năng, 1 tin nhắn. Mục mẫu được ghi rõ để thay bằng nội dung thật.
- Admin không có mật khẩu mặc định; tạo bằng CLI và lưu password_hash.

## Chạy Docker Desktop / PowerShell
Mở Docker Desktop. Giải nén, mở PowerShell trong thư mục portfolio:
```powershell
Copy-Item .env.example .env
notepad .env
```
Giữ DB_HOST=db (tên service MySQL), DB_PORT=3306; đặt DB_NAME và DB_USER theo ý bạn; thay DB_PASSWORD và DB_ROOT_PASSWORD bằng hai mật khẩu riêng không rỗng. Lưu file. Không đưa .env lên GitHub.
```powershell
docker compose up -d --build
docker compose exec web php tools/create-admin.php
```
Nhập tên đăng nhập (ít nhất 3 ký tự), mật khẩu (ít nhất 12 ký tự). Mật khẩu hiển thị trong terminal khi nhập.
- Website: http://localhost:8080
- Admin: http://localhost:8080/admin/
- phpMyAdmin: http://localhost:8081 — đăng nhập bằng DB_USER và DB_PASSWORD đã đặt.
- compose.yaml mount `database/schema.sql` vào `/docker-entrypoint-initdb.d/schema.sql` để tự import vào DB_NAME khi database khởi tạo lần đầu.
- SQL không cố định CREATE DATABASE/USE, nên đổi DB_NAME không cần sửa SQL.
- `docker compose down` giữ dữ liệu trong volume. `down -v` sẽ xóa dữ liệu, không dùng nếu cần giữ dữ liệu.
- SQL chỉ chạy tự động với volume mới. Với DB đã có, chọn đúng database trong phpMyAdmin → Import schema.sql; các ID trùng được giữ nguyên nhờ INSERT IGNORE.
- Đổi mật khẩu trong .env không tự đổi mật khẩu của database đã khởi tạo. Cần đổi trong MySQL trước rồi cập nhật cấu hình.
- Nếu đổi tên service db trong Compose, cập nhật DB_HOST và các depends_on tương ứng; không sửa PHP.

## Chạy bằng XAMPP với biến môi trường
1. Chép portfolio vào C:\xampp\htdocs\portfolio. Start Apache và MySQL.
2. phpMyAdmin: tạo database (ví dụ portfolio) với utf8mb4_unicode_ci, chọn database đó rồi Import database/schema.sql.
3. Tạo user database riêng có mật khẩu không rỗng, cấp quyền trên database vừa tạo.
4. Để Apache có biến môi trường: thêm SetEnv vào cấu hình Apache httpd.conf/vhost (ngoài mã PHP), rồi restart Apache:
```apache
SetEnv DB_HOST 127.0.0.1
SetEnv DB_PORT 3306
SetEnv DB_NAME portfolio
SetEnv DB_USER portfolio
SetEnv DB_PASSWORD "YOUR_OWN_PASSWORD"
```
Thay bằng cấu hình và mật khẩu thật của bạn. PHP dùng getenv() để đọc các giá trị do Apache truyền.
5. CLI có môi trường riêng, nên trong PowerShell đặt:
```powershell
$env:DB_HOST="127.0.0.1"
$env:DB_PORT="3306"
$env:DB_NAME="portfolio"
$env:DB_USER="portfolio"
$env:DB_PASSWORD="YOUR_OWN_PASSWORD"
cd C:\xampp\htdocs\portfolio
C:\xampp\php\php.exe tools\create-admin.php
```
6. Truy cập http://localhost/portfolio/ và http://localhost/portfolio/admin/.

## Chức năng và tệp
- index.php: portfolio, hồ sơ, kỹ năng, dự án công khai, form liên hệ lưu MySQL.
- admin/: đăng nhập, quản lý hồ sơ, thêm/sửa/xóa dự án và kỹ năng, ẩn/hiện dự án, đọc/xóa tin nhắn, đổi mật khẩu.
- config.php: đọc biến môi trường bắt buộc; bootstrap.php: PDO, session, CSRF, escape HTML.
- database/schema.sql: 6 bảng admins, profile, projects, skills, messages, login_attempts và dữ liệu mẫu.
- Dockerfile, compose.yaml, .env.example: triển khai PHP/Apache, MySQL, phpMyAdmin.
- assets/style.css: giao diện sáng, responsive.
- tools/create-admin.php: tạo admin qua terminal một lần.

## Kiểm thử sau khi chạy
1. docker compose ps: db healthy; web và phpmyadmin hoạt động.
2. phpMyAdmin thấy 6 bảng, 3 dự án, 4 kỹ năng và 1 tin nhắn mẫu.
3. Trang chủ thấy 2 dự án công khai; admin thấy cả bản nháp.
4. Thêm/sửa/xóa kỹ năng và dự án, đổi hồ sơ rồi tải lại trang chủ kiểm tra.
5. Gửi tin nhắn bằng email hợp lệ, nội dung ít nhất 10 ký tự; kiểm tra trong admin.
6. Đăng xuất rồi mở /admin/ phải quay về đăng nhập; kiểm tra đổi mật khẩu.
7. Thử thay DB_NAME trên môi trường/volume thử nghiệm mới: SQL import vào tên đã chọn.
8. Xóa một biến môi trường bắt buộc: Compose/config.php phải từ chối, không tự dùng localhost/root.

## Giới hạn
Chưa có PHP/MySQL/Docker trong môi trường tạo source, nên chưa chạy kiểm thử tích hợp thực tế. Đã kiểm tra tĩnh cấu hình và nội dung ZIP. Chưa deploy online. Tin nhắn lưu DB, không gửi email; chưa có upload ảnh hay reset mật khẩu qua email. Khi public cần HTTPS, DB user riêng, không public phpMyAdmin. Apache .htaccess chặn các thư mục SQL/tools và file cấu hình; Nginx cần quy tắc tương đương.
