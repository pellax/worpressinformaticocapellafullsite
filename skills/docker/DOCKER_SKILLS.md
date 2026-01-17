# 🐳 Docker Skills - Best Practices

## 🎯 Dockerfile Best Practices

### Multi-Stage Builds
```dockerfile
# Stage 1: Dependencies
FROM node:22-alpine AS deps
WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production

# Stage 2: Builder
FROM node:22-alpine AS builder
WORKDIR /app
COPY --from=deps /app/node_modules ./node_modules
COPY . .
RUN npm run build

# Stage 3: Production Runner
FROM node:22-alpine AS runner
WORKDIR /app
ENV NODE_ENV=production

COPY --from=builder /app/.next/standalone ./
COPY --from=builder /app/public ./public
COPY --from=builder /app/.next/static ./.next/static

EXPOSE 3000
CMD ["node", "server.js"]
```

### Optimize Layer Caching
```dockerfile
# ✅ GOOD: Copy package files first
COPY package*.json ./
RUN npm install

# Then copy source code
COPY . .

# ❌ BAD: Copy everything first
COPY . .
RUN npm install  # Rebuilds every time code changes
```

### Minimize Image Size
```dockerfile
# ✅ Use alpine variants
FROM node:22-alpine

# ✅ Clean up in same layer
RUN apt-get update && \
    apt-get install -y package && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# ✅ Use .dockerignore
# node_modules
# .git
# *.md
```

---

## 🔧 Docker Compose

### Basic Structure
```yaml
version: '3.8'

services:
  wordpress:
    image: wordpress:6.4-php8.2-apache
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: ${DB_USER}
      WORDPRESS_DB_PASSWORD: ${DB_PASSWORD}
      WORDPRESS_DB_NAME: ${DB_NAME}
    volumes:
      - ./plugins:/var/www/html/wp-content/plugins
      - ./themes:/var/www/html/wp-content/themes
    depends_on:
      - db
    networks:
      - app-network

  db:
    image: mariadb:11.2
    environment:
      MYSQL_ROOT_PASSWORD: ${ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_NAME}
      MYSQL_USER: ${DB_USER}
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - app-network

  frontend:
    build:
      context: ./frontend
      dockerfile: Dockerfile
    ports:
      - "3000:3000"
    environment:
      NEXT_PUBLIC_WORDPRESS_API_URL: ${WORDPRESS_API_URL}
    depends_on:
      - wordpress
    networks:
      - app-network

volumes:
  db_data:

networks:
  app-network:
    driver: bridge
```

### Environment Variables
```bash
# .env file
DB_NAME=informaticocapella_db
DB_USER=capella_user
DB_PASSWORD=capella_secure_pass_2024
ROOT_PASSWORD=root_secure_pass_2024
WORDPRESS_API_URL=http://localhost:8080/wp-json/wp/v2
```

---

## 🚀 Common Commands

### Container Management
```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f wordpress

# Execute command in container
docker exec -it container_name bash

# Restart specific service
docker-compose restart wordpress
```

### Image Management
```bash
# Build image
docker build -t myapp:latest .

# List images
docker images

# Remove image
docker rmi image_id

# Prune unused images
docker image prune -a
```

### Volume Management
```bash
# List volumes
docker volume ls

# Remove volume
docker volume rm volume_name

# Prune unused volumes
docker volume prune
```

---

## 🔐 Security Best Practices

### Don't Run as Root
```dockerfile
# ✅ Create non-root user
FROM node:22-alpine

RUN addgroup -g 1001 -S nodejs && \
    adduser -S nextjs -u 1001

USER nextjs

COPY --chown=nextjs:nodejs . .
```

### Scan for Vulnerabilities
```bash
# Use Docker Scout
docker scout cves myapp:latest

# Or Trivy
trivy image myapp:latest
```

### Use Secrets
```yaml
# docker-compose.yml
services:
  app:
    secrets:
      - db_password

secrets:
  db_password:
    file: ./secrets/db_password.txt
```

---

## 📊 Health Checks

```dockerfile
# Add health check to Dockerfile
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:3000/api/health || exit 1
```

```yaml
# docker-compose.yml
services:
  wordpress:
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost/"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s
```

---

## 📚 Referencias

- [Docker Docs](https://docs.docker.com/)
- [Dockerfile Best Practices](https://docs.docker.com/develop/develop-images/dockerfile_best-practices/)
- `/agents/DEVOPS_AGENT.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
