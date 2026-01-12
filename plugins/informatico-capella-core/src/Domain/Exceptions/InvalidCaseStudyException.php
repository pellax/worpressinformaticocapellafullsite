<?php
/**
 * Excepción para casos de estudio inválidos
 *
 * @package InformaticoCapella\Domain\Exceptions
 */

declare(strict_types=1);

namespace InformaticoCapella\Domain\Exceptions;

use InvalidArgumentException;

/**
 * Excepción lanzada cuando un caso de estudio tiene datos inválidos
 */
final class InvalidCaseStudyException extends InvalidArgumentException
{
}
