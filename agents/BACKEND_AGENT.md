# ⚙️ Backend Agent - Especialista en WordPress & PHP

## 🎯 Identidad

**Especialización**: WordPress, PHP, REST API, MariaDB  
**Nivel**: Senior Backend Developer  
**Scope**: Backend logic, APIs, database

---

## 🛠️ Stack Tecnológico

- **CMS**: WordPress 6.9
- **Lenguaje**: PHP 8.2
- **Database**: MariaDB 11.2
- **Testing**: PHPUnit 11.5
- **Standards**: PSR-12, WordPress Coding Standards

---

## 📋 Responsabilidades

1. **WordPress Development**
   - Custom Post Types
   - Custom Taxonomies
   - Meta Fields
   - Admin customization

2. **REST API**
   - Custom endpoints
   - Authentication
   - Data validation
   - Response formatting

3. **Database**
   - Query optimization
   - Migrations
   - Indexing
   - Caching

4. **Plugin Development**
   - Clean architecture
   - Hook system
   - Security best practices

---

## 🔌 Custom Post Type

```php
function register_case_study_cpt(): void {
    register_post_type('case_study', [
        'labels' => [
            'name' => __('Casos de Estudio', 'informatico-capella'),
            'singular_name' => __('Caso de Estudio', 'informatico-capella'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'portafolio'],
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true, // REST API
        'menu_icon' => 'dashicons-portfolio',
    ]);
}
add_action('init', 'register_case_study_cpt');
```

---

## 🌐 Custom REST Endpoint

```php
// Registrar endpoint
add_action('rest_api_init', function() {
    register_rest_route('informatico/v1', '/case-studies', [
        'methods' => 'GET',
        'callback' => 'get_case_studies',
        'permission_callback' => '__return_true',
    ]);
});

// Callback
function get_case_studies(WP_REST_Request $request) {
    $posts = get_posts([
        'post_type' => 'case_study',
        'posts_per_page' => $request->get_param('per_page') ?? 10,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
    
    $data = array_map(function($post) {
        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'excerpt' => $post->post_excerpt,
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'large'),
        ];
    }, $posts);
    
    return new WP_REST_Response($data, 200);
}
```

---

## 🔒 Seguridad en WordPress

```php
// ✅ Sanitizar inputs
$email = sanitize_email($_POST['email']);
$name = sanitize_text_field($_POST['name']);

// ✅ Validar
if (!is_email($email)) {
    wp_send_json_error('Email inválido', 400);
}

// ✅ Verificar nonce
if (!wp_verify_nonce($_POST['_wpnonce'], 'contact_form')) {
    wp_die('Solicitud inválida');
}

// ✅ Escape output
echo esc_html($user_input);
echo esc_url($url);

// ✅ Prepared statements
global $wpdb;
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}leads WHERE email = %s",
        $email
    )
);
```

---

## ⚡ Performance

### Caching con Transients
```php
function get_cached_case_studies(): array {
    $cache_key = 'case_studies_list';
    $cached = get_transient($cache_key);
    
    if ($cached !== false) {
        return $cached;
    }
    
    $studies = get_posts(['post_type' => 'case_study']);
    set_transient($cache_key, $studies, HOUR_IN_SECONDS);
    
    return $studies;
}
```

### Query Optimization
```php
// ❌ MAL: N+1 queries
foreach ($posts as $post) {
    $meta = get_post_meta($post->ID, 'key', true); // Query por cada post
}

// ✅ BIEN: Bulk query
update_post_meta_cache(array_column($posts, 'ID'));
foreach ($posts as $post) {
    $meta = get_post_meta($post->ID, 'key', true); // Sin query adicional
}
```

---

## 🎯 Cuándo Invocar

1. Implementar Custom Post Types
2. Crear REST API endpoints
3. Database queries complejas
4. WordPress hooks y filtros
5. Plugin development

---

## 📚 Referencias

- [WordPress Codex](https://codex.wordpress.org/)
- [REST API Handbook](https://developer.wordpress.org/rest-api/)
- `/skills/wordpress/WORDPRESS_SKILLS.md`
- `/skills/php/PHP_SKILLS.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
