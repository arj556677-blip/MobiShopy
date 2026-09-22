<?php
require_once 'config.php';
$action = $_GET['action'] ?? '';
$conn   = getConnection();

if ($action === 'list') {
    $result = $conn->query("SELECT customer_id, customer_name, address, phone, username FROM CUSTOMER ORDER BY customer_name");
    echo json_encode(['status' => 'success', 'data' => $result->fetch_all(MYSQLI_ASSOC)]);
    exit;
}

if ($action === 'get') {
    $id   = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT customer_id, customer_name, address, phone, username FROM CUSTOMER WHERE customer_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'data' => $stmt->get_result()->fetch_assoc()]);
    exit;
}

if ($action === 'delete') {
    $id   = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM CUSTOMER WHERE customer_id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Customer deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cannot delete — customer has sales records.']);
    }
    exit;
}

$conn->close();
echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
