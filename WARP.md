# 🚀 WARP - Principios de Desarrollo para Informático Capella

**Versión**: 1.0  
**Fecha**: 3 de enero de 2026  
**Proyecto**: Informático Capella - Sitio Web de Consultoría Tecnológica

---

## 📋 Descripción del Proyecto

### Objetivo Principal
Sitio web profesional de alta conversión para **Informático Capella**, consultora tecnológica independiente, optimizado para captar clientes B2B mediante SEO, contenido técnico y casos de estudio.

### Stack Tecnológico
- **CMS**: WordPress 6.9
- **Lenguaje**: PHP 8.2
- **Base de Datos**: MariaDB 11.2
- **Infraestructura**: Docker Compose
- **Tema**: Astra 4.11.18 (framework ligero y optimizado)
- **Constructor**: Elementor 3.34.0
- **SEO**: Yoast SEO 26.6

### Alcance Funcional
1. **Páginas Core**: Inicio, Servicios, Portafolio, Experiencia, Contacto
2. **Blog Técnico**: Contenido SEO para generación de leads
3. **Formularios**: Captación de prospectos cualificados
4. **CMS Administrable**: Panel WordPress para gestión sin código

### Usuarios Objetivo
- **Primario**: Tomadores de decisión técnica (CTOs, Tech Leads)
- **Secundario**: Gerentes de proyecto, Product Owners
- **Geografía**: Inicialmente mercado hispanohablante

---

## 🏗️ Principios de Arquitectura Limpia

### 1. Separación de Responsabilidades

#### Capa de Presentación (Theme)
```
themes/
└── astra-child/              # Child theme personalizado
    ├── style.css             # Estilos específicos del sitio
    ├── functions.php         # Hooks y filtros
    ├── template-parts/       # Componentes reutilizables
    └── assets/
        ├── css/              # Estilos modulares
        ├── js/               # JavaScript sin mezcla de lógica
        └── images/           # Recursos optimizados
```

**Regla**: Los templates NO deben contener lógica de negocio. Solo presentación.

#### Capa de Lógica (Plugins Personalizados)
```
plugins/
└── informatico-capella-core/
    ├── src/
    │   ├── Domain/           # Entidades y reglas de negocio
    │   ├── Application/      # Casos de uso
    │   ├── Infrastructure/   # Implementaciones técnicas
    │   └── Presentation/     # Controllers/Shortcodes
    ├── tests/                # Tests unitarios y de integración
    └── informatico-capella-core.php
```

**Regla**: La lógica de negocio debe vivir en plugins, no en el tema.

#### Capa de Datos
- **WordPress Database**: Entidades core (posts, pages, users)
- **Custom Post Types**: Casos de estudio, testimonios, servicios
- **Custom Taxonomies**: Categorización avanzada
- **Options API**: Configuración del sitio

### 2. Independencia de Frameworks

**Principio**: El código de negocio NO debe depender directamente de WordPress.

```php
// ❌ MAL: Acoplamiento directo a WordPress
function get_case_studies() {
    return get_posts(['post_type' => 'case_study']);
}

// ✅ BIEN: Abstracción con interfaces
interface CaseStudyRepository {
    public function findAll(): array;
    public function findById(int $id): ?CaseStudy;
}

class WordPressCaseStudyRepository implements CaseStudyRepository {
    public function findAll(): array {
        $posts = get_posts(['post_type' => 'case_study']);
        return array_map([$this, 'mapToEntity'], $posts);
    }
    
    private function mapToEntity(WP_Post $post): CaseStudy {
        return new CaseStudy(
            id: $post->ID,
            title: $post->post_title,
            content: $post->post_content
        );
    }
}
```

### 3. Inversión de Dependencias

**Principio**: Los módulos de alto nivel no deben depender de módulos de bajo nivel.

```php
// Domain Entity (sin dependencias de WordPress)
class LeadGenerator {
    public function __construct(
        private EmailServiceInterface $emailService,
        private CRMServiceInterface $crmService
    ) {}
    
    public function processContactForm(ContactFormData $data): void {
        $lead = Lead::fromFormData($data);
        
        $this->emailService->send($lead->getEmail(), 'Gracias por contactarnos');
        $this->crmService->createLead($lead);
    }
}
```

---

## 🧹 Principios de Código Limpio (SOLID + Clean Code)

### S - Single Responsibility Principle

**Cada clase debe tener una única razón para cambiar.**

