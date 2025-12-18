# Producto 3. Desarrollo de una aplicación gestión de transfers con Laravel

Proyecto Isla Transfer es una **aplicación web desarrollada con Laravel** para la gestión de reservas de traslados, hoteles y comisiones, orientada a un entorno corporativo con **distintos roles de usuario**, adpatada del Prodcuto 2.

La aplicación permite administrar reservas (Administrador, Hotel, Usuario), calcular comisiones por hotel, aplicar filtros temporales y visualizar información tanto **agregada como detallada**, garantizando la integridad de los datos y una arquitectura escalables.

---

## Tecnologías utilizadas

- PHP 8.x  
- Laravel  
- MySQL  
- Docker & Docker Compose  
- Blade (motor de plantillas)  
- Bootstrap  
- Eloquent ORM  

---

## Acceso a la aplicación en servidor

La aplicación está desplegada y accesible en el siguiente enlace:

🔗 https://fp064.techlab.uoc.edu/~uocx1/producto3/

---

##  Funcionalidades principales

- Gestión de reservas de traslados  
- Cálculo automático de comisiones por hotel  
- Filtros por mes, año y hotel  
- Visualización agregada y detallada de datos  
- Control de acceso por roles:
  - Administrador  
  - Hotel  
  - Usuario  
- Integridad referencial mediante claves foráneas  
- Arquitectura MVC  

---

## 🐳 Arranque del proyecto con Docker

### Requisitos previos
- Docker
- Docker Compose

---
### 1. Clonar el repositorio
```bash
git clone https://github.com/andreavalerosanz-rgb/php-1.git
cd php-1
```
---
### 2. Configurar variables de entorno
```bash
cp .env.example .env
```
---
### 3. Levantar los contenedores
```bash
docker-compose up -d
```
---
### 4. Instalar dependencias y preparar Laravel
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```
---
### 5. Acceso a la aplicación en local

http://localhost

El puerto puede variar según la configuración del docker-compose.yml.

---

# Autoras
Proyecto desarrollado por: 
Helena Vivas i Ramajo, 
Martha Milena Aguilar Parra, 
Cèlia Trullà Estruch 
y Andrea Valero Sanz
