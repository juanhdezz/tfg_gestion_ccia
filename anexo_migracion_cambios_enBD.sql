-- =====================================================
-- ANEXO DE MIGRACIÓN - SENTENCIAS SQL
-- Fecha: 22 de Mayo de 2025
-- Descripción: Sentencias SQL para migrar la base de datos
-- a la versión definitiva con todos los cambios implementados
-- =====================================================

-- EJECUTAR ESTAS SENTENCIAS EN ORDEN SECUENCIAL EN phpMyAdmin

-- =====================================================
-- 1. INFRAESTRUCTURA LARAVEL (24/01/2025)
-- =====================================================

-- Tabla de sesiones para Laravel
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de cache para Laravel
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de locks de cache
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de trabajos
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de lotes de trabajos
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de migraciones
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de trabajos fallidos
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Añadir remember_token a tabla usuarios
ALTER TABLE `usuarios` ADD COLUMN `remember_token` varchar(100) DEFAULT NULL AFTER `password`;

-- =====================================================
-- 2. SISTEMA DE ROLES Y PERMISOS SPATIE (28/01/2025)
-- =====================================================

-- Tabla de roles
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de permisos
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de relación modelo-permisos
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de relación modelo-roles
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de relación rol-permisos
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-01-28 11:42:02', '2025-01-28 11:42:02'),
(4, 'secretario', 'web', '2025-04-22 16:18:01', '2025-04-22 16:18:01'),
(5, 'subdirectorDocente', 'web', '2025-04-22 16:18:28', '2025-04-22 16:18:28'),
(6, 'gestorOrdenacionDocente', 'web', '2025-04-22 16:18:52', '2025-04-22 16:18:52'),
(7, 'contratado', 'web', '2025-04-22 16:19:10', '2025-04-22 16:19:10'),
(8, 'general', 'web', '2025-04-22 16:19:23', '2025-04-22 16:19:23');


-- =====================================================
-- 3. ASIGNATURAS EQUIVALENTES (11/02/2025)
-- =====================================================

CREATE TABLE `asignaturas_equivalentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asignatura_id` varchar(8) NOT NULL,
  `equivalente_id` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_asignatura_id` (`asignatura_id`),
  KEY `idx_equivalente_id` (`equivalente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- =====================================================
-- 4. ACTUALIZACIÓN TIPO USUARIO (13/02/2025)
-- =====================================================

-- Actualizar registros existentes de 'Becario' a 'Contratado'
UPDATE `usuarios` SET `tipo_usuario` = 'Contratado' WHERE `tipo_usuario` = 'Becario';

-- Modificar el ENUM para reemplazar 'Becario' por 'Contratado'
ALTER TABLE `usuarios` MODIFY COLUMN `tipo_usuario` enum('Administrador','Administrativo','Profesor','Contratado','Invitado', 'InvitadoP','NoAccess', 'Profesor externo', 'Estudiante') DEFAULT NULL;

-- =====================================================
-- 5. GRUPOS TEORIA-PRACTICA Y CAMPUS (21/02/2025)
-- =====================================================

-- Tabla grupo_teoria_practica
CREATE TABLE `grupo_teoria_practica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_asignatura` varchar(8) NOT NULL,
  `grupo_teoria` int(11) DEFAULT NULL,
  `grupo_practica` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_asignatura` (`id_asignatura`),
  KEY `idx_grupo_teoria` (`grupo_teoria`),
  KEY `idx_grupo_practica` (`grupo_practica`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- Tabla campus
CREATE TABLE `campus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- 6. MODIFICACIÓN TIPO DOCENCIA (25/02/2025)
-- =====================================================

-- Modificar el tipo de dato en usuario_asignatura
ALTER TABLE `usuario_asignatura` MODIFY COLUMN `tipo` enum('Teoría','Prácticas') DEFAULT NULL;

-- =====================================================
-- 7. CONFIGURACIÓN ORDENACIÓN (22/05/2025)
-- =====================================================

-- Crear tabla de configuración
CREATE TABLE `configuracion_ordenacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ultima_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave_unique` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar datos de configuración inicial
INSERT INTO `configuracion_ordenacion` (`id`, `clave`, `valor`, `descripcion`, `ultima_modificacion`) VALUES
(1, 'creditos_menos_permitidos', '0.5', 'Créditos por debajo de la carga que permiten pasar turno', '2025-05-21 10:24:53'),
(2, 'porcentaje_limite_menor', '25', 'Porcentaje límite menor para compensaciones (tradicionalmente 25%)', '2025-05-07 10:55:18'),
(3, 'porcentaje_limite_mayor', '50', 'Porcentaje límite mayor para compensaciones (tradicionalmente 50%)', '2025-05-07 10:55:18'),
(4, 'identificador_tfm', '9999601', 'Texto usado para identificar asignaturas de TFM', '2025-05-13 08:05:11');

-- =====================================================
-- 8. ELIMINACIÓN DE ATRIBUTOS SENSIBLES (22/05/2025)
-- =====================================================

-- IMPORTANTE: Realizar backup antes de ejecutar estas sentencias
-- Estas operaciones son IRREVERSIBLES

-- Eliminar columnas sensibles de la tabla asignatura
ALTER TABLE `asignatura` DROP COLUMN IF EXISTS `web_decsai`;
ALTER TABLE `asignatura` DROP COLUMN IF EXISTS `enlace_temario`;
ALTER TABLE `asignatura` DROP COLUMN IF EXISTS `temario_teoria`;
ALTER TABLE `asignatura` DROP COLUMN IF EXISTS `temario_practicas`;
ALTER TABLE `asignatura` DROP COLUMN IF EXISTS `bibliografia`;
ALTER TABLE `asignatura` DROP COLUMN IF EXISTS `evaluacion`;
ALTER TABLE `asignatura` DROP COLUMN IF EXISTS `recomendaciones`;

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================

-- Verificar que todas las tablas se han creado correctamente
SHOW TABLES LIKE 'sessions';
SHOW TABLES LIKE 'roles';
SHOW TABLES LIKE 'asignaturas_equivalentes';
SHOW TABLES LIKE 'grupo_teoria_practica';
SHOW TABLES LIKE 'campus';
SHOW TABLES LIKE 'configuracion_ordenacion';

-- Verificar la estructura de la tabla usuarios
DESCRIBE usuarios;

-- Verificar la estructura de la tabla usuario_asignatura
DESCRIBE usuario_asignatura;

-- Verificar datos de configuración
SELECT * FROM configuracion_ordenacion;

-- =====================================================
-- INSTRUCCIONES ADICIONALES
-- =====================================================

/*
NOTAS IMPORTANTES PARA LA IMPLEMENTACIÓN:

1. Ejecutar las sentencias en el orden mostrado
2. Realizar backup completo de la base de datos antes de ejecutar
3. Verificar que no existen conflictos con datos existentes
4. Las operaciones de eliminación de columnas son irreversibles
5. Verificar permisos de usuario de base de datos antes de ejecutar
6. Probar en entorno de desarrollo antes de aplicar en producción

DESPUÉS DE LA MIGRACIÓN:
- Configurar roles y permisos según las necesidades del sistema
- Poblar la tabla campus con los campus correspondientes
- Ajustar los valores de configuración según los requerimientos específicos
- Verificar el funcionamiento de todas las funcionalidades

CONTACTO:
En caso de dudas o problemas durante la migración, contactar con el equipo de desarrollo.
*/