<?php
require_once 'config.php';
$action = $_GET['action'] ?? '';
$conn   = getConnection();

if ($action === 'list') {
    $result = $conn->query("SELECT supplier_id, supplier_name, phone, username FROM SUPPLIER ORDER BY supplier_name");
    echo json_encode(['status' => 'success', 'data' => $result->fetch_all(MYSQLI_ASSOC)]);
    exit;
}

if ($action === 'get') {
    $id   = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT supplier_id, supplier_name, phone, username FROM SUPPLIER WHERE supplier_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'data' => $stmt->get_result()->fetch_assoc()]);
    exit;
}

if ($action === 'delete') {
    $id   = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM SUPPLIER WHERE supplier_id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Supplier deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cannot delete — supplier has mobile listings.']);
    }
    exit;
}

$conn->close();
echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
