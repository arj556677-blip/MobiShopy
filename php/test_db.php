<?php

require_once 'config.php';

$conn = getConnection();

$result = $conn->query("SELECT DATABASE() AS db_name");

if ($result) {
    $row = $result->fetch_assoc();

    echo json_encode([
        'status' => 'success',
        'message' => 'MySQL connection successful',
        'database' => $row['db_name']
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => $conn->error
    ]);
}

$conn->close();
