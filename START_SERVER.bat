@echo off
echo Starting OpenCATS Development Server...
echo.
cd /d "%~dp0"
php -S localhost:8000
pause
