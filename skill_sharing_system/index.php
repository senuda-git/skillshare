<?php
require_once __DIR__ . '/functions.php';
$skills = list_all_skills();
include 'templates/header.php';

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
  logout();
  redirect('index.php');
  exit;
}
?>

<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Skill Sharing - Home</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

  <div class="container">
    <h1>Skill Sharing — Browse Skills</h1>
    <?php if (empty($skills)): ?>
      <p>No skills yet. <a href="register.php">Register</a> and add your first skill.</p>
    <?php else: ?>
      <div class="grid">
        <?php foreach ($skills as $sk): ?>
          <div class="card">
            <h3><?= e($sk['skill_name']) ?></h3>
            <p><?= e(substr($sk['description'] ?? '', 0, 140)) ?><?= strlen($sk['description'] ?? '') > 140 ? '...' : '' ?></p>
            <a class="btn" href="skill_view.php?skill_id=<?= (int)$sk['skill_id'] ?>">View Tutors</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <script src="assets/js/script.js"></script>
</body>

</html>