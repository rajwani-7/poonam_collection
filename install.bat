@echo off
echo ========================================
echo  Poonam Collection - Windows Installer
echo ========================================
echo.

REM Check if XAMPP is installed
if exist "C:\xampp\apache\bin\httpd.exe" (
    echo [OK] XAMPP detected at C:\xampp
) else (
    echo [ERROR] XAMPP not found. Please install XAMPP first.
    echo Download from: https://www.apachefriends.org/
    pause
    exit
)

echo.
echo Starting Apache and MySQL...
echo.

REM Start Apache
call "C:\xampp\apache_start.bat" 2>nul
if errorlevel 1 (
    echo [WARNING] Could not auto-start Apache. Please start it manually from XAMPP Control Panel.
) else (
    echo [OK] Apache started
)

REM Start MySQL
call "C:\xampp\mysql_start.bat" 2>nul
if errorlevel 1 (
    echo [WARNING] Could not auto-start MySQL. Please start it manually from XAMPP Control Panel.
) else (
    echo [OK] MySQL started
)

echo.
echo ========================================
echo  Installation Steps
echo ========================================
echo.
echo 1. Copy this 'pc' folder to: C:\xampp\htdocs\
echo.
echo 2. Open your browser and go to:
echo    http://localhost/phpmyadmin
echo.
echo 3. Create a new database named: poonam_collection
echo.
echo 4. Import the file: database/setup.sql
echo.
echo 5. Then visit: http://localhost/pc/setup.php
echo.
echo ========================================
echo  Default Admin Credentials
echo ========================================
echo.
echo URL:      http://localhost/pc/admin/
echo Username: admin
echo Password: admin123
echo.
echo ========================================

echo.
echo Would you like to open the setup page now? (Y/N)
set /p choice=

if /i "%choice%"=="Y" (
    echo.
    echo Opening setup page...
    timeout /t 2 /nobreak >nul
    start http://localhost/pc/setup.php
    echo.
    echo Opening phpMyAdmin...
    timeout /t 2 /nobreak >nul
    start http://localhost/phpmyadmin
)

echo.
echo Installation wizard completed!
echo.
echo For detailed instructions, see:
echo - README.md
echo - QUICKSTART.md
echo.
pause
