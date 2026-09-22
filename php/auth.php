<?php
require_once 'config.php';

$action = $_GET['action'] ?? '';

// ── LOGIN ──────────────────────────────────────────────────
if ($action === 'login') {
    $data     = json_decode(file_get_contents('php://input'), true);
    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');
    $role     = trim($data['role']     ?? '');

    if (!$username || !$password || !$role) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Admin login (hardcoded)
    if ($role === 'admin') {
        if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
            $_SESSION['user_id']   = 0;
            $_SESSION['username']  = 'admin';
            $_SESSION['role']      = 'admin';
            echo json_encode(['status' => 'success', 'role' => 'admin', 'name' => 'Administrator']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid admin credentials.']);
        }
        exit;
    }

    $conn = getConnection();

    if ($role === 'customer') {
        $stmt = $conn->prepare("SELECT customer_id, customer_name, password FROM CUSTOMER WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['customer_id'];
            $_SESSION['username'] = $username;
            $_SESSION['role']     = 'customer';
            echo json_encode(['status' => 'success', 'role' => 'customer', 'name' => $user['customer_name'], 'id' => $user['customer_id']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials.']);
        }

    } elseif ($role === 'supplier') {
        $stmt = $conn->prepare("SELECT supplier_id, supplier_name, password FROM SUPPLIER WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['supplier_id'];
            $_SESSION['username'] = $username;
            $_SESSION['role']     = 'supplier';
            echo json_encode(['status' => 'success', 'role' => 'supplier', 'name' => $user['supplier_name'], 'id' => $user['supplier_id']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials.']);
        }
    }

    $conn->close();
    exit;
}

// ── REGISTER ───────────────────────────────────────────────
if ($action === 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    $role = trim($data['role'] ?? '');
    $conn = getConnection();

    if ($role === 'customer') {
        $name     = trim($data['name']     ?? '');
        $address  = trim($data['address']  ?? '');
        $phone    = trim($data['phone']    ?? '');
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$name || !$address || !$phone || !$username || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO CUSTOMER (customer_name, address, phone, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $name, $address, $phone, $username, $hashed);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Customer registered successfully.']);
        } else {
            $err = $conn->error;
            if (strpos($err, 'Duplicate') !== false) {
                echo json_encode(['status' => 'error', 'message' => 'Username or phone already exists.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Registration failed.']);
            }
        }

    } elseif ($role === 'supplier') {
        $name     = trim($data['name']     ?? '');
        $phone    = trim($data['phone']    ?? '');
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$name || !$phone || !$username || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO SUPPLIER (supplier_name, phone, username, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $phone, $username, $hashed);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Supplier registered successfully.']);
        } else {
            $err = $conn->error;
            if (strpos($err, 'Duplicate') !== false) {
                echo json_encode(['status' => 'error', 'message' => 'Username or phone already exists.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Registration failed.']);
            }
        }
    }

    $conn->close();
    exit;
}

// ── LOGOUT ─────────────────────────────────────────────────
if ($action === 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Logged out.']);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
