<?php
require_once 'config.php';

$action = $_GET['action'] ?? '';
$conn   = getConnection();

// ── PURCHASE (calls AddSale procedure inside a transaction) ───
if ($action === 'buy') {
    $data        = json_decode(file_get_contents('php://input'), true);
    $customer_id = (int)($data['customer_id'] ?? 0);
    $mobile_id   = (int)($data['mobile_id']   ?? 0);
    $quantity    = (int)($data['quantity']     ?? 1);
    $warranty    = !empty($data['warranty']) ? trim($data['warranty']) : null;

    if (!$customer_id || !$mobile_id || $quantity < 1) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit;
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Check stock first (concurrency check inside transaction)
        $stmt = $conn->prepare("SELECT stock, price FROM MOBILE WHERE mobile_id = ? FOR UPDATE");
        $stmt->bind_param('i', $mobile_id);
        $stmt->execute();
        $mobile = $stmt->get_result()->fetch_assoc();

        if (!$mobile || $mobile['stock'] < $quantity) {
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Not enough stock available.']);
            exit;
        }

        $amount = $mobile['price'] * $quantity;

        // Insert sale
        $stmt2 = $conn->prepare("INSERT INTO SALES (customer_id, mobile_id, quantity, amount, sales_date, warranty) VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt2->bind_param('iiids', $customer_id, $mobile_id, $quantity, $amount, $warranty);
        $stmt2->execute();
        $sales_id = $conn->insert_id;

        // Update stock
        $stmt3 = $conn->prepare("UPDATE MOBILE SET stock = stock - ? WHERE mobile_id = ?");
        $stmt3->bind_param('ii', $quantity, $mobile_id);
        $stmt3->execute();

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Purchase successful!', 'sales_id' => $sales_id, 'amount' => $amount]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Transaction failed. Please try again.']);
    }
    exit;
}

// ── GET BILL (single sale detail) ─────────────────────────────
if ($action === 'bill') {
    $sales_id = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("
        SELECT s.sales_id, s.quantity, s.amount, s.sales_date, s.warranty,
               c.customer_name, c.phone, c.address,
               m.brand, m.model, m.price AS unit_price,
               su.supplier_name
        FROM SALES s
        JOIN CUSTOMER c  ON s.customer_id = c.customer_id
        JOIN MOBILE   m  ON s.mobile_id   = m.mobile_id
        JOIN SUPPLIER su ON m.supplier_id  = su.supplier_id
        WHERE s.sales_id = ?
    ");
    $stmt->bind_param('i', $sales_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $row]);
    exit;
}

// ── GET HISTORY (customer's purchases) ────────────────────────
if ($action === 'history') {
    $customer_id = (int)($_GET['customer_id'] ?? 0);
    $stmt = $conn->prepare("
        SELECT s.sales_id, s.quantity, s.amount, s.sales_date, s.warranty,
               m.brand, m.model, m.price AS unit_price
        FROM SALES s
        JOIN MOBILE m ON s.mobile_id = m.mobile_id
        WHERE s.customer_id = ?
        ORDER BY s.sales_date DESC
    ");
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $rows]);
    exit;
}

// ── GET ALL SALES (admin) ─────────────────────────────────────
if ($action === 'all') {
    $result = $conn->query("
        SELECT s.sales_id, s.quantity, s.amount, s.sales_date, s.warranty,
               c.customer_name, m.brand, m.model
        FROM SALES s
        JOIN CUSTOMER c ON s.customer_id = c.customer_id
        JOIN MOBILE   m ON s.mobile_id   = m.mobile_id
        ORDER BY s.sales_date DESC
    ");
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    echo json_encode(['status' => 'success', 'data' => $rows]);
    exit;
}

// ── SALES SUMMARY VIEW ─────────────────────────────────────────
if ($action === 'summary') {
    $result = $conn->query("SELECT * FROM Mobile_Sales_Summary ORDER BY total_revenue DESC");
    $rows   = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    echo json_encode(['status' => 'success', 'data' => $rows]);
    exit;
}

$conn->close();
echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
