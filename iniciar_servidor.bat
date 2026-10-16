@echo off
title Servidor CAECA - UNIFEI
echo ========================================================
echo   Iniciando Servidor Web do CAECA (UNIFEI)...
echo ========================================================
echo.
echo   Acesse no seu navegador: http://localhost:8000
echo.
echo   Pressione Ctrl+C para encerrar o servidor.
echo ========================================================
echo.

set PATH=%USERPROFILE%\php;%PATH%
php -S localhost:8000 -t public
pause
