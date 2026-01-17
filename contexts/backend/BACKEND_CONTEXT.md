# ⚙️ Backend Context - WordPress Headless CMS

## 🎯 Overview

WordPress 6.9 configurado como headless CMS con Clean Architecture en plugin custom.

---

## 📁 Estructura

```
plugins/informatico-capella-core/
├── src/
│   ├── Domain/              # Pure PHP - Business Logic
│   │   ├── Entities/
│   │   │   └── CaseStudy.php (✅ 18 tests passing)
│   │   ├── Repositories/    # Interfaces
│   │   │   └── CaseStudyRepository.php
│   │   └── Exceptions/
│   │       └── InvalidCaseStudyException.php
│   ├── Application/         # Use Cases
│   ├── Infrastructure/      # WordPress implementations
│   └── Presentation/        # REST API Controllers
├── tests/
│   ├── Unit/               # ✅ 18 tests passing
│   ├── Integration/
│   └── E2E/
├── composer.json
└── phpunit.xml
```

---

## 🗄️ Database

### WordPress Core Tables
- wp_posts (posts, pages, CPTs)
- wp_postmeta (custom fields)
- wp_users
- wp_usermeta

### Connection
- Host: informaticocapella_db (Docker)
- Database: informaticocapella_db
- User: capella_user
- Password: capella_secure_pass_2024

---

## 🔌 Custom Post Types (Planned)

### 1. Case Studies
```php
register_post_type('case_study', [
    'labels' => [...],
    'public' => true,
    'show_in_rest' => true,
    'supports' => ['title', 'editor', 'thumbnail'],
]);
```

### 2. Testimonials (Future)
### 3. Services (Future)

---

## 🌐 REST API Endpoints (Planned)

```
GET  /wp-json/informatico/v1/case-studies
GET  /wp-json/informatico/v1/case-studies/{id}
POST /wp-json/informatico/v1/contact
```

---

## 🧪 Testing

### Current Status
- ✅ CaseStudy entity: 18 tests passing
- ⏳ Repository implementation: pending
- ⏳ Integration tests: pending

### Commands
```bash
# Run all tests
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit"

# Unit tests only
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit tests/Unit"
```

---

## 📚 Referencias

- `/agents/BACKEND_AGENT.md`
- `/agents/ARCHITECTURE_AGENT.md`
- `/skills/wordpress/WORDPRESS_SKILLS.md`
- `/skills/php/PHP_SKILLS.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
