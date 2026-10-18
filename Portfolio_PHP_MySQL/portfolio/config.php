<?php
declare(strict_types=1);
// All connection values MUST be supplied by the process environment.
$config = [];
foreach (['host'=>'DB_HOST','port'=>'DB_PORT','database'=>'DB_NAME','username'=>'DB_USER','password'=>'DB_PASSWORD'] as $key=>$variable) {
    $value = getenv($variable);
    if ($value === false || $value === '') {
        throw new RuntimeException('Missing required environment variable: '.$variable);
    }
    $config[$key] = $value;
}
if (!ctype_digit($config['port']) || (int)$config['port'] < 1 || (int)$config['port'] > 65535) {
    throw new RuntimeException('DB_PORT must be an integer between 1 and 65535.');
}
foreach (['host','database'] as $key) {
    if (preg_match('/[;\x00-\x20]/', $config[$key])) {
        throw new RuntimeException('Invalid database environment configuration.');
    }
}
return $config;