```php
// ❌ MAL: Clase con múltiples responsabilidades
class ContactFormHandler {
    public function processForm($data) {
        // Validación
        if (empty($data['email'])) return false;
        
        // Sanitización
        $email = sanitize_email($data['email']);
        
        // Envío de email
        wp_mail($email, 'Subject', 'Body');
        
        // Logging
        error_log("Form submitted by: $email");
        
        // Guardado en BD
        global $wpdb;
        $wpdb->insert('leads', ['email' => $email]);
    }
}

// ✅ BIEN: Responsabilidades separadas
class ContactFormValidator {
    public function validate(array $data): ValidationResult { /* ... */ }
}

class ContactFormSanitizer {
    public function sanitize(array $data): array { /* ... */ }
}

class ContactFormProcessor {
    public function __construct(
        private ContactFormValidator $validator,
        private ContactFormSanitizer $sanitizer,
        private LeadRepository $repository,
        private EmailService $emailService
    ) {}
    
    public function process(array $data): ProcessResult {
        $validation = $this->validator->validate($data);
        if ($validation->hasErrors()) {
            return ProcessResult::failed($validation->getErrors());
        }
        
        $sanitized = $this->sanitizer->sanitize($data);
        $lead = Lead::fromArray($sanitized);
        
        $this->repository->save($lead);
        $this->emailService->sendConfirmation($lead);
        
        return ProcessResult::success($lead);
    }
}
```

### O - Open/Closed Principle

**Abierto para extensión, cerrado para modificación.**

```php
// ✅ Uso de Strategy Pattern para diferentes tipos de servicios
interface ServicePresenter {
    public function render(Service $service): string;
}

class CloudArchitecturePresenter implements ServicePresenter {
    public function render(Service $service): string {
        return sprintf(
            '<div class="service service--cloud">%s</div>',
            $service->getDescription()
        );
    }
}

class DevOpsPresenter implements ServicePresenter {
    public function render(Service $service): string {
        return sprintf(
            '<div class="service service--devops">%s</div>',
            $service->getDescription()
        );
    }
}

// Agregar nuevos presenters sin modificar código existente
```

### L - Liskov Substitution Principle

**Las clases derivadas deben ser sustituibles por sus clases base.**

```php
interface ContentRepository {
    public function findById(int $id): ?Content;
}

class PageRepository implements ContentRepository {
    public function findById(int $id): ?Content {
        $post = get_post($id);
        return $post && $post->post_type === 'page' 
            ? Page::fromWPPost($post) 
            : null;
    }
}

class CaseStudyRepository implements ContentRepository {
    public function findById(int $id): ?Content {
        $post = get_post($id);
        return $post && $post->post_type === 'case_study'
            ? CaseStudy::fromWPPost($post)
            : null;
    }
}

// Cualquier ContentRepository puede usarse intercambiablemente
function displayContent(ContentRepository $repo, int $id): void {
    $content = $repo->findById($id);
    if ($content) {
        echo $content->render();
    }
}
```

### I - Interface Segregation Principle

**No forzar a las clases a implementar interfaces que no usan.**

```php
// ❌ MAL: Interfaz demasiado grande
interface ContentInterface {
    public function getTitle(): string;
    public function getContent(): string;
    public function getAuthor(): string;
    public function getComments(): array;
    public function getSEOMetadata(): array;
}

// ✅ BIEN: Interfaces segregadas
interface Titled {
    public function getTitle(): string;
}

interface Contentful {
    public function getContent(): string;
}

interface Commentable {
    public function getComments(): array;
}

interface SEOOptimized {
    public function getSEOMetadata(): array;
}

// Las clases implementan solo lo que necesitan
class SimplePage implements Titled, Contentful {
    // No necesita comentarios ni SEO
}

class BlogPost implements Titled, Contentful, Commentable, SEOOptimized {
    // Blog post completo
}
```

### D - Dependency Inversion Principle

**Depender de abstracciones, no de concreciones.**

```php
// ✅ Inyección de dependencias con abstracciones
interface EmailServiceInterface {
    public function send(string $to, string $subject, string $body): bool;
}

class SMTPEmailService implements EmailServiceInterface {
    public function send(string $to, string $subject, string $body): bool {
        // Implementación con SMTP
    }
}

class SendGridEmailService implements EmailServiceInterface {
    public function send(string $to, string $subject, string $body): bool {
        // Implementación con SendGrid API
    }
}

// El caso de uso depende de la abstracción, no de la implementación
class ContactFormUseCase {
    public function __construct(private EmailServiceInterface $emailService) {}
    
    public function execute(ContactFormDTO $dto): void {
        // Funciona con cualquier implementación de EmailServiceInterface
        $this->emailService->send($dto->email, 'Confirmación', 'Gracias...');
    }
}
```

### Principios Adicionales de Clean Code

