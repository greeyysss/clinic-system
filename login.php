<?php
session_start();
require_once('../includes/db.php');

$errors = [];
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Try database authentication if admins table exists. Use SHOW TABLES first so we don't call prepare on a missing table.
    $authed = false;
    $hasAdminsTable = false;
    $check = $conn->query("SHOW TABLES LIKE 'admins'");
    if ($check && $check->num_rows > 0) {
        $hasAdminsTable = true;
    }

    if ($hasAdminsTable) {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param('s', $username);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $hash = $row['password'];
                    // allow both password_hash() and plain text for simple setups
                    if ((function_exists('password_verify') && password_verify($password, $hash)) || $password === $hash) {
                        $_SESSION['admin_id'] = $row['id'];
                        $_SESSION['admin_user'] = $username;
                        $authed = true;
                    }
                }
            }
            $stmt->close();
        }
    }

    // Fallback: default credentials for initial setup
    if (!$authed) {
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_id'] = 1;
            $_SESSION['admin_user'] = 'admin';
            $authed = true;
        }
    }

    if ($authed) {
        header('Location: dashboard.php');
        exit();
    }

    $errors[] = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login - Clinic Management</title>
    <style>
        body {font-family: Arial, sans-serif; background:#f0f2f5; padding:40px}
        .card {max-width:420px;margin:0 auto;background:#fff;padding:24px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,.08)}
        label{display:block;margin-bottom:6px;font-weight:600}
        input{width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;margin-bottom:12px}
        button{background:#3498db;color:#fff;border:none;padding:10px 14px;border-radius:6px;cursor:pointer}
        .error{background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin-bottom:12px}
    </style>
</head>
<body>
    <div class="card">
        <h2>Admin Login</h2>
        <?php if (!empty($errors)): ?>
            <div class="error"><?php echo htmlspecialchars(implode('<br>', $errors)); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" required autofocus>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <button type="submit">Login</button>
        </form>
        <p style="margin-top:12px;font-size:13px;color:#666">Default credentials for initial setup: <strong>admin / admin123</strong>. Create an `admins` table and store password hashes for production.</p>
    </div>
</body>
</html>
