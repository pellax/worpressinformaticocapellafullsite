<?php
/**
 * Tests para la entidad CaseStudy
 *
 * @package InformaticoCapella\Tests\Unit\Domain
 */

declare(strict_types=1);

namespace InformaticoCapella\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use InformaticoCapella\Domain\Entities\CaseStudy;
use InformaticoCapella\Domain\Exceptions\InvalidCaseStudyException;

/**
 * Test de la entidad CaseStudy
 */
final class CaseStudyTest extends TestCase
{
    /**
     * Test: Crear caso de estudio con datos válidos
     * 
     * @test
     * @return void
     */
    public function it_creates_case_study_with_valid_data(): void
    {
        $caseStudy = new CaseStudy(
            id: null,
            title: 'Migración a AWS para Startup',
            client: 'Tech Startup Inc.',
            problem: 'Infraestructura on-premise costosa y poco escalable',
            solution: 'Migración completa a AWS con arquitectura serverless',
            results: 'Reducción de costos del 40% y mejora de performance del 200%',
            technologies: ['AWS', 'Lambda', 'DynamoDB', 'CloudFront'],
            slug: 'migracion-aws-startup'
        );
        
        $this->assertNull($caseStudy->getId());
        $this->assertSame('Migración a AWS para Startup', $caseStudy->getTitle());
        $this->assertSame('Tech Startup Inc.', $caseStudy->getClient());
        $this->assertSame('Infraestructura on-premise costosa y poco escalable', $caseStudy->getProblem());
        $this->assertSame('Migración completa a AWS con arquitectura serverless', $caseStudy->getSolution());
        $this->assertSame('Reducción de costos del 40% y mejora de performance del 200%', $caseStudy->getResults());
        $this->assertCount(4, $caseStudy->getTechnologies());
        $this->assertContains('AWS', $caseStudy->getTechnologies());
        $this->assertSame('migracion-aws-startup', $caseStudy->getSlug());
    }

    /**
     * Test: Lanzar excepción si el título está vacío
     * 
     * @test
     * @return void
     */
    public function it_throws_exception_if_title_is_empty(): void
    {
        $this->expectException(InvalidCaseStudyException::class);
        $this->expectExceptionMessage('El título del caso de estudio no puede estar vacío');
        
        new CaseStudy(
            id: null,
            title: '',
            client: 'Tech Startup Inc.',
            problem: 'Problem description',
            solution: 'Solution description',
            results: 'Results description',
            technologies: ['AWS'],
            slug: 'test-slug'
        );
    }

    /**
     * Test: Lanzar excepción si el cliente está vacío
     * 
     * @test
     * @return void
     */
    public function it_throws_exception_if_client_is_empty(): void
    {
        $this->expectException(InvalidCaseStudyException::class);
        $this->expectExceptionMessage('El cliente no puede estar vacío');
        
        new CaseStudy(
            id: null,
            title: 'Test Title',
            client: '',
            problem: 'Problem description',
            solution: 'Solution description',
            results: 'Results description',
            technologies: ['AWS'],
            slug: 'test-slug'
        );
    }

    /**
     * Test: Generar slug automáticamente si no se proporciona
     * 
     * @test
     * @return void
     */
    public function it_generates_slug_automatically_if_not_provided(): void
    {
        $caseStudy = new CaseStudy(
            id: null,
            title: 'Migración a AWS para Startup',
            client: 'Tech Startup Inc.',
            problem: 'Problem description',
            solution: 'Solution description',
            results: 'Results description',
            technologies: ['AWS'],
            slug: null
        );
        
        $this->assertSame('migracion-a-aws-para-startup', $caseStudy->getSlug());
    }

    /**
     * Test: Convertir caso de estudio a array
     * 
     * @test
     * @return void
     */
    public function it_converts_to_array(): void
    {
        $caseStudy = new CaseStudy(
            id: 1,
            title: 'Test Case Study',
            client: 'Test Client',
            problem: 'Test Problem',
            solution: 'Test Solution',
            results: 'Test Results',
            technologies: ['PHP', 'WordPress'],
            slug: 'test-case-study'
        );
        
        $array = $caseStudy->toArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('client', $array);
        $this->assertArrayHasKey('problem', $array);
        $this->assertArrayHasKey('solution', $array);
        $this->assertArrayHasKey('results', $array);
        $this->assertArrayHasKey('technologies', $array);
        $this->assertArrayHasKey('slug', $array);
        $this->assertSame(1, $array['id']);
    }

    /**
     * Test: Crear caso de estudio desde array
     * 
     * @test
     * @return void
     */
    public function it_creates_from_array(): void
    {
        $data = [
            'id' => 5,
            'title' => 'DevOps Implementation',
            'client' => 'Enterprise Corp',
            'problem' => 'Manual deployments',
            'solution' => 'CI/CD pipeline with Jenkins',
            'results' => 'Deployment time reduced by 80%',
            'technologies' => ['Jenkins', 'Docker', 'Kubernetes'],
            'slug' => 'devops-implementation'
        ];
        
        $caseStudy = CaseStudy::fromArray($data);
        
        $this->assertInstanceOf(CaseStudy::class, $caseStudy);
        $this->assertSame(5, $caseStudy->getId());
        $this->assertSame('DevOps Implementation', $caseStudy->getTitle());
        $this->assertSame('Enterprise Corp', $caseStudy->getClient());
    }

    /**
     * Test: Validar que las tecnologías sean un array
     * 
     * @test
     * @return void
     */
    public function it_validates_technologies_is_array(): void
    {
        $caseStudy = new CaseStudy(
            id: null,
            title: 'Test',
            client: 'Client',
            problem: 'Problem',
            solution: 'Solution',
            results: 'Results',
            technologies: [],
            slug: 'test'
        );
        
        $this->assertIsArray($caseStudy->getTechnologies());
        $this->assertCount(0, $caseStudy->getTechnologies());
    }

    /**
     * Test: Verificar que el ID puede ser null o entero
     * 
     * @test
     * @return void
     */
    public function it_accepts_null_or_int_id(): void
    {
        $caseStudyWithoutId = new CaseStudy(
            id: null,
            title: 'Test',
            client: 'Client',
            problem: 'Problem',
            solution: 'Solution',
            results: 'Results',
            technologies: ['PHP'],
            slug: 'test'
        );
        
        $caseStudyWithId = new CaseStudy(
            id: 42,
            title: 'Test',
            client: 'Client',
            problem: 'Problem',
            solution: 'Solution',
            results: 'Results',
            technologies: ['PHP'],
            slug: 'test'
        );
        
        $this->assertNull($caseStudyWithoutId->getId());
        $this->assertSame(42, $caseStudyWithId->getId());
    }

    /**
     * Test: Validar longitud mínima del título
     * 
     * @test
     * @return void
     */
    public function it_validates_minimum_title_length(): void
    {
        $this->expectException(InvalidCaseStudyException::class);
        $this->expectExceptionMessage('El título debe tener al menos 3 caracteres');
        
        new CaseStudy(
            id: null,
            title: 'AB',
            client: 'Client',
            problem: 'Problem',
            solution: 'Solution',
            results: 'Results',
            technologies: ['PHP'],
            slug: 'test'
        );
    }
}
