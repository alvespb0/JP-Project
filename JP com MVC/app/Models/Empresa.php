<?php
namespace App\Models;
include_once __DIR__ . '/../../config/db.php';


class empresa{
    private $conn;

    public function __construct() {
        global $conn; // Usando a conexão global do db.php
        $this->conn = $conn;
    }


    public function getAllEmpresas(){
        $sql = "SELECT * from empresa";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function getEmpresaByID($id){
        $sql = "SELECT * from empresa where ID_empresa = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEmpresaImportacaoById($id){
        $sql = "SELECT * from empresa_importacao where ID_daEmpresa = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getFormaImportacaoById($id){
        $sql = "SELECT tipo_formasImportacao FROM formas_importacao WHERE ID_formasImportacao = $id";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['tipo_formasImportacao'];
        } else {
            return null;
        }
    }
    
    public function getEmpresaRecebimentoById($id){
        $sql = "SELECT * from empresa_recebimento where empresa_id = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFormaRecebimentoByID($id){
        $sql = "SELECT * from forma_recebimento where ID_formaRecebimento = $id";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
            return $data['Tipo_formaRecebimento'];
        } else {
            return null;
        }
    }

    public function getSubFormaRecebimentoById($id){
        $sql = "SELECT * from subforma_recebimento where ID_formaDeRecebimento = $id";
        $result = $this->conn->query($sql);
            // Verifica se a consulta retornou algum resultado
        if ($result && $result->num_rows > 0) {
            $subFormas = [];
            while ($row = $result->fetch_assoc()) {
                $subFormas[] = $row['nome_subforma'];
            }
            return $subFormas;
        } else {
            return [];
        }
    }

    public function getEmpresaBySearch($search = '') {
        // Protege contra SQL Injection
        $search = $this->conn->real_escape_string($search);
        
        if (!empty($search)) {
            $sql = "SELECT * FROM empresa WHERE nome_empresa LIKE '%$search%'";
        } else {
            $sql = "SELECT * FROM empresa";
        }
        
        $result = $this->conn->query($sql);
        return $result;
    }

    public function CreateEmpresa() {
        $nome = $_POST['nome_empresa'];
        $cnpj = $_POST['cnpj_empresa'];
        $particularidades = $_POST['particularidades'];
        $links = $_POST['links_empresa'];
        $endereco = $_POST['endereco_empresa'];
        $observacao_importacao = isset($_POST['OBS_importacao']) ? $this->conn->real_escape_string($_POST['OBS_importacao']) : '';
        $observacao_recebimento = isset($_POST['OBS_recebimentos']) ? $this->conn->real_escape_string($_POST['OBS_recebimentos']) : '';
        $observacao_link = isset($_POST['OBS_links']) ? $this->conn->real_escape_string($_POST['OBS_links']) : '';
        $observacao_particularidades = isset($_POST['OBS_particularidades']) ? $this->conn->real_escape_string($_POST['OBS_particularidades']) : '';

        $sql = "INSERT INTO empresa (links_empresa, nome_empresa, cnpj_empresa, particularidades_empresa, endereco_empresa, obs_link, obs_particularidades) 
                VALUES ('$links', '$nome', '$cnpj', '$particularidades', '$endereco', '$observacao_link', '$observacao_particularidades')";

        if ($this->conn->query($sql) === TRUE) {
            echo "Empresa criada com sucesso!";
            $empresa_id = $this->conn->insert_id; // Pega o último id inserido na tabela empresa

            if (!empty($_POST['importacao'])) { // Verifica se a array das checkboxes de importação está vazia
                foreach ($_POST['importacao'] as $descricao) {
                    $sql_importacao = "INSERT INTO formas_importacao(tipo_formasImportacao) VALUES ('$descricao')";

                    if ($this->conn->query($sql_importacao) === TRUE) {
                        $importacao_id = $this->conn->insert_id;

                        $sql_empresa_importacao = "INSERT INTO empresa_importacao (ID_daEmpresa, ID_formaDeImportacao, obs_importacao) 
                                                   VALUES ($empresa_id, $importacao_id, '$observacao_importacao')";
                        if ($this->conn->query($sql_empresa_importacao) !== TRUE) {
                            echo "Erro ao inserir na tabela de relacionamento: " . $this->conn->error;
                        }
                    } else {
                        echo "Erro ao inserir forma de importação: " . $this->conn->error;
                    }
                }
            }

            // Inserir forma de recebimento
            if (!empty($_POST['forma_recebimento'])) {
                $forma_recebimento = $this->conn->real_escape_string($_POST['forma_recebimento']);
                $sql_recebimento = "INSERT INTO forma_recebimento (tipo_formaRecebimento) VALUES ('$forma_recebimento')";

                if ($this->conn->query($sql_recebimento) === TRUE) {
                    $recebimento_id = $this->conn->insert_id;

                    if (!empty($_POST['subformas_recebimento'])) {
                        foreach ($_POST['subformas_recebimento'] as $subdescricao) {
                            $subdescricao = $this->conn->real_escape_string($subdescricao);
                            $sql_subforma = "INSERT INTO subforma_recebimento (ID_formaDeRecebimento, nome_subforma) 
                                             VALUES ($recebimento_id, '$subdescricao')";

                            if ($this->conn->query($sql_subforma) === TRUE) {
                                $subforma_id = $this->conn->insert_id;

                                $sql_empresa_recebimento = "INSERT INTO empresa_recebimento (empresa_id, forma_recebimento_id, subforma_recebimento_id, obs_recebimento) 
                                                            VALUES ($empresa_id, $recebimento_id, $subforma_id, '$observacao_recebimento')";

                                if ($this->conn->query($sql_empresa_recebimento) !== TRUE) {
                                    echo "Erro ao inserir na tabela de relacionamento de recebimento: " . $this->conn->error;
                                }
                            } else {
                                echo "Erro ao inserir subforma de recebimento: " . $this->conn->error;
                            }
                        }
                    } else {
                        $sql_subforma_padrao = "INSERT INTO subforma_recebimento (ID_formaDeRecebimento, nome_subforma) 
                                                VALUES ($recebimento_id, 'Padrão')";

                        if ($this->conn->query($sql_subforma_padrao) === TRUE) {
                            $subforma_id = $this->conn->insert_id;

                            $sql_empresa_recebimento = "INSERT INTO empresa_recebimento (empresa_id, forma_recebimento_id, subforma_recebimento_id, obs_recebimento) 
                                                        VALUES ($empresa_id, $recebimento_id, $subforma_id, '$observacao_recebimento')";

                            if ($this->conn->query($sql_empresa_recebimento) !== TRUE) {
                                echo "Erro ao inserir na tabela de relacionamento de recebimento: " . $this->conn->error;
                            }
                        } else {
                            echo "Erro ao inserir subforma padrão: " . $this->conn->error;
                        }
                    }
                } else {
                    echo "Erro ao inserir forma de recebimento: " . $this->conn->error;
                }
            }
        } else {
            echo "Erro: " . $sql . "<br>" . $this->conn->error;
        }
    }

