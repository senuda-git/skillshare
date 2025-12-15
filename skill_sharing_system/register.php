<?php
require_once __DIR__ . '/config.php';     // FIX: load DB
require_once __DIR__ . '/functions.php';

safe_session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = strtolower(trim($_POST['email'] ?? ''));
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';
    $token      = $_POST['csrf'] ?? '';

    // CSRF check
    if (!csrf_verify($token)) {
        $errors[] = 'Invalid request.';
    }

    // Basic validation
    if ($first_name === '' || $last_name === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password is short. Password must be at least 6 characters.';
    }

    if (strlen($password) > 12) {
        $errors[] = 'Password is large. Password must be less than 12 characters.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    // If no errors so far → attempt registration
    if (!$errors) {

        $existing = find_user_by_email($email);

        if ($existing) {
            $errors[] = 'Email already registered. Try login.';
        } else {

            $pwHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (first_name, last_name, email, password_hash)
                    VALUES (?, ?, ?, ?)";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('ssss', $first_name, $last_name, $email, $pwHash);

            if ($stmt->execute()) {

                // Auto login
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;

                redirect('dashboard.php');
                exit;
            } else {
                $errors[] = 'Server error. Please try again.';
            }

            $stmt->close();
        }
    }
}

// Create new CSRF token
$token = csrf_token();
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Register - SkillShare</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/main.js" defer></script>
</head>

<body>

    <?php include 'templates/header.php'; ?>

    <div class="container">
        <h2>Register</h2>

        <!-- Error messages -->
        <?php foreach ($errors as $err): ?>
            <p class="error"><?= e($err) ?></p>
        <?php endforeach; ?>

        <form method="post" action="">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">

            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name"
                value="<?= e($_POST['first_name'] ?? '') ?>"><br>

            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name"
                value="<?= e($_POST['last_name'] ?? '') ?>"><br>

            <label for="email">Email</label>
            <input type="email" name="email" id="email"
                value="<?= e($_POST['email'] ?? '') ?>"><br>

            <label for="password">Password</label>
            <input type="password" name="password" id="password"><br>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password"><br>

            <button class="btn" type="submit">Register</button>
        </form>
    </div>

</body>

</html>