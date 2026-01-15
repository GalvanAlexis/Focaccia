#!/bin/bash

# Script para clonar configuración AIDO en nuevos proyectos
# Uso: ./aido-setup.sh /ruta/al/nuevo/proyecto

if [ -z "$1" ]; then
    echo "❌ Error: Debes proporcionar la ruta del proyecto"
    echo "Uso: ./aido-setup.sh /ruta/al/nuevo/proyecto"
    exit 1
fi

PROJECT_PATH="$1"
TEMPLATE_PATH="$(dirname "$0")/.aido-templates"

echo "==================================="
echo "AIDO PROJECT SETUP"
echo "==================================="
echo ""
echo "Proyecto: $PROJECT_PATH"
echo "Template: $TEMPLATE_PATH"
echo ""

# Verificar que el template existe
if [ ! -d "$TEMPLATE_PATH" ]; then
    echo "❌ Error: Template no encontrado en $TEMPLATE_PATH"
    exit 1
fi

# Verificar que el proyecto existe
if [ ! -d "$PROJECT_PATH" ]; then
    echo "❌ Error: Proyecto no encontrado en $PROJECT_PATH"
    exit 1
fi

# 1. Copiar configuración npm
echo "[1/7] Copiando package.json..."
cp "$TEMPLATE_PATH/package.json" "$PROJECT_PATH/package.json"

# 2. Copiar configuración Vite
echo "[2/7] Copiando vite.config.js..."
cp "$TEMPLATE_PATH/vite.config.js" "$PROJECT_PATH/vite.config.js"

# 3. Crear estructura de directorios
echo "[3/7] Creando estructura de recursos..."
mkdir -p "$PROJECT_PATH/resources/css"
mkdir -p "$PROJECT_PATH/resources/js"
mkdir -p "$PROJECT_PATH/resources/views/layouts/partials"

# 4. Copiar archivos CSS/JS base
echo "[4/7] Copiando assets base..."
cp "$TEMPLATE_PATH/app.css" "$PROJECT_PATH/resources/css/app.css"
cp "$TEMPLATE_PATH/app.js" "$PROJECT_PATH/resources/js/app.js"

# 5. Copiar layout base
echo "[5/7] Copiando layout base..."
cp "$TEMPLATE_PATH/layout.blade.php" "$PROJECT_PATH/resources/views/layouts/app.blade.php"

# 6. Instalar dependencias
echo "[6/7] Instalando dependencias npm..."
cd "$PROJECT_PATH"
npm install

# 7. Compilar assets
echo "[7/7] Compilando assets..."
npm run build

echo ""
echo "==================================="
echo "✓ SETUP COMPLETADO"
echo "==================================="
echo ""
echo "Próximos pasos:"
echo "1. cd $PROJECT_PATH"
echo "2. Configurar .env"
echo "3. docker-compose up -d"
echo "4. php artisan migrate"
echo "5. npm run dev (para desarrollo)"
echo ""
echo "Tu proyecto ahora tiene:"
echo "✓ Bootstrap 5.3.3"
echo "✓ Bootstrap Icons"
echo "✓ Alpine.js"
echo "✓ Tailwind CSS"
echo "✓ Axios configurado"
echo "✓ AIDO utilities"
echo "✓ Sin dependencias de CDN externos"
echo ""
