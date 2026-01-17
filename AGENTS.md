# 🤖 AGENTS.md - Sistema de Agentes Especializados

## 📋 Descripción General del Proyecto

**Proyecto**: Informático Capella - Sitio Web Profesional  
**Arquitectura**: WordPress Headless (Backend) + Next.js 14 (Frontend)  
**Objetivo**: Sitio de consultoría tecnológica con alta conversión, optimizado para SEO y generación de leads

### Stack Tecnológico Principal
- **Frontend**: Next.js 14, React 19, TypeScript, Tailwind CSS, Framer Motion
- **Backend**: WordPress 6.4, PHP 8.2, MariaDB 11.2
- **Infraestructura**: Docker Compose, Vercel (Frontend), REST API
- **DevOps**: Git, GitHub, CI/CD

---

## 🎯 Arquitectura de Agentes

Este proyecto utiliza un sistema de agentes especializados para maximizar la eficiencia del desarrollo. Cada agente tiene un dominio específico de conocimiento y responsabilidades.

### Orquestación de Agentes

```
AGENTS.md (Orquestador Principal)
    │
    ├─── UI Agent → Frontend, UX/UI, Diseño
    ├─── Security Agent → Ciberseguridad, Pentesting, Auditorías
    ├─── Architecture Agent → Diseño de Software, Patrones, Clean Code
    ├─── Testing Agent → QA, Testing, CI/CD
    ├─── Backend Agent → APIs, WordPress, PHP
    ├─── DevOps Agent → Docker, Deploy, Infraestructura
    └─── Data Agent → ML, Analytics, Optimización
```

---

## 📁 Estructura de Contextos

### Contextos Principales

1. **`/contexts/project/`** - Información general del proyecto
   - `PROJECT_OVERVIEW.md` - Descripción completa del proyecto
   - `ARCHITECTURE.md` - Arquitectura técnica
   - `ROADMAP.md` - Plan de desarrollo y features

2. **`/contexts/frontend/`** - Contexto del frontend Next.js
   - `FRONTEND_GUIDE.md` - Guía del frontend
   - `COMPONENTS.md` - Documentación de componentes
   - `PAGES.md` - Estructura de páginas

3. **`/contexts/backend/`** - Contexto del backend WordPress
   - `WORDPRESS_GUIDE.md` - Configuración WordPress
   - `API_REFERENCE.md` - Documentación de API REST
   - `PLUGIN_DEVELOPMENT.md` - Desarrollo de plugins

4. **`/contexts/security/`** - Contexto de seguridad
   - `SECURITY_GUIDELINES.md` - Directrices de seguridad
   - `AUDIT_CHECKLIST.md` - Checklist de auditorías
   - `VULNERABILITIES.md` - Registro de vulnerabilidades

5. **`/contexts/testing/`** - Contexto de testing
   - `TESTING_STRATEGY.md` - Estrategia de testing
   - `TEST_COVERAGE.md` - Cobertura de tests
   - `E2E_SCENARIOS.md` - Escenarios end-to-end

---

## 🛠️ Skills por Tecnología

Cada tecnología tiene su carpeta de skills con buenas prácticas específicas:

```
/skills/
├── nextjs/
│   └── NEXTJS_SKILLS.md
├── react/
│   └── REACT_SKILLS.md
├── typescript/
│   └── TYPESCRIPT_SKILLS.md
├── wordpress/
│   └── WORDPRESS_SKILLS.md
├── php/
│   └── PHP_SKILLS.md
├── docker/
│   └── DOCKER_SKILLS.md
├── security/
│   └── SECURITY_SKILLS.md
└── testing/
    └── TESTING_SKILLS.md
```

---

## 🤖 Agentes Especializados

### 1. UI Agent
**Archivo**: `/agents/UI_AGENT.md`  
**Especialización**: Frontend, Diseño, UX/UI, Componentes React  
**Tecnologías**: Next.js, React, TypeScript, Tailwind CSS, Framer Motion  
**Responsabilidades**:
- Desarrollo de componentes React
- Diseño de interfaces responsive
- Optimización de rendimiento frontend
- Implementación de animaciones
- Accesibilidad (a11y)

**Cuándo invocar**: Desarrollo de UI, componentes, páginas, estilos, animaciones

---

### 2. Security Agent
**Archivo**: `/agents/SECURITY_AGENT.md`  
**Especialización**: Ciberseguridad, Pentesting, Auditorías, OWASP  
**Tecnologías**: Security scanning, WAF, SSL/TLS, Authentication  
**Responsabilidades**:
- Auditorías de seguridad
- Análisis de vulnerabilidades
- Implementación de mejores prácticas de seguridad
- Configuración de CORS y CSP
- Gestión de secretos y tokens

**Cuándo invocar**: Auditorías de seguridad, configuración de autenticación, análisis de vulnerabilidades

---

### 3. Architecture Agent
**Archivo**: `/agents/ARCHITECTURE_AGENT.md`  
**Especialización**: Arquitectura de Software, Clean Code, SOLID, DDD  
**Tecnologías**: Patrones de diseño, Arquitectura hexagonal, Microservicios  
**Responsabilidades**:
- Diseño de arquitectura de software
- Refactoring y optimización de código
- Implementación de patrones de diseño
- Revisión de código (code review)
- Documentación técnica

