# La Bartola - Laravel 12

Aplicación migrada de CodeIgniter 4 a Laravel 12 para gestión de pedidos, menú y caja chica.

## 🚀 Inicio Rápido

### 1. Iniciar Base de Datos

```bash
cd C:\Dev\labartola
docker-compose up -d mysql
```

### 2. Iniciar Servidor Laravel

```bash
cd C:\Dev\labartolalaravel
php artisan serve
```

### 3. Acceder

- **Home:** http://localhost:8000
- **Admin:** http://localhost:8000/login
  - `admin@labartola.com` / `admin123`

---

## 📊 Base de Datos

MySQL 8.0 en puerto **3307**

```env
DB_PORT=3307
DB_DATABASE=labartola
DB_USERNAME=root
DB_PASSWORD=root_password_2024
```

---

## 🔐 Roles

- **Admin:** Acceso total
- **Vendedor:** Menú y pedidos
- **Cliente:** Ver pedidos

---

## ✨ Funcionalidades

- Pedidos públicos (sin login)
- Validación de stock en tiempo real
- Integración WhatsApp
- Auto-descuento de stock
- Caja chica automática
- Notificaciones

---

Ver `MIGRACION_COMPLETA.md` para documentación detallada.
