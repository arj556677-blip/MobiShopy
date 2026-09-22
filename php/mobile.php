<?php
require_once 'config.php';

$action = $_GET['action'] ?? '';
$conn   = getConnection();

// ── GET ALL MOBILES (with optional filters) ──────────────────
if ($action === 'list') {
    $where  = ['1=1'];
    $params = [];
    $types  = '';

    if (!empty($_GET['brand'])) {
        $where[] = 'brand = ?';
        $params[] = $_GET['brand'];
        $types   .= 's';
    }
    if (!empty($_GET['min_price'])) {
        $where[] = 'price >= ?';
        $params[] = (float)$_GET['min_price'];
        $types   .= 'd';
    }
    if (!empty($_GET['max_price'])) {
        $where[] = 'price <= ?';
        $params[] = (float)$_GET['max_price'];
        $types   .= 'd';
    }
    if (!empty($_GET['in_stock'])) {
        $where[] = 'stock > 0';
    }

    $sql  = "SELECT m.*, s.supplier_name FROM MOBILE m JOIN SUPPLIER s ON m.supplier_id = s.supplier_id WHERE " . implode(' AND ', $where);
    $sql .= " ORDER BY m.brand ASC, m.model ASC";
    $stmt = $conn->prepare($sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $rows]);
    exit;
}

// ── GET SINGLE MOBILE ─────────────────────────────────────────
if ($action === 'get') {
    $id   = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT m.*, s.supplier_name FROM MOBILE m JOIN SUPPLIER s ON m.supplier_id = s.supplier_id WHERE m.mobile_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row  = $stmt->get_result()->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $row]);
    exit;
}

// ── GET MOBILES BY SUPPLIER ───────────────────────────────────
if ($action === 'by_supplier') {
    $sid  = (int)($_GET['supplier_id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM MOBILE WHERE supplier_id = ? ORDER BY brand, model");
    $stmt->bind_param('i', $sid);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $rows]);
    exit;
}

// ── ADD MOBILE (supplier) ─────────────────────────────────────
if ($action === 'add') {
    $data        = json_decode(file_get_contents('php://input'), true);
    $supplier_id = (int)($data['supplier_id'] ?? 0);
    $brand       = trim($data['brand']  ?? '');
    $model       = trim($data['model']  ?? '');
    $price       = (float)($data['price'] ?? 0);
    $stock       = (int)($data['stock']   ?? 0);

    if (!$supplier_id || !$brand || !$model || $price <= 0 || $stock < 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO MOBILE (supplier_id, brand, model, price, stock) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('issdi', $supplier_id, $brand, $model, $price, $stock);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Mobile added.', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add mobile.']);
    }
    exit;
}

// ── UPDATE MOBILE ─────────────────────────────────────────────
if ($action === 'update') {
    $data  = json_decode(file_get_contents('php://input'), true);
    $id    = (int)($data['mobile_id'] ?? 0);
    $brand = trim($data['brand']  ?? '');
    $model = trim($data['model']  ?? '');
    $price = (float)($data['price'] ?? 0);
    $stock = (int)($data['stock']   ?? 0);

    $stmt = $conn->prepare("UPDATE MOBILE SET brand=?, model=?, price=?, stock=? WHERE mobile_id=?");
    $stmt->bind_param('ssdii', $brand, $model, $price, $stock, $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Mobile updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Update failed.']);
    }
    exit;
}

// ── DELETE MOBILE ─────────────────────────────────────────────
if ($action === 'delete') {
    $id   = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM MOBILE WHERE mobile_id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Mobile deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cannot delete — mobile has sales records.']);
    }
    exit;
}

// ── GET DISTINCT BRANDS ───────────────────────────────────────
if ($action === 'brands') {
    $result = $conn->query("SELECT DISTINCT brand FROM MOBILE ORDER BY brand");
    $brands = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $brands]);
    exit;
}

$conn->close();
echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
