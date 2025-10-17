# Script pour redemarrer Apache (httpd)
Write-Host "==================================" -ForegroundColor Cyan
Write-Host "  Redemarrage d'Apache (httpd)   " -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

# Trouver les processus httpd
$httpdProcesses = Get-Process -Name "httpd" -ErrorAction SilentlyContinue

if ($httpdProcesses) {
    Write-Host "Arret des processus Apache..." -ForegroundColor Yellow
    $httpdProcesses | ForEach-Object {
        Write-Host "  - Arret du processus httpd (PID: $($_.Id))" -ForegroundColor Gray
        Stop-Process -Id $_.Id -Force -ErrorAction SilentlyContinue
    }
    
    Write-Host "Attente de l'arret complet..." -ForegroundColor Yellow
    Start-Sleep -Seconds 3
    
    Write-Host ""
    Write-Host "Apache arrete avec succes!" -ForegroundColor Green
    Write-Host ""
    Write-Host "IMPORTANT:" -ForegroundColor Red
    Write-Host "Vous devez maintenant DEMARRER Apache via Laragon:" -ForegroundColor Yellow
    Write-Host "  1. Ouvrir Laragon" -ForegroundColor Gray
    Write-Host "  2. Clic sur 'Start All'" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Ou utilisez le bouton 'Start' dans l'interface Laragon" -ForegroundColor Yellow
    
} else {
    Write-Host "Aucun processus Apache (httpd) en cours d'execution" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Demarrez Apache via Laragon:" -ForegroundColor Cyan
    Write-Host "  1. Ouvrir Laragon" -ForegroundColor Gray
    Write-Host "  2. Clic sur 'Start All'" -ForegroundColor Gray
}

Write-Host ""
Write-Host "Apres le demarrage, testez:" -ForegroundColor Cyan
Write-Host "  http://127.0.0.1:8000/phpinfo.php" -ForegroundColor Gray
Write-Host ""
