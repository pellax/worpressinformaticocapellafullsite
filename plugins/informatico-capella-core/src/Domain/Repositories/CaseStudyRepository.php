<?php
/**
 * Interface CaseStudyRepository
 *
 * Define el contrato para el repositorio de casos de estudio
 *
 * @package InformaticoCapella\Domain\Repositories
 */

declare(strict_types=1);

namespace InformaticoCapella\Domain\Repositories;

use InformaticoCapella\Domain\Entities\CaseStudy;

/**
 * Repositorio de casos de estudio
 *
 * Siguiendo el patrón Repository, esta interface define las operaciones
 * de persistencia sin acoplar a una implementación específica
 */
interface CaseStudyRepository
{
    /**
     * Obtener todos los casos de estudio
     *
     * @return array<CaseStudy>
     */
    public function findAll(): array;

    /**
     * Obtener un caso de estudio por ID
     *
     * @param int $id
     * @return CaseStudy|null
     */
    public function findById(int $id): ?CaseStudy;

    /**
     * Obtener un caso de estudio por slug
     *
     * @param string $slug
     * @return CaseStudy|null
     */
    public function findBySlug(string $slug): ?CaseStudy;

    /**
     * Guardar un caso de estudio
     *
     * @param CaseStudy $caseStudy
     * @return int ID del caso de estudio guardado
     */
    public function save(CaseStudy $caseStudy): int;

    /**
     * Actualizar un caso de estudio existente
     *
     * @param CaseStudy $caseStudy
     * @return bool
     */
    public function update(CaseStudy $caseStudy): bool;

    /**
     * Eliminar un caso de estudio
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Obtener casos de estudio por tecnología
     *
     * @param string $technology
     * @return array<CaseStudy>
     */
    public function findByTechnology(string $technology): array;

    /**
     * Contar total de casos de estudio
     *
     * @return int
     */
    public function count(): int;
}
