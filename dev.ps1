# Territorial Army — dev helper
# Usage: .\dev.ps1 <command>
#
# Commands:
#   start   - open admin and public site in the browser
#   fresh   - wipe DB and reseed with demo data (destroys all data)
#   clear   - clear Laravel caches after you changed config or .env
#   pull    - pull latest code from GitHub and run install + migrate
#   admin   - create / reset the super admin password
#   logs    - tail the Laravel log file
#   help    - show this message

param([string]$cmd = "help")

$project = $PSScriptRoot
Set-Location $project

function Open-App {
    Start-Process "http://army.test"
    Start-Process "http://army.test/admin"
    Write-Host "Opened in browser:" -ForegroundColor Green
    Write-Host "  Public : http://army.test"
    Write-Host "  Admin  : http://army.test/admin"
}

function Fresh-DB {
    Write-Host "Wiping database and reseeding..." -ForegroundColor Yellow
    php artisan migrate:fresh --seed
    php artisan optimize:clear
    Write-Host "Done. Admin login below:" -ForegroundColor Green
    Select-String -Path ".env" -Pattern "^ADMIN_"
}

function Clear-Caches {
    php artisan optimize:clear
    Write-Host "Caches cleared." -ForegroundColor Green
}

function Pull-Latest {
    git pull
    composer install
    php artisan migrate
    php artisan optimize:clear
    Write-Host "Up to date." -ForegroundColor Green
}

function Reset-Admin {
    $email = Read-Host "Admin email"
    $pwd   = Read-Host "New password" -AsSecureString
    $plain = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto(
        [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($pwd))
    $code = "\App\Models\Admin::updateOrCreate(['email'=>'$email'],['name'=>'Super Admin','password'=>bcrypt('$plain'),'role'=>'super_admin']); echo 'done';"
    php artisan tinker --execute="$code"
}

function Tail-Logs {
    Get-Content -Path "storage\logs\laravel.log" -Wait -Tail 50
}

function Show-Help {
    Get-Content $PSCommandPath | Select-Object -First 15 | ForEach-Object { $_ -replace "^# ?", "" }
}

switch ($cmd.ToLower()) {
    "start" { Open-App }
    "fresh" { Fresh-DB }
    "clear" { Clear-Caches }
    "pull"  { Pull-Latest }
    "admin" { Reset-Admin }
    "logs"  { Tail-Logs }
    default { Show-Help }
}
