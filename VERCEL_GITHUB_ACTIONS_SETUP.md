# 🚀 Configuración de GitHub Actions para Deploy Automático en Vercel

Este documento explica cómo configurar el despliegue automático del frontend en Vercel mediante GitHub Actions.

---

## 📋 Prerrequisitos

1. Cuenta de Vercel activa
2. Proyecto en Vercel ya creado (o seguir pasos de creación)
3. Acceso al repositorio de GitHub con permisos de administrador

---

## 🔑 Paso 1: Obtener Tokens de Vercel

### 1.1 Vercel Token
1. Ve a https://vercel.com/account/tokens
2. Click en "Create Token"
3. Dale un nombre descriptivo: `GitHub Actions - Informático Capella`
4. Copia el token (lo necesitarás para GitHub Secrets)

### 1.2 Vercel Project ID y Org ID

**Opción A: Desde Vercel CLI (local)**
```bash
cd frontend
npx vercel link

# Esto creará un archivo .vercel/project.json
cat .vercel/project.json
```

**Opción B: Desde Vercel Dashboard**
1. Ve a tu proyecto en Vercel
2. Settings > General
3. Copia el **Project ID**
4. Ve a tu Team/Organization settings
5. Copia el **Organization ID** (Team ID)

---

## 🔐 Paso 2: Configurar GitHub Secrets

1. Ve a tu repositorio en GitHub:
   ```
   https://github.com/pellax/worpressinformaticocapellafullsite
   ```

2. Click en **Settings** > **Secrets and variables** > **Actions**

3. Click en **New repository secret** y agrega los siguientes secrets:

### Secrets Requeridos:

| Name | Value | Descripción |
|------|-------|-------------|
| `VERCEL_TOKEN` | `tu_token_de_vercel` | Token de acceso de Vercel |
| `VERCEL_ORG_ID` | `team_xxx` o `prj_xxx` | Tu Organization/Team ID |
| `VERCEL_PROJECT_ID` | `prj_xxx` | Tu Project ID |
| `NEXT_PUBLIC_WORDPRESS_API_URL` | `https://tu-wordpress.com/wp-json/wp/v2` | URL de WordPress API (producción) |
| `NEXT_PUBLIC_SITE_URL` | `https://informaticocapella.com` | URL del sitio en producción |

---

## ✅ Paso 3: Verificar Configuración

### 3.1 Estructura de Archivos

Verifica que existe el archivo:
```
.github/workflows/deploy-vercel.yml
```

### 3.2 Primer Deploy Manual (opcional)

Para verificar que todo funciona, puedes hacer un deploy manual primero:

```bash
cd frontend

# Install Vercel CLI
npm install -g vercel

# Login
vercel login

# Link project
vercel link

# Deploy a producción
vercel --prod
```

---

## 🚀 Paso 4: Trigger Automático

Una vez configurados los secrets, el deploy se ejecutará automáticamente cuando:

1. **Push a main**: Cualquier cambio en `frontend/` en la rama `main`
2. **Pull Request**: Crea un preview deployment

### Ejemplo de commit que triggerea deploy:

```bash
# Hacer un cambio en frontend
cd frontend
echo "// Update" >> app/page.tsx

# Commit y push
git add .
git commit -m "feat(frontend): update homepage

Co-Authored-By: Warp <agent@warp.dev>"
git push origin main
```

---

## 📊 Paso 5: Monitorear Deploys

### En GitHub
1. Ve a la pestaña **Actions** en tu repositorio
2. Verás el workflow "Deploy to Vercel" ejecutándose
3. Click en el run para ver logs detallados

### En Vercel
1. Ve a tu proyecto en Vercel Dashboard
2. Pestaña **Deployments**
3. Verás los deploys con source "GitHub Actions"

---

## 🔧 Configuración Avanzada

### Preview Deploys en Pull Requests

El workflow ya está configurado para crear preview deployments automáticos en PRs:
- Cada PR obtiene su propia URL de preview
- Se agrega un comentario automático en el PR con la URL

### Variables de Entorno por Branch

Si quieres diferentes configuraciones para staging/production, puedes modificar el workflow:

```yaml
# En deploy-vercel.yml
- name: Deploy to staging
  if: github.ref == 'refs/heads/develop'
  run: vercel deploy --token=${{ secrets.VERCEL_TOKEN }}

- name: Deploy to production  
  if: github.ref == 'refs/heads/main'
  run: vercel deploy --prod --token=${{ secrets.VERCEL_TOKEN }}
```

---

## 🚨 Troubleshooting

### Error: "Vercel Token is invalid"
- Verifica que el token esté correctamente copiado en GitHub Secrets
- Asegúrate que el token no haya expirado
- Regenera el token en Vercel si es necesario

### Error: "Project not found"
- Verifica que `VERCEL_PROJECT_ID` sea correcto
- Asegúrate que el proyecto existe en Vercel
- Ejecuta `vercel link` localmente primero

### Deploy Stuck o Lento
- Verifica logs en GitHub Actions
- Revisa build logs en Vercel Dashboard
- Aumenta timeout si es necesario (por defecto 10min)

### Changes no triggerea deploy
- Verifica que los cambios estén en `frontend/` directory
- Revisa la configuración de `paths` en el workflow
- Asegúrate de hacer push a la rama `main`

---

## 📝 Comandos Útiles

```bash
# Ver logs de último deploy
vercel logs <deployment-url>

# Listar todos los deployments
vercel ls

# Rollback a deployment anterior
vercel rollback <deployment-url>

# Inspeccionar deployment
vercel inspect <deployment-url>
```

---

## 🎯 Checklist de Configuración

- [ ] Token de Vercel creado
- [ ] Project ID obtenido
- [ ] Organization ID obtenido
- [ ] Todos los secrets configurados en GitHub
- [ ] Workflow file existe en `.github/workflows/`
- [ ] Primer deploy manual exitoso (opcional)
- [ ] Push a main triggerea deploy automático
- [ ] Preview deploy funciona en PR

---

## 📚 Referencias

- [Vercel CLI Documentation](https://vercel.com/docs/cli)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Vercel + GitHub Actions](https://vercel.com/guides/how-can-i-use-github-actions-with-vercel)

---

**Última actualización**: 2026-01-17  
**Mantenido por**: Pellax (Informático Capella)
