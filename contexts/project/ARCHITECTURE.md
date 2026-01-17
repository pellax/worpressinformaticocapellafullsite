# 🏛️ Arquitectura del Sistema - Informático Capella

## 📐 Visión General

Sistema híbrido headless CMS con separación clara entre frontend y backend, siguiendo principios de Clean Architecture en el backend y component-driven development en el frontend.

---

## 🎯 Decisiones Arquitectónicas

### 1. Arquitectura Headless

**Decisión**: Separar frontend (Next.js) y backend (WordPress) como aplicaciones independientes.

**Razones**:
- ✅ **Performance**: Next.js SSR/SSG para tiempos de carga óptimos
- ✅ **Developer Experience**: React moderno vs PHP templates
- ✅ **Escalabilidad**: Frontend y backend escalan independientemente
- ✅ **Flexibilidad**: Cambiar frontend sin afectar backend
- ✅ **SEO**: Next.js App Router con metadata API

**Trade-offs**:
- ❌ Mayor complejidad inicial
- ❌ Dos deployments independientes
- ❌ Sincronización entre sistemas

---

### 2. Clean Architecture en WordPress Plugin

**Decisión**: Implementar Clean Architecture con 4 capas en plugin custom.

```
Domain (Core)
    ↓
Application (Use Cases)
    ↓
Infrastructure (WordPress)
    ↓
Presentation (API/Views)
```

**Razones**:
- ✅ **Testability**: Lógica de negocio sin dependencias de WordPress
- ✅ **Maintainability**: Código organizado y predecible
- ✅ **Independence**: Fácil migrar de WordPress si fuera necesario
- ✅ **SOLID Principles**: Código limpio y escalable

**Ejemplo**:
```php
// Domain: Pure PHP, no WordPress
class CaseStudy {
    public function __construct(
        private string $title,
        private string $client,
        private string $description
    ) {}
}

// Infrastructure: WordPress implementation
class WordPressCaseStudyRepository implements CaseStudyRepository {
    public function save(CaseStudy $case): int {
        return wp_insert_post([...]);
    }
}
```

---

### 3. Component-Driven Frontend

**Decisión**: React Server Components + Client Components donde necesario.

**Estrategia**:
- **Server Components** por defecto (mejor performance)
- **Client Components** solo cuando necesario (interactividad)

```typescript
// Server Component (default)
export default async function PortafolioPage() {
  const cases = await getCaseStudies(); // Server-side fetch
  return <CaseStudyList cases={cases} />;
}

// Client Component (when needed)
'use client';
export function ContactForm() {
  const [data, setData] = useState({});
  // Interactivity
}
```

---

### 4. API Design: REST vs GraphQL

**Decisión**: REST API (WordPress REST API + custom endpoints)

**Razones**:
- ✅ WordPress tiene REST API built-in
- ✅ Más simple para este proyecto
- ✅ Cacheable con Next.js ISR
- ✅ Familiaridad del equipo

**Endpoints Principales**:
```
GET /wp-json/wp/v2/posts
GET /wp-json/wp/v2/pages
GET /wp-json/informatico/v1/case-studies
GET /wp-json/informatico/v1/testimonials
POST /wp-json/informatico/v1/contact
```

---

### 5. State Management

**Decisión**: No usar Redux/Zustand inicialmente. React Server Components + URL state.

**Razones**:
- ✅ Server Components reducen necesidad de estado global
- ✅ Menos complejidad
- ✅ URL como source of truth (filters, pagination)
- ✅ React 19 useOptimistic para UI optimista

**Cuando agregar estado global**:
- Multi-step forms complejos
- Shopping cart (si se agrega e-commerce)
- Real-time features

---

### 6. Styling Strategy

**Decisión**: Tailwind CSS utility-first

**Razones**:
- ✅ Desarrollo rápido
- ✅ Tree-shaking automático
- ✅ Design system consistente
- ✅ No CSS-in-JS runtime overhead

**Configuración**:
```typescript
// tailwind.config.ts
export default {
  theme: {
    extend: {
      colors: {
        primary: {
          light: '#10B981', // Emerald
          DEFAULT: '#0EA5E9', // Sky Blue
          dark: '#0C4A6E'
        }
      }
    }
  }
}
```

---

### 7. Data Fetching Strategy

**Decisión**: Next.js native fetching con caching

```typescript
// ISR: Revalidate every hour
async function getCaseStudies() {
  const res = await fetch(
    `${process.env.NEXT_PUBLIC_WORDPRESS_API_URL}/case-studies`,
    { next: { revalidate: 3600 } } // 1 hour cache
  );
  return res.json();
}
```

**Estrategias por tipo de contenido**:
- **Static**: Sobre Mí, Servicios (SSG)
- **ISR**: Portafolio, Blog (revalidate: 3600s)
- **Dynamic**: Contacto, Search (no cache)

---

### 8. Testing Strategy

**Decisión**: Test pyramid - 60% Unit, 30% Integration, 10% E2E

