# Sistema de Reservas de Canchas - Laravel

Sistema de gestión de reservas de canchas de fútbol con autenticación personalizada usando Guard y Laravel Socialite.

## 👥 Equipo
- Oscar Enrique Rodriguez Rangel
- [Nombre de tu compañero]

## 🚀 Requisitos

- PHP 8.2+
- Composer 2.x
- MySQL 5.7+
- Node.js 18+ y NPM
- XAMPP (recomendado)

## 📦 Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/alfha1011/Aplicaciones-Web.git
cd Aplicaciones-Web
```

### 2. Instalar dependencias de PHP
```bash
composer install
```

### 3. Instalar dependencias de Node.js
```bash
npm install
```

### 4. Configurar variables de entorno
```bash
cp .env.example .env
```

Edita el archivo `.env` con tus credenciales:
```env
DB_DATABASE=reservas_canchas
DB_USERNAME=root
DB_PASSWORD=

GOOGLE_CLIENT_ID=tu_client_id
GOOGLE_CLIENT_SECRET=tu_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

### 5. Generar key de la aplicación
```bash
php artisan key:generate
```

### 6. Crear la base de datos

En phpMyAdmin, crea una base de datos llamada: `reservas_canchas`

### 7. Importar la estructura

Importa el archivo `database/reservas_canchas.sql` en phpMyAdmin

### 8. Compilar assets (en una terminal)
```bash
npm run dev
```

**IMPORTANTE:** Deja esta terminal corriendo

### 9. Iniciar el servidor (en otra terminal)
```bash
php artisan serve
```

### 10. Acceder al sistema

Abre tu navegador en: `http://127.0.0.1:8000`

## 🔑 Credenciales de prueba

### Administrador principal:
- **Email:** `admin@canchas.com`
- **Password:** `password`

### Cuenta de Oscar:
- **Email:** `2124100008@soy.utj.edu.mx`
- **Password:** `123456`

## 📋 Características principales

### Autenticación
- ✅ Sistema de login manual con guard personalizado `admin`
- ✅ Autenticación con Google (Laravel Socialite)
- ✅ Validación de estado activo del administrador
- ✅ Protección de rutas con middleware `auth:admin`
- ✅ Manejo de errores detallado
- ✅ Cierre de sesión seguro con invalidación de tokens

### Gestión
- ✅ CRUD completo de Administradores
- ✅ CRUD completo de Clientes
- ✅ CRUD completo de Canchas
- ✅ Dashboard con estadísticas en tiempo real
- ✅ Contraseñas hasheadas con Bcrypt

### Base de datos
- Tabla `administradores` con campo `activo` para validación
- Passwords encriptados con algoritmo Bcrypt
- Guard personalizado conectado a tabla `administradores`

## 🛠️ Tecnologías utilizadas

- **Backend:** Laravel 10.x
- **Frontend:** Tailwind CSS + Vite
- **Base de datos:** MySQL
- **Autenticación OAuth:** Laravel Socialite (Google)

## 📁 Estructura importante
```
app/
├── Http/
│   └── Controllers/
│       └── Admin/
│           └── LoginController.php    # Controlador de autenticación
├── Models/
│   └── Administrador.php              # Modelo con Authenticatable
config/
└── auth.php                           # Guards y providers personalizados
routes/
└── web.php                           # Rutas protegidas con auth:admin
resources/
└── views/
    ├── admin/
    │   └── dashboard.blade.php       # Dashboard principal
    └── auth/
        └── login.blade.php           # Formulario de login
```

## ⚙️ Configuración del Guard Admin

El sistema usa un guard personalizado llamado `admin` configurado en `config/auth.php`:
```php
'guards' => [
    'admin' => [
        'driver' => 'session',
        'provider' => 'administradores',
    ],
],

'providers' => [
    'administradores' => [
        'driver' => 'eloquent',
        'model' => App\Models\Administrador::class,
    ],
],
```

## 🔧 Comandos útiles

### Limpiar cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Ver rutas registradas
```bash
php artisan route:list
```

### Crear nuevo administrador (Tinker)
```bash
php artisan tinker
```
```php
\App\Models\Administrador::create([
    'nombre' => 'Nombre',
    'apellido' => 'Apellido',
    'email' => 'email@example.com',
    'password' => bcrypt('password'),
    'telefono' => '1234567890',
    'activo' => 1
]);
exit
```

## 📝 Notas importantes

- ⚠️ Nunca subas el archivo `.env` al repositorio
- ⚠️ Las contraseñas deben estar siempre hasheadas con `bcrypt()` o `Hash::make()`
- ⚠️ Solo administradores con `activo = 1` pueden iniciar sesión
- ⚠️ Mantén actualizadas tus dependencias con `composer update` y `npm update`

## 🤝 Colaboración

Para trabajar en equipo:

### Descargar cambios del compañero:
```bash
git pull
```

### Subir tus cambios:
```bash
git add .
git commit -m "Descripción de los cambios"
git push
```

### Si hay conflictos:
Git te indicará los archivos con conflicto. Ábrelos, resuélvelos manualmente y luego:
```bash
git add .
git commit -m "Resueltos conflictos"
git push
```

## 📧 Contacto

- **GitHub:** [alfha1011](https://github.com/alfha1011)
- **Repositorio:** https://github.com/alfha1011/Aplicaciones-Web

## 📄 Licencia

Este proyecto fue desarrollado como parte de la materia de Aplicaciones Web - UTJ 2026.