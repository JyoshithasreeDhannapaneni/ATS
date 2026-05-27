# Run this script as Administrator in PowerShell
# It resets the postgres user password to 'Joshi@515' to match config.php

$pgData = "C:\Program Files\PostgreSQL\17\data"
$pgBin  = "C:\Program Files\PostgreSQL\17\bin"
$hbaFile = "$pgData\pg_hba.conf"

# Backup original
Copy-Item $hbaFile "$hbaFile.bak" -Force
Write-Host "Backed up pg_hba.conf"

# Replace scram-sha-256 with trust for local connections
$content = Get-Content $hbaFile -Raw
$content = $content -replace 'scram-sha-256', 'trust'
Set-Content -Path $hbaFile -Value $content -Encoding UTF8
Write-Host "Switched auth to trust"

# Reload PostgreSQL config
& "$pgBin\pg_ctl.exe" reload -D $pgData
Start-Sleep -Seconds 2
Write-Host "Reloaded PostgreSQL config"

# Set the postgres password to Joshi@515
& "$pgBin\psql.exe" -U postgres -c "ALTER USER postgres WITH PASSWORD 'Joshi@515';"
Write-Host "Password set to Joshi@515"

# Restore scram-sha-256
$content = Get-Content $hbaFile -Raw
$content = $content -replace 'trust', 'scram-sha-256'
Set-Content -Path $hbaFile -Value $content -Encoding UTF8
Write-Host "Restored scram-sha-256 auth"

# Reload again
& "$pgBin\pg_ctl.exe" reload -D $pgData
Write-Host "Done! PostgreSQL password is now 'Joshi@515'. Restart your PHP server."
