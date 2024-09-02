<?php
include 'db.php';

$sql = "SELECT * FROM empresa";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["ID_empresa"]. " - Nome: " . $row["nome_empresa"]. " - CNPJ: " . $row["cnpj_empresa"]. "<br>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>