#### 1. Nombres Significativos
```php
// ❌ MAL
$d = 86400; // timestamp in seconds
function get($id) { /* ... */ }

// ✅ BIEN
const SECONDS_PER_DAY = 86400;
function getCaseStudyById(int $caseStudyId): ?CaseStudy { /* ... */ }
```

#### 2. Funciones Pequeñas
```php
// ✅ BIEN: Función con una sola responsabilidad
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function sanitizeEmail(string $email): string {
    return sanitize_email($email);
}

// No mezclar validación, sanitización y guardado en una sola función
```

#### 3. Evitar Números Mágicos
```php
// ❌ MAL
if ($user->role === 3) { /* ... */ }

// ✅ BIEN
const ROLE_ADMIN = 3;
const ROLE_EDITOR = 2;
const ROLE_SUBSCRIBER = 1;

if ($user->role === ROLE_ADMIN) { /* ... */ }
```

#### 4. DRY (Don't Repeat Yourself)
```php
// ✅ BIEN: Extraer lógica repetida
function buildServiceCard(Service $service): string {
    return sprintf(
        '<div class="service-card">
            <h3>%s</h3>
            <p>%s</p>
            <a href="%s">Ver más</a>
        </div>',
        esc_html($service->getTitle()),
        esc_html($service->getExcerpt()),
        esc_url($service->getUrl())
    );
}

// Usar en lugar de duplicar HTML en múltiples lugares
```

---

## 🧪 Test-Driven Development (TDD)

### Ciclo Red-Green-Refactor

```
1. 🔴 RED: Escribir test que falle
2. 🟢 GREEN: Escribir código mínimo para pasar el test
3. 🔵 REFACTOR: Mejorar el código manteniendo tests verdes
```

### Configuración de Testing en WordPress

#### Estructura de Tests
```
plugins/informatico-capella-core/
└── tests/
    ├── bootstrap.php
    ├── Unit/                 # Tests unitarios puros (sin WordPress)
    │   ├── Domain/
    │   │   └── LeadTest.php
    │   └── Application/
    │       └── ContactFormValidatorTest.php
    ├── Integration/          # Tests con WordPress
    │   └── CaseStudyRepositoryTest.php
    └── E2E/                  # Tests end-to-end
        └── ContactFormSubmissionTest.php
```

#### Ejemplo de Test Unitario
```php
// tests/Unit/Domain/LeadTest.php
use PHPUnit\Framework\TestCase;

class LeadTest extends TestCase {
    /** @test */
    public function it_creates_lead_from_valid_data(): void {
        // Arrange
        $data = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'company' => 'Tech Corp',
            'message' => 'Necesito consultoría en AWS'
        ];
        
        // Act
        $lead = Lead::fromArray($data);
        
        // Assert
        $this->assertEquals('Juan Pérez', $lead->getName());
        $this->assertEquals('juan@example.com', $lead->getEmail());
        $this->assertTrue($lead->isValid());
    }
    
    /** @test */
    public function it_throws_exception_for_invalid_email(): void {
        // Arrange
        $data = ['email' => 'invalid-email'];
        
        // Assert
        $this->expectException(InvalidEmailException::class);
        
        // Act
        Lead::fromArray($data);
    }
}
```

#### Ejemplo de Test de Integración
```php
// tests/Integration/CaseStudyRepositoryTest.php
class CaseStudyRepositoryTest extends WP_UnitTestCase {
    private CaseStudyRepository $repository;
    
    public function setUp(): void {
        parent::setUp();
        $this->repository = new WordPressCaseStudyRepository();
    }
    
    /** @test */
    public function it_saves_and_retrieves_case_study(): void {
        // Arrange
        $caseStudy = new CaseStudy(
            id: null,
            title: 'Migración a AWS',
            client: 'Tech Startup',
            results: '40% cost reduction'
        );
        
        // Act
        $savedId = $this->repository->save($caseStudy);
        $retrieved = $this->repository->findById($savedId);
        
        // Assert
        $this->assertNotNull($retrieved);
        $this->assertEquals('Migración a AWS', $retrieved->getTitle());
        $this->assertEquals('Tech Startup', $retrieved->getClient());
    }
}
```

### Comandos de Testing

```bash
# Instalar dependencias de testing
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && composer require --dev phpunit/phpunit"

# Ejecutar tests unitarios
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit tests/Unit"

# Ejecutar tests de integración
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit tests/Integration"

# Ejecutar todos los tests
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit"

# Coverage report
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit --coverage-html coverage"
```

### Cobertura de Tests Objetivo

