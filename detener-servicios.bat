@echo off
REM ============================================
REM  Detener servicios del proyecto
REM ============================================
echo.
echo Deteniendo servidores del proyecto...
echo.

REM Solo detener procesos PHP que sirven en el puerto 8000
for /f "tokens=5" %%a in ('netstat -ano ^| findstr ":8000" ^| findstr "LISTENING"') do (
    echo Deteniendo proceso en puerto 8000 (PID: %%a)
    taskkill /PID %%a /F 2>nul
)

REM Detener SonarQube si está corriendo en el puerto del proyecto
for /f "tokens=5" %%a in ('netstat -ano ^| findstr ":9000" ^| findstr "LISTENING"') do (
    echo Deteniendo proceso en puerto 9000 (PID: %%a)
    taskkill /PID %%a /F 2>nul
)

echo.
echo Servicios del proyecto detenidos.
pause
