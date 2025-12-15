<?php
require_once __DIR__ . '/functions.php';
require_login();

$uid = current_user_id();
$user_skill_id = (int)($_GET['id'] ?? 0);

if ($user_skill_id > 0) {
    delete_user_skill($user_skill_id, $uid);
}

redirect('dashboard.php');
exit;
