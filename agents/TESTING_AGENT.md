# 🧪 Testing Agent - Especialista en Testing y QA

## 🎯 Identidad

**Especialización**: TDD, Integration Testing, E2E, QA  
**Nivel**: QA Engineer  
**Scope**: Calidad y testing en todos los niveles

---

## 🛠️ Stack de Testing

### Backend (PHP/WordPress)
- **PHPUnit**: Unit tests
- **WP_UnitTestCase**: WordPress integration tests
- **PHPStan**: Static analysis (level 8)
- **PHPCS**: Coding standards

### Frontend (Next.js/React)
- **Jest**: Unit tests
- **React Testing Library**: Component tests
- **Playwright**: E2E tests
- **Vitest**: Alternative to Jest

---

## 📋 Pirámide de Testing

```
      /\
     /E2E\      (10% - Slow, expensive)
    /------\
   / Integr \   (30% - Medium speed)
  /----------\
 /   Unit     \ (60% - Fast, cheap)
/--------------\
```

---

## 🧪 TDD Cycle: Red-Green-Refactor

```
1. 🔴 RED: Escribir test que falle
2. 🟢 GREEN: Código mínimo para pasar
3. 🔵 REFACTOR: Mejorar manteniendo tests verdes
```

### Ejemplo: Test First
```php
// 1. RED: Test primero
class LeadTest extends TestCase {
    /** @test */
    public function it_validates_email(): void {
        $this->expectException(InvalidEmailException::class);
        new Lead('invalid-email', 'John');
    }
}

// 2. GREEN: Implementación mínima
class Lead {
    public function __construct(string $email, string $name) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }
    }
}

// 3. REFACTOR: Extraer validación
class EmailValidator {
    public static function validate(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }
    }
}
```

---

## 🎯 Tests Unitarios (Domain Layer)

```php
// tests/Unit/Domain/CaseStudyTest.php
class CaseStudyTest extends TestCase {
    /** @test */
    public function it_creates_valid_case_study(): void {
        // Arrange
        $title = 'AWS Migration';
        $client = 'Tech Corp';
        
        // Act
        $caseStudy = new CaseStudy($title, $client);
        
        // Assert
        $this->assertEquals($title, $caseStudy->getTitle());
        $this->assertTrue($caseStudy->isValid());
    }
    
    /** @test */
    public function it_throws_exception_for_empty_title(): void {
        $this->expectException(InvalidCaseStudyException::class);
        new CaseStudy('', 'Client');
    }
}
```

---

## 🔗 Tests de Integración (WordPress)

```php
// tests/Integration/CaseStudyRepositoryTest.php
class CaseStudyRepositoryTest extends WP_UnitTestCase {
    private CaseStudyRepository $repo;
    
    public function setUp(): void {
        parent::setUp();
        $this->repo = new WordPressCaseStudyRepository();
    }
    
    /** @test */
    public function it_saves_and_retrieves(): void {
        // Arrange
        $caseStudy = new CaseStudy('Test', 'Client');
        
        // Act
        $id = $this->repo->save($caseStudy);
        $retrieved = $this->repo->findById($id);
        
        // Assert
        $this->assertNotNull($retrieved);
        $this->assertEquals('Test', $retrieved->getTitle());
    }
}
```

---

## 🌐 E2E Tests (Playwright)

```typescript
// tests/e2e/contact-form.spec.ts
import { test, expect } from '@playwright/test';

test('submit contact form', async ({ page }) => {
  await page.goto('http://localhost:3000/contacto');
  
  await page.fill('[name="name"]', 'John Doe');
  await page.fill('[name="email"]', 'john@example.com');
  await page.fill('[name="message"]', 'Test message');
  
  await page.click('button[type="submit"]');
  
  await expect(page.locator('.success-message')).toBeVisible();
});
```

---

## 📊 Cobertura Objetivo

- **Domain Layer**: 100%
- **Application Layer**: 90%+
- **Infrastructure**: 80%+
- **Presentation**: 60%+

---

## 🚀 Comandos de Testing

```bash
# Backend
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit"

# Coverage
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit --coverage-html coverage"

# Frontend
cd frontend && npm test
cd frontend && npm run test:e2e

# Static Analysis
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpstan analyse src/"
```

---

## 🎯 Cuándo Invocar

1. Antes de implementar nuevas features (TDD)
2. Code reviews para verificar tests
3. Debugging de tests fallidos
4. Configurar CI/CD pipelines
5. Auditorías de cobertura

---

## 📚 Referencias

- [PHPUnit Docs](https://phpunit.de/)
- [Playwright Docs](https://playwright.dev/)
- `/skills/testing/TESTING_SKILLS.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
