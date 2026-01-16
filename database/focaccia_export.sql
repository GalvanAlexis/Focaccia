-- Focaccia Database Export
-- Generated: 2026-01-15 21:50:51

-- Table: cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"));

-- Table: cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"));

-- Table: caja_chica
DROP TABLE IF EXISTS `caja_chica`;
CREATE TABLE "caja_chica" ("id" integer primary key autoincrement not null, "fecha" date not null, "hora" time not null, "concepto" varchar not null, "tipo" varchar check ("tipo" in ('entrada', 'salida')) not null, "monto" numeric not null default '0', "es_digital" tinyint(1) not null default '0', "user_id" integer, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete set null);

-- Data for table: caja_chica
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('1', '2026-01-07 00:00:00', '20:21:00', 'Pago de servicios - Luz', 'salida', '4723', '0', '1', '2026-01-07 21:49:38', '2026-01-07 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('2', '2026-01-06 00:00:00', '16:31:00', 'Venta bebidas', 'entrada', '34234', '1', '1', '2026-01-06 21:49:38', '2026-01-06 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('3', '2026-01-11 00:00:00', '20:34:00', 'Pago transferencia cliente', 'entrada', '13580', '0', '1', '2026-01-11 21:49:38', '2026-01-11 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('4', '2026-01-12 00:00:00', '15:46:00', 'Compra de packaging', 'salida', '23303', '0', '1', '2026-01-12 21:49:38', '2026-01-12 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('5', '2026-01-11 00:00:00', '19:27:00', 'Venta del día - Pizzas', 'entrada', '21199', '1', '1', '2026-01-11 21:49:38', '2026-01-11 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('6', '2026-01-11 00:00:00', '21:10:00', 'Pago de cliente - Delivery', 'entrada', '29436', '0', '1', '2026-01-11 21:49:38', '2026-01-11 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('7', '2026-01-14 00:00:00', '18:30:00', 'Delivery - Combustible', 'salida', '3197', '1', '1', '2026-01-14 21:49:38', '2026-01-14 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('8', '2026-01-10 00:00:00', '13:37:00', 'Pago transferencia cliente', 'entrada', '13840', '0', '1', '2026-01-10 21:49:38', '2026-01-10 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('9', '2026-01-15 00:00:00', '22:21:00', 'Pago de cliente - Delivery', 'entrada', '32991', '1', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('10', '2026-01-10 00:00:00', '12:02:00', 'Mantenimiento horno', 'salida', '22829', '0', '1', '2026-01-10 21:49:38', '2026-01-10 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('11', '2026-01-10 00:00:00', '10:15:00', 'Venta del día - Pizzas', 'entrada', '20808', '0', '1', '2026-01-10 21:49:38', '2026-01-10 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('12', '2026-01-12 00:00:00', '22:27:00', 'Venta del día - Empanadas', 'entrada', '7510', '0', '1', '2026-01-12 21:49:38', '2026-01-12 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('13', '2026-01-10 00:00:00', '11:27:00', 'Mantenimiento horno', 'salida', '12300', '0', '1', '2026-01-10 21:49:38', '2026-01-10 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('14', '2026-01-15 00:00:00', '18:16:00', 'Pago de cliente - Delivery', 'entrada', '9477', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('15', '2026-01-08 00:00:00', '21:23:00', 'Venta del día - Empanadas', 'entrada', '9059', '1', '1', '2026-01-08 21:49:38', '2026-01-08 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('16', '2026-01-07 00:00:00', '12:34:00', 'Compra de packaging', 'salida', '17535', '0', '1', '2026-01-07 21:49:38', '2026-01-07 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('17', '2026-01-10 00:00:00', '11:10:00', 'Venta mostrador', 'entrada', '32772', '0', '1', '2026-01-10 21:49:38', '2026-01-10 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('18', '2026-01-15 00:00:00', '12:25:00', 'Venta del día - Pizzas', 'entrada', '20651', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('19', '2026-01-12 00:00:00', '15:46:00', 'Sueldo empleado', 'salida', '19098', '1', '1', '2026-01-12 21:49:38', '2026-01-12 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('20', '2026-01-08 00:00:00', '15:17:00', 'Venta del día - Empanadas', 'entrada', '16027', '1', '1', '2026-01-08 21:49:38', '2026-01-08 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('21', '2026-01-10 00:00:00', '12:34:00', 'Pago pendiente recibido', 'entrada', '26956', '1', '1', '2026-01-10 21:49:38', '2026-01-10 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('22', '2026-01-08 00:00:00', '20:15:00', 'Compra de packaging', 'salida', '24916', '0', '1', '2026-01-08 21:49:38', '2026-01-08 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('23', '2026-01-07 00:00:00', '15:48:00', 'Venta del día - Empanadas', 'entrada', '29908', '0', '1', '2026-01-07 21:49:38', '2026-01-07 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('24', '2026-01-05 00:00:00', '17:57:00', 'Venta del día - Empanadas', 'entrada', '27421', '0', '1', '2026-01-05 21:49:38', '2026-01-05 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('25', '2026-01-08 00:00:00', '20:22:00', 'Mantenimiento horno', 'salida', '18585', '0', '1', '2026-01-08 21:49:38', '2026-01-08 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('26', '2026-01-09 00:00:00', '18:47:00', 'Venta del día - Pizzas', 'entrada', '18549', '1', '1', '2026-01-09 21:49:38', '2026-01-09 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('27', '2026-01-15 00:00:00', '19:37:00', 'Venta del día - Empanadas', 'entrada', '6274', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('28', '2026-01-07 00:00:00', '16:00:00', 'Compra de ingredientes - Tomates', 'salida', '20355', '1', '1', '2026-01-07 21:49:38', '2026-01-07 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('29', '2026-01-10 00:00:00', '17:38:00', 'Venta bebidas', 'entrada', '24433', '0', '1', '2026-01-10 21:49:38', '2026-01-10 21:49:38');
INSERT INTO `caja_chica` (`id`, `fecha`, `hora`, `concepto`, `tipo`, `monto`, `es_digital`, `user_id`, `created_at`, `updated_at`) VALUES ('30', '2026-01-14 00:00:00', '21:22:00', 'Venta mostrador', 'entrada', '30338', '0', '1', '2026-01-14 21:49:38', '2026-01-14 21:49:38');

-- Table: categorias
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE "categorias" ("id" integer primary key autoincrement not null, "nombre" varchar not null, "orden" integer not null default '0', "activa" tinyint(1) not null default '1', "created_at" datetime, "updated_at" datetime);

-- Data for table: categorias
INSERT INTO `categorias` (`id`, `nombre`, `orden`, `activa`, `created_at`, `updated_at`) VALUES ('1', 'Bebidas', '1', '1', '2026-01-15 21:49:37', '2026-01-15 21:49:37');
INSERT INTO `categorias` (`id`, `nombre`, `orden`, `activa`, `created_at`, `updated_at`) VALUES ('2', 'Empanadas', '2', '1', '2026-01-15 21:49:37', '2026-01-15 21:49:37');
INSERT INTO `categorias` (`id`, `nombre`, `orden`, `activa`, `created_at`, `updated_at`) VALUES ('3', 'Pizzas', '3', '1', '2026-01-15 21:49:37', '2026-01-15 21:49:37');
INSERT INTO `categorias` (`id`, `nombre`, `orden`, `activa`, `created_at`, `updated_at`) VALUES ('4', 'Tartas', '4', '1', '2026-01-15 21:49:37', '2026-01-15 21:49:37');
INSERT INTO `categorias` (`id`, `nombre`, `orden`, `activa`, `created_at`, `updated_at`) VALUES ('5', 'Postres', '5', '1', '2026-01-15 21:49:37', '2026-01-15 21:49:37');

-- Table: failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);

-- Table: job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"));

-- Table: jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);

-- Table: migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);

-- Data for table: migrations
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_01_04_015819_create_permission_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_01_04_015953_create_categorias_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_01_04_020001_create_platos_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_01_04_020002_create_pedidos_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_01_04_020003_create_caja_chica_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_01_04_020003_create_notificaciones_table', '1');

-- Table: model_has_permissions
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE "model_has_permissions" ("permission_id" integer not null, "model_type" varchar not null, "model_id" integer not null, foreign key("permission_id") references "permissions"("id") on delete cascade, primary key ("permission_id", "model_id", "model_type"));

-- Table: model_has_roles
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE "model_has_roles" ("role_id" integer not null, "model_type" varchar not null, "model_id" integer not null, foreign key("role_id") references "roles"("id") on delete cascade, primary key ("role_id", "model_id", "model_type"));

-- Data for table: model_has_roles
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES ('1', 'App\Models\User', '1');

-- Table: notificaciones
DROP TABLE IF EXISTS `notificaciones`;
CREATE TABLE "notificaciones" ("id" integer primary key autoincrement not null, "user_id" integer not null, "tipo" varchar not null, "titulo" varchar not null, "mensaje" text not null, "icono" varchar, "url" varchar, "leida" tinyint(1) not null default '0', "data" text, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete cascade);

-- Table: password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));

-- Table: pedidos
DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE "pedidos" ("id" integer primary key autoincrement not null, "usuario_id" integer, "plato_id" integer not null, "cantidad" integer not null default '1', "total" numeric not null, "estado" varchar not null default 'pendiente', "tipo_entrega" varchar, "direccion" text, "forma_pago" varchar, "notas" text, "created_at" datetime, "updated_at" datetime, foreign key("usuario_id") references "users"("id") on delete cascade, foreign key("plato_id") references "platos"("id") on delete cascade);

-- Data for table: pedidos
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('1', '1', '14', '4', '74000', 'pendiente', 'retiro', NULL, 'efectivo', 'Sin cebolla por favor', '2026-01-14 21:49:38', '2026-01-14 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('2', '1', '7', '2', '27600', 'cancelado', 'delivery', 'Av. Libertador 1234, Chascomús', 'transferencia', NULL, '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('3', '1', '2', '4', '4833.32', 'entregado', 'retiro', NULL, 'mercadopago', NULL, '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('4', '1', '1', '1', '1208.33', 'cancelado', 'retiro', NULL, 'mercadopago', 'Sin cebolla por favor', '2026-01-08 21:49:38', '2026-01-08 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('5', '1', '22', '3', '10800', 'confirmado', 'delivery', 'Av. Libertador 1234, Chascomús', 'transferencia', NULL, '2026-01-14 21:49:38', '2026-01-14 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('6', '1', '7', '4', '55200', 'confirmado', 'delivery', 'Bolivia 123, Chascomús', 'mercadopago', NULL, '2026-01-08 21:49:38', '2026-01-08 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('7', '1', '20', '4', '15200', 'cancelado', 'delivery', 'San Martín 890, Chascomús', 'transferencia', 'Sin cebolla por favor', '2026-01-13 21:49:38', '2026-01-13 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('8', '1', '8', '4', '62000', 'cancelado', 'delivery', 'San Martín 890, Chascomús', 'efectivo', NULL, '2026-01-10 21:49:38', '2026-01-10 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('9', '1', '13', '4', '64000', 'cancelado', 'delivery', 'Bolivia 123, Chascomús', 'transferencia', NULL, '2026-01-14 21:49:38', '2026-01-14 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('10', '1', '23', '1', '3000', 'pendiente', 'retiro', NULL, 'mercadopago', 'Sin cebolla por favor', '2026-01-14 21:49:38', '2026-01-14 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('11', '1', '24', '4', '7200', 'pendiente', 'retiro', NULL, 'efectivo', NULL, '2026-01-13 21:49:38', '2026-01-13 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('12', '1', '1', '1', '1208.33', 'en_preparacion', 'delivery', 'Calle Mitre 567, Chascomús', 'mercadopago', NULL, '2026-01-09 21:49:38', '2026-01-09 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('13', '1', '16', '3', '66000', 'confirmado', 'delivery', 'Calle Mitre 567, Chascomús', 'efectivo', 'Sin cebolla por favor', '2026-01-08 21:49:38', '2026-01-08 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('14', '1', '14', '4', '74000', 'pendiente', 'retiro', NULL, 'efectivo', NULL, '2026-01-10 21:49:38', '2026-01-10 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('15', '1', '21', '3', '10800', 'confirmado', 'retiro', NULL, 'efectivo', NULL, '2026-01-09 21:49:38', '2026-01-09 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('16', '1', '15', '1', '13500', 'entregado', 'retiro', NULL, 'efectivo', 'Sin cebolla por favor', '2026-01-12 21:49:38', '2026-01-12 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('17', '1', '11', '3', '38400', 'confirmado', 'delivery', 'Bolivia 123, Chascomús', 'transferencia', NULL, '2026-01-11 21:49:38', '2026-01-11 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('18', '1', '8', '2', '31000', 'confirmado', 'retiro', NULL, 'mercadopago', NULL, '2026-01-14 21:49:38', '2026-01-14 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('19', '1', '3', '3', '3624.99', 'entregado', 'delivery', 'Rivadavia 456, Chascomús', 'transferencia', 'Sin cebolla por favor', '2026-01-08 21:49:38', '2026-01-08 21:49:38');
INSERT INTO `pedidos` (`id`, `usuario_id`, `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `created_at`, `updated_at`) VALUES ('20', '1', '19', '2', '31000', 'en_preparacion', 'retiro', NULL, 'efectivo', NULL, '2026-01-08 21:49:38', '2026-01-08 21:49:38');

-- Table: permissions
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE "permissions" ("id" integer primary key autoincrement not null, "name" varchar not null, "guard_name" varchar not null, "created_at" datetime, "updated_at" datetime);

-- Table: platos
DROP TABLE IF EXISTS `platos`;
CREATE TABLE "platos" ("id" integer primary key autoincrement not null, "nombre" varchar not null, "descripcion" text, "precio" numeric not null, "categoria" varchar, "disponible" tinyint(1) not null default '1', "imagen" varchar, "stock" integer not null default '0', "stock_ilimitado" tinyint(1) not null default '1', "created_at" datetime, "updated_at" datetime);

-- Data for table: platos
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('1', 'Empanada Criolla', 'Unidad tradicional de carne', '1208.33', 'Empanadas', '1', 'empanada_carne.png', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('2', 'Empanada Capresse', 'Unidad de tomate, queso y albahaca', '1208.33', 'Empanadas', '1', 'caprese empa.avif', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('3', 'Empanada Pollo', 'Unidad de pollo con verdeo', '1208.33', 'Empanadas', '1', 'empanada pollo.jpg', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('4', 'Empanada Jamón y Queso', 'Unidad clásica', '1208.33', 'Empanadas', '1', 'empanada_jyq.jpg', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('5', 'Empanada Humita', 'Unidad de choclo y queso', '1208.33', 'Empanadas', '1', 'empanada_humita.jpg', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('6', 'Pizza Muzzarella', 'Doble muzzarella, aceitunas y orégano', '12500', 'Pizzas', '1', 'pizza_muzzarella.png', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('7', 'Pizza Italiana', 'Salsa, muzzarella y condimento italiano', '13800', 'Pizzas', '1', 'pizza_italiana.png', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('8', 'Pizza Especial', 'Jamón, morrón y aceitunas', '15500', 'Pizzas', '1', 'pizza-especial-jamon.jpg', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('9', 'Pizza Provolone', 'Muzzarella y queso provolone gratinado', '16500', 'Pizzas', '1', 'pizza_muzzarella.png', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('10', 'Pizza Napolitana', 'Tomate natural, ajo y albahaca', '14200', 'Pizzas', '1', 'pizza tomate y albaca.webp', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('11', 'Pizza Ajillo', 'Salsa roja y mucho ajo frito', '12800', 'Pizzas', '1', 'pizza_cat.png', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('12', 'Pizza Calabresa', 'Muzzarella y longaniza calabresa', '17200', 'Pizzas', '1', 'pizza_italiana.png', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('13', 'Pizza de Verdura', 'Acelga fresca y salsa blanca', '16000', 'Pizzas', '1', 'pizza_verdura.png', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('14', 'Pizza 4 Quesos', 'El mejor mix de quesos premium', '18500', 'Pizzas', '1', 'pizza_verdura.png', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('15', 'Pizza Fugazzeta', 'Cebolla blanca y muzzarella', '13500', 'Pizzas', '1', 'fugazza-argentina_web.jpg.webp', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('16', 'Pizza Focaccia', 'Especialidad de la casa con masa focaccia', '22000', 'Pizzas', '1', 'pizza_cat.png', '0', '1', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('17', 'Tarta de Verdura XL', 'Gigante de acelga, huevo y queso', '12500', 'Tartas', '1', 'tarta_verdura.png', '10', '0', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('18', 'Tarta Capresse XL', 'Tomate cherry, muzzarella y albahaca', '13800', 'Tartas', '1', 'tarta_caprese.png', '10', '0', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('19', 'Tarta Completa', 'Jamón, queso, huevo y vegetales', '15500', 'Tartas', '1', 'tarta_completa.png', '10', '0', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('20', 'Coca Cola 1.5L', 'Gaseosa original fría', '3800', 'Bebidas', '1', 'cocacola.png', '100', '0', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('21', 'Sprite 1.5L', 'Lima limón súper refrescante', '3600', 'Bebidas', '1', 'Gaseosa-Sprite-2-25-Lt-1-1183.webp', '100', '0', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('22', 'Seven Up 1.5L', 'Sabor a lima-limón clásico', '3600', 'Bebidas', '1', 'bebidas_cat.png', '100', '0', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('23', 'Cerveza Imperial 473ml', 'Lata bien helada', '3000', 'Bebidas', '1', 'Cerveza-Golden-Imperial-Lata-473.jpg', '200', '0', '2026-01-15 21:49:38', '2026-01-15 21:49:38');
INSERT INTO `platos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `created_at`, `updated_at`) VALUES ('24', 'Agua Mineral Villavicencio 500ml', 'Sin gas, directo de manantial', '1800', 'Bebidas', '1', 'bebidas_cat.png', '50', '0', '2026-01-15 21:49:38', '2026-01-15 21:49:38');

-- Table: role_has_permissions
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE "role_has_permissions" ("permission_id" integer not null, "role_id" integer not null, foreign key("permission_id") references "permissions"("id") on delete cascade, foreign key("role_id") references "roles"("id") on delete cascade, primary key ("permission_id", "role_id"));

-- Table: roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE "roles" ("id" integer primary key autoincrement not null, "name" varchar not null, "guard_name" varchar not null, "created_at" datetime, "updated_at" datetime);

-- Data for table: roles
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('1', 'admin', 'web', '2026-01-15 21:49:37', '2026-01-15 21:49:37');
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('2', 'vendedor', 'web', '2026-01-15 21:49:37', '2026-01-15 21:49:37');
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('3', 'cliente', 'web', '2026-01-15 21:49:37', '2026-01-15 21:49:37');

-- Table: sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));

-- Table: users
DROP TABLE IF EXISTS `users`;
CREATE TABLE "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime);

-- Data for table: users
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Administrador', 'admin@focaccia.com', '2026-01-15 21:49:38', '$2y$12$8BrCu2Ea2UIyJZhKFVSvPOEgyDQIZebxMkjF74pVnAGVY2akSx32O', NULL, '2026-01-15 21:49:38', '2026-01-15 21:49:38');

