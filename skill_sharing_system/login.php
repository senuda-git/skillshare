<?php
require_once __DIR__ . '/functions.php';

// If already logged in, redirect
if (is_logged_in()) {
  redirect('dashboard.php');
  exit;
}

// Handle form submission
$errors = [];

// process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = strtolower(trim($_POST['email'] ?? ''));
  $password = $_POST['password'] ?? '';
  $token = $_POST['csrf'] ?? '';

  if (!csrf_verify($token)) {
    $errors[] = 'Invalid request. Please refresh and try again.';
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    $errors[] = 'Invalid email or password.';
  } else {
    $user = find_user_by_email($email);
    if ($user && password_verify($password, $user['password_hash'])) {
      // set session
      $_SESSION['user_id'] = (int)$user['user_id'];
      $_SESSION['first_name'] = $user['first_name'];
      $_SESSION['last_name'] = $user['last_name'];
      redirect('dashboard.php');
      exit;
    } else {
      $errors[] = 'Invalid credentials.';
    }
  }
}
$token = csrf_token();
?>


<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Login - SkillShare</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/main.js" defer></script>
</head>

<body>
  <?php include 'templates/header.php'; ?>

  <!-- Login Form -->
  <div class="container">
    <h2>Login</h2>

    <?php foreach ($errors as $err): ?>
      <p class="error"><?= e($err) ?></p>
    <?php endforeach; ?>

    <form method="post" action="">

      <input type="hidden" name="csrf" value="<?= e($token) ?>">
      <label>Email <input name="email" value="<?= e($_POST['email'] ?? '') ?>"></label>
      <label>Password <input type="password" name="password"></label>
      <br><button class="btn" type="submit">Login</button>

    </form>
  </div>
</body>

</html>