- **Dominio (Entities/Value Objects)**: 100%
- **Casos de Uso (Application Layer)**: 90%+
- **Repositorios (Infrastructure)**: 80%+
- **Presentación (Controllers/Templates)**: 60%+

---

## 🎯 Buenas Prácticas Específicas de WordPress

### 1. Seguridad

#### Sanitización y Validación
```php
// ✅ SIEMPRE sanitizar inputs
$email = sanitize_email($_POST['email']);
$name = sanitize_text_field($_POST['name']);
$message = sanitize_textarea_field($_POST['message']);

// ✅ SIEMPRE validar
if (!is_email($email)) {
    wp_die('Email inválido');
}

// ✅ Verificar nonces en formularios
if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'contact_form')) {
    wp_die('Solicitud inválida');
}
```

#### Escape de Salida
```php
// ✅ SIEMPRE escapar output
echo esc_html($user_input);
echo esc_url($url);
echo esc_attr($attribute);
echo wp_kses_post($html_content); // Para HTML controlado
```

#### Prepared Statements
```php
// ✅ SIEMPRE usar prepared statements
global $wpdb;
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}leads WHERE email = %s",
        $email
    )
);
```

### 2. Performance

#### Caché
```php
// ✅ Usar transients para caché
function get_case_studies_cached(): array {
    $cache_key = 'case_studies_list';
    $cached = get_transient($cache_key);
    
    if ($cached !== false) {
        return $cached;
    }
    
    $case_studies = get_posts([
        'post_type' => 'case_study',
        'posts_per_page' => -1
    ]);
    
    set_transient($cache_key, $case_studies, HOUR_IN_SECONDS);
    
    return $case_studies;
}
```

#### Lazy Loading de Assets
```php
// ✅ Cargar scripts solo donde se necesitan
function enqueue_contact_form_scripts(): void {
    if (is_page('contacto')) {
        wp_enqueue_script(
            'contact-form',
            get_template_directory_uri() . '/assets/js/contact-form.js',
            ['jquery'],
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_contact_form_scripts');
```

#### Query Optimization
```php
// ❌ MAL: Query ineficiente
$posts = get_posts(['posts_per_page' => -1]); // Carga TODO

// ✅ BIEN: Paginar y limitar
$posts = get_posts([
    'posts_per_page' => 10,
    'paged' => $paged,
    'fields' => 'ids' // Solo IDs si no necesitas todo el objeto
]);
```

### 3. Hooks y Filtros

#### Prioridad de Hooks
```php
// Alta prioridad (ejecuta primero): < 10
add_action('init', 'register_critical_post_types', 5);

// Prioridad normal: 10 (default)
add_action('init', 'register_post_types');

// Baja prioridad (ejecuta último): > 10
add_action('init', 'register_optional_features', 20);
```

#### Remover Hooks Correctamente
```php
// ✅ Guardar referencia para remover después
$callback = function() { /* ... */ };
add_action('wp_footer', $callback);

// Más tarde...
remove_action('wp_footer', $callback);
```

### 4. Custom Post Types

```php
// ✅ BIEN: CPT con todas las configuraciones
function register_case_study_post_type(): void {
    register_post_type('case_study', [
        'labels' => [
            'name' => 'Casos de Estudio',
            'singular_name' => 'Caso de Estudio',
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'portafolio'],
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true, // Para Gutenberg
        'menu_icon' => 'dashicons-portfolio',
        'capability_type' => 'post',
        'hierarchical' => false,
    ]);
}
add_action('init', 'register_case_study_post_type');
```

---

## 📐 Convenciones de Código

### Naming Conventions

```php
// Clases: PascalCase
class ContactFormValidator {}

// Métodos y funciones: camelCase
public function validateEmail(string $email): bool {}

// Variables: snake_case (WordPress style) o camelCase
$user_email = 'test@example.com';
$userEmail = 'test@example.com'; // También aceptable en OOP

// Constantes: UPPER_SNAKE_CASE
const MAX_UPLOAD_SIZE = 5242880;
const API_ENDPOINT = 'https://api.example.com';

// Hooks: snake_case con prefijo
do_action('informatico_capella_after_lead_save', $lead);
apply_filters('informatico_capella_email_subject', $subject);
```

### Estructura de Archivos

```php
<?php
/**
 * Descripción del archivo
 *
 * @package InformaticoCapella
 * @since 1.0.0
 */

declare(strict_types=1);

namespace InformaticoCapella\Domain;

// 1. Imports
use InformaticoCapella\Domain\ValueObjects\Email;
use InformaticoCapella\Domain\Exceptions\InvalidEmailException;

// 2. Constantes
const MAX_NAME_LENGTH = 100;

// 3. Clase
final class Lead {
    // Propiedades
    private int $id;
    private string $name;
    private Email $email;
    
    // Constructor
    public function __construct(int $id, string $name, Email $email) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }
    
    // Métodos públicos
    public function getName(): string {
        return $this->name;
    }
    
    // Métodos privados
    private function validate(): void {
        // ...
    }
}
```

