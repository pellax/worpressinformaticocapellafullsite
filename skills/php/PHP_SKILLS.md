# 🐘 PHP 8.2 Skills - Best Practices

## 🎯 Modern PHP Features

### Type Declarations
```php
<?php
declare(strict_types=1);

// ✅ Typed properties (PHP 7.4+)
class User {
    public int $id;
    public string $name;
    public ?string $email = null; // Nullable
    private array $roles = [];
}

// ✅ Return types
function getUser(int $id): ?User {
    return $id > 0 ? new User() : null;
}

// ✅ Union types (PHP 8.0+)
function process(int|float $value): string {
    return (string) $value;
}
```

### Constructor Property Promotion (PHP 8.0+)
```php
// ✅ Concise constructor
class CaseStudy {
    public function __construct(
        private string $title,
        private string $client,
        private ?string $description = null
    ) {}
    
    public function getTitle(): string {
        return $this->title;
    }
}
```

### Readonly Properties (PHP 8.1+)
```php
class ValueObject {
    public function __construct(
        public readonly string $id,
        public readonly string $value
    ) {}
}

$vo = new ValueObject('123', 'test');
// $vo->id = '456'; // Error: Cannot modify readonly property
```

---

## 🔐 Security

### Input Validation
```php
// ✅ Filter input
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if ($email === false) {
    throw new InvalidArgumentException('Invalid email');
}

// ✅ Sanitize
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
```

### Password Hashing
```php
// ✅ Hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// ✅ Verify password
if (password_verify($inputPassword, $hashedPassword)) {
    // Login successful
}

// ✅ Rehash if needed
if (password_needs_rehash($hash, PASSWORD_BCRYPT)) {
    $newHash = password_hash($password, PASSWORD_BCRYPT);
}
```

### Prepared Statements (PDO)
```php
// ✅ ALWAYS use prepared statements
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

// ❌ NEVER concatenate
// $query = "SELECT * FROM users WHERE email = '$email'"; // SQL Injection!
```

---

## 🎯 SOLID Principles

### Single Responsibility
```php
// ✅ Each class has one responsibility
class EmailValidator {
    public function validate(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

class EmailSender {
    public function send(string $to, string $subject, string $body): void {
        mail($to, $subject, $body);
    }
}

// ❌ Bad: Too many responsibilities
class UserManager {
    public function validateEmail() { /* ... */ }
    public function sendEmail() { /* ... */ }
    public function saveToDatabase() { /* ... */ }
}
```

### Dependency Injection
```php
// ✅ Inject dependencies
class UserService {
    public function __construct(
        private UserRepository $repository,
        private EmailService $emailService
    ) {}
    
    public function createUser(array $data): User {
        $user = User::fromArray($data);
        $this->repository->save($user);
        $this->emailService->sendWelcome($user);
        return $user;
    }
}

// Usage
$service = new UserService(
    new MySQLUserRepository($pdo),
    new SMTPEmailService($config)
);
```

---

## 📦 Namespaces & Autoloading

### PSR-4 Autoloading
```json
// composer.json
{
    "autoload": {
        "psr-4": {
            "InformaticoCapella\\": "src/"
        }
    }
}
```

```php
<?php
namespace InformaticoCapella\Domain\Entities;

class CaseStudy {
    // ...
}

// Usage
use InformaticoCapella\Domain\Entities\CaseStudy;

$case = new CaseStudy();
```

---

## 🎨 Design Patterns

### Repository Pattern
```php
interface CaseStudyRepository {
    public function findById(int $id): ?CaseStudy;
    public function save(CaseStudy $caseStudy): int;
    public function delete(int $id): bool;
}

class MySQLCaseStudyRepository implements CaseStudyRepository {
    public function __construct(private PDO $pdo) {}
    
    public function findById(int $id): ?CaseStudy {
        $stmt = $this->pdo->prepare('SELECT * FROM case_studies WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        
        return $data ? CaseStudy::fromArray($data) : null;
    }
}
```

### Factory Pattern
```php
class CaseStudyFactory {
    public static function create(array $data): CaseStudy {
        return new CaseStudy(
            title: $data['title'],
            client: $data['client'],
            description: $data['description'] ?? null
        );
    }
}
```

---

## 🚫 Common Mistakes

### ❌ Don't use `@` error suppression
```php
// BAD
$data = @file_get_contents($file); // Hides errors

// GOOD
if (!file_exists($file)) {
    throw new FileNotFoundException();
}
$data = file_get_contents($file);
```

### ✅ Use strict types
```php
<?php
declare(strict_types=1);

function add(int $a, int $b): int {
    return $a + $b;
}

// add('1', '2'); // TypeError!
```

---

## 📚 Referencias

- [PHP Manual](https://www.php.net/manual/en/)
- [PSR Standards](https://www.php-fig.org/psr/)
- `/agents/BACKEND_AGENT.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
