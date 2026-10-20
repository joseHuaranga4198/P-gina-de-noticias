@echo off
REM ============================================
REM  Ejecutar pruebas PHPUnit
REM ============================================
echo.
echo ==========================================
echo   Ejecutando pruebas PHPUnit
echo ==========================================
echo.

cd /d "E:\Página de noticias\app"

set XDEBUG_MODE=coverage

php artisan test --coverage-clover=reports/coverage-clover.xml --log-junit=reports/junit.xml 2>&1

echo.
echo Informes generados en: E:\Página de noticias\app\reports\
pause
