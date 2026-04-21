# 🐾 VetSystem — Sistema de Gestión de Citas Veterinarias

Sistema web desarrollado con **Laravel 11**, **PHP 8.2+** y **MySQL** para la gestión integral de una clínica veterinaria.

---

## 📋 Módulos del Sistema

| Módulo | Descripción |
|---|---|
| 🔐 Autenticación | Login con roles: Admin, Recepcionista, Veterinario |
| 👥 Clientes | Registro y gestión de dueños de mascotas |
| 🐾 Pacientes | Ficha clínica completa por mascota |
| 📅 Citas | Registro, programación y cancelación de citas |
| 🩺 Historial Médico | Diagnósticos, tratamientos y reportes por paciente |
| 🧾 Facturación | Generación de facturas con ítems y registro de pagos |
| 🔔 Notificaciones | Envío por email con programación de envíos |
| 👨‍⚕️ Veterinarios | Gestión del equipo médico y horarios |

---

## ⚙️ Requisitos del Sistema

- PHP >= 8.2
- Composer >= 2.x
- MySQL >= 8.0
- Node.js >= 18 (opcional, para assets)
- Git

---

## 🚀 Instalación y Despliegue Local

### 1. Clonar el repositorio

```bash
git clone https://github.com/TU_USUARIO/veterinaria-sistema.git
cd veterinaria-sistema
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
php artisan key:generate
```

Editar el archivo `.env` con los datos de tu base de datos:

```env
APP_NAME="VetSystem"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=veterinaria_db
DB_USERNAME=root
DB_PASSWORD=tu_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tucorreo@gmail.com
MAIL_PASSWORD=tu_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@veterinaria.com"
MAIL_FROM_NAME="VetSystem"
```

### 4. Crear la base de datos en MySQL

```sql
CREATE DATABASE veterinaria_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Ejecutar migraciones y seeders

```bash
# Crear todas las tablas
php artisan migrate

# Cargar datos de prueba (usuarios, pacientes, clientes de ejemplo)
php artisan db:seed
```

### 6. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

Accede en: **http://localhost:8000**

---

## 👤 Usuarios de Prueba (Seeders)

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | admin@veterinaria.com | password |
| Recepcionista | recepcion@veterinaria.com | password |
| Veterinario 1 | cmendoza@veterinaria.com | password |
| Veterinario 2 | sramirez@veterinaria.com | password |

---

## 🗄️ Estructura de la Base de Datos

```
users               → Usuarios del sistema (roles)
veterinarios        → Perfil extendido del veterinario
clientes            → Dueños de mascotas
pacientes           → Mascotas registradas
citas               → Citas médicas programadas
historiales_medicos → Registros clínicos por cita
facturas            → Facturas generadas
detalle_facturas    → Ítems de cada factura
pagos               → Pagos registrados por factura
notificaciones      → Notificaciones enviadas/programadas
```

---

## 📁 Estructura del Proyecto

```
veterinaria/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── PacienteController.php
│   │   │   ├── ClienteController.php
│   │   │   ├── CitaController.php
│   │   │   ├── VeterinarioController.php
│   │   │   ├── HistorialController.php
│   │   │   ├── FacturaController.php
│   │   │   └── NotificacionController.php
│   │   └── Middleware/Authenticate.php
│   └── Models/
│       ├── User.php
│       ├── Veterinario.php
│       ├── Cliente.php
│       ├── Paciente.php
│       ├── Cita.php
│       ├── HistorialMedico.php
│       ├── Factura.php
│       ├── DetalleFactura.php
│       ├── Pago.php
│       └── Notificacion.php
├── database/
│   ├── migrations/          → 9 migraciones
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── auth/login.blade.php
│   ├── dashboard.blade.php
│   ├── pacientes/           → index, create, edit, show
│   ├── clientes/            → index, create, edit, show
│   ├── citas/               → index, create, edit, show
│   ├── historial/           → index, create, edit, show, reporte
│   ├── facturas/            → index, create, show
│   ├── notificaciones/      → index, create
│   └── veterinarios/        → index, create, edit, show
└── routes/web.php
```

---

## ☁️ Despliegue en Producción

### Opción A — Servidor VPS / Hosting compartido (cPanel)

1. **Subir archivos** via FTP o Git al servidor.
2. **Apuntar el `DocumentRoot`** a la carpeta `/public` del proyecto.
3. **Configurar `.env`** en producción:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://tudominio.com
   ```
