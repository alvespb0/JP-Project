<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome_empresa'];
    $cnpj = $_POST['cnpj_empresa'];
    $particularidades = $_POST['particularidades'];
    $links = $_POST['links_empresa'];
    $endereco = $_POST['endereco_empresa'];
    $observacao_importacao = isset($_POST['OBS_importacao']) ? $conn->real_escape_string($_POST['OBS_importacao']) : '';


    $sql = "INSERT INTO empresa (links_empresa, nome_empresa, cnpj_empresa, particularidades_empresa, endereco_empresa) values ('$links', '$nome', '$cnpj', '$particularidades', '$endereco')";
    


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
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

?>