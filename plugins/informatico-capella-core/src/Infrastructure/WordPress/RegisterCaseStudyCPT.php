<?php
declare(strict_types=1);

namespace InformaticoCapella\Infrastructure\WordPress;

/**
 * Register Case Study Custom Post Type
 */
final class RegisterCaseStudyCPT
{
    public static function register(): void
    {
        register_post_type('case_study', [
            'labels' => [
                'name' => __('Casos de Estudio', 'informatico-capella'),
                'singular_name' => __('Caso de Estudio', 'informatico-capella'),
                'add_new' => __('Añadir Nuevo', 'informatico-capella'),
                'add_new_item' => __('Añadir Nuevo Caso de Estudio', 'informatico-capella'),
                'edit_item' => __('Editar Caso de Estudio', 'informatico-capella'),
                'new_item' => __('Nuevo Caso de Estudio', 'informatico-capella'),
                'view_item' => __('Ver Caso de Estudio', 'informatico-capella'),
                'search_items' => __('Buscar Casos de Estudio', 'informatico-capella'),
                'not_found' => __('No se encontraron casos de estudio', 'informatico-capella'),
                'not_found_in_trash' => __('No se encontraron casos de estudio en la papelera', 'informatico-capella'),
                'all_items' => __('Todos los Casos de Estudio', 'informatico-capella'),
                'menu_name' => __('Casos de Estudio', 'informatico-capella'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'portafolio'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true, // Enable Gutenberg editor and REST API
            'rest_base' => 'case-studies',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ]);
        
        // Register meta fields for REST API
        self::registerMetaFields();
    }
    
    private static function registerMetaFields(): void
    {
        $metaFields = [
            'client' => 'string',
            'results' => 'string',
            'technologies' => 'string',
            'date_completed' => 'string',
        ];
        
        foreach ($metaFields as $key => $type) {
            register_post_meta('case_study', $key, [
                'type' => $type,
                'description' => ucfirst(str_replace('_', ' ', $key)),
                'single' => true,
                'show_in_rest' => true,
            ]);
        }
    }
}
