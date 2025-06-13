# 📚 Trabajo Final de Grado en Ingeniería Informática - Desarrollo de aplicación web para la gestión Interna del departamento de Ciencias de la Computación e Inteligencia Artificial

## Resumen del TFG

**Título:** Desarrollo de aplicación web para la gestión Interna del departamento de Ciencias de la Computación e Inteligencia Artificial  
**Autor:** Juan Ricardo Hernández Sánchez-Agesta  
**Director:** Miguel García Silvente

**Contexto:**  
El Departamento de Ciencias de la Computación e Inteligencia Artificial (CCIA) de la Universidad de Granada utilizaba una herramienta obsoleta, desarrollada en PHP puro, difícil de mantener y escalar. La falta de acceso al código fuente hacía inviable su migración directa. Este proyecto reconstruye desde cero la aplicación para modernizarla y adaptarla a las necesidades actuales.

**Objetivos:**
- Sustituir una aplicación heredada por un sistema moderno basado en Laravel 11 con MVC.
- Rediseñar la base de datos y la interfaz de usuario.
- Implementar control de acceso por roles y permisos.
- Garantizar escalabilidad, accesibilidad y seguridad.
- Aplicar una metodología ágil Kanban para un desarrollo iterativo e incremental.

**Metodología:**  
Desarrollo web con Laravel 11, Blade, TailwindCSS y Alpine.js. Se adoptó una arquitectura MVC en tres capas, con control de versiones Git y gestión visual de tareas mediante Kanban. El sistema se construyó de forma iterativa con entregas frecuentes y validación continua mediante reuniones con el tutor.

**Resultados:**  
- Aplicación modular, mantenible y escalable adaptada a los flujos reales del departamento.
- Mejora significativa en la experiencia de usuario y en la organización de los procesos administrativos.
- Base de datos optimizada y segura.
- Documentación técnica detallada y sistema preparado para ampliaciones futuras.

**Conclusiones:**  
El nuevo sistema sustituye eficazmente al anterior, solucionando sus limitaciones técnicas y funcionales. Se recomienda como futuras líneas de trabajo la integración de servicios de inteligencia artificial para automatizar tareas administrativas, y mejoras en la experiencia de usuario mediante interfaces más inteligentes y personalizadas.


## Objetivo

Desarrollar desde cero una **aplicación web moderna, segura, escalable y mantenible**, que reemplace un sistema legacy en PHP puro, utilizando **Laravel 11**, **Blade**, **TailwindCSS** y una **base de datos MySQL optimizada**. El sistema gestiona múltiples procesos administrativos no triviales como asignaciones docentes, tutorías, reservas de salas, y solicitudes académicas.

## Arquitectura del Sistema

La aplicación está diseñada en base al patrón **MVC (Modelo-Vista-Controlador)** y organizada en **cuatro capas**:

- **Frontend (Blade + TailwindCSS + Alpine.js)**: Interfaces accesibles y adaptativas.
- **Backend (Laravel 11)**: Lógica de negocio modular y basada en controladores, servicios y middleware.
- **Base de Datos (MySQL)**: Diseño normalizado, relacional y optimizado para operaciones complejas.
- **Cliente (navegador)**: Accede vía HTTP a través de vistas renderizadas en el servidor.

## Estructura del Proyecto

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
- Gestión de despachos
- Gestión de asignaciones
- Sistema de tutorías con alta personalización 
- Página de configuración de ciertos parámetros
- Gestión de proyectos de investigación (Operaciones CRUD + Control de compensaciones y distribución de créditos)
- Control y visualización de plazos administrativos que habilitan funcionalidades
- Módulo de reservas de salas con validación y notificaciones por correo electrónico
- Gestión de salas para las reservas
- Sistema de solicitudes de libros con cargo a múltiples (limitados) motivos académicos con flujos de aprobación estrictos y porocedurales
- Gestión doble de las bases de datos para cursos actuales y próximos

## 🗂️ Diseño basado en roles

Cada funcionalidad está segmentada por perfil de usuario:

| Rol           | Funcionalidades disponibles |
|---------------|-----------------------------|
| Admin         | Gestión total del sistema   |
| Secretario      | Gestión total del CURSO ACTUAL |
| SubdirectorDocente       | Funcionalidades relacionadas con la ordenación docente |
| GestorOrdenacionDocente      | Funcionalidades correctivas relacionadas con la ordenación docente |
| Contratado    | Acceso a información personal (Asignaturas, proyectos, etc.) |
| General    | Consulta limitada a definir por el administrador en cada momento |



## 📚 Documentación

Todo el desarrollo está documentado, incluyendo:

- Memoria completa del TFG (`docs/Memoria_TFG.pdf`)
- Bocetos de interfaz y diagramas UML
- Scripts de sanitización de datos
- Guía de despliegue y pruebas



## Futuras mejoras

- Integración con APIs externas para docencia , bibliotecas y plataformas
- Incorporación de analítica y dashboards predictivos
- Implementación de sistema de elección de docencia basados en algoritmos de ML
- Soporte multilingüe (ES/EN)

---





























## Instrucciones de Instalación de la Aplicación

### Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes programas:

- [XAMPP](https://www.apachefriends.org/es/download.html) 🖥️
- [Composer](https://getcomposer.org/Composer-Setup.exe) 📦
- [Node.js](https://nodejs.org/) 🌐
- [Git](https://git-scm.com/downloads) 🛠️

---

### Instalación Paso a Paso

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
