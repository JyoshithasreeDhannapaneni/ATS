@echo off
echo ========================================
echo   Starting OpenCATS Application
echo ========================================
echo.

REM Start MySQL
echo [1/2] Starting MySQL...
start /B "" "C:\xampp\mysql\bin\mysqld.exe" --defaults-file=C:\xampp\mysql\bin\my.ini
timeout /t 3 /nobreak >nul
echo    MySQL started
echo.

REM Start PHP Server
echo [2/2] Starting PHP Development Server...
echo.
echo Server will run on: http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo.
cd /d "%~dp0"
php -S localhost:8000
pause
