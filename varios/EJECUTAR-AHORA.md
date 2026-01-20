# ⚡ EJECUTAR AHORA - La Bartola

## 🎯 Solución inmediata a ERR_NAME_NOT_RESOLVED

---

## PASO 1: Instalar Dependencias (3 minutos)

Abre la terminal en la raíz del proyecto:

### Windows (CMD o PowerShell):
```bash
cd C:\Dev\labartolalaravel
setup-local-assets.bat
```

### Linux/Mac:
```bash
cd /path/to/labartolalaravel
chmod +x setup-local-assets.sh
./setup-local-assets.sh
```

**Esto instalará:**
- Bootstrap 5.3.3 localmente
- Bootstrap Icons localmente
- Compilará todos los assets

---

## PASO 2: Verificar Instalación

Deberías ver en la consola:
```
✓ Bootstrap instalado correctamente
✓ Bootstrap Icons instalado correctamente
✓ Assets compilados en public/build
✓ INSTALACIÓN COMPLETADA
```

Si ves esto, **¡estás listo!** 🎉

---

## PASO 3: Levantar Docker

```bash
docker-compose up -d
```

---

## PASO 4: Verificar que funciona

Abre en el navegador:
```
http://localhost/carrito
```

**¿Qué deberías ver?**
- ✅ Página carga correctamente
- ✅ Bootstrap styles aplicados
- ✅ Iconos visibles
- ✅ Sin errores en consola
- ✅ Zero errores ERR_NAME_NOT_RESOLVED

---

## ⚠️ TROUBLESHOOTING RÁPIDO

### Error: "comando npm no encontrado"
**Solución:** Instalar Node.js
```bash
# Descargar de: https://nodejs.org/
# Version LTS recomendada: 20.x
```

### Error: "Vite manifest not found"
**Solución:**
```bash
npm run build
```

### Error: Puerto 5173 ocupado
**Solución:** Matar proceso
```bash
# Windows
netstat -ano | findstr :5173
taskkill /PID [número] /F

# Linux/Mac
lsof -ti:5173 | xargs kill -9
```

### Error: Docker no inicia
**Solución:**
```bash
# Verificar Docker Desktop está corriendo
# Reiniciar Docker
docker-compose down
docker-compose up -d --force-recreate
```

---

## 🔄 MODO DESARROLLO (Opcional)

Si quieres hot-reload mientras desarrollas:

**Terminal 1:**
```bash
npm run dev
```

**Terminal 2:**
```bash
docker-compose up -d
```

Ahora cada cambio en CSS/JS se refleja instantáneamente.

---

## ✅ CHECKLIST RÁPIDO

- [ ] `npm install` ejecutado sin errores
- [ ] `npm run build` completado exitosamente
- [ ] Carpeta `public/build` existe
- [ ] Carpeta `node_modules` existe
- [ ] Docker levantado con `docker-compose up -d`
- [ ] Navegador muestra `/carrito` correctamente
- [ ] No hay errores ERR_NAME_NOT_RESOLVED en consola

Si todos los checks están ✅, **el problema está resuelto**.

---

## 📞 ¿Aún tienes problemas?

1. Verifica logs de Docker:
```bash
docker-compose logs -f app
```

2. Verifica que Vite compiló correctamente:
```bash
ls -la public/build
# Deberías ver: manifest.json y archivos CSS/JS
```

3. Limpia caché del navegador:
```
Ctrl + Shift + Delete (Chrome)
Cmd + Shift + Delete (Mac)
```

---

## 🚀 SIGUIENTE PASO: Aplicar a otros proyectos

Una vez que La Bartola funcione correctamente, usa:

```bash
# Para nuevo proyecto
aido-setup.bat C:\Dev\nuevo-proyecto

# O copia manualmente:
# 1. package.json
# 2. vite.config.js
# 3. resources/css/app.css
# 4. resources/js/app.js
# 5. Ejecuta: npm install && npm run build
```

---

**TIEMPO TOTAL ESTIMADO:** 5-10 minutos

**¿Funcionó? ¡Perfecto! Ahora tienes un sistema escalable para tus 30 proyectos.**
