<?php
include_once '../layouts/navbar.php';
require_once '../../Controllers/EmpresaController.php';

use App\Controllers\EmpresaController;

$empresaController = new EmpresaController();
$empresaId = isset($_POST['ID_empresa']) ? intval($_POST['ID_empresa']) : 0;

$empresa = $empresaController->exibirDetalhes($empresaId);
foreach ($empresa as $empresa) {
}

print_r ($empresaController->retornaImportacoes($empresaId));


// Verifica se o formulário foi submetido para atualizar os dados
/* if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza a empresa com os novos dados do formulário
    $empresaController->atualizarEmpresa($empresaId);
}
 */
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Empresa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Seu CSS personalizado */
        body {
            background-color: #f8f9fa; /* Cor de fundo leve */
        }
        .container {
            margin-top: 30px;
        }
        h1 {
            color: #003366; /* Azul escuro */
            margin-bottom: 30px;
        }
        .form-control, .form-select {
            border-radius: 0;
        }
        .form-label {
            color: #003366; /* Azul escuro */
        }
        .btn-primary {
            background-color: #c8102e; /* Vermelho */
            border-color: #c8102e;
        }
        .btn-primary:hover {
            background-color: #a00d1e; /* Vermelho escuro no hover */
            border-color: #a00d1e;
        }
        .form-check-input:checked {
            background-color: #c8102e; /* Vermelho */
            border-color: #c8102e;
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(200, 16, 46, 0.25); /* Efeito de foco no vermelho */
        }
        .form-check-label {
            color: #003366; /* Azul escuro */
        }
        .mb-3, .mt-4 {
            margin-bottom: 1.5rem;
        }
        .row {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar empresa</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Empresa</label>
                <input type="text" id="nome" name="nome_empresa" class="form-control" value="<?php echo htmlspecialchars($empresa['nome_empresa']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="cnpj" class="form-label">CNPJ</label>
                <input type="text" id="cnpj" name="cnpj_empresa" class="form-control" value="<?php echo htmlspecialchars($empresa['cnpj_empresa']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" id="endereco" name="endereco_empresa" class="form-control" value="<?php echo htmlspecialchars($empresa['endereco_empresa']); ?>">
            </div>
            <div class="mb-3">
                <label for="links" class="form-label">Links da Empresa</label>
                <input type="text" id="links" name="links_empresa" class="form-control" value="<?php echo htmlspecialchars($empresa['links_empresa']); ?>">
            </div>
            <div class="mb-3">
                <label for="OBS_links" class="form-label">Observações sobre os Links</label>
                <input type="text" id="OBS_links" name="OBS_links" class="form-control" value="<?php echo htmlspecialchars($empresa['obs_link']); ?>">
            </div>
            <div class="mb-3">
                <label for="particularidades" class="form-label">Particularidades</label>
                <input type="text" id="particularidades" name="particularidades" class="form-control" value="<?php echo htmlspecialchars($empresa['particularidades_empresa']); ?>">
            </div>
            <div class="mb-3">
                <label for="OBS_particularidades" class="form-label">Observações sobre Particularidades</label>
                <input type="text" id="OBS_particularidades" name="OBS_particularidades" class="form-control" value="<?php echo htmlspecialchars($empresa['obs_particularidades']); ?>">
            </div>
            <h3>Formas de importacao</h3>
            <?php echo $empresaController->montaHtmlFormasImportacao($empresaId);?>
            <h3 class="mt-4">Formas de Recebimento</h3>
            <label class="form-label">Selecione as Formas de Recebimento:</label>
            <?php echo $empresaController->montaHTMLRecebimentos($empresaId);?>
            <button type="submit" class="btn btn-primary">Editar</button>
        </form>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-puhDKOtLg2geVJtshPYcPfMBgPrrb6Hw8fYpibcUwRbp/da7g+UqIEZ+tiBUCw4z" crossorigin="anonymous"></script>
</body>
</html>
                


