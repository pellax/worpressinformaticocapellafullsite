# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 🏗️ Project Architecture

**Informático Capella** is a professional consulting website built with a headless WordPress + Next.js architecture:

- **Frontend**: Next.js 14 (App Router) + React 19 + TypeScript + Tailwind CSS (Port 3000)
- **Backend**: WordPress 6.4 + MariaDB 11.2 (Port 8080)
- **Infrastructure**: Docker Compose for local development, Vercel for frontend deployment
- **Repository**: https://github.com/pellax/worpressinformaticocapellafullsite

## 🛠️ Development Commands

### Docker Stack Management
```bash
# Start all services
docker-compose up -d --build

# Stop all services
docker-compose down

# View logs
docker-compose logs -f
docker logs -f informaticocapella_frontend
docker logs -f informaticocapella_wp

# Restart services
docker-compose restart
docker-compose restart frontend
```

### Frontend Development
```bash
cd frontend

# Install dependencies
npm install

# Development server (local, connects to Docker WordPress)
npm run dev

# Build for production
npm run build

# Start production server
npm start

# Lint code
npm run lint
```

### WordPress Backend
- **Local URL**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/wp-admin
- **Credentials**: admin / Admin2024Capella!
- **REST API**: http://localhost:8080/wp-json/wp/v2

### Database Access
- **Host**: localhost:3306 (from host) or db:3306 (from container)
- **Database**: informaticocapella_db
- **User**: capella_user
- **Password**: capella_secure_pass_2024

## 📁 Code Structure

### Frontend Architecture (Next.js 14 App Router)
```
frontend/
├── app/                    # Pages using App Router
│   ├── page.tsx           # Homepage
│   ├── sobre-mi/page.tsx  # About page
│   ├── servicios/page.tsx # Services page
│   ├── portafolio/page.tsx# Portfolio page
│   ├── experiencia/page.tsx# Experience page
│   ├── contacto/page.tsx  # Contact page
│   ├── layout.tsx         # Root layout
│   └── globals.css        # Global styles
├── components/            # React components
│   ├── Navbar.tsx
│   ├── Hero.tsx
│   └── Footer.tsx
├── lib/
│   └── wordpress.ts       # WordPress API client
└── public/                # Static assets
    └── profile.jpg
```

### Backend Architecture (Clean Architecture)
```
plugins/informatico-capella-core/
├── src/
│   ├── Domain/            # Business logic (pure PHP)
│   │   ├── Entities/      # Core entities
│   │   ├── Repositories/  # Interface definitions
│   │   └── Exceptions/    # Domain exceptions
│   ├── Application/       # Use cases
│   │   ├── UseCases/
│   │   └── DTOs/
│   ├── Infrastructure/    # WordPress implementations
│   │   ├── Repositories/  # WordPress data layer
│   │   └── Services/
│   └── Presentation/      # Controllers and views
│       ├── Controllers/
│       └── REST/
└── tests/                 # PHPUnit test suites
    ├── Unit/             # Domain layer tests
    ├── Integration/      # WordPress integration tests
    └── E2E/              # End-to-end tests
```

## 🎨 Design System