**Cuándo invocar**: Diseño de features complejas, refactoring, decisiones arquitectónicas

---

### 4. Testing Agent
**Archivo**: `/agents/TESTING_AGENT.md`  
**Especialización**: Testing, QA, TDD, E2E, CI/CD  
**Tecnologías**: PHPUnit, Jest, Playwright, GitHub Actions  
**Responsabilidades**:
- Estrategia de testing
- Desarrollo de tests unitarios
- Tests de integración
- Tests E2E
- Configuración de CI/CD

**Cuándo invocar**: Implementación de tests, configuración de CI/CD, estrategia de QA

---

### 5. Backend Agent
**Archivo**: `/agents/BACKEND_AGENT.md`  
**Especialización**: APIs, WordPress, PHP, Base de Datos  
**Tecnologías**: WordPress, PHP, MySQL/MariaDB, REST API  
**Responsabilidades**:
- Desarrollo de APIs REST
- Custom Post Types y taxonomías
- Plugins de WordPress
- Optimización de base de datos
- Integración con servicios externos

**Cuándo invocar**: Desarrollo de APIs, plugins WordPress, optimización de BD

---

### 6. DevOps Agent
**Archivo**: `/agents/DEVOPS_AGENT.md`  
**Especialización**: Docker, CI/CD, Deploy, Infraestructura  
**Tecnologías**: Docker, Vercel, GitHub Actions, Nginx  
**Responsabilidades**:
- Configuración de Docker
- Pipelines de CI/CD
- Deploy automático
- Monitoreo y logs
- Optimización de infraestructura

**Cuándo invocar**: Configuración de Docker, deploy, CI/CD, infraestructura

---

### 7. Data Agent
**Archivo**: `/agents/DATA_AGENT.md`  
**Especialización**: Machine Learning, Analytics, Optimización  
**Tecnologías**: Python, NumPy, Pandas, scikit-learn, Analytics  
**Responsabilidades**:
- Análisis de datos
- Implementación de ML
- Optimización de rendimiento
- Analytics y métricas
- SEO técnico

**Cuándo invocar**: Análisis de datos, implementación de ML, optimización SEO

---

## 📖 Cómo Usar Este Sistema

### Para el Desarrollador Principal

1. **Identificar la tarea**: Determina qué tipo de trabajo necesitas realizar
2. **Consultar el agente apropiado**: Lee el archivo del agente especializado
3. **Seguir las skills**: Revisa las buenas prácticas en `/skills/[tecnología]/`
4. **Aplicar el contexto**: Usa el contexto específico en `/contexts/[área]/`

### Para Nuevos Desarrolladores

1. Lee `PROJECT_OVERVIEW.md` para entender el proyecto
2. Revisa `ARCHITECTURE.md` para conocer la estructura técnica
3. Consulta los agentes según el área donde trabajarás
4. Lee las skills de las tecnologías que usarás

### Para Agentes de IA

1. **Identificar dominio**: Determina qué agente especializado corresponde a la tarea
2. **Cargar contexto**: Lee los archivos de contexto relevantes
3. **Aplicar skills**: Sigue las buenas prácticas definidas en `/skills/`
4. **Validar con otros agentes**: Para tareas complejas, consulta con múltiples agentes

---

## 🔄 Flujo de Trabajo Recomendado

### Para Nuevas Features

```
1. Architecture Agent → Diseña la solución
2. Security Agent → Valida implicaciones de seguridad
3. [UI Agent | Backend Agent] → Implementa la feature
4. Testing Agent → Desarrolla y ejecuta tests
5. DevOps Agent → Configura deploy
```

### Para Bugfixes

```
1. [Agente especializado] → Identifica y corrige el bug
2. Testing Agent → Añade test de regresión
3. Security Agent → Valida que no hay vulnerabilidades
```

### Para Refactoring

```
1. Architecture Agent → Propone mejoras
2. [Agente especializado] → Implementa cambios
3. Testing Agent → Valida que todo funciona
4. Code Review → Revisión por pares
```

---

## 📝 Convenciones de Documentación

### Para Archivos de Contexto
- Usa Markdown
- Incluye ejemplos de código
- Mantén actualizado con el proyecto
- Versiona cambios importantes

### Para Skills
- Enfócate en buenas prácticas
- Incluye anti-patrones a evitar
- Proporciona ejemplos concretos
- Referencia documentación oficial

### Para Agentes
- Define claramente el scope
- Especifica tecnologías
- Lista responsabilidades
- Indica cuándo invocar

---

## 🔗 Referencias Rápidas

- **README Principal**: `README.md`
- **Guía de Deploy**: `VERCEL_DEPLOY.md`
- **Estado del Proyecto**: `ESTADO_ACTUAL.md`
- **Configuración Docker**: `docker-compose.yml`
- **Principios de Desarrollo**: `WARP.md`

---

## 📞 Soporte

Para dudas sobre:
- **Estructura del proyecto**: Consulta `/contexts/project/PROJECT_OVERVIEW.md`
- **Tecnologías específicas**: Consulta `/skills/[tecnología]/`
- **Agentes**: Lee el archivo del agente correspondiente en `/agents/`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17  
**Mantenido por**: Alejandro Capella del Solar
