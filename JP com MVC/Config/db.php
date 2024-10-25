<?php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "jp"; 

/* $servername = "localhost";
$username = "jp";
$password = "NBJ394JBE2yT";
$dbname = "jpcontab_jp"; */ 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

?>