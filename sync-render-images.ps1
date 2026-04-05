# Sync Product Images to Cloudinary (from local to Render DB)
# This script temporarily loads .env.render to update DB Render with Cloudinary URLs

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "Syncing images to Cloudinary (Render DB)" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan

# Load .env.render into current session
Write-Host "`n[1/4] Loading Render database config..." -ForegroundColor Yellow
$envContent = Get-Content ".env.render" | Where-Object { $_ -and -not $_.StartsWith("#") }
$envContent | ForEach-Object {
    $key, $value = $_.Split("=", 2)
    if ($key -and $value) {
        [System.Environment]::SetEnvironmentVariable($key, $value, [System.EnvironmentVariableTarget]::Process)
    }
}

# Clear Laravel config cache
Write-Host "[2/4] Clearing Laravel config cache..." -ForegroundColor Yellow
php artisan config:clear

# Run sync command
Write-Host "[3/4] Syncing product images to Cloudinary..." -ForegroundColor Yellow
php artisan products:sync-images-cloudinary

# Restore local env
Write-Host "[4/4] Restoring local environment..." -ForegroundColor Yellow
php artisan config:clear

Write-Host "`n=====================================" -ForegroundColor Green
Write-Host "Done! Images synced to Cloudinary." -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green
Write-Host "`nYou can now restart Render backend." -ForegroundColor Cyan
