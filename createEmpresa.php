<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome_empresa'];
    $cnpj = $_POST['cnpj_empresa'];
    $particularidades = $_POST['particularidades'];
    $links = $_POST['links_empresa'];
    $endereco = $_POST['endereco_empresa'];

    $sql = "INSERT INTO empresa (links_empresa, nome_empresa, cnpj_empresa, particularidades_empresa, endereco_empresa) values ('$links', '$nome', '$cnpj', '$particularidades', '$endereco')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Empresa criada com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

?>