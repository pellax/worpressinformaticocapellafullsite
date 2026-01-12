<?php
/**
 * PHPUnit Bootstrap File
 * 
 * Inicializa el entorno de testing
 *
 * @package InformaticoCapella\Tests
 */

declare(strict_types=1);

// Autoloader de Composer
$autoloader = dirname(__DIR__) . '/vendor/autoload.php';

if (!file_exists($autoloader)) {
    throw new RuntimeException(
        'No se pudo encontrar el autoloader de Composer. ' .
        'Ejecuta "composer install" antes de correr los tests.'
    );
}

require_once $autoloader;

// Definir constantes para tests
define('INFORMATICO_CAPELLA_TESTS', true);
define('INFORMATICO_CAPELLA_TEST_DIR', __DIR__);
define('INFORMATICO_CAPELLA_PLUGIN_DIR', dirname(__DIR__));

// Timezone
date_default_timezone_set('UTC');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "\n";
echo "========================================\n";
echo "Informático Capella Core - Test Suite\n";
echo "========================================\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "PHPUnit: Bootstrap loaded successfully\n";
echo "========================================\n\n";
