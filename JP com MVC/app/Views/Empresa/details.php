<?php
include_once '../layouts/navbar.php';
require_once '../../Controllers/EmpresaController.php';

use App\Controllers\EmpresaController;
$empresaController = new EmpresaController();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Empresa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 20px;
        }
        .table th, .table td {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Detalhes da Empresa</h1>
        
        <!-- Informações da Empresa -->
        <div class="card">
            <div class="card-body">
                <h3><?php echo $empresaController->empresaEncontradaURL()['nome_empresa']; ?></h3>
                <p><strong>CNPJ:</strong> <?php echo  $empresaController->empresaEncontradaURL()['cnpj_empresa']; ?></p>
                <p><strong>Endereço:</strong> <?php echo  $empresaController->empresaEncontradaURL()['endereco_empresa']; ?></p>
            </div>
        </div>

        <!-- Formas de Importação -->
        <div class="card">
            <div class="card-body">
                <h3>Formas de Importação</h3>
                <?php $empresaController->DetailsFormasDeImportacao();?>
            </div>
        </div>

        <!-- Links -->
        <div class="card">
            <div class="card-body">
                <h3>Links</h3>
                <?php $empresaController->DetailsLinks();?>
            </div>
        </div>

        <!-- Forma de Recebimento -->
        <div class="card">
            <div class="card-body">
                <h3>Forma de Recebimento</h3>
                <?php $empresaController->DetailsFormasDeRecebimento();?>
            </div>
        </div>


        <!-- Particularidades -->
        <div class="card">
            <div class="card-body">
                <h3>Particularidades</h3>
                <?php $empresaController->DetailsParticularidades();?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>