- **Colors**: Blue (#0EA5E9) to Emerald (#10B981) gradients
- **Backgrounds**: Slate-900/950 for dark theme
- **Typography**: Inter font family
- **Framework**: Tailwind CSS with utility-first approach
- **Responsive**: Mobile-first design

## 🔧 Technical Patterns

### Frontend Patterns
- **Server Components**: Default for better performance
- **Client Components**: Only when interactivity is needed (use `'use client'`)
- **Data Fetching**: Next.js native fetch with ISR caching
- **State Management**: URL state + React Server Components (no global state library)

### Backend Patterns
- **Clean Architecture**: 4-layer separation (Domain/Application/Infrastructure/Presentation)
- **Repository Pattern**: Abstract data persistence
- **TDD Approach**: Tests first, especially for Domain layer
- **WordPress Integration**: Custom plugin following WordPress standards

### API Design
- **REST API**: WordPress REST API + custom endpoints
- **Endpoints**: `/wp-json/informatico/v1/` namespace for custom APIs
- **Caching**: Next.js ISR with 1-hour revalidation for most content

## 🧪 Testing Strategy

### Backend Testing (PHPUnit)
```bash
cd plugins/informatico-capella-core
./vendor/bin/phpunit                    # Run all tests
./vendor/bin/phpunit --testsuite=Unit   # Run unit tests only
```

### Frontend Testing (Not yet implemented)
- Jest for unit/integration tests
- Playwright for E2E tests

**Current Status**: Backend has 18 passing tests for CaseStudy entity

## 🔒 Security Configuration

### Next.js Security Headers
Already configured in `next.config.ts`:
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- Strict-Transport-Security
- X-XSS-Protection
- Referrer-Policy
- Permissions-Policy

### WordPress Security
- Debug mode enabled for development
- Custom plugin follows WordPress security best practices
- Input sanitization and validation in custom endpoints

## 📦 Deployment

### Frontend (Vercel)
- **Root Directory**: `frontend`
- **Build Command**: `npm run build`
- **Environment Variables**:
  - `NEXT_PUBLIC_WORDPRESS_API_URL=http://localhost:8080/wp-json/wp/v2`
  - `NEXT_PUBLIC_SITE_URL=http://localhost:3000`

### Backend (Local Development)
- Runs in Docker container
- Volume mounts for plugins, themes, and uploads
- Database persisted in Docker volume

## 🤖 Agent-Based Development

This project uses a specialized agent system documented in `AGENTS.md`:

- **UI Agent**: Frontend, React, TypeScript, Tailwind
- **Security Agent**: Security audits, vulnerability analysis
- **Architecture Agent**: Clean Architecture, design patterns
- **Testing Agent**: TDD, PHPUnit, testing strategy
- **Backend Agent**: WordPress, PHP, API development
- **DevOps Agent**: Docker, deployment, CI/CD

Consult the appropriate agent documentation in `/agents/` for specialized tasks.

## 📋 Common Development Tasks

### Adding a New Page
1. Create `app/new-page/page.tsx` following App Router conventions
2. Add navigation link in `components/Navbar.tsx`
3. Update sitemap if needed

### Adding a Custom Post Type
1. Define entity in `src/Domain/Entities/`
2. Create repository interface in `src/Domain/Repositories/`
3. Implement WordPress repository in `src/Infrastructure/Repositories/`
4. Add REST controller in `src/Presentation/REST/`
5. Write comprehensive tests

### API Integration
- WordPress API client is in `lib/wordpress.ts`
- Use Next.js fetch with caching: `{ next: { revalidate: 3600 } }`
- Handle errors gracefully with try/catch

## 🔍 Troubleshooting

### Frontend not connecting to WordPress
```bash
# Check WordPress container is running
docker ps

# Check WordPress logs
docker logs informaticocapella_wp

# Verify API endpoint
curl http://localhost:8080/wp-json/wp/v2
```

### Permission errors in WordPress
```bash
docker exec -it informaticocapella_wp chown -R www-data:www-data /var/www/html
```

### Reset everything
```bash
docker-compose down -v  # ⚠️ This deletes the database
docker-compose up -d --build
```

## 📚 Key Documentation Files

- `README.md` - Project setup and overview
- `AGENTS.md` - Agent-based development system
- `contexts/project/PROJECT_OVERVIEW.md` - Detailed project information
- `contexts/project/ARCHITECTURE.md` - Technical architecture decisions
- `WARP.md` - Development principles and methodology

## ⚙️ Environment Configuration

### Local Development Environment Variables
Frontend `.env.local`:
```
NEXT_PUBLIC_WORDPRESS_API_URL=http://localhost:8080/wp-json/wp/v2
NEXT_PUBLIC_SITE_URL=http://localhost:3000
```

### Docker Configuration
All environment variables are configured in `docker-compose.yml` - no manual setup needed for local development.
## 📝 Spec-Driven Development

### Constitution
This project follows a constitutional development approach defined in `.specify/memory/constitution.md`

### Feature Development Process
1. **Create Spec**: Use `.specify/templates/spec-template.md` for new features
2. **Follow Constitution**: All development must comply with constitutional principles
3. **Agent Integration**: Consult appropriate agents from `/agents/` directory
4. **TDD Approach**: Tests first, especially for Domain layer

### Available Scripts
- `.specify/scripts/bash/create-new-feature.sh` - Create new feature spec
- `.specify/scripts/bash/check-prerequisites.sh` - Validate setup
- `.specify/scripts/bash/update-agent-context.sh` - Update agent knowledge

### Templates
- `.specify/templates/spec-template.md` - Feature specifications
- `.specify/templates/plan-template.md` - Development plans
- `.specify/templates/tasks-template.md` - Task breakdowns

