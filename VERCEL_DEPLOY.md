# 🚀 Deploy a Vercel - Guía Rápida

## Paso 1: Preparar el Repositorio

1. **Crear repositorio en GitHub**:
```bash
cd /home/pellax/Documents/informaticocapella
git add .
git commit -m "feat: proyecto completo con frontend Next.js y backend WordPress"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/informatico-capella.git
git push -u origin main
```

## Paso 2: Conectar con Vercel

### Opción A: Desde la Web (Recomendado)

1. Ve a [vercel.com](https://vercel.com) e inicia sesión
2. Click en **"Add New Project"**
3. Selecciona **"Import Git Repository"**
4. Conecta tu cuenta de GitHub si aún no lo has hecho
5. Busca tu repositorio `informatico-capella`
6. Click en **"Import"**

### Configuración del Proyecto en Vercel:

**Root Directory**: `frontend`
- ⚠️ MUY IMPORTANTE: Configura esto porque el proyecto Next.js está en el subdirectorio `frontend/`

**Framework Preset**: Next.js
- Vercel lo detectará automáticamente

**Build Command**: `npm run build` (automático)

**Output Directory**: `.next` (automático)

**Install Command**: `npm install` (automático)

### Variables de Entorno en Vercel:

Añade estas variables en: **Project Settings → Environment Variables**

#### Variables requeridas:

1. **`NEXT_PUBLIC_WORDPRESS_API_URL`**
   - Valor: La URL donde estará tu WordPress en producción
   - Ejemplo: `https://api.informaticocapella.com/wp-json/wp/v2`
   - O si WordPress está en el mismo dominio: `https://informaticocapella.com/wp-json/wp/v2`

2. **`NEXT_PUBLIC_SITE_URL`**
   - Valor: La URL de tu sitio Vercel
   - Ejemplo: `https://informaticocapella.vercel.app`
   - O tu dominio personalizado: `https://informaticocapella.com`

## Paso 3: Configurar Dominio Personalizado (Opcional)

1. En tu proyecto de Vercel, ve a **Settings → Domains**
2. Añade tu dominio personalizado
3. Vercel te dará los registros DNS que necesitas configurar
4. En tu proveedor de dominios (OVH, etc.), añade:
   - **Tipo A**: Apunta a la IP de Vercel
   - O **Tipo CNAME**: Apunta a `cname.vercel-dns.com`

## Paso 4: Deploy

### Deploy Automático:
- Cada push a la rama `main` desplegará automáticamente
- Vercel te enviará notificaciones del estado del deploy

### Deploy Manual:
1. Ve a tu proyecto en Vercel
2. Click en **"Deployments"**
3. Click en **"Redeploy"** en el último deployment

### Ver Progreso:
- Vercel te mostrará logs en tiempo real del build
- Recibirás una URL de preview cuando termine

## Configuración Avanzada

### Next.js Config para Vercel

El archivo `frontend/next.config.ts` ya está configurado correctamente con:
```typescript
output: 'standalone'  // Optimizado para Vercel
```

### WordPress en Producción

Para que el frontend funcione, necesitas:

1. **WordPress accesible públicamente**:
   - Opción A: WordPress en el mismo servidor/dominio que el frontend
   - Opción B: WordPress en subdominio (ej: `api.tudominio.com`)
   - Opción C: WordPress en OVH, frontend en Vercel

2. **Configurar CORS en WordPress** (si están en dominios diferentes):
```php
// En tu plugin o functions.php
add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: https://informaticocapella.vercel.app');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        return $value;
    });
}, 15);
```

### Environment Variables por Entorno

Vercel te permite configurar variables diferentes para:
- **Production**: Variables para `main`
- **Preview**: Variables para ramas de feature
- **Development**: Variables locales

## URLs Resultantes

Después del deploy tendrás:

- **URL de Vercel**: `https://informatico-capella.vercel.app`
- **URL con dominio**: `https://informaticocapella.com` (si lo configuras)
- **Previews**: Cada PR tendrá su propia URL de preview

## Ventajas de Vercel vs OVH

✅ **Deploy automático** con cada push
✅ **SSL gratuito** automático
✅ **CDN global** para velocidad máxima
✅ **Preview deployments** para cada PR
✅ **Rollback** instantáneo a versiones anteriores
✅ **Zero config** para Next.js
✅ **Analytics** integrado
✅ **Edge Functions** disponibles
✅ **100% optimizado** para Next.js

## Troubleshooting

### Error: "Root directory not found"
- Asegúrate de configurar **Root Directory** como `frontend`

### Error: "Module not found"
- Verifica que `package.json` esté en `frontend/`
- Ejecuta `npm install` localmente para verificar dependencias

### Imágenes no cargan
- Verifica `NEXT_PUBLIC_WORDPRESS_API_URL` en las variables de entorno
- Asegúrate de que WordPress permite acceso externo

### WordPress API no responde
- Verifica que WordPress esté accesible públicamente
- Prueba la URL manualmente: `curl https://tu-wordpress.com/wp-json/wp/v2/pages`
- Configura CORS si es necesario

## Monitoreo Post-Deploy

### Analytics de Vercel:
1. Ve a **Analytics** en tu proyecto
2. Verás métricas de:
   - Page views
   - Top pages
   - Top referrers
   - Dispositivos

### Performance:
1. Ve a **Speed Insights**
2. Verás Core Web Vitals automáticamente

## Comandos Útiles Vercel CLI (Opcional)

Instalar CLI:
```bash
npm i -g vercel
```

Login:
```bash
vercel login
```

Deploy desde terminal:
```bash
cd /home/pellax/Documents/informaticocapella/frontend
vercel
```

Deploy a producción:
```bash
vercel --prod
```

Ver logs:
```bash
vercel logs
```

---

**¡Listo!** Tu sitio estará desplegado en Vercel con SSL, CDN global y deploy automático. 🎉
