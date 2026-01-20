@echo off
REM Script para clonar configuración AIDO en nuevos proyectos
REM Uso: aido-setup.bat C:\ruta\al\nuevo\proyecto

if "%~1"=="" (
    echo - Error: Debes proporcionar la ruta del proyecto
    echo Uso: aido-setup.bat C:\ruta\al\nuevo\proyecto
    exit /b 1
)

set PROJECT_PATH=%~1
set TEMPLATE_PATH=%~dp0.aido-templates

echo ===================================
echo AIDO PROJECT SETUP
echo ===================================
echo.
echo Proyecto: %PROJECT_PATH%
echo Template: %TEMPLATE_PATH%
echo.

REM Verificar que el template existe
if not exist "%TEMPLATE_PATH%" (
    echo - Error: Template no encontrado en %TEMPLATE_PATH%
    exit /b 1
)

REM Verificar que el proyecto existe
if not exist "%PROJECT_PATH%" (
    echo - Error: Proyecto no encontrado en %PROJECT_PATH%
    exit /b 1
)

REM 1. Copiar configuración npm
echo [1/7] Copiando package.json...
copy "%TEMPLATE_PATH%\package.json" "%PROJECT_PATH%\package.json" >nul

REM 2. Copiar configuración Vite
echo [2/7] Copiando vite.config.js...
copy "%TEMPLATE_PATH%\vite.config.js" "%PROJECT_PATH%\vite.config.js" >nul

REM 3. Crear estructura de directorios
echo [3/7] Creando estructura de recursos...
if not exist "%PROJECT_PATH%\resources\css" mkdir "%PROJECT_PATH%\resources\css"
if not exist "%PROJECT_PATH%\resources\js" mkdir "%PROJECT_PATH%\resources\js"
if not exist "%PROJECT_PATH%\resources\views\layouts\partials" mkdir "%PROJECT_PATH%\resources\views\layouts\partials"

REM 4. Copiar archivos CSS/JS base
echo [4/7] Copiando assets base...
copy "%TEMPLATE_PATH%\app.css" "%PROJECT_PATH%\resources\css\app.css" >nul
copy "%TEMPLATE_PATH%\app.js" "%PROJECT_PATH%\resources\js\app.js" >nul

REM 5. Copiar layout base
echo [5/7] Copiando layout base...
copy "%TEMPLATE_PATH%\layout.blade.php" "%PROJECT_PATH%\resources\views\layouts\app.blade.php" >nul

REM 6. Instalar dependencias
echo [6/7] Instalando dependencias npm...
cd /d "%PROJECT_PATH%"
call npm install

REM 7. Compilar assets
echo [7/7] Compilando assets...
call npm run build

echo.
echo ===================================
echo + SETUP COMPLETADO
echo ===================================
echo.
echo Próximos pasos:
echo 1. cd %PROJECT_PATH%
echo 2. Configurar .env
echo 3. docker-compose up -d
echo 4. php artisan migrate
echo 5. npm run dev (para desarrollo)
echo.
echo Tu proyecto ahora tiene:
echo + Bootstrap 5.3.3
echo + Bootstrap Icons
echo + Alpine.js
echo + Tailwind CSS
echo + Axios configurado
echo + AIDO utilities
echo + Sin dependencias de CDN externos
echo.
pause
