<?php
// list_tables.php

include 'db_connect.php';

$pdo = getDatabaseConnection();

$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Escrever os nomes das tabelas em um arquivo JSON
file_put_contents('tables.json', json_encode($tables));

Echo "Foi criado um arquivo tables.json com as tabelas do banco de dados";

?>