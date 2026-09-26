<?php
/**
 * Admin Login Page - RBK Studio CMS
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

if (!empty($_SESSION['admin_user'])) {
    header('Location: /admin/dashboard.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi.';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name']
            ];
            header('Location: /admin/dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | RBK Studio CMS</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="login-container">
  <div class="login-box">
    <div style="text-align: center; margin-bottom: 2rem;">
      <div style="display: inline-block; background: #F5502D; color: #000; font-weight: 900; padding: 0.3rem 0.8rem; font-size: 0.875rem; border-radius: 2px; margin-bottom: 0.5rem;">RBK STUDIO</div>
      <h1 style="font-size: 1.5rem; font-weight: 700; color: #111;">CMS Dashboard Login</h1>
      <p style="font-size: 0.8125rem; color: #6B7280; margin-top: 0.25rem;">Kelola Konten & Lead WhatsApp Landing Page</p>
    </div>

    <?php if ($error): ?>
      <div class="alert-flash danger"><?= e($error); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group-admin">
        <label class="form-label-admin">Username</label>
        <input type="text" name="username" class="form-control-admin" required placeholder="admin" autofocus>
      </div>

      <div class="form-group-admin">
        <label class="form-label-admin">Password</label>
        <input type="password" name="password" class="form-control-admin" required placeholder="••••••••">
      </div>

      <div style="margin-top: 1.5rem;">
        <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center; padding: 0.875rem;">
          Masuk ke Dashboard
        </button>
      </div>
    </form>

    <div style="text-align: center; margin-top: 2rem; font-size: 0.75rem; color: #9CA3AF;">
      Default login: <strong>admin</strong> / <strong>admin123</strong>
    </div>
  </div>
</body>
</html>
