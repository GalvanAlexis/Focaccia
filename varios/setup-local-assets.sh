#!/bin/bash

# Script de instalación para La Bartola Laravel
# Elimina dependencias de CDN externos

echo "==================================="
echo "INSTALACIÓN LA BARTOLA - RECURSOS LOCALES"
echo "==================================="

# 1. Instalar dependencias npm
echo ""
echo "[1/4] Instalando dependencias npm..."
npm install

# 2. Compilar assets con Vite
echo ""
echo "[2/4] Compilando assets con Vite..."
npm run build

# 3. Verificar instalación
echo ""
echo "[3/4] Verificando instalación..."
if [ -d "node_modules/bootstrap" ] && [ -d "node_modules/bootstrap-icons" ]; then
    echo "✓ Bootstrap instalado correctamente"
    echo "✓ Bootstrap Icons instalado correctamente"
else
    echo "✗ Error: Faltan dependencias"
    exit 1
fi

# 4. Verificar assets compilados
echo ""
echo "[4/4] Verificando assets compilados..."
if [ -d "public/build" ]; then
    echo "✓ Assets compilados en public/build"
else
    echo "✗ Error: Assets no compilados"
    exit 1
fi

echo ""
echo "==================================="
echo "✓ INSTALACIÓN COMPLETADA"
echo "==================================="
echo ""
echo "Próximos pasos:"
echo "1. Levantar Docker: docker-compose up -d"
echo "2. Ejecutar migraciones: docker-compose exec app php artisan migrate"
echo "3. Acceder al proyecto en: http://localhost"
echo ""
echo "Nota: Si modificas CSS/JS, ejecuta: npm run dev"
echo ""
