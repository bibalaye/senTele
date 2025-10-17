# Script pour activer l'extension OpenSSL dans PHP 8.4
$phpIniPath = "C:\tools\php84\php.ini"

Write-Host "Activation de l'extension OpenSSL..." -ForegroundColor Cyan

if (-not (Test-Path $phpIniPath)) {
    Write-Host "Fichier php.ini introuvable: $phpIniPath" -ForegroundColor Red
    exit 1
}

$content = Get-Content $phpIniPath -Raw

if ($content -match "^extension=openssl" -and $content -notmatch "^;extension=openssl") {
    Write-Host "OpenSSL est deja active!" -ForegroundColor Green
    exit 0
}

$backupPath = "$phpIniPath.backup"
Copy-Item $phpIniPath $backupPath
Write-Host "Sauvegarde creee: $backupPath" -ForegroundColor Gray

$content = $content -replace ";extension=openssl", "extension=openssl"
Set-Content $phpIniPath $content -NoNewline

Write-Host "Extension OpenSSL activee!" -ForegroundColor Green
Write-Host "IMPORTANT: Redemarrez Apache/Laragon" -ForegroundColor Yellow
