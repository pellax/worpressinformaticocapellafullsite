<?php
/**
 * Example Test
 * 
 * Test de ejemplo para verificar que el entorno de testing funciona correctamente
 *
 * @package InformaticoCapella\Tests\Unit
 */

declare(strict_types=1);

namespace InformaticoCapella\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Clase de test de ejemplo
 */
final class ExampleTest extends TestCase
{
    /**
     * Test básico: verificar que true es true
     * 
     * @test
     * @return void
     */
    public function it_can_assert_true(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test de suma simple
     * 
     * @test
     * @return void
     */
    public function it_can_add_numbers(): void
    {
        $result = 2 + 2;
        
        $this->assertSame(4, $result);
    }

    /**
     * Test de arrays
     * 
     * @test
     * @return void
     */
    public function it_can_work_with_arrays(): void
    {
        $array = ['foo' => 'bar', 'baz' => 'qux'];
        
        $this->assertArrayHasKey('foo', $array);
        $this->assertSame('bar', $array['foo']);
        $this->assertCount(2, $array);
    }

    /**
     * Test de strings
     * 
     * @test
     * @return void
     */
    public function it_can_work_with_strings(): void
    {
        $string = 'Informático Capella';
        
        $this->assertStringContainsString('Capella', $string);
        $this->assertStringStartsWith('Informático', $string);
        $this->assertSame(20, strlen($string)); // UTF-8: 'á' son 2 bytes
    }

    /**
     * Test de excepciones
     * 
     * @test
     * @return void
     */
    public function it_can_catch_exceptions(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument');
        
        throw new \InvalidArgumentException('Invalid argument');
    }

    /**
     * Test con data provider
     * 
     * @test
     * @dataProvider numbersProvider
     * @param int $a
     * @param int $b
     * @param int $expected
     * @return void
     */
    public function it_can_multiply_numbers(int $a, int $b, int $expected): void
    {
        $result = $a * $b;
        
        $this->assertSame($expected, $result);
    }

    /**
     * Data provider para test de multiplicación
     * 
     * @return array<int, array<int, int>>
     */
    public static function numbersProvider(): array
    {
        return [
            [2, 3, 6],
            [5, 5, 25],
            [10, 0, 0],
            [-2, 3, -6],
        ];
    }
}
