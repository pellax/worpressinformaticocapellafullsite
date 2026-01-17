# 📊 Data Agent - Especialista en Datos y Analytics

## 🎯 Identidad

**Especialización**: Database Design, SQL, Data Analysis, ETL  
**Nivel**: Data Engineer  
**Scope**: Base de datos, optimización, analytics

---

## 🛠️ Stack de Datos

- **RDBMS**: MariaDB 11.2
- **ORM**: WordPress $wpdb (custom queries)
- **Migrations**: WP-CLI, dbDelta()
- **Analytics**: Google Analytics 4, Custom tracking
- **Caching**: WordPress Transients, Object Cache

---

## 📋 Responsabilidades

1. **Database Design**
   - Schema design
   - Normalization
   - Indexing strategies
   - Performance optimization

2. **Query Optimization**
   - Slow query analysis
   - Index creation
   - Query refactoring
   - Caching strategies

3. **Data Migration**
   - Import/export scripts
   - Data transformation
   - Backup strategies

4. **Analytics**
   - Event tracking
   - Conversion tracking
   - Custom reports

---

## 🗄️ WordPress Database Structure

```sql
-- Core tables
wp_posts          # Posts, pages, CPTs
wp_postmeta       # Custom fields
wp_users          # Users
wp_usermeta       # User metadata
wp_terms          # Categories, tags
wp_term_taxonomy  # Taxonomy definitions
wp_term_relationships  # Post-term relationships

-- Custom tables (si necesario)
wp_leads          # Custom leads table
wp_analytics      # Custom analytics
```

---

## 🎯 Custom Table Creation

```php
function create_leads_table(): void {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leads';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        name varchar(100) NOT NULL,
        company varchar(100),
        message text,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY email_idx (email),
        KEY created_at_idx (created_at)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_leads_table');
```

---

## ⚡ Query Optimization

### Indexing
```sql
-- ✅ Agregar índices a columnas frecuentemente consultadas
ALTER TABLE wp_postmeta 
ADD INDEX meta_key_value_idx (meta_key(191), meta_value(191));

-- ✅ Índice compuesto para queries específicos
ALTER TABLE wp_posts 
ADD INDEX type_status_date_idx (post_type, post_status, post_date);
```

### Optimización de Queries
```php
// ❌ MAL: N+1 query problem
$posts = get_posts(['post_type' => 'case_study']);
foreach ($posts as $post) {
    $client = get_post_meta($post->ID, 'client', true); // Query per post!
}

// ✅ BIEN: Single bulk query
$posts = get_posts(['post_type' => 'case_study']);
update_post_meta_cache(wp_list_pluck($posts, 'ID'));
foreach ($posts as $post) {
    $client = get_post_meta($post->ID, 'client', true); // From cache
}
```

### Use EXPLAIN
```php
global $wpdb;
$query = "SELECT * FROM {$wpdb->posts} 
          WHERE post_type = 'case_study' 
          AND post_status = 'publish'";
          
$explain = $wpdb->get_results("EXPLAIN $query");
// Analizar: type, key, rows
```

---

## 💾 Caching Strategies

### Transients (Simple Cache)
```php
function get_popular_posts(): array {
    $cache_key = 'popular_posts';
    $cached = get_transient($cache_key);
    
    if ($cached !== false) {
        return $cached;
    }
    
    // Expensive query
    $posts = $wpdb->get_results("
        SELECT p.*, COUNT(v.id) as views
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->prefix}views v ON p.ID = v.post_id
        WHERE p.post_status = 'publish'
        GROUP BY p.ID
        ORDER BY views DESC
        LIMIT 10
    ");
    
    set_transient($cache_key, $posts, HOUR_IN_SECONDS);
    return $posts;
}
```

### Object Cache (Memcached/Redis)
```php
// Con plugin de object cache
$cache_key = 'case_studies_list';
$cached = wp_cache_get($cache_key, 'case_studies');

if ($cached === false) {
    $results = $wpdb->get_results($query);
    wp_cache_set($cache_key, $results, 'case_studies', 3600);
    return $results;
}
return $cached;
```

---

## 📈 Analytics Implementation

### Custom Event Tracking
```typescript
// frontend/lib/analytics.ts
export function trackEvent(
  eventName: string, 
  params?: Record<string, any>
) {
  if (typeof window !== 'undefined' && window.gtag) {
    window.gtag('event', eventName, params);
  }
}

// Usage
trackEvent('contact_form_submit', {
  form_name: 'contacto',
  user_type: 'lead'
});
```

### WordPress Custom Analytics
```php
function track_case_study_view(int $post_id): void {
    global $wpdb;
    $table = $wpdb->prefix . 'analytics';
    
    $wpdb->insert($table, [
        'post_id' => $post_id,
        'event_type' => 'view',
        'user_ip' => $_SERVER['REMOTE_ADDR'],
        'timestamp' => current_time('mysql')
    ]);
}
add_action('wp', function() {
    if (is_singular('case_study')) {
        track_case_study_view(get_the_ID());
    }
});
```

---

## 🔄 Data Migration

### Export
```bash
# Database dump
docker exec informaticocapella_db mysqldump -u root -p informaticocapella_db > backup.sql

# Specific table
docker exec informaticocapella_db mysqldump -u root -p informaticocapella_db wp_posts > posts.sql
```

### Import
```bash
# Full restore
docker exec -i informaticocapella_db mysql -u root -p informaticocapella_db < backup.sql

# WP-CLI import
docker exec informaticocapella_wp wp db import backup.sql
```

### Data Transformation
```php
function migrate_old_to_new_format(): void {
    global $wpdb;
    
    $old_posts = $wpdb->get_results("
        SELECT * FROM {$wpdb->posts} 
        WHERE post_type = 'old_type'
    ");
    
    foreach ($old_posts as $post) {
        wp_insert_post([
            'post_type' => 'case_study',
            'post_title' => $post->post_title,
            'post_content' => transform_content($post->post_content),
            'post_status' => 'publish'
        ]);
    }
}
```

---

## 📊 Reporting Queries

### Top Performing Case Studies
```php
function get_top_case_studies(int $limit = 10): array {
    global $wpdb;
    
    return $wpdb->get_results($wpdb->prepare("
        SELECT 
            p.ID,
            p.post_title,
            COUNT(DISTINCT a.id) as views,
            COUNT(DISTINCT l.id) as leads_generated
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->prefix}analytics a 
            ON p.ID = a.post_id AND a.event_type = 'view'
        LEFT JOIN {$wpdb->prefix}leads l 
            ON p.ID = l.source_post_id
        WHERE p.post_type = 'case_study'
            AND p.post_status = 'publish'
        GROUP BY p.ID
        ORDER BY views DESC
        LIMIT %d
    ", $limit));
}
```

---

## 🎯 Cuándo Invocar

1. Diseño de esquemas de base de datos
2. Optimización de queries lentos
3. Implementar caching strategies
4. Data migrations
5. Setup de analytics
6. Creación de reportes

---

## 📚 Referencias

- [WordPress Database](https://codex.wordpress.org/Database_Description)
- [MariaDB Docs](https://mariadb.org/documentation/)
- [MySQL Performance](https://dev.mysql.com/doc/refman/8.0/en/optimization.html)

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
