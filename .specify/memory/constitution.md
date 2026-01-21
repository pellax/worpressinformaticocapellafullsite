# Informático Capella Constitution

## Core Principles

### I. Clean Architecture First
Every feature must follow Clean Architecture principles with clear separation:
- Domain layer (pure business logic, no WordPress dependencies)
- Application layer (use cases, orchestration)
- Infrastructure layer (WordPress/Next.js implementations)
- Presentation layer (REST controllers, React components)

### II. Test-Driven Development (NON-NEGOTIABLE)
TDD mandatory: Tests written → User approved → Tests fail → Then implement
- Domain entities: 100% test coverage required
- Backend: PHPUnit with Unit/Integration/E2E suites
- Frontend: Jest unit tests + Playwright E2E (planned)
- Red-Green-Refactor cycle strictly enforced

### III. API Contract First
WordPress-Next.js communication via documented REST API:
- Custom endpoints: /wp-json/informatico/v1/*
- JSON schema validation required
- Contract tests for all endpoints
- ISR caching strategy documented

### IV. Agent-Based Development
Leverage specialized documentation agents:
- UI Agent: Frontend, React, TypeScript, Tailwind
- Security Agent: Vulnerability assessment, hardening
- Architecture Agent: Design patterns, clean code
- Backend Agent: WordPress, PHP, database design

### V. Component-Driven Frontend
React development follows modern patterns:
- Server Components by default (performance)
- Client Components only for interactivity ('use client')
- Tailwind utility-first styling
- TypeScript strict mode required

## Technology Standards

### Approved Stack
- **Frontend**: Next.js 14 (App Router), React 19, TypeScript 5, Tailwind CSS
- **Backend**: WordPress 6.4, PHP 8.2, MariaDB 11.2
- **Testing**: PHPUnit (backend), Jest/Playwright (frontend planned)
- **Infrastructure**: Docker Compose, Vercel, OVH

### Development Constraints
- No jQuery in new frontend code
- No inline styles (use Tailwind classes)
- No direct WordPress database queries (use repositories)
- No hardcoded credentials or API keys

## Development Workflow

### Feature Specification Process
1. Create feature spec using .specify/templates/spec-template.md
2. Define user stories with priorities (P1, P2, P3)
3. Write acceptance criteria (Given/When/Then)
4. Get stakeholder approval before implementation

### Implementation Process
1. Write failing tests first (Domain layer)
2. Implement Domain entities (pure PHP/TypeScript)
3. Add Application layer (use cases)
4. Implement Infrastructure layer (WordPress/Next.js)
5. Create Presentation layer (REST API/React components)
6. Update relevant agent documentation

### Quality Gates
- All PHPUnit tests must pass (18+ currently passing)
- Frontend lint/build must succeed (npm run lint, npm run build)
- Docker services must start successfully
- Security headers configured (Next.js security headers)
- Agent documentation updated for new features

## Governance

Constitution supersedes all other development practices.
All PRs must verify compliance with these principles.
Agent-based documentation system maintains project knowledge.
Use AGENTS.md for runtime development guidance.

**Version**: 1.0 | **Ratified**: 2026-01-21 | **Last Amended**: 2026-01-21