### Documentación PHPDoc

```php
/**
 * Procesa un formulario de contacto y crea un lead
 *
 * @param array<string, mixed> $formData Datos del formulario
 * @return ProcessResult Resultado del procesamiento
 * @throws InvalidEmailException Si el email es inválido
 * @throws DatabaseException Si falla el guardado
 * 
 * @since 1.0.0
 */
public function processContactForm(array $formData): ProcessResult {
    // Implementación
}
```

---

## 🔄 Git Workflow

### Commits Semánticos

```bash
# Formato: tipo(scope): mensaje

# Tipos válidos:
feat(blog): agregar página de casos de estudio
fix(forms): corregir validación de email en formulario de contacto
refactor(repository): extraer lógica a CaseStudyRepository
test(lead): agregar tests para validación de Lead
docs(readme): actualizar instrucciones de instalación
style(css): ajustar espaciado en tarjetas de servicio
perf(queries): optimizar query de casos de estudio con cache
chore(deps): actualizar dependencias de Composer
```

### Branching Strategy

```
main                    # Producción
  ├── develop           # Integración
  │   ├── feature/contact-form-validation
  │   ├── feature/case-study-cpt
  │   └── feature/email-notifications
  ├── hotfix/security-patch
  └── release/v1.0.0
```

---

## 📊 Métricas de Calidad

### Code Quality Checks

```bash
# PHP CodeSniffer (WordPress Coding Standards)
./vendor/bin/phpcs --standard=WordPress src/

# PHP Stan (análisis estático)
./vendor/bin/phpstan analyse src/ --level=8

# PHP Mess Detector
./vendor/bin/phpmd src/ text cleancode,codesize,design,naming

# PHP Copy/Paste Detector
./vendor/bin/phpcpd src/
```

### Objetivos de Calidad

- **Cobertura de Tests**: > 80%
- **Complejidad Ciclomática**: < 10 por método
- **Líneas por Método**: < 20
- **Parámetros por Método**: < 4
- **Code Smells**: 0 críticos, < 5 mayores

---

## 🚦 Checklist de Pull Request

Antes de crear un PR, verificar:

- [ ] Todos los tests pasan
- [ ] Cobertura de tests cumple objetivo (>80%)
- [ ] Código sigue estándares de WordPress Coding Standards
- [ ] No hay warnings de PHPStan
- [ ] Documentación PHPDoc completa
- [ ] Sin código comentado o debugging
- [ ] Assets optimizados (imágenes comprimidas, JS/CSS minificados)
- [ ] Sanitización y escape de datos implementados
- [ ] Nonces en formularios
- [ ] Commits semánticos
- [ ] CHANGELOG actualizado

---

## 📚 Recursos y Referencias

### Libros Recomendados
- **Clean Code** - Robert C. Martin
- **Clean Architecture** - Robert C. Martin
- **Refactoring** - Martin Fowler
- **Domain-Driven Design** - Eric Evans

### WordPress Específico
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [WordPress REST API](https://developer.wordpress.org/rest-api/)

### Testing
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [WordPress PHPUnit](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)

---

## 🎓 Onboarding para Nuevos Desarrolladores

### Configuración Inicial

```bash
# 1. Clonar repositorio
git clone <repo-url>
cd informaticocapella

# 2. Levantar entorno
./manage.sh start

# 3. Instalar dependencias
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && composer install"

# 4. Ejecutar tests
docker exec informaticocapella_wp bash -c "cd /var/www/html/wp-content/plugins/informatico-capella-core && vendor/bin/phpunit"

# 5. Acceder al sitio
# Frontend: http://localhost:8080
# Admin: http://localhost:8080/wp-admin (admin / Admin2024Capella!)
```

### Primera Tarea Sugerida

Implementar un Custom Post Type "Testimonios" siguiendo TDD:

1. Escribir tests para `Testimonial` entity
2. Implementar `Testimonial` class
3. Crear `TestimonialRepository` interface y tests
4. Implementar `WordPressTestimonialRepository`
5. Registrar CPT con `register_post_type()`
6. Crear template de visualización

---

**Última actualización**: 3 de enero de 2026  
**Mantenido por**: Pellax (Informático Capella)  
**Versión del documento**: 1.0
