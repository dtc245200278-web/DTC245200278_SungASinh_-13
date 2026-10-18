$env:DB_HOST = "127.0.0.1"
$env:DB_PORT = "3306"
$env:DB_NAME = "portfolio"
$env:DB_USER = "root"
$env:DB_PASSWORD = "ngoc123"

$php = "C:\Users\Ngoc\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
Write-Host "Starting PHP server on http://127.0.0.1:8080 with DB $env:DB_NAME..."
& $php -S 127.0.0.1:8080
