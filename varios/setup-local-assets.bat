@echo off
REM Script de instalación para La Bartola Laravel (Windows)
REM Elimina dependencias de CDN externos

echo ===================================
echo INSTALACIÓN LA BARTOLA - RECURSOS LOCALES
echo ===================================

REM 1. Instalar dependencias npm
echo.
echo [1/4] Instalando dependencias npm...
call npm install
if errorlevel 1 goto error

REM 2. Compilar assets con Vite
echo.
echo [2/4] Compilando assets con Vite...
call npm run build
if errorlevel 1 goto error

REM 3. Verificar instalación
echo.
echo [3/4] Verificando instalación...
if exist "node_modules\bootstrap" (
    echo + Bootstrap instalado correctamente
) else (
    echo - Error: Bootstrap no instalado
    goto error
)

if exist "node_modules\bootstrap-icons" (
    echo + Bootstrap Icons instalado correctamente
) else (
    echo - Error: Bootstrap Icons no instalado
    goto error
)

REM 4. Verificar assets compilados
echo.
echo [4/4] Verificando assets compilados...
if exist "public\build" (
    echo + Assets compilados en public\build
) else (
    echo - Error: Assets no compilados
    goto error
)

echo.
echo ===================================
echo + INSTALACIÓN COMPLETADA
echo ===================================
echo.
echo Próximos pasos:
echo 1. Levantar Docker: docker-compose up -d
echo 2. Ejecutar migraciones: docker-compose exec app php artisan migrate
echo 3. Acceder al proyecto en: http://localhost
echo.
echo Nota: Si modificas CSS/JS, ejecuta: npm run dev
echo.
goto end

:error
echo.
echo ===================================
echo - ERROR EN LA INSTALACIÓN
echo ===================================
echo.
pause
exit /b 1

:end
pause
