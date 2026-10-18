<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require dirname(__DIR__).'/bootstrap.php';
if(q('SELECT COUNT(*) FROM admins')->fetchColumn()){exit("Admin đã tồn tại. Đổi mật khẩu trong trang quản trị.\n");}
echo "Tên đăng nhập: ";$u=trim(fgets(STDIN));echo "Mật khẩu (tối thiểu 12 ký tự, hiển thị khi nhập): ";$p=trim(fgets(STDIN));
if(strlen($u)<3||strlen($u)>80||strlen($p)<12)exit("Thông tin không hợp lệ.\n");
q('INSERT INTO admins(username,password_hash) VALUES(?,?)',[$u,password_hash($p,PASSWORD_DEFAULT)]);echo "Đã tạo tài khoản admin.\n";
