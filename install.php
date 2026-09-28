<?php
require_once 'config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (strlen($username) < 3 || strlen($password) < 10) {
        $message = 'Username must be at least 3 characters and password at least 10 characters.';
    } else {
        $count = (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
        if ($count > 0) {
            $message = 'An admin already exists. Delete install.php for security and use login.php.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO admins (username,password_hash) VALUES (?,?)');
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
            $message = 'Admin created. Delete install.php now, then sign in.';
        }
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Setup Admin | Nitya Herbal</title><link rel="stylesheet" href="assets/style.css"></head><body class="auth-bg"><main class="auth-card"><h1>Nitya Herbal</h1><p>Create your first administrator account</p><?php if($message): ?><div class="notice"><?=e($message)?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Admin username</label><input name="username" required minlength="3" autocomplete="username"><label>Password (10+ characters)</label><input type="password" name="password" required minlength="10" autocomplete="new-password"><button class="btn full">Create admin</button></form><p class="muted small">After setup, delete install.php from the folder.</p></main></body></html>
