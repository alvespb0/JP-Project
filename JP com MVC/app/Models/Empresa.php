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