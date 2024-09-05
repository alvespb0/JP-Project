<?php
// Inclui o arquivo de configuração do banco de dados
require_once __DIR__ . '/../config/db.php';

// Função de autoloading manual
function my_autoload($class) {
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

// Registra a função de autoloading
spl_autoload_register('my_autoload');

// Define a ação padrão
$action = isset($_GET['action']) ? $_GET['action'] : 'listar';

// Instancia o controlador
use App\Controllers\EmpresaController;

$controller = new EmpresaController();

switch ($action) {
    case 'listar':
        $controller->listarEmpresas();
        break;
    // Adicione outros casos conforme necessário
    default:
        echo "Ação não encontrada.";
        break;
}
?>