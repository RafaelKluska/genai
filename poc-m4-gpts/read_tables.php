<?php
// read_table.php

include 'db_connect.php';

$pdo = getDatabaseConnection();
$tabelasValidas = json_decode(file_get_contents('tables.json'), true);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tableName = $_GET['tableName'];
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100; // Default to 100 if not specified

    if (!in_array($tableName, $tabelasValidas)) {
        die("Nome de tabela inválido ou não listado.");
    }

    // Prepare a consulta SQL
    $sql = "SELECT * FROM `$tableName` LIMIT :limit";
    echo "Query: " . $sql . "\n"; // Exibe a consulta SQL
    echo "Limit: " . $limit . "\n"; // Exibe o limite

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

    // Tente executar a consulta e capturar qualquer exceção
    try {
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (Exception $e) {
        echo "Erro ao executar a consulta: " . $e->getMessage();
    }
} else {
    echo "Método de requisição inválido.";
}
?>