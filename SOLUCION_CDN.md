# SOLUCIÓN: ERR_NAME_NOT_RESOLVED en Docker
## Sistema Escalable para Proyectos Laravel + Docker

---

## 🎯 PROBLEMA IDENTIFICADO

**Error:** `ERR_NAME_NOT_RESOLVED` al cargar recursos desde CDN externos
**Causa:** Docker en tu entorno no tiene conectividad DNS para resolver dominios externos
**Recursos afectados:**
- cdn.jsdelivr.net (Bootstrap, Bootstrap Icons)
- fonts.googleapis.com (Google Fonts)
- Cualquier CDN externo

---

## ✅ SOLUCIÓN IMPLEMENTADA

### Estrategia: **Recursos Locales + Vite**

1. **Dependencias npm locales** (no CDN externos)
2. **Vite** compila y sirve los assets
3. **Zero dependencias de red externa** en runtime

---

## 📦 ARCHIVOS MODIFICADOS/CREADOS

### 1. `package.json` - Dependencias locales
```json
{
  "dependencies": {
    "bootstrap": "^5.3.3",
    "bootstrap-icons": "^1.11.3"
  }
}
```

### 2. `vite.config.js` - Configuración Vite
```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/carrito.css',
                'resources/js/carrito.js'
            ],
            refresh: true,
        }),
    ],
});
```

### 3. `resources/css/carrito.css` - Imports locales
```css
@import 'bootstrap/dist/css/bootstrap.min.css';
@import 'bootstrap-icons/font/bootstrap-icons.min.css';
```

### 4. `resources/js/carrito.js` - Bootstrap JS
```javascript
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
```

### 5. `resources/views/carrito/index.blade.php` - Blade con Vite
```php
@vite(['resources/css/carrito.css', 'resources/js/carrito.js'])
```

---

## 🚀 INSTALACIÓN (Primera vez)

### Windows:
```bash
setup-local-assets.bat
```

### Linux/Mac:
```bash
chmod +x setup-local-assets.sh
./setup-local-assets.sh
```

### Manual:
```bash
# 1. Instalar dependencias
npm install

# 2. Compilar assets
npm run build

# 3. Levantar Docker
docker-compose up -d

# 4. Migraciones (si es necesario)
docker-compose exec app php artisan migrate
```

---

## 🔄 WORKFLOW DE DESARROLLO

### Modo desarrollo (Hot Reload):
```bash
npm run dev
```

### Compilar para producción:
```bash
npm run build
```

---

## 📋 APLICAR A OTROS PROYECTOS

### Checklist rápido:

1. ✅ **Copiar `package.json`** con dependencias
2. ✅ **Copiar `vite.config.js`** con entradas
3. ✅ **Crear archivos CSS/JS** en `resources/`
4. ✅ **Reemplazar CDN por `@vite()`** en Blade
5. ✅ **Ejecutar `npm install && npm run build`**

### Template base para cualquier vista:

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mi Proyecto</title>
    
    <!-- Recursos locales vía Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Tu contenido -->
</body>
</html>
```

---

## 🛠️ TROUBLESHOOTING

### Problema: "Vite manifest not found"
**Solución:**
```bash
npm run build
```

### Problema: Estilos no se cargan
**Verificar:**
1. `public/build` existe
2. `npm run dev` está corriendo (desarrollo)
3. Assets compilados con `npm run build` (producción)

### Problema: Bootstrap no funciona
**Verificar:**
```bash
ls node_modules/bootstrap      # Debe existir
ls node_modules/bootstrap-icons # Debe existir
ls public/build                 # Debe tener archivos
```

---

## 📊 VENTAJAS DE ESTA SOLUCIÓN

| Aspecto | CDN Externo | Recursos Locales |
|---------|-------------|------------------|
| **Conectividad** | Requiere internet | ✅ Sin internet |
| **Docker DNS** | Requiere DNS funcional | ✅ No requiere DNS |
| **Performance** | Depende de CDN | ✅ Servido localmente |
| **Versionado** | Puede cambiar | ✅ Version lock |
| **Offline dev** | ❌ No funciona | ✅ Funciona |

---

## 🔐 BUENAS PRÁCTICAS

1. **Versionado:** Siempre especificar versiones exactas en `package.json`
2. **Build:** Commitear `package-lock.json` para reproducibilidad
3. **Assets:** NO commitear `node_modules` ni `public/build`
4. **Git:** Agregar a `.gitignore`:
   ```
   /node_modules
   /public/build
   /public/hot
   ```

---

## 🎯 PRÓXIMOS PROYECTOS DE AIDO

### Template base para nuevos proyectos:

```bash
# 1. Crear estructura base
mkdir -p resources/{css,js}

# 2. Copiar archivos desde La Bartola
cp package.json nuevo-proyecto/
cp vite.config.js nuevo-proyecto/

# 3. Instalar y compilar
cd nuevo-proyecto
npm install
npm run build

# 4. Listo para desarrollo
npm run dev
```

---

## 📝 NOTAS IMPORTANTES

- **Google Fonts:** Sigue requiriendo internet, pero tiene fallback a fuentes del sistema
- **Alternativa:** Descargar fuentes y servirlas localmente si 100% offline es crítico
- **Vite dev server:** Corre en puerto 5173 por defecto (configurable)
- **HMR (Hot Module Replacement):** Funciona automáticamente en modo dev

---

## 🚀 ESCALABILIDAD

Esta solución es **perfecta para los 30 proyectos de AIDO** porque:

1. ✅ **Repetible:** Mismo setup en todos los proyectos
2. ✅ **Sin sorpresas:** No depende de disponibilidad de CDN
3. ✅ **Performance:** Assets optimizados por Vite
4. ✅ **Mantenible:** Actualizaciones centralizadas en `package.json`

---

## 💡 TIP PRO

Crear un **repositorio template** en AIDO con:
- `package.json` base
- `vite.config.js` base
- Estructura `resources/` preconfigurada
- Scripts de setup automatizados

Así cada proyecto nuevo parte de esta base sólida.

---

**Autor:** Carlos Oliver - AIDO  
**Fecha:** Enero 2025  
**Proyecto:** La Bartola Laravel
