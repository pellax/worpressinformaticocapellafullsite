<?php
/**
 * Plugin Name: Informático Capella Core
 * Plugin URI: https://informaticocapella.com
 * Description: Plugin core con lógica de negocio para el sitio de Informático Capella. Implementa arquitectura limpia, SOLID y TDD.
 * Version: 1.0.0
 * Author: Pellax (Informático Capella)
 * Author URI: https://informaticocapella.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: informatico-capella-core
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.2
 *
 * @package InformaticoCapella
 */

declare(strict_types=1);

namespace InformaticoCapella;

// Si se accede directamente, abortar
if (!defined('ABSPATH')) {
    exit;
}

// Constantes del plugin
define('IC_CORE_VERSION', '1.0.0');
define('IC_CORE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IC_CORE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('IC_CORE_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoloader de Composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Clase principal del plugin
 * 
 * @since 1.0.0
 */
final class InformaticoCapellaCore
{
    /**
     * Instancia única del plugin (Singleton)
     *
     * @var InformaticoCapellaCore|null
     */
    private static ?InformaticoCapellaCore $instance = null;

    /**
     * Obtener instancia única del plugin
     *
     * @return InformaticoCapellaCore
     */
    public static function getInstance(): InformaticoCapellaCore
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    /**
     * Constructor privado (Singleton)
     */
    private function __construct()
    {
        $this->init();
    }

    /**
     * Inicializar el plugin
     *
     * @return void
     */
    private function init(): void
    {
        // Hooks de activación/desactivación
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // Cargar traducciones
        add_action('plugins_loaded', [$this, 'loadTextDomain']);

        // Inicializar componentes
        add_action('init', [$this, 'registerPostTypes']);
        add_action('init', [$this, 'registerTaxonomies']);

        // Registrar rutas REST API
        add_action('rest_api_init', [$this, 'registerRestRoutes']);

        // Enqueue scripts y estilos
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
    }

    /**
     * Activación del plugin
     *
     * @return void
     */
    public function activate(): void
    {
        // Crear tablas personalizadas si es necesario
        $this->createDatabaseTables();

        // Registrar post types para flush rewrite rules
        $this->registerPostTypes();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Log de activación
        error_log('Informático Capella Core: Plugin activado');
    }

    /**
     * Desactivación del plugin
     *
     * @return void
     */
    public function deactivate(): void
    {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Log de desactivación
        error_log('Informático Capella Core: Plugin desactivado');
    }

    /**
     * Cargar traducciones
     *
     * @return void
     */
    public function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'informatico-capella-core',
            false,
            dirname(IC_CORE_PLUGIN_BASENAME) . '/languages'
        );
    }

    /**
     * Registrar Custom Post Types
     *
     * @return void
     */
    public function registerPostTypes(): void
    {
        // Register Case Study CPT
        \InformaticoCapella\Infrastructure\WordPress\RegisterCaseStudyCPT::register();
        
        /**
         * Hook para que otros componentes registren post types
         *
         * @since 1.0.0
         */
        do_action('ic_core_register_post_types');
    }

    /**
     * Registrar Taxonomías personalizadas
     *
     * @return void
     */
    public function registerTaxonomies(): void
    {
        /**
         * Hook para que otros componentes registren taxonomías
         *
         * @since 1.0.0
         */
        do_action('ic_core_register_taxonomies');
    }

    /**
     * Registrar rutas REST API personalizadas
     *
     * @return void
     */
    public function registerRestRoutes(): void
    {
        // Register Case Study REST routes - manual include to avoid autoloader conflicts
        require_once IC_CORE_PLUGIN_DIR . 'src/Presentation/REST/CaseStudyController.php';

        if (class_exists('\InformaticoCapella\Presentation\REST\CaseStudyController')) {
            \InformaticoCapella\Presentation\REST\CaseStudyController::register_routes();
        } else {
            error_log('InformaticoCapella: CaseStudyController class not found after manual include');
        }

        /**
         * Hook para que otros componentes registren rutas REST
         *
         * @since 1.0.0
         */
        do_action('ic_core_register_rest_routes');
    }

    /**
     * Enqueue scripts y estilos del frontend
     *
     * @return void
     */
    public function enqueueScripts(): void
    {
        // Scripts del frontend se cargarán aquí según necesidad
        
        /**
         * Hook para enqueue de scripts personalizados
         *
         * @since 1.0.0
         */
        do_action('ic_core_enqueue_scripts');
    }

    /**
     * Enqueue scripts y estilos del admin
     *
     * @return void
     */
    public function enqueueAdminScripts(): void
    {
        /**
         * Hook para enqueue de scripts del admin
         *
         * @since 1.0.0
         */
        do_action('ic_core_enqueue_admin_scripts');
    }

    /**
     * Crear tablas de base de datos personalizadas
     *
     * @return void
     */
    private function createDatabaseTables(): void
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Ejemplo: tabla de leads
        $table_name = $wpdb->prefix . 'ic_leads';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            company varchar(255) DEFAULT NULL,
            phone varchar(50) DEFAULT NULL,
            message text DEFAULT NULL,
            service_type varchar(100) DEFAULT NULL,
            budget_range varchar(50) DEFAULT NULL,
            status varchar(50) DEFAULT 'new',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY email (email),
            KEY status (status),
            KEY created_at (created_at)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    /**
     * Prevenir clonación (Singleton)
     */
    private function __clone()
    {
    }

    /**
     * Prevenir deserialización (Singleton)
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}

/**
 * Función helper para obtener la instancia del plugin
 *
 * @return InformaticoCapellaCore
 */
function ic_core(): InformaticoCapellaCore
{
    return InformaticoCapellaCore::getInstance();
}

// Inicializar el plugin
ic_core();
