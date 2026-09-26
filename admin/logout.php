<?php
/**
 * Admin Logout Handler
 */
require_once __DIR__ . '/../config/auth.php';

unset($_SESSION['admin_user']);
session_destroy();

header('Location: /admin/index.php');
exit;
