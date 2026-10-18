-- Select a database before importing. Docker selects MYSQL_DATABASE automatically.
SET NAMES utf8mb4;
CREATE TABLE IF NOT EXISTS admins(id INT PRIMARY KEY AUTO_INCREMENT, username VARCHAR(80) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL);
CREATE TABLE IF NOT EXISTS profile(id INT PRIMARY KEY, name VARCHAR(150) NOT NULL, role VARCHAR(255) NOT NULL, bio TEXT NOT NULL, email VARCHAR(255) NOT NULL DEFAULT '', location VARCHAR(255) NOT NULL DEFAULT '', github VARCHAR(500) NOT NULL DEFAULT '');
INSERT IGNORE INTO profile VALUES(1,'Đặng Văn Ngọc','Sinh viên Công nghệ thông tin','Tôi yêu thích xây dựng phần mềm có ích, khám phá trí tuệ nhân tạo và biến những ý tưởng thành sản phẩm thực tế.','','Thái Nguyên, Việt Nam','https://github.com/ngoc-oss');
CREATE TABLE IF NOT EXISTS projects(id INT PRIMARY KEY AUTO_INCREMENT,title VARCHAR(150) NOT NULL,category VARCHAR(80) NOT NULL,description TEXT NOT NULL,tech VARCHAR(255) NOT NULL,url VARCHAR(500) NOT NULL DEFAULT '',published TINYINT NOT NULL DEFAULT 1);
CREATE TABLE IF NOT EXISTS skills(id INT PRIMARY KEY AUTO_INCREMENT,name VARCHAR(100) NOT NULL,category VARCHAR(80) NOT NULL);
CREATE TABLE IF NOT EXISTS messages(id INT PRIMARY KEY AUTO_INCREMENT,name VARCHAR(100) NOT NULL,email VARCHAR(255) NOT NULL,body TEXT NOT NULL,is_read TINYINT NOT NULL DEFAULT 0,created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS login_attempts(ip_hash CHAR(64) PRIMARY KEY,attempts INT NOT NULL DEFAULT 0,last_attempt BIGINT NOT NULL);

-- DEMO DATA: illustrative examples, not claims about the owner's accomplishments.
INSERT IGNORE INTO projects(id,title,category,description,tech,url,published) VALUES
(1,'Portfolio cá nhân — Dữ liệu mẫu','Web','Ví dụ minh họa website giới thiệu cá nhân có trang quản trị nội dung. Hãy sửa bằng dự án thực tế trước khi công khai.','PHP, MySQL, Docker','',1),
(2,'Quản lý thư viện — Dữ liệu mẫu','Ứng dụng quản lý','Ví dụ minh họa chức năng quản lý sách và lượt mượn, không phải thành tích đã xác minh.','PHP, MySQL','',1),
(3,'Dự án đang chuẩn bị — Dữ liệu mẫu','Bản nháp','Ví dụ bản nháp: không xuất hiện trên trang công khai.','PHP','',0);
INSERT IGNORE INTO skills(id,name,category) VALUES
(1,'PHP (mẫu)','Backend'),(2,'MySQL (mẫu)','Database'),(3,'HTML / CSS (mẫu)','Frontend'),(4,'Docker (mẫu)','Triển khai');
INSERT IGNORE INTO messages(id,name,email,body,is_read) VALUES
(1,'Khách demo','demo@example.com','Đây là tin nhắn mẫu để kiểm tra chức năng đọc và xóa trong trang quản trị.',0);
-- No default admin password. Create your own admin using tools/create-admin.php.
