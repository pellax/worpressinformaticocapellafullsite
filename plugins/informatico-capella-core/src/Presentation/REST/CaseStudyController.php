<?php
declare(strict_types=1);

namespace InformaticoCapella\Presentation\REST;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

/**
 * REST API Controller for Case Studies
 */
final class CaseStudyController
{
    public const NAMESPACE = 'informatico/v1';
    public const RESOURCE_NAME = 'case-studies';

    public static function register_routes(): void
    {
        register_rest_route(self::NAMESPACE, '/' . self::RESOURCE_NAME, [
            [
                'methods' => 'GET',
                'callback' => [self::class, 'get_case_studies'],
                'permission_callback' => '__return_true',
            ],
        ]);

        register_rest_route(self::NAMESPACE, '/' . self::RESOURCE_NAME . '/(?P<slug>[a-zA-Z0-9-]+)', [
            [
                'methods' => 'GET',
                'callback' => [self::class, 'get_case_study_by_slug'],
                'permission_callback' => '__return_true',
                'args' => [
                    'slug' => [
                        'required' => true,
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_title',
                    ],
                ],
            ],
        ]);
    }

    public static function get_case_studies(WP_REST_Request $request): WP_REST_Response
    {
        $args = [
            'post_type' => 'case_study',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => [],
        ];

        // Filter by technology if requested
        $technology = $request->get_param('technology');
        if ($technology) {
            $args['meta_query'][] = [
                'key' => 'technologies',
                'value' => $technology,
                'compare' => 'LIKE',
            ];
        }

        $posts = get_posts($args);
        $case_studies = [];

        foreach ($posts as $post) {
            $case_studies[] = self::format_case_study($post);
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => $case_studies,
            'total' => count($case_studies),
        ], 200);
    }

    public static function get_case_study_by_slug(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $slug = $request->get_param('slug');

        $posts = get_posts([
            'post_type' => 'case_study',
            'post_status' => 'publish',
            'name' => $slug,
            'posts_per_page' => 1,
        ]);

        if (empty($posts)) {
            return new WP_Error(
                'case_study_not_found',
                __('Case study not found', 'informatico-capella'),
                ['status' => 404]
            );
        }

        $case_study = self::format_case_study($posts[0]);

        return new WP_REST_Response([
            'success' => true,
            'data' => $case_study,
        ], 200);
    }

    private static function format_case_study(\WP_Post $post): array
    {
        // Get meta fields
        $client = get_post_meta($post->ID, 'client', true);
        $results = get_post_meta($post->ID, 'results', true);
        $technologies = get_post_meta($post->ID, 'technologies', true);
        $date_completed = get_post_meta($post->ID, 'date_completed', true);

        // Get featured image
        $featured_image = null;
        if (has_post_thumbnail($post->ID)) {
            $image_id = get_post_thumbnail_id($post->ID);
            $featured_image = [
                'url' => get_the_post_thumbnail_url($post->ID, 'full'),
                'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
                'sizes' => [
                    'thumbnail' => get_the_post_thumbnail_url($post->ID, 'thumbnail'),
                    'medium' => get_the_post_thumbnail_url($post->ID, 'medium'),
                    'large' => get_the_post_thumbnail_url($post->ID, 'large'),
                    'full' => get_the_post_thumbnail_url($post->ID, 'full'),
                ],
            ];
        }

        // Parse technologies (assuming comma-separated string)
        $tech_array = [];
        if ($technologies) {
            $tech_array = array_map('trim', explode(',', $technologies));
        }

        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'excerpt' => $post->post_excerpt ?: wp_trim_words($post->post_content, 30),
            'content' => $post->post_content,
            'client' => $client ?: '',
            'problem' => $post->post_content, // Using content as problem for now
            'solution' => '', // Will be extracted from content structure later
            'results' => $results ?: '',
            'technologies' => $tech_array,
            'date_completed' => $date_completed ?: $post->post_date,
            'featured_image' => $featured_image,
            'url' => get_permalink($post->ID),
            'date_published' => $post->post_date,
            'date_modified' => $post->post_modified,
        ];
    }

    public static function get_available_technologies(): array
    {
        global $wpdb;

        $results = $wpdb->get_col("
            SELECT DISTINCT meta_value
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = 'technologies'
            AND p.post_type = 'case_study'
            AND p.post_status = 'publish'
            AND pm.meta_value != ''
        ");

        $all_technologies = [];
        foreach ($results as $tech_string) {
            $techs = array_map('trim', explode(',', $tech_string));
            $all_technologies = array_merge($all_technologies, $techs);
        }

        return array_unique(array_filter($all_technologies));
    }
}