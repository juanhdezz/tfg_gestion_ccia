# 🚀 Instrucciones de Instalación de la Aplicación

## 📌 Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes programas:

- [XAMPP](https://www.apachefriends.org/es/download.html) 🖥️
- [Composer](https://getcomposer.org/Composer-Setup.exe) 📦
- [Node.js](https://nodejs.org/) 🌐
- [Git](https://git-scm.com/downloads) 🛠️

---

## 🏗️ Instalación Paso a Paso

### 1️⃣ Instalar XAMPP

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

### 2️⃣ Instalar Composer

1. Descarga el instalador de Composer.
2. Instálalo con la configuración predeterminada.
3. Verifica la instalación ejecutando en la terminal:
   ```sh
   composer --version
   ```

---

### 3️⃣ Instalar Node.js

1. Descarga e instala **Node.js**.
2. Confirma que está correctamente instalado con:
   ```sh
   node -v
   npm -v
   ```

---

### 4️⃣ Instalar Git y Clonar el Proyecto

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

## 📂 Configuración de la Base de Datos

1. Accede a **phpMyAdmin**.
2. Crea una nueva base de datos llamada **`tfg_gestion_ccia`**.
3. Ve a la pestaña **Importar** y carga el archivo `.sql` proporcionado.

---

## 🔧 Configuración del Proyecto en VSCode

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

## 🌍 Acceso a la Aplicación

1. Abre tu navegador y ve a:
   ```
   localhost/laravel/tfg_gestion_ccia/public
   ```
2. Inicia sesión con las siguientes credenciales:
   - **Usuario**: `admin`
   - **Contraseña**: `admin`

---

✅ **¡Listo! Ahora puedes comenzar a usar la aplicación.** 🚀
