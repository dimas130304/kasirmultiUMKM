@echo off
REM Gunakan PHP 8.2 untuk Laravel 12 (jangan PHP 8.0)
set PHP=C:\xampp\php84\php.exe
cd /d "%~dp0"
"%PHP%" %*
