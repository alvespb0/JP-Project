<?php
include 'db.php';
include 'index.php'; 

// Obtém o ID da empresa a partir da URL
$empresa_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obter os detalhes da empresa a partir do ID obtido anteriormente
$sql_empresa = "SELECT * FROM empresa WHERE ID_empresa = $empresa_id";
$result_empresa = $conn->query($sql_empresa);
$empresa = $result_empresa->fetch_assoc();

// Verifica se a emresa foi encontrada
if (!$empresa) {
    echo "Empresa não encontrada.";
    exit();
}

// Consulta para obter formas de importação || essa parte do join foi utilizado o chat, não lembrava como fazia
$sql_importacao = "SELECT f.tipo_formasImportacao, e.obs_importacao 
                    FROM formas_importacao f
                    JOIN empresa_importacao e ON f.ID_formasImportacao = e.ID_formaDeImportacao
                    WHERE e.ID_daEmpresa = $empresa_id";

$result_importacao = $conn->query($sql_importacao);

// apenas para agrupar a obs e não ficar duplicando
$obs_importacao = null;
$importacao_data = [];
while ($row = $result_importacao->fetch_assoc()) {
    $importacao_data[] = $row['tipo_formasImportacao'];
    $obs_importacao = $row['obs_importacao']; 
}


// Consulta para obter links e observações
$sql_links = "SELECT links_empresa, obs_link FROM empresa WHERE ID_empresa = $empresa_id";

// Consulta para obter formas de recebimento e suas subformas
$sql_recebimento = "SELECT f.Tipo_formaRecebimento, sr.nome_subforma, e.obs_recebimento
                    FROM forma_recebimento f 
                    LEFT JOIN empresa_recebimento e ON f.ID_formaRecebimento = e.forma_recebimento_id 
                    LEFT JOIN subforma_recebimento sr ON e.subforma_recebimento_id = sr.ID_subforma 
                    WHERE e.empresa_id = $empresa_id LIMIT 0, 25;
";

$result_recebimento = $conn->query($sql_recebimento);

// apenas para agrupar a obs e não ficar duplicando
$obs_recebimento = null;
$recebimento_data = [];
while ($row = $result_recebimento->fetch_assoc()) {
    $recebimento_data[] = $row;
    $obs_recebimento = $row['obs_recebimento']; 
}

// Consulta para obter particularidades e observações
$sql_particularidades = "SELECT particularidades_empresa, OBS_particularidades FROM empresa WHERE ID_empresa = $empresa_id";

$result_importacao = $conn->query($sql_importacao);
$result_links = $conn->query($sql_links);
$result_recebimento = $conn->query($sql_recebimento);
$result_particularidades = $conn->query($sql_particularidades);
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
                <h3><?php echo htmlspecialchars($empresa['nome_empresa']); ?></h3>
                <p><strong>CNPJ:</strong> <?php echo htmlspecialchars($empresa['cnpj_empresa']); ?></p>
                <p><strong>Endereço:</strong> <?php echo htmlspecialchars($empresa['endereco_empresa']); ?></p>
            </div>
        </div>

        <!-- Formas de Importação -->
        <div class="card">
            <div class="card-body">
                <h3>Formas de Importação</h3>
                <?php if (!empty($importacao_data)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Forma de Importação</th>
                                <th>Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($importacao_data as $forma): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($forma); ?></td>
                                    <?php if ($forma === reset($importacao_data)): // Exibe a observação apenas para a primeira linha ?>
                                        <td rowspan="<?php echo count($importacao_data); ?>"><?php echo htmlspecialchars($obs_importacao); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhuma forma de importação encontrada.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Links -->
        <div class="card">
            <div class="card-body">
                <h3>Links</h3>
                <?php if ($result_links->num_rows > 0): ?>
                    <?php $links = $result_links->fetch_assoc(); ?>
                    <p><strong>Links:</strong> <?php echo htmlspecialchars($links['links_empresa']); ?></p>
                    <p><strong>Observações:</strong> <?php echo htmlspecialchars($links['obs_link']); ?></p>
                <?php else: ?>
                    <p>Nenhum link encontrado.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Forma de Recebimento -->
        <div class="card">
            <div class="card-body">
                <h3>Forma de Recebimento</h3>
                <?php if (!empty($recebimento_data)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Forma de Recebimento</th>
                                <th>Subforma</th>
                                <th>Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recebimento_data as $index => $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Tipo_formaRecebimento']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nome_subforma']); ?></td>
                                    <?php if ($index === 0): // Exibe a observação apenas para a primeira linha ?>
                                        <td rowspan="<?php echo count($recebimento_data); ?>"><?php echo htmlspecialchars($obs_recebimento); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhuma forma de recebimento encontrada.</p>
                <?php endif; ?>
            </div>
        </div>


        <!-- Particularidades -->
        <div class="card">
            <div class="card-body">
                <h3>Particularidades</h3>
                <?php if ($result_particularidades->num_rows > 0): ?>
                    <?php $particularidades = $result_particularidades->fetch_assoc(); ?>
                    <p><strong>Particularidades:</strong> <?php echo htmlspecialchars($particularidades['particularidades_empresa']); ?></p>
                    <p><strong>Observações:</strong> <?php echo htmlspecialchars($particularidades['OBS_particularidades']); ?></p>
                <?php else: ?>
                    <p>Nenhuma particularidade encontrada.</p>
                <?php endif; ?>
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

