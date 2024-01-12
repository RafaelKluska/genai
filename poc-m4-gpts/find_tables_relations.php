<?php
// find_table_relations.php

include 'db_connect.php';

$pdo = getDatabaseConnection();
$dbName = getDatabaseName(); // Obtém o nome do banco de dados da função no db_connect.php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tableName = $_GET['tableName'];

    // Armazena as relações
    $relations = [
        'is_primary_key_in' => [],
        'related_tables' => []
    ];

    // Encontra tabelas onde a tabela especificada é uma chave primária
    $sqlPrimary = "SELECT TABLE_NAME
                   FROM information_schema.TABLE_CONSTRAINTS
                   WHERE TABLE_SCHEMA = :dbName AND CONSTRAINT_TYPE = 'PRIMARY KEY' AND TABLE_NAME = :tableName";

    $stmtPrimary = $pdo->prepare($sqlPrimary);
    $stmtPrimary->execute(['dbName' => $dbName, 'tableName' => $tableName]);

    while ($row = $stmtPrimary->fetch(PDO::FETCH_ASSOC)) {
        $relations['is_primary_key_in'][] = $row['TABLE_NAME'];
    }

    // Encontra relações de chave estrangeira que referenciam a tabela especificada
    $sqlForeign = "SELECT DISTINCT TABLE_NAME
                   FROM information_schema.KEY_COLUMN_USAGE
                   WHERE REFERENCED_TABLE_SCHEMA = :dbName AND REFERENCED_TABLE_NAME = :tableName";

    $stmtForeign = $pdo->prepare($sqlForeign);
    $stmtForeign->execute(['dbName' => $dbName, 'tableName' => $tableName]);

    while ($row = $stmtForeign->fetch(PDO::FETCH_ASSOC)) {
        $relations['related_tables'][] = $row['TABLE_NAME'];
    }

    echo json_encode($relations);
} else {
    echo "Método de requisição inválido.";
}
?>
