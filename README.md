# 🧠 Intranet CCIA - Gestión Interna de Departamento

Proyecto desarrollado como Trabajo de Fin de Grado (TFG) en Ingeniería Informática, orientado a modernizar y reemplazar una aplicación obsoleta de gestión administrativa interna del Departamento de Ciencias de la Computación e Inteligencia Artificial (CCIA) de la Universidad de Granada.

## 🎯 Objetivo

Desarrollar desde cero una **aplicación web moderna, segura, escalable y mantenible**, que reemplace un sistema legacy en PHP puro, utilizando **Laravel 11**, **Blade**, **TailwindCSS** y una **base de datos MySQL optimizada**. El sistema gestiona múltiples procesos administrativos no triviales como asignaciones docentes, tutorías, reservas de salas, y solicitudes académicas.

## 🏗️ Arquitectura del Sistema

La aplicación está diseñada en base al patrón **MVC (Modelo-Vista-Controlador)** y organizada en **cuatro capas**:

- **Frontend (Blade + TailwindCSS + Alpine.js)**: Interfaces accesibles y adaptativas.
- **Backend (Laravel 11)**: Lógica de negocio modular y basada en controladores, servicios y middleware.
- **Base de Datos (MySQL)**: Diseño normalizado, relacional y optimizado para operaciones complejas.
- **Cliente (navegador)**: Accede vía HTTP a través de vistas renderizadas en el servidor.

## 🧩 Estructura del Proyecto

```
├── app/                  # Controladores, modelos, servicios, políticas
├── resources/views/      # Vistas Blade organizadas por módulos
├── database/migrations/  # Migraciones y seeds
├── routes/web.php        # Rutas agrupadas por roles
├── public/               # Recursos públicos (JS/CSS/Imágenes)
├── tests/                # Pruebas unitarias y E2E
├── .env.example          # Configuración de entorno
└── README.md             # Este archivo 😄
```

## 🛠️ Stack Tecnológico

- **Framework principal:** Laravel 11
- **Frontend:** Blade, TailwindCSS, Alpine.js, Flowbite
- **Backend:** Laravel MVC + Middleware + Policies + Eloquent ORM
- **Base de datos:** MySQL (multi-entorno con `mysql` y `mysql_proximo`)
- **DevOps:** Vite (compilación), Git, migraciones versionadas, scripts de instalación
- **Pruebas:** Testing E2E con entorno virtualizado
- **Metodología:** Kanban con prototipos incrementales

## 🔐 Seguridad

- Middleware para control de acceso por rol
- Autenticación robusta con Laravel Breeze
- Protección contra CSRF, XSS y SQL Injection
- Políticas de seguridad por rutas y modelos
- Cierre automático por inactividad

## 🧪 Pruebas y Validación

- Sistema testado con casos reales del departamento
- Pruebas end-to-end sobre máquina virtual aislada
- Feedback continuo con el usuario final (mi tutor)

## 📦 Funcionalidades Principales

- Gestión de usuarios con roles personalizados
- Gestión de asignaturas, asignaciones y grupos
- Sistema de tutorías por horarios semanales
- Control y visualización de plazos administrativos
- Módulo de reservas de salas con validación y notificaciones
- Sistema de solicitudes académicas con flujos de aprobación
- Doble base de datos para cursos actuales y próximos

## 🗂️ Diseño basado en roles

Cada funcionalidad está segmentada por perfil de usuario:

| Rol           | Funcionalidades disponibles |
|---------------|-----------------------------|
| Admin         | Gestión total del sistema   |
| Profesor      | Acceso a asignaciones, tutorías, solicitudes |
| Becario       | Funcionalidades limitadas por asignación |
| Invitado      | Acceso parcial y controlado |
| Estudiante    | Consulta limitada de asignaturas |

## 🚀 Instalación rápida