4. Ejecutar:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Opción B — Docker (recomendado)

Crear `docker-compose.yml` en la raíz:

```yaml
version: '3.8'
services:
  app:
    image: php:8.2-fpm
    volumes:
      - .:/var/www/html
    depends_on:
      - db

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
    volumes:
      - .:/var/www/html
      - ./nginx.conf:/etc/nginx/conf.d/default.conf

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: veterinaria_db
      MYSQL_ROOT_PASSWORD: secret
    ports:
      - "3306:3306"
    volumes:
      - dbdata:/var/lib/mysql

volumes:
  dbdata:
```

```bash
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan migrate --seed
```

---

## 📤 Subir a GitHub

```bash
# 1. Inicializar repositorio (si no existe)
git init

# 2. Agregar todos los archivos (respetando .gitignore)
git add .

# 3. Primer commit
git commit -m "feat: sistema de gestión de citas veterinarias - v1.0"

# 4. Conectar con repositorio remoto
git remote add origin https://github.com/TU_USUARIO/veterinaria-sistema.git

# 5. Subir al repositorio
git branch -M main
git push -u origin main
```

> ⚠️ **Nunca subas el archivo `.env`** a GitHub. Ya está incluido en `.gitignore`.

---

## 🔄 Flujo Principal del Sistema

```
Recepcionista
  └─► Registra Cliente y Mascota
  └─► Verifica disponibilidad del veterinario
  └─► Registra/Programa la Cita
  └─► Genera Factura al finalizar
  └─► Envía notificaciones al cliente

Veterinario
  └─► Atiende la cita
  └─► Registra Historial Médico (diagnóstico, tratamiento)
  └─► Genera reporte médico del paciente
```

---

## 📌 Requerimientos Funcionales Implementados

| ID | Descripción | Estado |
|---|---|---|
| RF-01 | Registro de Pacientes | ✅ |
| RF-02 | Gestión de Citas (crear, editar, cancelar, verificar disponibilidad) | ✅ |
| RF-03 | Historial Médico (diagnóstico, tratamiento, medicamentos, reportes) | ✅ |
| RF-04 | Facturación y Pagos (facturas con ítems, IVA, registro de pagos) | ✅ |
| RF-05 | Notificaciones (email, programadas, automáticas al crear/cancelar cita) | ✅ |

## 🛡️ Requerimientos No Funcionales Implementados

| ID | Descripción | Implementación |
|---|---|---|
| RNF-01 | Seguridad | Autenticación Laravel Auth, CSRF, validaciones, roles |
| RNF-02 | Rendimiento | Eager loading, paginación, índices en BD |
| RNF-03 | Usabilidad | UI Bootstrap 5, diseño responsive, mensajes de éxito/error |
| RNF-04 | Disponibilidad | Estructura lista para producción con caché de rutas/vistas |
| RNF-05 | Mantenibilidad | MVC estricto, código documentado, seeders para pruebas |

---

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 8.2, Laravel 11
- **Base de datos:** MySQL 8.0, Eloquent ORM
- **Frontend:** Blade Templates, Bootstrap 5, Bootstrap Icons
- **Autenticación:** Laravel Auth nativo
- **Email:** Laravel Mail (SMTP configurable)

---

## 📞 Soporte

Para reportar errores o solicitar mejoras, abre un **Issue** en el repositorio de GitHub.
