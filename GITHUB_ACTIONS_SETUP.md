# 🚀 Configuración de GitHub Actions para Deploy a OVH

Esta guía te ayudará a configurar el despliegue automático del frontend de Next.js a tu hosting de OVH vía FTP.

## 📋 Requisitos Previos

- Cuenta de GitHub con el repositorio creado
- Acceso FTP a tu hosting de OVH
- Node.js instalado en tu servidor (para ejecutar Next.js) **O** hosting con soporte para aplicaciones Node.js

## 🔐 Paso 1: Configurar Secretos en GitHub

Los secretos son variables de entorno seguras que GitHub Actions usa durante el deploy. Necesitarás configurar los siguientes:

### Acceder a la configuración de secretos:

1. Ve a tu repositorio en GitHub
2. Click en **Settings** (Configuración)
3. En el menú lateral, click en **Secrets and variables** → **Actions**
4. Click en **New repository secret**

### Secretos requeridos:

#### `FTP_SERVER`
- **Descripción**: Dirección del servidor FTP de OVH
- **Ejemplo**: `ftp.tudominio.com` o `ftp.cluster0XX.hosting.ovh.net`
- **Dónde encontrarlo**: Panel de OVH → Web Cloud → Hosting → FTP

#### `FTP_USERNAME`
- **Descripción**: Usuario FTP
- **Ejemplo**: `tudominio` o `login-ftp`
- **Dónde encontrarlo**: Panel de OVH → Web Cloud → Hosting → FTP

#### `FTP_PASSWORD`
- **Descripción**: Contraseña FTP
- **⚠️ IMPORTANTE**: Usa la contraseña FTP, no la de tu cuenta OVH
- **Cómo obtenerla**: Si no la recuerdas, puedes cambiarla desde el panel de OVH

#### `WORDPRESS_API_URL`
- **Descripción**: URL de la API REST de WordPress
- **Valor**: La URL donde está tu WordPress
- **Ejemplos**:
  - Producción: `https://tudominio.com/wp-json/wp/v2`
  - Si WordPress está en subdominio: `https://api.tudominio.com/wp-json/wp/v2`

#### `SITE_URL`
- **Descripción**: URL pública de tu sitio frontend
- **Valor**: La URL donde estará desplegado el frontend
- **Ejemplos**:
  - `https://tudominio.com`
  - `https://www.tudominio.com`

## 📁 Paso 2: Verificar Estructura en OVH

### Estructura recomendada en tu hosting OVH:

```
/www/                          # Directorio raíz web
├── server.js                  # Servidor Next.js (del build standalone)
├── package.json               # Del build standalone
├── .next/
│   └── static/               # Archivos estáticos de Next.js
├── public/                    # Archivos públicos (imágenes, etc.)
└── node_modules/             # Dependencias (si es necesario)
```

### ⚠️ Notas importantes sobre OVH:

1. **Verificar si tu hosting soporta Node.js**:
   - Los hosting compartidos básicos de OVH **NO** soportan Node.js
   - Necesitas un VPS, Cloud, o hosting específico para Node.js
   - Alternativa: Desplegar como sitio estático (export)

2. **Si tu hosting NO soporta Node.js**, necesitas modificar el workflow para usar `next export`:
   - Cambia en `frontend/next.config.ts`: `output: 'export'` en lugar de `'standalone'`
   - El sitio será completamente estático (sin Server-Side Rendering)

## 🔄 Paso 3: Configurar tu Hosting OVH para Node.js

### Opción A: VPS o Cloud (Recomendado)

Si tienes un VPS o Cloud de OVH:

1. **Instalar Node.js**:
```bash
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt-get install -y nodejs
```

2. **Instalar PM2** (para mantener la app corriendo):
```bash
sudo npm install -g pm2
```

3. **Configurar PM2 para iniciar con el sistema**:
```bash
pm2 startup
pm2 save
```

4. **Crear script de inicio** (`/www/start.sh`):
```bash
#!/bin/bash
cd /www
NODE_ENV=production PORT=3000 node server.js
```

5. **Iniciar con PM2**:
```bash
cd /www
pm2 start start.sh --name "informatico-capella"
pm2 save
```

### Opción B: Hosting Compartido (Estático)

Si tu hosting NO soporta Node.js, modifica para export estático:

