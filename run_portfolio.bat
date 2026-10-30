@echo off
title Lokesh Pon - Civil Engineering Portfolio
echo =====================================================================
echo  Starting Lokesh Pon Civil Engineering Portfolio (PHP + Vue.js)
echo =====================================================================
echo.

set PHP_PATH=C:\xampp\php\php.exe

if not exist "%PHP_PATH%" (
    echo [ERROR] PHP was not found at %PHP_PATH%.
    echo Please make sure XAMPP is installed or edit this batch file with your PHP path.
    pause
    exit /b
)

echo Starting local server on http://localhost:8080 ...
start "" "http://localhost:8080"
"%PHP_PATH%" -S localhost:8080
pause

