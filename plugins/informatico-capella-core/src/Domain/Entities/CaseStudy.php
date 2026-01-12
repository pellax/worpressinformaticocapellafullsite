<?php
/**
 * Entidad CaseStudy
 *
 * Representa un caso de estudio de la consultora
 *
 * @package InformaticoCapella\Domain\Entities
 */

declare(strict_types=1);

namespace InformaticoCapella\Domain\Entities;

use InformaticoCapella\Domain\Exceptions\InvalidCaseStudyException;

/**
 * Entidad de dominio: Caso de Estudio
 */
final class CaseStudy
{
    private const MIN_TITLE_LENGTH = 3;

    /**
     * Constructor
     *
     * @param int|null $id ID del caso de estudio (null para nuevos casos)
     * @param string $title Título del caso de estudio
     * @param string $client Cliente del proyecto
     * @param string $problem Problema que enfrentaba el cliente
     * @param string $solution Solución implementada
     * @param string $results Resultados obtenidos
     * @param array<string> $technologies Tecnologías utilizadas
     * @param string|null $slug Slug para URL (se genera automáticamente si es null)
     * 
     * @throws InvalidCaseStudyException Si los datos son inválidos
     */
    public function __construct(
        private ?int $id,
        private string $title,
        private string $client,
        private string $problem,
        private string $solution,
        private string $results,
        private array $technologies,
        private ?string $slug = null
    ) {
        $this->validate();
        
        if ($this->slug === null) {
            $this->slug = $this->generateSlug($this->title);
        }
    }

    /**
     * Validar los datos del caso de estudio
     *
     * @return void
     * @throws InvalidCaseStudyException
     */
    private function validate(): void
    {
        if (empty(trim($this->title))) {
            throw new InvalidCaseStudyException(
                'El título del caso de estudio no puede estar vacío'
            );
        }

        if (mb_strlen(trim($this->title)) < self::MIN_TITLE_LENGTH) {
            throw new InvalidCaseStudyException(
                sprintf('El título debe tener al menos %d caracteres', self::MIN_TITLE_LENGTH)
            );
        }

        if (empty(trim($this->client))) {
            throw new InvalidCaseStudyException('El cliente no puede estar vacío');
        }
    }

    /**
     * Generar slug desde el título
     *
     * @param string $title
     * @return string
     */
    private function generateSlug(string $title): string
    {
        // Convertir a minúsculas
        $slug = mb_strtolower($title);
        
        // Reemplazar caracteres acentuados
        $slug = $this->removeAccents($slug);
        
        // Reemplazar espacios y caracteres especiales con guiones
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
        
        // Eliminar guiones al inicio y final
        $slug = trim($slug, '-');
        
        return $slug;
    }

    /**
     * Eliminar acentos de un string
     *
     * @param string $string
     * @return string
     */
    private function removeAccents(string $string): string
    {
        $accents = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
            'ñ' => 'n', 'Ñ' => 'n',
            'ü' => 'u', 'Ü' => 'u'
        ];
        
        return str_replace(array_keys($accents), array_values($accents), $string);
    }

    /**
     * Obtener ID
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtener título
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Obtener cliente
     *
     * @return string
     */
    public function getClient(): string
    {
        return $this->client;
    }

    /**
     * Obtener problema
     *
     * @return string
     */
    public function getProblem(): string
    {
        return $this->problem;
    }

    /**
     * Obtener solución
     *
     * @return string
     */
    public function getSolution(): string
    {
        return $this->solution;
    }

    /**
     * Obtener resultados
     *
     * @return string
     */
    public function getResults(): string
    {
        return $this->results;
    }

    /**
     * Obtener tecnologías
     *
     * @return array<string>
     */
    public function getTechnologies(): array
    {
        return $this->technologies;
    }

    /**
     * Obtener slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Convertir a array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'client' => $this->client,
            'problem' => $this->problem,
            'solution' => $this->solution,
            'results' => $this->results,
            'technologies' => $this->technologies,
            'slug' => $this->slug,
        ];
    }

    /**
     * Crear caso de estudio desde array
     *
     * @param array<string, mixed> $data
     * @return self
     * @throws InvalidCaseStudyException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? '',
            client: $data['client'] ?? '',
            problem: $data['problem'] ?? '',
            solution: $data['solution'] ?? '',
            results: $data['results'] ?? '',
            technologies: $data['technologies'] ?? [],
            slug: $data['slug'] ?? null
        );
    }
}