#### Backend (PHPUnit)
```
tests/
├── Unit/           # Domain layer (100% coverage goal)
├── Integration/    # WordPress integration
└── E2E/            # Full flow tests
```

#### Frontend (Jest + Playwright)
```
__tests__/
├── unit/           # Components, utils
├── integration/    # Page tests
└── e2e/            # Playwright full flows
```

---

### 9. Security Layers

#### Defense in Depth
1. **Network**: Firewall, HTTPS only
2. **Application**: Input sanitization, CORS, CSP
3. **Data**: Prepared statements, encryption at rest
4. **Access Control**: JWT, role-based permissions

#### WordPress Hardening
```php
// wp-config.php
define('DISALLOW_FILE_EDIT', true);
define('FORCE_SSL_ADMIN', true);
add_filter('xmlrpc_enabled', '__return_false');
```

#### Next.js Security Headers
```typescript
// next.config.ts
const securityHeaders = [
  { key: 'X-Frame-Options', value: 'SAMEORIGIN' },
  { key: 'X-Content-Type-Options', value: 'nosniff' },
  { key: 'Strict-Transport-Security', value: 'max-age=31536000' }
];
```

---

### 10. Deployment Architecture

```
┌─────────────────┐
│   Vercel CDN    │  ← Frontend (Next.js)
└────────┬────────┘
         │ HTTPS
         ↓
┌─────────────────┐
│  WordPress API  │  ← Backend (WordPress)
│   (OVH/VPS)     │
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│   MariaDB 11.2  │  ← Database
└─────────────────┘
```

**Frontend (Vercel)**:
- Global CDN
- Automatic HTTPS
- Serverless functions
- Zero-config deployment

**Backend (OVH - Planned)**:
- VPS con Docker
- Nginx reverse proxy
- Let's Encrypt SSL
- Automated backups

---

## 🔄 Data Flow

### Read Flow (Case Study)
```
User Request
    ↓
Next.js Page (SSR/ISR)
    ↓
fetch('/wp-json/informatico/v1/case-studies')
    ↓
WordPress REST Controller
    ↓
Use Case (Application Layer)
    ↓
Repository (Infrastructure Layer)
    ↓
WordPress Database
    ↓
Response (JSON)
    ↓
Next.js renders
    ↓
HTML to user
```

### Write Flow (Contact Form)
```
User submits form
    ↓
Next.js API Route (/api/contact)
    ↓
POST /wp-json/informatico/v1/contact
    ↓
Validate & Sanitize
    ↓
Save to Database (wp_leads)
    ↓
Send Email (WordPress mail)
    ↓
Return success/error
    ↓
Update UI
```

---

## 📦 Module Organization

### Frontend (Next.js)
```
app/
├── layout.tsx          # Root layout
├── page.tsx            # Homepage
├── sobre-mi/
│   └── page.tsx
├── servicios/
│   └── page.tsx
└── api/                # API routes
    └── contact/
        └── route.ts

components/
├── Navbar.tsx
├── Hero.tsx
└── Footer.tsx

lib/
├── wordpress.ts        # API client
├── analytics.ts
└── utils.ts
```

### Backend (WordPress Plugin)
```
src/
├── Domain/
│   ├── Entities/
│   ├── Repositories/   # Interfaces
│   └── Exceptions/
├── Application/
│   ├── UseCases/
│   └── DTOs/
├── Infrastructure/
│   ├── Repositories/   # Implementations
│   └── Services/
└── Presentation/
    ├── Controllers/
    └── REST/
```

---

## 🎯 Patrones Utilizados

### Backend
- **Repository Pattern**: Abstracción de persistencia
- **Dependency Injection**: Inversión de control
- **Factory Pattern**: Creación de objetos complejos
- **Strategy Pattern**: Diferentes implementaciones intercambiables

### Frontend
- **Composition**: Components pequeños y reutilizables
- **Render Props**: Compartir lógica entre components
- **Custom Hooks**: Lógica reutilizable
- **Server Actions**: Mutations desde client components

---

## 📊 Decisiones de Performance

### Backend
- **Transients**: Cache de queries pesados (1h TTL)
- **Object Cache**: Redis/Memcached (futuro)
- **Database Indexing**: Índices en columnas frecuentes
- **Lazy Loading**: Images y assets

### Frontend
- **Image Optimization**: Next/Image con WebP
- **Code Splitting**: Dynamic imports
- **Prefetching**: Link prefetch automático
- **Font Optimization**: next/font con subset

---

## 🔮 Evolución Futura

### Corto Plazo (3 meses)
- Blog técnico con MDX
- Sistema de comentarios
- Newsletter integration

### Medio Plazo (6 meses)
- Dashboard de analytics
- CRM integration
- A/B testing

### Largo Plazo (1 año)
- Multilingual (EN/ES)
- Payment integration (servicios premium)
- Mobile app (React Native)

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17  
**Autor**: Architecture Agent
