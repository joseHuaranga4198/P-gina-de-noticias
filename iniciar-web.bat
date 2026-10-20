@echo off
REM ============================================
REM  Iniciar Portal de Noticias
REM  Ejecutar desde: E:\Página de noticias\
REM ============================================
echo.
echo ==========================================
echo   Portal de Noticias - Iniciando servidor
echo ==========================================
echo.

cd /d "E:\Página de noticias\app"

REM Verificar que la base de datos exista
if not exist "database\database.sqlite" (
    echo Creando base de datos...
    copy NUL "database\database.sqlite"
    php artisan migrate --force
    php artisan db:seed --force
)

echo Iniciando servidor en http://localhost:8000
echo Presione Ctrl+C para detener el servidor
echo.

php artisan serve --host=localhost --port=8000
