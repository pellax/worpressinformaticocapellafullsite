# 🔷 WordPress Skills - Best Practices

## 🎯 Plugin Development

### Plugin Structure
```php
<?php
/**
 * Plugin Name: Informático Capella Core
 * Description: Custom functionality for Informático Capella
 * Version: 1.0.0
 * Author: Pellax
 */

// Security: prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Initialize plugin
add_action('plugins_loaded', function() {
    // Bootstrap application
});
```

---

## 🔌 Custom Post Types

```php
function register_case_study_cpt(): void {
    register_post_type('case_study', [
        'labels' => [
            'name' => __('Case Studies', 'informatico-capella'),
            'singular_name' => __('Case Study', 'informatico-capella'),
            'add_new_item' => __('Add New Case Study', 'informatico-capella'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'portafolio'],
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest' => true, // Enable Gutenberg & REST API
        'menu_icon' => 'dashicons-portfolio',
        'capability_type' => 'post',
    ]);
}
add_action('init', 'register_case_study_cpt');
```

---

## 🏷️ Custom Taxonomies

```php
function register_service_category(): void {
    register_taxonomy('service_category', ['case_study'], [
        'labels' => [
            'name' => __('Service Categories', 'informatico-capella'),
            'singular_name' => __('Service Category', 'informatico-capella'),
        ],
        'hierarchical' => true, // Like categories
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'services'],
    ]);
}
add_action('init', 'register_service_category');
```

---

## 🌐 REST API

### Register Custom Endpoint
```php
add_action('rest_api_init', function() {
    register_rest_route('informatico/v1', '/case-studies', [
        'methods' => 'GET',
        'callback' => 'get_case_studies_api',
        'permission_callback' => '__return_true',
        'args' => [
            'per_page' => [
                'default' => 10,
                'sanitize_callback' => 'absint',
            ],
        ],
    ]);
});

function get_case_studies_api(WP_REST_Request $request) {
    $per_page = $request->get_param('per_page');
    
    $posts = get_posts([
        'post_type' => 'case_study',
        'posts_per_page' => $per_page,
        'post_status' => 'publish',
    ]);
    
    $data = array_map(function($post) {
        return [
            'id' => $post->ID,
            'title' => get_the_title($post),
            'excerpt' => get_the_excerpt($post),
            'thumbnail' => get_the_post_thumbnail_url($post, 'large'),
            'link' => get_permalink($post),
        ];
    }, $posts);
    
    return new WP_REST_Response($data, 200);
}
```

---

## 🔒 Security Best Practices

### Input Sanitization
```php
// ✅ Sanitize text input
$name = sanitize_text_field($_POST['name']);

// ✅ Sanitize email
$email = sanitize_email($_POST['email']);

// ✅ Sanitize textarea
$message = sanitize_textarea_field($_POST['message']);

// ✅ Sanitize URL
$url = esc_url_raw($_POST['url']);
```

### Output Escaping
```php
// ✅ Escape HTML
echo esc_html($user_input);

// ✅ Escape attributes
<div class="<?php echo esc_attr($class); ?>">

// ✅ Escape URL
<a href="<?php echo esc_url($url); ?>">

// ✅ Escape JavaScript
<script>var data = <?php echo wp_json_encode($data); ?>;</script>
```

### Nonce Verification
```php
// Create nonce
wp_nonce_field('contact_form', '_wpnonce');

// Verify nonce
if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'contact_form')) {
    wp_die('Invalid request');
}
```

### Prepared Statements
```php
global $wpdb;

// ✅ ALWAYS use prepared statements
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}posts WHERE post_author = %d AND post_status = %s",
        $author_id,
        'publish'
    )
);

// ❌ NEVER concatenate
// $query = "SELECT * FROM posts WHERE author = $author_id"; // Vulnerable!
```

---

## 🎣 Hooks & Filters

### Actions
```php
// ✅ Add action
add_action('init', 'my_function');
add_action('init', 'my_function', 10, 2); // Priority 10, accepts 2 arguments

// ✅ Custom action
do_action('informatico_after_case_study_save', $post_id);

// ✅ Hook into custom action
add_action('informatico_after_case_study_save', function($post_id) {
    // Clear cache, send notification, etc.
});
```

### Filters
```php
// ✅ Modify content
add_filter('the_content', function($content) {
    return $content . '<div>Additional content</div>';
});

// ✅ Custom filter
$title = apply_filters('informatico_case_study_title', $title, $post_id);

// ✅ Hook into custom filter
add_filter('informatico_case_study_title', function($title, $post_id) {
    return strtoupper($title);
}, 10, 2);
```

---

## 💾 Database Queries

### WP_Query
```php
// ✅ Query posts
$args = [
    'post_type' => 'case_study',
    'posts_per_page' => 10,
    'post_status' => 'publish',
    'meta_query' => [
        [
            'key' => 'featured',
            'value' => '1',
            'compare' => '='
        ]
    ],
    'tax_query' => [
        [
            'taxonomy' => 'service_category',
            'field' => 'slug',
            'terms' => 'cloud-architecture'
        ]
    ]
];

$query = new WP_Query($args);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        // Display post
    }
    wp_reset_postdata(); // Important!
}
```

### Custom Tables
```php
global $wpdb;

// Create table on activation
function create_leads_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leads';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        name varchar(100) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY email_idx (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_leads_table');
```

---

## ⚡ Performance

### Transients (Caching)
```php
// ✅ Set transient
$data = get_transient('case_studies_cache');

if ($data === false) {
    // Expensive operation
    $data = get_posts(['post_type' => 'case_study']);
    set_transient('case_studies_cache', $data, HOUR_IN_SECONDS);
}

// ✅ Delete transient
delete_transient('case_studies_cache');
```

### Optimize Queries
```php
// ❌ BAD: N+1 queries
$posts = get_posts();
foreach ($posts as $post) {
    $meta = get_post_meta($post->ID, 'key', true); // Query per post!
}

// ✅ GOOD: Bulk query
$posts = get_posts();
update_post_meta_cache(wp_list_pluck($posts, 'ID'));
foreach ($posts as $post) {
    $meta = get_post_meta($post->ID, 'key', true); // From cache
}
```

---

## 📚 Referencias

- [WordPress Developer Resources](https://developer.wordpress.org/)
- [Plugin Handbook](https://developer.wordpress.org/plugins/)
- [REST API Handbook](https://developer.wordpress.org/rest-api/)
- `/agents/BACKEND_AGENT.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
