<?php
require_once 'config.php';
$action = $_GET['action'] ?? '';
$conn   = getConnection();

// ── DASHBOARD STATS ────────────────────────────────────────────
if ($action === 'stats') {
    $stats = [];
    $res1 = $conn->query("SELECT COUNT(*) AS c FROM CUSTOMER");
    $stats['total_customers'] = $res1 ? $res1->fetch_assoc()['c'] : 0;
    $res2 = $conn->query("SELECT COUNT(*) AS c FROM SUPPLIER");
    $stats['total_suppliers'] = $res2 ? $res2->fetch_assoc()['c'] : 0;
    $res3 = $conn->query("SELECT COUNT(*) AS c FROM MOBILE");
    $stats['total_mobiles']   = $res3 ? $res3->fetch_assoc()['c'] : 0;
    $res4 = $conn->query("SELECT COUNT(*) AS c FROM SALES");
    $stats['total_sales']     = $res4 ? $res4->fetch_assoc()['c'] : 0;
    $res5 = $conn->query("SELECT COALESCE(SUM(amount),0) AS r FROM SALES");
    $stats['total_revenue']   = $res5 ? $res5->fetch_assoc()['r'] : 0;
    echo json_encode(['status' => 'success', 'data' => $stats]);
    exit;
}

// ── REVENUE PER SUPPLIER (for Chart.js) ───────────────────────
if ($action === 'revenue_by_supplier') {
    $result = $conn->query("
        SELECT su.supplier_name, COALESCE(SUM(s.amount), 0) AS total_revenue
        FROM SUPPLIER su
        LEFT JOIN MOBILE m ON su.supplier_id = m.supplier_id
        LEFT JOIN SALES  s ON m.mobile_id    = s.mobile_id
        GROUP BY su.supplier_id, su.supplier_name
        ORDER BY total_revenue DESC
    ");
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    echo json_encode(['status' => 'success', 'data' => $rows]);
    exit;
}

// ── TOP 5 SELLING MOBILES (for Chart.js) ─────────────────────
if ($action === 'top_mobiles') {
    $result = $conn->query("
        SELECT CONCAT(m.brand, ' ', m.model) AS mobile_name,
               COALESCE(SUM(s.quantity), 0) AS total_sold
        FROM MOBILE m
        LEFT JOIN SALES s ON m.mobile_id = s.mobile_id
        GROUP BY m.mobile_id
        ORDER BY total_sold DESC
        LIMIT 5
    ");
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    echo json_encode(['status' => 'success', 'data' => $rows]);
    exit;
}

// ── RECENT SALES (last 10) ─────────────────────────────────────
if ($action === 'recent_sales') {
    $result = $conn->query("
        SELECT s.sales_id, c.customer_name, m.brand, m.model,
               s.quantity, s.amount, s.sales_date
        FROM SALES s
        JOIN CUSTOMER c ON s.customer_id = c.customer_id
        JOIN MOBILE   m ON s.mobile_id   = m.mobile_id
        ORDER BY s.sales_date DESC
        LIMIT 10
    ");
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    echo json_encode(['status' => 'success', 'data' => $rows]);
    exit;
}

$conn->close();
echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
