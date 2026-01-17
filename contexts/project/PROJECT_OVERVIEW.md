# 📋 Informático Capella - Project Overview

## 🎯 Descripción del Proyecto

**Informático Capella** es un sitio web profesional para una consultoría tecnológica independiente especializada en arquitectura de software, DevOps, y tecnologías cloud. El objetivo principal es generar leads B2B de alta calidad mediante contenido técnico, casos de estudio, y una presencia web profesional optimizada para SEO.

---

## 🏗️ Arquitectura General

### Stack Tecnológico

#### Frontend
- **Framework**: Next.js 14 (App Router)
- **UI Library**: React 19
- **Styling**: Tailwind CSS 3.4
- **Animations**: Framer Motion
- **TypeScript**: 5.x
- **Port**: 3000

#### Backend
- **CMS**: WordPress 6.9
- **Language**: PHP 8.2
- **Database**: MariaDB 11.2
- **Theme**: Astra 4.11 (Child theme)
- **Port**: 8080

#### Infrastructure
- **Containerization**: Docker + Docker Compose
- **Version Control**: Git + GitHub
- **Deployment**: 
  - Frontend: Vercel
  - Backend: OVH (planned)

---

## 📁 Estructura del Proyecto

```
informaticocapella/
├── AGENTS.md                        # Orchestrator
├── WARP.md                          # Development principles
├── agents/                          # Specialized agents
│   ├── UI_AGENT.md
│   ├── SECURITY_AGENT.md
│   ├── ARCHITECTURE_AGENT.md
│   ├── TESTING_AGENT.md
│   ├── BACKEND_AGENT.md
│   ├── DEVOPS_AGENT.md
│   └── DATA_AGENT.md
├── contexts/                        # Project context
│   ├── project/
│   ├── frontend/
│   ├── backend/
│   ├── security/
│   └── testing/
├── skills/                          # Technology best practices
│   ├── nextjs/
│   ├── react/
│   ├── typescript/
│   ├── wordpress/
│   ├── php/
│   ├── docker/
│   ├── security/
│   └── testing/
├── frontend/                        # Next.js application
│   ├── app/
│   ├── components/
│   ├── public/
│   └── Dockerfile
├── plugins/                         # WordPress plugins
│   └── informatico-capella-core/
│       ├── src/
│       │   ├── Domain/
│       │   ├── Application/
│       │   ├── Infrastructure/
│       │   └── Presentation/
│       └── tests/
├── themes/                          # WordPress themes
│   └── astra-child/
├── docker-compose.yml
├── manage.sh                        # Docker management script
└── uploads/                         # Assets
```

---

## 🎨 Frontend (Next.js)

### Páginas Implementadas
1. **Homepage** (`/`) - Hero, stats, services preview
2. **Sobre Mí** (`/sobre-mi`) - Biography, skills, certifications
3. **Servicios** (`/servicios`) - 6 core services + process
4. **Portafolio** (`/portafolio`) - Case studies + testimonials
5. **Experiencia** (`/experiencia`) - Timeline + tech skills
6. **Contacto** (`/contacto`) - Form + FAQs

### Componentes Principales
- `Navbar`: Responsive navigation with gradient logo
- `Hero`: Animated gradients, CTAs, stats
- `Footer`: Social links, navigation, copyright

### Design System
- **Colors**: Blue (#0EA5E9) to Emerald (#10B981) gradients
- **Backgrounds**: Slate-900/950
- **Typography**: Inter font family
- **Responsive**: Mobile-first approach

---

## ⚙️ Backend (WordPress)

### Plugin: Informatico Capella Core

Estructura Clean Architecture:

#### Domain Layer
- **Entities**: `CaseStudy` (implemented with 18 passing tests)
- **Value Objects**: To be implemented
- **Repositories**: Interfaces only
- **Exceptions**: Custom exceptions

#### Application Layer
- **Use Cases**: To be implemented
- **DTOs**: To be implemented

#### Infrastructure Layer
- **Repositories**: WordPress implementations (in progress)
- **Services**: To be implemented

#### Presentation Layer
- **Controllers**: REST API endpoints
- **Views**: Template parts

### Custom Post Types (Planned)
- Case Studies
- Testimonials
- Services

### REST API Endpoints (Planned)
- `/wp-json/informatico/v1/case-studies`
- `/wp-json/informatico/v1/testimonials`

---

## 🗄️ Base de Datos

### WordPress Database
- **Name**: `informaticocapella_db`
- **User**: `capella_user`
- **Password**: `capella_secure_pass_2024`

### Tables
- WordPress core tables (wp_posts, wp_postmeta, etc.)
- Custom tables (planned): wp_leads, wp_analytics

---

## 🚀 Deployment

### Frontend (Vercel)
- **Repo**: https://github.com/pellax/worpressinformaticocapellafullsite
- **Root Directory**: `frontend`
- **Environment Variables**:
  - `NEXT_PUBLIC_WORDPRESS_API_URL`
  - `NEXT_PUBLIC_SITE_URL`

### Backend (Local Development)
- **URL**: http://localhost:8080
- **Admin**: http://localhost:8080/wp-admin
- **Credentials**: admin / Admin2024Capella!

---

## 🧪 Testing

### Backend
- **Framework**: PHPUnit 11.5.46
- **Suites**: Unit, Integration, E2E
- **Coverage Goal**: 80%+
- **Current Status**: 18 tests passing (CaseStudy entity)

### Frontend
- **Framework**: Jest (planned)
- **E2E**: Playwright (planned)

---

## 📊 Estado Actual

### ✅ Completado
- Docker setup (WordPress + MariaDB + Next.js)
- Frontend: 6 páginas completas con diseño moderno
- Profile photo integration
- CV data integration (real experience & skills)
- GitHub repository setup
- Plugin base structure with Clean Architecture
- CaseStudy entity with TDD (18 tests passing)
- Astra child theme
- Agent-based documentation system

### 🚧 En Progreso
- WordPress Custom Post Types implementation
- REST API endpoints
- Backend repository layer

### 📋 Pendiente
- WordPress CPT registration
- Template de visualización
- Formulario de contacto funcional
- Analytics integration
- SEO optimization
- Production deployment

---

## 👥 Usuarios Objetivo

### Primario
- **CTOs** y **Tech Leads**
- Empresas buscando consultoría técnica
- Startups en fase de escalamiento

### Secundario
- **Product Owners**
- **Engineering Managers**
- Empresas en proceso de transformación digital

### Geografía
- Inicialmente mercado hispanohablante
- Expansión a mercado angloparlante (futuro)

---

## 📈 Objetivos de Negocio

1. **Lead Generation**: 10-15 leads cualificados/mes
2. **Conversión**: 20-30% de leads a clientes
3. **Posicionamiento SEO**: Top 10 para keywords principales
4. **Credibilidad**: Portafolio de 6+ casos de estudio
5. **Contenido**: Blog técnico con 2-4 posts/mes

---

## 🔗 Enlaces Importantes

- **Repository**: https://github.com/pellax/worpressinformaticocapellafullsite
- **Frontend Local**: http://localhost:3000
- **Backend Local**: http://localhost:8080
- **Documentation**: `/AGENTS.md`, `/WARP.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17  
**Mantenido por**: Pellax (Informático Capella)
