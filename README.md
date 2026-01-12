# Informático Capella - Sitio Web Profesional

Proyecto completo con WordPress headless (backend) y Next.js 14 (frontend) para consultoría tecnológica.

## 🏗️ Arquitectura

- **Backend**: WordPress 6.4 + MariaDB 11.2 (Puerto 8080)
- **Frontend**: Next.js 14 + React + TypeScript + Tailwind CSS (Puerto 3000)
- **Containerización**: Docker Compose

## 🚀 Estado Actual

✅ **Entorno Docker completo funcionando**
✅ **WordPress Backend operativo**
✅ **Frontend Next.js desplegado**
✅ **5 páginas completadas** (Home, Sobre Mí, Servicios, Portafolio, Experiencia, Contacto)

## 📋 Acceso a los Servicios

- **Frontend**: http://localhost:3000
- **WordPress Admin**: http://localhost:8080/wp-admin
- **WordPress REST API**: http://localhost:8080/wp-json/wp/v2

## 🛠️ Comandos Rápidos

### Levantar todo el stack

```bash
# Construir y levantar todos los servicios
docker-compose up -d --build

# Solo levantar (sin rebuild)
docker-compose up -d
```

### Detener servicios

```bash
docker-compose down
```

### Ver logs

```bash
# Todos los servicios
docker-compose logs -f

# Solo frontend
docker logs -f informaticocapella_frontend

# Solo WordPress
docker logs -f informaticocapella_wp
```

### Reiniciar servicios

```bash
# Reiniciar todos
docker-compose restart

# Reiniciar solo frontend
docker-compose restart frontend
```

## 📦 Estructura del Proyecto

```
informaticocapella/
├── docker-compose.yml          # Orquestación de contenedores
├── frontend/                   # Aplicación Next.js
│   ├── app/                   # Pages y layouts (App Router)
│   │   ├── page.tsx          # Homepage
│   │   ├── sobre-mi/         # Página Sobre Mí
│   │   ├── servicios/        # Página Servicios
│   │   ├── portafolio/       # Página Portafolio
│   │   ├── experiencia/      # Página Experiencia
│   │   └── contacto/         # Página Contacto
│   ├── components/            # Componentes React
│   │   ├── Navbar.tsx
│   │   ├── Hero.tsx
│   │   └── Footer.tsx
│   ├── lib/                   # Utilidades
│   ├── public/                # Archivos estáticos
│   │   └── profile.jpg       # Foto de perfil
│   ├── Dockerfile             # Imagen Docker del frontend
│   └── next.config.ts         # Configuración Next.js
├── plugins/                   # Plugins WordPress custom
│   └── informatico-capella-core/
│       ├── src/              # Código fuente (Clean Architecture)
│       └── tests/            # Tests PHPUnit
├── themes/                    # Temas WordPress custom
├── uploads/                   # Uploads de WordPress
└── README.md                 # Este archivo
```

## 🔧 Configuración Técnica

### Credenciales WordPress
- **URL Admin**: http://localhost:8080/wp-admin
- **Usuario**: admin
- **Contraseña**: Admin2024Capella!

### Credenciales de Base de Datos
- **Host**: db:3306 (desde contenedor) o localhost:3306 (desde host)
- **Base de datos**: informaticocapella_db
- **Usuario**: capella_user
- **Contraseña**: capella_secure_pass_2024

### Puertos
- **Frontend**: 3000
- **WordPress**: 8080
- **MariaDB**: 3306 (solo interno)

## 🎨 Frontend - Tecnologías

- **Framework**: Next.js 14 (App Router)
- **UI**: React 19 + TypeScript
- **Estilos**: Tailwind CSS
- **Animaciones**: Framer Motion
- **Fuentes**: Google Fonts (Inter)
- **Optimización**: Standalone build para Docker

## 🔌 Backend - WordPress

- **Plugin Custom**: Informático Capella Core
- **Arquitectura**: Clean Architecture (Domain/Application/Infrastructure)
- **Testing**: PHPUnit con 3 test suites (Unit/Integration/E2E)
- **Theme**: Astra Child Theme personalizado

## 🌐 API REST

El frontend consume el WordPress REST API:
- **Desde Docker**: `http://wordpress/wp-json/wp/v2`
- **Desarrollo local**: `http://localhost:8080/wp-json/wp/v2`

## 💻 Desarrollo Local

### Frontend (sin Docker)

Si prefieres desarrollar el frontend localmente sin Docker:

```bash
cd frontend
npm install
npm run dev
```

El frontend estará en http://localhost:3000 y se conectará automáticamente al WordPress en Docker (puerto 8080).

### WordPress

Ya está instalado y configurado. Accede a:
- **Admin**: http://localhost:8080/wp-admin
- **Credenciales**: admin / Admin2024Capella!

## 📦 Deploy

### Reconstruir servicios

```bash
# Reconstruir solo el frontend
docker-compose up -d --build frontend

# Reconstruir todos los servicios
docker-compose up -d --build
```

### Variables de Entorno

**Frontend (.env.local):**
```env
NEXT_PUBLIC_WORDPRESS_API_URL=http://localhost:8080/wp-json/wp/v2
NEXT_PUBLIC_SITE_URL=http://localhost:3000
```

**Docker (en docker-compose.yml):**
Las variables de entorno ya están configuradas automáticamente.

## 🧪 Testing

### Frontend
```bash
cd frontend
npm run lint
npm run build
```

### WordPress Plugin
```bash
cd plugins/informatico-capella-core
./vendor/bin/phpunit
```

## 🚀 Próximos Pasos

- [ ] Configurar GitHub Actions para CI/CD
- [ ] Deploy automático a OVH
- [ ] Configurar SEO avanzado con next-seo
- [ ] Implementar Custom Post Type para Case Studies
- [ ] Añadir tests para el frontend
- [ ] Configurar dominio personalizado
- [ ] SSL/HTTPS para producción

## 🔐 Seguridad

⚠️ **IMPORTANTE**: Esta configuración es para desarrollo local.

Antes de producción:
- Cambiar todas las contraseñas
- Configurar SSL/HTTPS
- Implementar backups automáticos
- Configurar firewall
- Actualizar permisos de archivos

## 🐛 Troubleshooting

### El frontend no se conecta a WordPress
- Verifica que WordPress esté corriendo: `docker ps`
- Revisa los logs: `docker logs informaticocapella_wp`
- Asegúrate de que la red Docker esté funcionando

### Error de permisos en WordPress
```bash
docker exec -it informaticocapella_wp chown -R www-data:www-data /var/www/html
```

### Limpiar todo y empezar de cero
```bash
docker-compose down -v  # ⚠️ Esto borrará los volúmenes (base de datos)
docker-compose up -d --build
```

### Ver estado de contenedores
```bash
docker ps
```

## 📝 Notas

- Los volúmenes Docker persisten los datos entre reinicios
- El frontend usa output standalone para optimizar el contenedor
- WordPress está configurado con WP_DEBUG activado para desarrollo
- El frontend y backend se comunican a través de la red Docker interna

---

**Proyecto**: Informático Capella  
**Arquitectura**: WordPress Headless + Next.js 14  
**Stack**: Next.js 14 + React + TypeScript + Tailwind + WordPress 6.4 + MariaDB 11.2  
**Containerización**: Docker Compose
