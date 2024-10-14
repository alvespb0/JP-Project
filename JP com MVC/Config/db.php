<?php

/* $servername = "ns4.brdrive.net";
$username = "jpcontab"; 
$password = "jOg8M4&wO=r5doFruprl"; 
$dbname = "jp"; 
 */
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "jp"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

?>