/*     public function UpdateEmpresaById($empresaId){
        echo 'entrou na função de update';
        $nome = $_POST['nome_empresa'] ?? '';
        $cnpj = $_POST['cnpj_empresa'] ?? '';
        $particularidades = $_POST['particularidades'] ?? '';
        $links = $_POST['links_empresa'] ?? '';
        $endereco = $_POST['endereco_empresa'] ?? '';
        $observacao_importacao = $this->conn->real_escape_string($_POST['OBS_importacao'] ?? '');
        $observacao_recebimento = $this->conn->real_escape_string($_POST['OBS_recebimentos'] ?? '');
        $observacao_link = $this->conn->real_escape_string($_POST['OBS_links'] ?? '');
        $observacao_particularidades = $this->conn->real_escape_string($_POST['OBS_particularidades'] ?? '');
    
        $sql = "UPDATE empresa 
                SET links_empresa = '$links', 
                    nome_empresa = '$nome', 
                    cnpj_empresa = '$cnpj', 
                    particularidades_empresa = '$particularidades', 
                    endereco_empresa = '$endereco', 
                    obs_link = '$observacao_link', 
                    obs_particularidades = '$observacao_particularidades' 
                WHERE id = $empresaId";
    
        $this->conn->query($sql);

        //update formas importacao

        $resultImportacao = true;
        if (isset($_POST['importacao'])) {
            $formasImportacaoSelecionadas = $_POST['importacao'];
    
            // Limpa as importações existentes da empresa
            $this->conn->query("DELETE FROM empresa_importacao WHERE ID_daEmpresa = $empresaId");
    
            // Insere as novas importações
            foreach ($formasImportacaoSelecionadas as $forma) {
                // Obtém o ID da forma de importação
                $formaEscapada = $this->conn->real_escape_string($forma);
                $stmtForma = $this->conn->query("SELECT ID_formasImportacao FROM formas_importacao WHERE tipo = '$formaEscapada'");
                mysqli_error($this->conn);
                $resultado = $stmtForma->fetch_assoc();
    
                if ($resultado) {
                    $idFormaImportacao = $resultado['ID_formasImportacao'];
                // Cria a consulta de inserção
                    $sqlImportacao = "INSERT INTO empresa_importacao (ID_daEmpresa, ID_formaDeImportacao, OBS_importacao) 
                                  VALUES ($empresaId, $idFormaImportacao, '$observacao_importacao')";
                // Executa a consulta
                    $resultImportacao = $this->conn->query($sqlImportacao);                
                }
            }
        }
        if (isset($_POST['recebimento'])) {
            $formasRecebimentoSelecionadas = $_POST['recebimento'];
    
            // Limpa as formas de recebimento existentes da empresa
            $this->conn->query("DELETE FROM empresa_recebimento WHERE empresa_id = $empresaId");
    
            // Insere as novas formas de recebimento
            foreach ($formasRecebimentoSelecionadas as $formaRecebimento) {
                $formaRecebimentoEscapada = $this->conn->real_escape_string($formaRecebimento);
                $stmtFormaRecebimento = $this->conn->query("SELECT ID_formaRecebimento FROM forma_recebimento WHERE Tipo_formaRecebimento = '$formaRecebimentoEscapada'");
                $resultadoRecebimento = $stmtFormaRecebimento->fetch_assoc();
    
                if ($resultadoRecebimento) {
                    $idFormaRecebimento = $resultadoRecebimento['ID_formaRecebimento'];
    
                    // Insere também as subformas de recebimento, se houver
                    if (isset($_POST['subformas_recebimento'][$formaRecebimento])) {
                        $subformasRecebimentoSelecionadas = $_POST['subformas_recebimento'][$formaRecebimento];
    
                        foreach ($subformasRecebimentoSelecionadas as $subforma) {
                            $subformaEscapada = $this->conn->real_escape_string($subforma);
                            $stmtSubforma = $this->conn->query("SELECT ID_subForma FROM subforma_recebimento WHERE nome_subforma = '$subformaEscapada'");
                            $resultadoSubforma = $stmtSubforma->fetch_assoc();
    
                            if ($resultadoSubforma) {
                                $idSubforma = $resultadoSubforma['ID_subForma'];
    
                                // Cria a consulta de inserção
                                $sqlRecebimento = "INSERT INTO empresa_recebimento (empresa_id, forma_recebimento_id, obs_recebimento, subforma_recebimento_id) 
                                                   VALUES ($empresaId, $idFormaRecebimento, '$observacao_recebimento', $idSubforma)";
                                $resultRecebimento = $this->conn->query($sqlRecebimento);
                            }
                        }
                    } else {
                        // Caso não haja subforma, insira sem subforma
                        $sqlRecebimento = "INSERT INTO empresa_recebimento (empresa_id, forma_recebimento_id, obs_recebimento) 
                                           VALUES ($empresaId, $idFormaRecebimento, '$observacao_recebimento')";
                        $resultRecebimento = $this->conn->query($sqlRecebimento);
                    }
                }
            }
        }
    } */
    public function UpdateEmpresaById($empresaId) {
        echo 'entrou na função de update';
        
        // Coleta dos dados do POST
        $nome = $_POST['nome_empresa'] ?? '';
        $cnpj = $_POST['cnpj_empresa'] ?? '';
        $particularidades = $_POST['particularidades'] ?? '';
        $links = $_POST['links_empresa'] ?? '';
        $endereco = $_POST['endereco_empresa'] ?? '';
        $observacao_importacao = $this->conn->real_escape_string($_POST['OBS_importacao'] ?? '');
        $observacao_recebimento = $this->conn->real_escape_string($_POST['OBS_recebimentos'] ?? '');
        $observacao_link = $this->conn->real_escape_string($_POST['OBS_links'] ?? '');
        $observacao_particularidades = $this->conn->real_escape_string($_POST['OBS_particularidades'] ?? '');
        
        // Atualiza os dados da empresa
        $sql = "UPDATE empresa 
                SET links_empresa = '$links', 
                    nome_empresa = '$nome', 
                    cnpj_empresa = '$cnpj', 
                    particularidades_empresa = '$particularidades', 
                    endereco_empresa = '$endereco', 
                    obs_link = '$observacao_link', 
                    obs_particularidades = '$observacao_particularidades' 
                WHERE ID_empresa = $empresaId";
    
        if (!$this->conn->query($sql)) {
            echo "Erro na atualização: " . $this->conn->error;
            return;
        }
    
        // Atualiza formas de importação
        $resultImportacao = true;

        if (isset($_POST['importacao'])) {
            $formasImportacaoSelecionadas = $_POST['importacao'];
            print_r($formasImportacaoSelecionadas);
            
            // Limpa as importações existentes para a empresa
            if (!$this->conn->query("DELETE FROM empresa_importacao WHERE ID_daEmpresa = $empresaId")) {
                echo "Erro na remoção de importações: " . $this->conn->error;
                $resultImportacao = false;
            }
        
            // Insere novas importações
            foreach ($formasImportacaoSelecionadas as $forma) {
                $formaEscapada = $this->conn->real_escape_string($forma);
                $observacaoEscapada = $this->conn->real_escape_string($observacao_importacao);
        
                // Insere a nova forma de importação (caso não exista)
                $sqlInsertForma = "INSERT IGNORE INTO formas_importacao (tipo_formasImportacao) VALUES ('$formaEscapada')";
        
                if (!$this->conn->query($sqlInsertForma)) {
                    echo "Erro ao inserir forma de importação: " . $this->conn->error;
                    $resultImportacao = false;
                    continue; // continua para a próxima forma
                }
        
                // Busca a ID da forma de importação
                $sqlCheck = "SELECT ID_formasImportacao FROM formas_importacao WHERE tipo_formasImportacao = '$formaEscapada' LIMIT 1";
                $resultCheck = $this->conn->query($sqlCheck);
        
                if ($resultCheck && $resultCheck->num_rows > 0) {
                    $formaId = $resultCheck->fetch_assoc()['ID_formasImportacao'];
        
                    // Insere a nova importação
                    $sqlImportacao = "INSERT INTO empresa_importacao (ID_daEmpresa, ID_formaDeImportacao, obs_importacao) 
                                      VALUES (?, ?, ?)";
        
                    // Preparar a declaração
                    if ($stmt = $this->conn->prepare($sqlImportacao)) {
                        $stmt->bind_param("iis", $empresaId, $formaId, $observacaoEscapada);
                        if (!$stmt->execute()) {
                            echo "Erro na inserção de importação: " . $stmt->error;
                            $resultImportacao = false;
                        }
                        $stmt->close();
                    } else {
                        echo "Erro ao preparar a declaração: " . $this->conn->error;
                        $resultImportacao = false;
                    }
                } else {
                    echo "Erro ao buscar ID da forma de importação: " . $this->conn->error;
                    $resultImportacao = false;
                }
            }
        
            if ($resultImportacao) {
                echo "Importações atualizadas com sucesso.";
            } else {
                echo "Houve um erro ao atualizar as importações.";
            }
        }
                
        // Opcional: lidar com o resultado da operação
        if ($resultImportacao) {
            echo "Importações atualizadas com sucesso.";
        } else {
            echo "Houve um erro ao atualizar as importações.";
        }
        
    
// Atualiza formas de recebimento
if (isset($_POST['forma_recebimento'])) {
    $formaRecebimento = $_POST['forma_recebimento'];
    $subFormasRecebimento = $_POST['subformas_recebimento'] ?? []; // Garante que seja um array
    $obsRecebimento = $_POST['OBS_recebimentos'];
    echo $formaRecebimento;
    foreach($subFormasRecebimento as $f){
        echo $f;
        echo '<br>';
    }
    echo $obsRecebimento;
        // Consulta o forma_recebimento_id na tabela empresa_recebimento
        $stmtConsulta = $this->conn->prepare("SELECT forma_recebimento_id FROM empresa_recebimento WHERE empresa_id = ?");
        $stmtConsulta->bind_param("i", $empresaId);
        $stmtConsulta->execute();
        $stmtConsulta->bind_result($formaRecebimentoId);
        $stmtConsulta->fetch();
        $stmtConsulta->close();

        // Verifica se o forma_recebimento_id foi encontrado
        if ($formaRecebimentoId) {
            // Atualiza o Tipo_formaRecebimento na tabela forma_recebimento
            $stmtUpdate = $this->conn->prepare("UPDATE forma_recebimento SET Tipo_formaRecebimento = ? WHERE ID_formaRecebimento = ?");
            $stmtUpdate->bind_param("si", $formaRecebimento, $formaRecebimentoId);

            if ($stmtUpdate->execute()) {
                // Mensagem de sucesso
                echo "Forma de recebimento atualizada com sucesso!";
            } else {
                // Mensagem de erro
                echo "Erro ao atualizar a forma de recebimento: " . $stmtUpdate->error;
            }
        }
    }

    //atualiza subformas
    $stmtConsulta = $this->conn->prepare("SELECT subforma_recebimento_id FROM empresa_recebimento WHERE empresa_id = ?");
    $stmtConsulta->bind_param("i", $empresaId);
    $stmtConsulta->execute();
    $result = $stmtConsulta->get_result();
    
    // Armazena os IDs retornados
    $subformasIds = [];
    while ($row = $result->fetch_assoc()) {
        $subformasIds[] = $row['subforma_recebimento_id'];
    }
    foreach ($subformasIds as $id){
        echo $id;
    }
    $stmtConsulta->close();

    // Atualiza ou insere as subformas de recebimento
    foreach ($subformasIds as $subformaId) {
        // Verifica se a subforma está no array recebido

        if (in_array($subformaId, $subFormasRecebimento)) {
            // Se a subforma já está na lista, atualiza
            echo "entrou no if";
            $nomeSubforma = 'Novo Nome'; // Substitua 'Novo Nome' pelo nome correto que você deseja
            $stmtUpdate = $this->conn->prepare("UPDATE subforma_recebimento SET nome_subforma = ? WHERE ID_subforma = ?");
            $stmtUpdate->bind_param("si", $nomeSubforma, $subformaId);
            $stmtUpdate->execute();
            $stmtUpdate->close();

        }

        } 
    }

    public function deleteEmpresaById($id){

            // Iniciar a transação
            $this->conn->begin_transaction();
        
            try {
                // Deletar registros nas tabelas relacionadas
                $stmt1 = $this->conn->prepare("DELETE FROM empresa_importacao WHERE ID_daEmpresa = ?");
                $stmt1->bind_param('i', $id);
                $stmt1->execute();
        
                $stmt2 = $this->conn->prepare("DELETE FROM empresa_recebimento WHERE empresa_id = ?");
                $stmt2->bind_param('i', $id);
                $stmt2->execute();
        
                // Deletar formas de recebimento relacionadas à empresa
                $stmt3 = $this->conn->prepare("DELETE FROM forma_recebimento WHERE ID_formaRecebimento IN (SELECT forma_recebimento_id FROM empresa_recebimento WHERE empresa_id = ?)");
                $stmt3->bind_param('i', $id);
                $stmt3->execute();
        
                // Deletar subformas de recebimento relacionadas
                $stmt4 = $this->conn->prepare("DELETE FROM subforma_recebimento WHERE ID_formaDeRecebimento IN (SELECT ID_formaRecebimento FROM forma_recebimento WHERE ID_formaRecebimento IN (SELECT forma_recebimento_id FROM empresa_recebimento WHERE empresa_id = ?))");
                $stmt4->bind_param('i', $id);
                $stmt4->execute();
        
                // Finalmente, deletar o registro da tabela empresa
                $stmt5 = $this->conn->prepare("DELETE FROM empresa WHERE ID_empresa = ?");
                $stmt5->bind_param('i', $id);
                $stmt5->execute();
        
                // Commit da transação
                $this->conn->commit();
            } catch (Exception $e) {
                // Em caso de erro, fazer rollback da transação
                $this->conn->rollback();
                // Logar ou tratar o erro como necessário
                echo "Erro ao excluir a empresa: " . $e->getMessage();
            }
                }

    public function getEmpresasTableRows() {
        $sql = "SELECT * FROM empresa";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $rows = '';

            while ($row = $result->fetch_assoc()) {
                $rows .= '<tr>';
                $rows .= '<td><a href="detalhes.php?id=' . htmlspecialchars($row['ID_empresa']) . '">' . htmlspecialchars($row['nome_empresa']) . '</a></td>';
                $rows .= '<td>' . htmlspecialchars($row['cnpj_empresa']) . '</td>';
                $rows .= '</tr>';
            }

            return $rows;
        } else {
            return '<tr><td colspan="2" class="text-center">Nenhuma empresa encontrada.</td></tr>';
        }
    }
    public function getEmpresaBYUrl(){
        $empresa_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        // Consulta para obter os detalhes da empresa a partir do ID obtido anteriormente
        $sql_empresa = "SELECT * FROM empresa WHERE ID_empresa = $empresa_id";
        $result_empresa = $this->conn->query($sql_empresa);
        if ($result_empresa && $result_empresa->num_rows > 0) {
            // Retorna o resultado como um array associativo
            return $result_empresa->fetch_assoc();
        } else {
            return null; // Ou algum outro valor que indique que a empresa não foi encontrada
        }    
    }
    public function getFormasImportacaoBYUrl(){
        $empresa_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $sql_importacao = "SELECT f.tipo_formasImportacao, e.obs_importacao 
        FROM formas_importacao f
        JOIN empresa_importacao e ON f.ID_formasImportacao = e.ID_formaDeImportacao
        WHERE e.ID_daEmpresa = $empresa_id";
        $result_importacao = $this->conn->query($sql_importacao);  

        return $result_importacao;  
    }

    public function getLinksBYUrl(){
        $empresa_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $sql_links = "SELECT links_empresa, obs_link FROM empresa WHERE ID_empresa = $empresa_id";
        $result_links = $this->conn->query($sql_links);
        if ($result_links->num_rows > 0){
            return $result_links->fetch_assoc();
        }else{
            return "nenhum link encontrado";
        }
    }

    public function getFormasRecebimentoBYUrl(){
        $empresa_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        // Consulta para obter formas de recebimento e suas subformas
        $sql_recebimento = "SELECT f.Tipo_formaRecebimento, sr.nome_subforma, e.obs_recebimento
                    FROM forma_recebimento f 
                    LEFT JOIN empresa_recebimento e ON f.ID_formaRecebimento = e.forma_recebimento_id 
                    LEFT JOIN subforma_recebimento sr ON e.subforma_recebimento_id = sr.ID_subforma 
                    WHERE e.empresa_id = $empresa_id LIMIT 0, 25;
        ";
        // Execute a consulta
        $result_recebimento = $this->conn->query($sql_recebimento);
        return $result_recebimento;
    }

    public function getParticularidadesBYUrl(){
        $empresa_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $sql_particularidades = "SELECT particularidades_empresa, OBS_particularidades FROM empresa WHERE ID_empresa = $empresa_id";
        $result_particularidades = $this->conn->query($sql_particularidades);
        return $result_particularidades;
    }

}
?>