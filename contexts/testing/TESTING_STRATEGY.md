# 🧪 Testing Strategy - Informático Capella

## 🎯 Testing Philosophy

Seguimos **Test-Driven Development (TDD)** y la pirámide de testing: 60% Unit, 30% Integration, 10% E2E.

---

## 📊 Test Pyramid

```
        /\
       /E2E\      10% - Slow, expensive
      /------\
     / Integr \   30% - Medium speed
    /----------\
   /   Unit     \ 60% - Fast, cheap
  /--------------\
```

---

## 🔴 TDD Workflow

```
1. 🔴 RED: Write failing test
2. 🟢 GREEN: Write minimum code to pass
3. 🔵 REFACTOR: Improve code while tests stay green
```

### Example
```php
// 1. RED - Write test first
public function test_case_study_requires_title(): void {
    $this->expectException(InvalidCaseStudyException::class);
    new CaseStudy('', 'Client');
}

// 2. GREEN - Implement
class CaseStudy {
    public function __construct(string $title, string $client) {
        if (empty($title)) {
            throw new InvalidCaseStudyException();
        }
    }
}

// 3. REFACTOR - Extract validation
class CaseStudy {
    public function __construct(string $title, string $client) {
        $this->validateTitle($title);
    }
    
    private function validateTitle(string $title): void {
        if (empty($title)) {
            throw new InvalidCaseStudyException();
        }
    }
}
```

---

## 🧪 Backend Testing (PHPUnit)

### Current Status
```
✅ Unit Tests: 18 passing (CaseStudy entity)
⏳ Integration Tests: 0 (pending)
⏳ E2E Tests: 0 (pending)
```

### Test Suites
```xml
<!-- phpunit.xml -->
<testsuites>
    <testsuite name="Unit">
        <directory>tests/Unit</directory>
    </testsuite>
    <testsuite name="Integration">
        <directory>tests/Integration</directory>
    </testsuite>
    <testsuite name="E2E">
        <directory>tests/E2E</directory>
    </testsuite>
</testsuites>
```

### Commands
```bash
# All tests
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit"

# Specific suite
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit tests/Unit"

# With coverage
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit --coverage-html coverage"
```

---

## ⚛️ Frontend Testing (Planned)

### Tools
- **Jest**: Unit and integration tests
- **React Testing Library**: Component tests
- **Playwright**: E2E tests

### Setup (TODO)
```bash
cd frontend
npm install -D jest @testing-library/react @testing-library/jest-dom
npm install -D @playwright/test
```

---

## 📈 Coverage Goals

### Backend
- **Domain Layer**: 100% coverage (business logic)
- **Application Layer**: 90%+ (use cases)
- **Infrastructure Layer**: 80%+ (implementations)
- **Presentation Layer**: 60%+ (controllers)

### Frontend (Target)
- **Utilities**: 100%
- **Components**: 80%+
- **Pages**: 60%+

### Current Coverage
```
Domain:         100% (18/18 tests passing)
Application:    0% (not implemented yet)
Infrastructure: 0% (not implemented yet)
Presentation:   0% (not implemented yet)
```

---

## 🎯 What to Test

### ✅ Always Test
- Business logic (Domain layer)
- Data transformations
- Validation rules
- Edge cases
- Error handling

### ⚠️ Test When Needed
- API integrations
- Database queries
- Third-party libraries

### ❌ Don't Test
- Framework internals (WordPress, Next.js)
- Third-party library internals
- Getters/setters without logic

---

## 🔄 CI/CD Integration (Planned)

```yaml
# .github/workflows/test.yml
name: Tests

on: [push, pull_request]

jobs:
  backend-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Run PHPUnit
        run: |
          docker-compose up -d
          docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit"
  
  frontend-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Run Jest
        run: |
          cd frontend
          npm install
          npm test
```

---

## 🚀 Next Steps

### Short Term
1. ✅ Implement WordPressCaseStudyRepository
2. ✅ Write integration tests for repository
3. ✅ Write E2E test for case study creation

### Medium Term
1. Setup Jest for frontend
2. Write component tests
3. Setup Playwright for E2E
4. Integrate tests in CI/CD

### Long Term
1. Achieve 80%+ overall coverage
2. Automated visual regression tests
3. Performance testing
4. Load testing

---

## 📚 Referencias

- [PHPUnit Docs](https://phpunit.de/)
- [Jest Docs](https://jestjs.io/)
- [Playwright Docs](https://playwright.dev/)
- `/agents/TESTING_AGENT.md`
- `/skills/testing/TESTING_SKILLS.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