1. **Modificar `frontend/next.config.ts`**:
```typescript
const nextConfig: NextConfig = {
  output: 'export',  // Cambiado de 'standalone'
  images: {
    unoptimized: true,  // Requerido para export estático
    // ... resto de configuración
  },
};
```

2. **Modificar `.github/workflows/deploy.yml`**:
```yaml
- name: 📤 Deploy vía FTP
  uses: SamKirkland/FTP-Deploy-Action@v4.3.5
  with:
    server: ${{ secrets.FTP_SERVER }}
    username: ${{ secrets.FTP_USERNAME }}
    password: ${{ secrets.FTP_PASSWORD }}
    local-dir: ./frontend/out/  # Cambiado de .next/standalone
    server-dir: /www/
    dangerous-clean-slate: false
```

## 🚀 Paso 4: Probar el Deploy

### Desde línea de comandos:

1. **Commit y push**:
```bash
cd /home/pellax/Documents/informaticocapella
git add .
git commit -m "feat: configurar GitHub Actions para deploy a OVH"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/TU_REPO.git
git push -u origin main
```

2. **Ver el progreso**:
   - Ve a tu repositorio en GitHub
   - Click en la pestaña **Actions**
   - Verás el workflow ejecutándose

### Deploy manual (sin esperar push):

1. Ve a **Actions** en GitHub
2. Selecciona el workflow "Deploy to OVH"
3. Click en **Run workflow**
4. Selecciona la rama `main`
5. Click en **Run workflow**

## 🔍 Verificación Post-Deploy

### Checklist:

- [ ] El workflow de GitHub Actions completó sin errores
- [ ] Los archivos están en el servidor FTP
- [ ] La URL del sitio carga correctamente
- [ ] Las imágenes se muestran correctamente
- [ ] La navegación entre páginas funciona
- [ ] El sitio se conecta correctamente a la API de WordPress

### Comandos útiles para verificar en el servidor:

```bash
# Ver estructura de archivos
ls -la /www/

# Ver logs de PM2 (si usas Node.js)
pm2 logs informatico-capella

# Reiniciar aplicación
pm2 restart informatico-capella

# Ver estado
pm2 status
```

## 🐛 Troubleshooting

### Error: "Failed to connect to FTP server"
- Verifica que `FTP_SERVER` sea correcto
- Prueba la conexión FTP manualmente con FileZilla
- Verifica que tu IP no esté bloqueada en OVH

### Error: "Authentication failed"
- Verifica `FTP_USERNAME` y `FTP_PASSWORD`
- Resetea la contraseña FTP desde el panel de OVH si es necesario

### El sitio no carga después del deploy
- Verifica que los archivos estén en el directorio correcto
- Revisa logs del servidor: `pm2 logs`
- Verifica permisos de archivos: `chmod -R 755 /www/`

### Imágenes no cargan
- Verifica que `public/` esté desplegado correctamente
- Revisa la configuración de `images` en `next.config.ts`

### API de WordPress no funciona
- Verifica `WORDPRESS_API_URL` en los secretos
- Asegúrate de que WordPress permite CORS si están en dominios diferentes
- Prueba la API manualmente: `curl https://tudominio.com/wp-json/wp/v2/pages`

## 📊 Monitoreo

### Ver logs de deploy:
- GitHub Actions → Pestaña Actions → Selecciona el workflow más reciente

### Ver logs del servidor (VPS/Cloud):
```bash
pm2 logs informatico-capella --lines 100
```

### Ver estado de la aplicación:
```bash
pm2 status
```

## 🔄 Flujo de Trabajo

1. Haces cambios en el código localmente
2. Commit y push a la rama `main`
3. GitHub Actions se ejecuta automáticamente
4. Build de producción de Next.js
5. Deploy vía FTP a OVH
6. (Si VPS) PM2 detecta cambios y reinicia automáticamente

## 📝 Notas Finales

- **Backups**: Configura backups automáticos de tu hosting en OVH
- **SSL/HTTPS**: Configura certificado SSL desde el panel de OVH (Let's Encrypt gratuito)
- **DNS**: Apunta tu dominio a los servidores de OVH
- **Caché**: Considera usar Cloudflare para CDN y caché adicional

---

**¿Necesitas ayuda?** Revisa los logs de GitHub Actions y del servidor para identificar el problema específico.
