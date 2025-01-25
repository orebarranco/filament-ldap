# Filament-LDAP

Este proyecto implementa un sistema de gestión basado en **FilamentPHP**, con autenticación integrada a través de
**LDAP**. Está diseñado para facilitar la administración y la gestión de usuarios autenticados mediante un directorio
LDAP, combinando la flexibilidad de Laravel con la robustez de los sistemas empresariales de autenticación.

## Características

- Autenticación mediante **LDAP** con soporte para credenciales locales como fallback.
- Integración con **FilamentPHP** para una interfaz administrativa moderna y funcional.
- Configuración simplificada de LDAP a través de variables de entorno.
- Comando personalizado para crear usuarios de Filament (`make:filament-user`).

## Requisitos previos

- **PHP 8.2+**
- **Laravel 11+**
- **Composer**
- **Docker** y **Docker Compose** (solo para pruebas)

## Instalación

Sigue estos pasos para configurar y ejecutar el proyecto en tu entorno local:

### 1. Clona este repositorio

```bash
git clone https://github.com/orebarranco/filament-ldap.git
cd filament-ldap
```

### 2. Instalación de dependencias

```bash
composer install
yarn run build
```

### 3. Configuración del archivo `.env`

Copia el archivo `.env.example` a `.env` y completa los valores requeridos:

```bash
cp .env.example .env
```

Configura las variables relacionadas con LDAP:

```dotenv
LDAP_HOST=127.0.0.1
LDAP_USERNAME="cn=Administrator,cn=Users,dc=example,dc=com"
LDAP_PASSWORD=Passw0rd
LDAP_BASE_DN="dc=example,dc=com"
LDAP_PORT=389
LDAP_SSL=false
LDAP_TLS=false
```

### 4. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 5. Configuración de la base de datos

Actualiza las credenciales de la base de datos en el archivo `.env` y ejecuta las migraciones:

```bash
php artisan migrate
```

### 6. Crear un usuario administrador

Usa el comando `make:filament-user` para generar un usuario administrador:

```bash
php artisan make:filament-user
```

Ingresa el nombre, correo electrónico, nombre de usuario y contraseña cuando se te solicite.

### 7. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Pruebas con Docker Compose

Este proyecto incluye un archivo `docker-compose.yml` que permite configurar un servidor LDAP (Active Directory) y una
interfaz web de administración para LDAP (phpLDAPadmin).

### 1. Configurar Docker

Asegúrate de tener **Docker** y **Docker Compose** instalados en tu máquina. Si no los tienes, puedes seguir las guías
oficiales para instalarlos:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### 2. Levantar los servicios de Docker

En el directorio raíz del proyecto, ejecuta el siguiente comando para levantar los contenedores:

```bash
docker-compose up -d
```

Esto iniciará los siguientes servicios:

- **LDAP (Active Directory)**: Servicio de servidor LDAP usando la imagen `smblds/smblds`.
- **phpLDAPadmin**: Interfaz web para administrar LDAP, accesible en el puerto `8080` de tu máquina.

### 3. Acceder a phpLDAPadmin

Para administrar el servidor LDAP, abre tu navegador y accede a:

[http://localhost:8080](http://localhost:8080)

- **Usuario**: `cn=Administrator,cn=Users,dc=example,dc=com`
- **Contraseña**: `Passw0rd`

### 4. Verificar la autenticación LDAP

En tu aplicación Laravel, puedes probar la autenticación LDAP utilizando las credenciales de usuario configuradas en el
servidor LDAP. Si las credenciales son correctas, el usuario podrá acceder al panel de administración de Filament.