```bash
git clone https://github.com/juanhdezz/tfg_gestion_ccia
cd tfg_gestion_ccia
cp .env.example .env
composer install
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

## 📚 Documentación

Todo el desarrollo está documentado, incluyendo:

- Memoria completa del TFG (`docs/Memoria_TFG.pdf`)
- Bocetos de interfaz y diagramas UML
- Scripts de sanitización de datos
- Guía de despliegue y pruebas

## 🧠 Aprendizajes clave

> “El mayor reto fue transformar procesos administrativos complejos, sin código previo, en funcionalidades sólidas y mantenibles, aprendiendo Laravel desde cero. Si sobreviví a eso, ¡puedo con cualquier monolito!”

## 🧭 Futuras mejoras

- Integración con APIs externas para docencia y bibliotecas
- Incorporación de analítica y dashboards predictivos
- Soporte multilingüe (ES/EN)
- Migración a arquitectura de microservicios

---

## 👨‍💻 Autor

**Juan Ricardo Hernández Sánchez-Agesta**  
Estudiante del Doble Grado en Ingeniería Informática y ADE  
Universidad de Granada – ETSIIT  
[LinkedIn](https://www.linkedin.com/in/juan-hernandez-sag/) | [Portfolio](https://portfolio-web-juanhdezzs-projects.vercel.app/)

---

## 📝 Licencia

Este proyecto se publica bajo la licencia MIT. Si lo usas, cita al autor. Si mejoras algo, comparte. Y si lo rompes… haz una issue primero 😉.



























## 🚀 Instrucciones de Instalación de la Aplicación

### 📌 Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes programas:

- [XAMPP](https://www.apachefriends.org/es/download.html) 🖥️
- [Composer](https://getcomposer.org/Composer-Setup.exe) 📦
- [Node.js](https://nodejs.org/) 🌐
- [Git](https://git-scm.com/downloads) 🛠️

---

### 🏗️ Instalación Paso a Paso

#### 1️⃣ Instalar XAMPP

1. Descarga e instala **XAMPP**.
2. Asegúrate de tener **PHP 8.2 o superior**.
3. Ejecuta **XAMPP** y enciende los siguientes servicios:
   - **Apache** ▶️ Start
   - **MySQL** ▶️ Start
4. Habilita la extensión ZIP:
   - Abre `php.ini` desde la configuración de XAMPP.
   - Busca la línea `extension=zip` y descoméntala (quita el `;` al inicio).
   - Reinicia Apache (Stop → Start).

---

#### 2️⃣ Instalar Composer

1. Descarga el instalador de Composer.
2. Instálalo con la configuración predeterminada.
3. Verifica la instalación ejecutando en la terminal:
   ```sh
   composer --version
   ```

---

#### 3️⃣ Instalar Node.js

1. Descarga e instala **Node.js**.
2. Confirma que está correctamente instalado con:
   ```sh
   node -v
   npm -v
   ```

---

#### 4️⃣ Instalar Git y Clonar el Proyecto

1. Descarga e instala **Git**.
2. Configura tu usuario:
   ```sh
   git config --global user.name "Tu Nombre"
   git config --global user.email "tuemail@example.com"
   ```
3. Clona el repositorio:
   ```sh
   git clone git@github.com:juanhdezz/tfg_gestion_ccia.git
   ```
4. Mueve el proyecto a la carpeta `C:\xampp\htdocs\laravel` (créala si no existe).

---

### 📂 Configuración de la Base de Datos

1. Accede a **phpMyAdmin**.
2. Crea una nueva base de datos llamada **`tfg_gestion_ccia`**.
3. Ve a la pestaña **Importar** y carga el archivo `.sql` proporcionado.

---

### 🔧 Configuración del Proyecto en VSCode

1. Abre **Visual Studio Code** y accede al directorio del proyecto.
2. Instala las dependencias de Vite:
   ```sh
   npm install vite
   ```
3. Compila el proyecto:
   ```sh
   npm run build
   ```
4. Inicia el servidor de desarrollo:
   ```sh
   npm run dev
   ```

---

### 🌍 Acceso a la Aplicación

1. Abre tu navegador y ve a:
   ```
   localhost/laravel/tfg_gestion_ccia/public
   ```
2. Inicia sesión con las siguientes credenciales:
   - **Usuario**: `admin`
   - **Contraseña**: `admin`

---

✅ **¡Listo! Ahora puedes comenzar a usar la aplicación.** 🚀
