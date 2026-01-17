<?php
declare(strict_types=1);

namespace InformaticoCapella\Infrastructure\Repositories;

use InformaticoCapella\Domain\Entities\CaseStudy;
use InformaticoCapella\Domain\Repositories\CaseStudyRepository;
use WP_Post;
use WP_Query;

/**
 * WordPress implementation of CaseStudyRepository
 * 
 * Uses WordPress Custom Post Types to persist CaseStudy entities
 */
final class WordPressCaseStudyRepository implements CaseStudyRepository
{
    private const POST_TYPE = 'case_study';
    
    /**
     * Save a case study to WordPress
     */
    public function save(CaseStudy $caseStudy): int
    {
        $postData = [
            'post_type' => self::POST_TYPE,
            'post_title' => $caseStudy->getTitle(),
            'post_content' => $caseStudy->getDescription(),
            'post_status' => 'publish',
            'meta_input' => [
                'client' => $caseStudy->getClient(),
                'results' => $caseStudy->getResults(),
                'technologies' => $caseStudy->getTechnologies(),
                'date_completed' => $caseStudy->getDateCompleted()->format('Y-m-d'),
            ],
        ];
        
        // If case study already has an ID, update it
        if ($caseStudy->getId() !== null) {
            $postData['ID'] = $caseStudy->getId();
            $postId = wp_update_post($postData, true);
        } else {
            $postId = wp_insert_post($postData, true);
        }
        
        if (is_wp_error($postId)) {
            throw new \RuntimeException('Failed to save case study: ' . $postId->get_error_message());
        }
        
        return $postId;
    }
    
    /**
     * Find a case study by ID
     */
    public function findById(int $id): ?CaseStudy
    {
        $post = get_post($id);
        
        if (!$post || $post->post_type !== self::POST_TYPE) {
            return null;
        }
        
        return $this->mapPostToCaseStudy($post);
    }
    
    /**
     * Find all case studies
     * 
     * @return CaseStudy[]
     */
    public function findAll(): array
    {
        $query = new WP_Query([
            'post_type' => self::POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        
        if (!$query->have_posts()) {
            return [];
        }
        
        $caseStudies = [];
        foreach ($query->posts as $post) {
            $caseStudies[] = $this->mapPostToCaseStudy($post);
        }
        
        wp_reset_postdata();
        
        return $caseStudies;
    }
    
    /**
     * Delete a case study
     */
    public function delete(int $id): bool
    {
        $result = wp_delete_post($id, true);
        return $result !== false && $result !== null;
    }
    
    /**
     * Map WP_Post to CaseStudy entity
     */
    private function mapPostToCaseStudy(WP_Post $post): CaseStudy
    {
        $client = get_post_meta($post->ID, 'client', true);
        $results = get_post_meta($post->ID, 'results', true);
        $technologies = get_post_meta($post->ID, 'technologies', true);
        $dateCompleted = get_post_meta($post->ID, 'date_completed', true);
        
        return new CaseStudy(
            id: $post->ID,
            title: $post->post_title,
            client: $client ?: '',
            description: $post->post_content ?: '',
            results: $results ?: '',
            technologies: $technologies ?: '',
            dateCompleted: $dateCompleted ? new \DateTime($dateCompleted) : new \DateTime()
        );
    }
}
