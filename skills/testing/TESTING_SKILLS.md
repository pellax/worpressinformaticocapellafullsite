# 🧪 Testing Skills - Best Practices

## 🎯 TDD Cycle

```
1. 🔴 RED: Write failing test
2. 🟢 GREEN: Write minimum code to pass
3. 🔵 REFACTOR: Improve code while keeping tests green
```

---

## 🧪 PHPUnit (Backend Testing)

### Unit Test Example
```php
<?php
namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use InformaticoCapella\Domain\Entities\CaseStudy;

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
        $this->assertEquals($client, $caseStudy->getClient());
        $this->assertTrue($caseStudy->isValid());
    }
    
    /** @test */
    public function it_throws_exception_for_empty_title(): void {
        // Assert
        $this->expectException(InvalidCaseStudyException::class);
        
        // Act
        new CaseStudy('', 'Client');
    }
}
```

### Integration Test (WordPress)
```php
<?php
namespace Tests\Integration;

use WP_UnitTestCase;

class CaseStudyRepositoryTest extends WP_UnitTestCase {
    private CaseStudyRepository $repository;
    
    public function setUp(): void {
        parent::setUp();
        $this->repository = new WordPressCaseStudyRepository();
    }
    
    /** @test */
    public function it_saves_and_retrieves_case_study(): void {
        // Arrange
        $caseStudy = new CaseStudy('Test', 'Client');
        
        // Act
        $id = $this->repository->save($caseStudy);
        $retrieved = $this->repository->findById($id);
        
        // Assert
        $this->assertNotNull($retrieved);
        $this->assertEquals('Test', $retrieved->getTitle());
    }
}
```

### Run Tests
```bash
# All tests
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit"

# Specific suite
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit tests/Unit"

# With coverage
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit --coverage-html coverage"
```

---

## ⚛️ Jest (Frontend Testing)

### Component Test
```typescript
// Button.test.tsx
import { render, screen, fireEvent } from '@testing-library/react';
import { Button } from './Button';

describe('Button', () => {
  it('renders with label', () => {
    render(<Button label="Click me" onClick={() => {}} />);
    expect(screen.getByText('Click me')).toBeInTheDocument();
  });
  
  it('calls onClick when clicked', () => {
    const handleClick = jest.fn();
    render(<Button label="Click" onClick={handleClick} />);
    
    fireEvent.click(screen.getByText('Click'));
    
    expect(handleClick).toHaveBeenCalledTimes(1);
  });
  
  it('is disabled when disabled prop is true', () => {
    render(<Button label="Click" onClick={() => {}} disabled />);
    expect(screen.getByRole('button')).toBeDisabled();
  });
});
```

### Hook Test
```typescript
// useLocalStorage.test.ts
import { renderHook, act } from '@testing-library/react';
import { useLocalStorage } from './useLocalStorage';

describe('useLocalStorage', () => {
  beforeEach(() => {
    localStorage.clear();
  });
  
  it('returns initial value', () => {
    const { result } = renderHook(() => useLocalStorage('key', 'initial'));
    expect(result.current[0]).toBe('initial');
  });
  
  it('updates value', () => {
    const { result } = renderHook(() => useLocalStorage('key', 'initial'));
    
    act(() => {
      result.current[1]('updated');
    });
    
    expect(result.current[0]).toBe('updated');
    expect(localStorage.getItem('key')).toBe(JSON.stringify('updated'));
  });
});
```

---

## 🎭 Playwright (E2E Testing)

### E2E Test Example
```typescript
// tests/e2e/contact-form.spec.ts
import { test, expect } from '@playwright/test';

test.describe('Contact Form', () => {
  test('submits form successfully', async ({ page }) => {
    // Navigate
    await page.goto('http://localhost:3000/contacto');
    
    // Fill form
    await page.fill('[name="name"]', 'John Doe');
    await page.fill('[name="email"]', 'john@example.com');
    await page.fill('[name="message"]', 'Test message here');
    
    // Submit
    await page.click('button[type="submit"]');
    
    // Assert success message
    await expect(page.locator('.success-message')).toBeVisible();
    await expect(page.locator('.success-message')).toContainText('Mensaje enviado');
  });
  
  test('shows validation errors', async ({ page }) => {
    await page.goto('http://localhost:3000/contacto');
    
    // Submit empty form
    await page.click('button[type="submit"]');
    
    // Assert error messages
    await expect(page.locator('.error-name')).toBeVisible();
    await expect(page.locator('.error-email')).toBeVisible();
  });
});
```

### Run E2E Tests
```bash
# Install
npm install -D @playwright/test

# Run tests
npx playwright test

# Run with UI
npx playwright test --ui

# Run specific test
npx playwright test contact-form.spec.ts
```

---

## 📊 Test Coverage Goals

### Backend (PHPUnit)
- **Domain Layer**: 100% coverage
- **Application Layer**: 90%+
- **Infrastructure Layer**: 80%+
- **Presentation Layer**: 60%+

### Frontend (Jest)
- **Utilities/Helpers**: 100%
- **Components**: 80%+
- **Pages**: 60%+

---

## 🎯 Testing Best Practices

### AAA Pattern
```typescript
test('example', () => {
  // Arrange - Setup
  const value = 10;
  
  // Act - Execute
  const result = double(value);
  
  // Assert - Verify
  expect(result).toBe(20);
});
```

### Test Isolation
```typescript
// ✅ Each test is independent
describe('Calculator', () => {
  let calculator: Calculator;
  
  beforeEach(() => {
    calculator = new Calculator(); // Fresh instance
  });
  
  test('adds numbers', () => {
    expect(calculator.add(2, 3)).toBe(5);
  });
  
  test('subtracts numbers', () => {
    expect(calculator.subtract(5, 3)).toBe(2);
  });
});
```

### Mock External Dependencies
```typescript
// ✅ Mock API calls
jest.mock('./api', () => ({
  fetchUser: jest.fn().mockResolvedValue({ id: 1, name: 'John' })
}));

test('fetches user', async () => {
  const user = await fetchUser(1);
  expect(user.name).toBe('John');
});
```

---

## 🚫 Testing Anti-Patterns

### ❌ Testing Implementation Details
```typescript
// BAD
test('has correct state', () => {
  const { result } = renderHook(() => useCounter());
  expect(result.current.count).toBe(0); // Testing internal state
});

// GOOD
test('displays count', () => {
  render(<Counter />);
  expect(screen.getByText('Count: 0')).toBeInTheDocument();
});
```

### ❌ Interdependent Tests
```typescript
// BAD
let sharedState = 0;

test('increments', () => {
  sharedState++; // Modifies global state
  expect(sharedState).toBe(1);
});

test('increments again', () => {
  sharedState++; // Depends on previous test
  expect(sharedState).toBe(2); // Will fail if run alone
});
```

---

## 📚 Referencias

- [PHPUnit Docs](https://phpunit.de/)
- [Jest Docs](https://jestjs.io/)
- [Playwright Docs](https://playwright.dev/)
- [Testing Library](https://testing-library.com/)
- `/agents/TESTING_AGENT.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
