<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome_empresa'];
    $cnpj = $_POST['cnpj_empresa'];
    $particularidades = $_POST['particularidades'];
    $links = $_POST['links_empresa'];
    $endereco = $_POST['endereco_empresa'];
    $observacao_importacao = isset($_POST['OBS_importacao']) ? $conn->real_escape_string($_POST['OBS_importacao']) : '';
    $observacao_recebimento = isset($_POST['OBS_recebimentos']) ? $conn->real_escape_string($_POST['OBS_recebimentos']) : '';
    $observacao_link = isset($_POST['OBS_links']) ? $conn -> real_escape_string($_POST['OBS_links']) : '';
    $observacao_particularidades = isset($_POST['OBS_particularidades']) ? $conn -> real_escape_string($_POST['OBS_particularidades']) : '';

    $forma_recebimento = $_POST['forma_recebimento'];

    echo $observacao_recebimento;


    $sql = "INSERT INTO empresa (links_empresa, nome_empresa, cnpj_empresa, particularidades_empresa, endereco_empresa, obs_link, obs_particularidades) 
    values ('$links', '$nome', '$cnpj', '$particularidades', '$endereco', '$observacao_link', '$observacao_particularidades')";
    


    if ($conn->query($sql) === TRUE) {
        echo "Empresa criada com sucesso!";
        $empresa_id = $conn->insert_id; //pega o ultimo id inserido na tabela empresa

        if(!empty($_POST['importacao'])){ //verifica se a array das checkbox de importacao está vazia
            foreach($_POST['importacao'] as $descricao){//se ela não estiver vazia, vai percorrer a array inteira e dar um insert por loop

                $sql_importacao = "INSERT INTO formas_importacao(tipo_formasImportacao) VALUES ('$descricao')";
                
                if($conn->query($sql_importacao) === TRUE){
                    $importacao_id = $conn->insert_id;

                    $sql_empresa_importacao = "INSERT INTO empresa_importacao (ID_daEmpresa, ID_formaDeImportacao, obs_importacao) 
                                               VALUES ($empresa_id, $importacao_id, '$observacao_importacao')";                    
                    if ($conn->query($sql_empresa_importacao) !== TRUE) {
                        echo "Erro ao inserir na tabela de relacionamento: " . $conn->error;
                    }
                    }else{
                    // Exibe mensagem de erro se o INSERT na `formas_importacao` falhar
                    echo "Erro ao inserir forma de importação: " . $conn->error;
                    }
            }

        }
        
// Inserir forma de recebimento

        if (!empty($_POST['forma_recebimento'])) {
            $forma_recebimento = $conn->real_escape_string($_POST['forma_recebimento']);
            $sql_recebimento = "INSERT INTO forma_recebimento (tipo_formaRecebimento) VALUES ('$forma_recebimento')";
        
            if ($conn->query($sql_recebimento) === TRUE) {
                $recebimento_id = $conn->insert_id;

                /* ATÉ AQUI FOI */
            
                // Verificar subformas
                if(!empty($_POST['subformas_recebimento'])){ //verifica se a array das checkbox de importacao está vazia
                    foreach($_POST['subformas_recebimento'] as $subdescricao){
                        $subdescricao = $conn->real_escape_string($subdescricao);
                        $sql_subforma = "INSERT INTO subforma_recebimento (ID_formaDeRecebimento, nome_subforma) 
                                         VALUES ($recebimento_id, '$subdescricao')";
        
                        if ($conn->query($sql_subforma) === TRUE) {
                            $subforma_id = $conn->insert_id;
                        
                            // Inserir relacionamento com subforma válida
                            $sql_empresa_recebimento = "INSERT INTO empresa_recebimento (empresa_id, forma_recebimento_id, subforma_recebimento_id, obs_recebimento) 
                                                        VALUES ($empresa_id, $recebimento_id, $subforma_id, '$observacao_recebimento')";
        
                            if ($conn->query($sql_empresa_recebimento) !== TRUE) {
                                echo "Erro ao inserir na tabela de relacionamento de recebimento: " . $conn->error;
                            }
                        } else {
                            echo "Erro ao inserir subforma de recebimento: " . $conn->error;
                        }
                    }
                } else {
                    // Caso não haja subformas, criar uma subforma padrão ou ajustar para um valor padrão
                    // Exemplo: "Nenhuma" ou "Padrão", conforme necessário.
                    $sql_subforma_padrao = "INSERT INTO subforma_recebimento (ID_formaDeRecebimento, nome_subforma) 
                                            VALUES ($recebimento_id, 'Padrão')";
        
                    if ($conn->query($sql_subforma_padrao) === TRUE) {
                        $subforma_id = $conn->insert_id;
                    
                        // Inserir com subforma padrão
                        $sql_empresa_recebimento = "INSERT INTO empresa_recebimento (empresa_id, forma_recebimento_id, subforma_recebimento_id, obs_recebimento) 
                                                    VALUES ($empresa_id, $recebimento_id, $subforma_id, '$observacao_recebimento')";
        
                        if ($conn->query($sql_empresa_recebimento) !== TRUE) {
                            echo "Erro ao inserir na tabela de relacionamento de recebimento: " . $conn->error;
                        }
                    } else {
                        echo "Erro ao inserir subforma padrão: " . $conn->error;
                    }
                }
    } else {
        echo "Erro ao inserir forma de recebimento: " . $conn->error;
    }
}


                
                
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

?>