<?php
declare(strict_types=1);
ini_set('session.use_strict_mode','1');
session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off','samesite'=>'Lax']);
session_start();
header('X-Content-Type-Options: nosniff'); header('X-Frame-Options: DENY'); header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; style-src 'self'; img-src 'self' https:; form-action 'self'; frame-ancestors 'none'; base-uri 'self'");
try {$c=require __DIR__.'/config.php';$db=new PDO("mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset=utf8mb4",$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);}
catch(RuntimeException $e){http_response_code(503); exit('Chưa kết nối được MySQL. Kiểm tra biến môi trường DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD và import database/schema.sql theo README.md.');}
function e($s):string{return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function q(string $sql,array $args=[]):PDOStatement{global $db;$s=$db->prepare($sql);$s->execute($args);return $s;}
function go(string $url):void{header('Location: '.$url);exit;}
function csrf():string{$_SESSION['csrf']??=bin2hex(random_bytes(32));return '<input type="hidden" name="csrf" value="'.e($_SESSION['csrf']).'">';}
function check():void{if(!hash_equals($_SESSION['csrf']??'',(string)($_POST['csrf']??'')) || empty($_SESSION['csrf'])){http_response_code(403);exit('Phiên không hợp lệ. Hãy tải lại trang.');}}
function val(string $key,int $max=255):string{$v=trim((string)($_POST[$key]??''));if(strlen($v)>$max)throw new RuntimeException('Nội dung quá dài: '.$key);return $v;}
function urlval(string $key):string{$v=val($key,500);if($v!=='' && (!filter_var($v,FILTER_VALIDATE_URL) || !in_array(strtolower(parse_url($v,PHP_URL_SCHEME)??''),['http','https'],true)))throw new RuntimeException('Liên kết phải bắt đầu bằng http:// hoặc https://');return $v;}
function flash(string $s):void{$_SESSION['flash']=$s;}
function notice():void{if(isset($_SESSION['flash'])){echo '<p class="notice" role="status">'.e($_SESSION['flash']).'</p>';unset($_SESSION['flash']);}}
function head(string $title,string $prefix=''):void{echo '<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($title).'</title><link rel="stylesheet" href="'.$prefix.'assets/style.css"></head><body>';}
