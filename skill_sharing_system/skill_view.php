<?php
require_once __DIR__ . '/functions.php';
$skill_id = isset($_GET['skill_id']) ? (int)$_GET['skill_id'] : 0;
if (!$skill_id) {
  redirect('index.php');
  exit;
}

// get skill
$sql = "SELECT skill_id, skill_name, description FROM skills WHERE skill_id = ? LIMIT 1";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $skill_id);
$stmt->execute();
$skill = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$skill) {
  redirect('index.php');
  exit;
}

// tutors
$tutors = list_tutors_for_skill($skill_id);
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title><?= e($skill['skill_name']) ?> - SkillShare</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <?php include 'templates/header.php'; ?>
  <div class="container">
    <h2><?= e($skill['skill_name']) ?></h2>
    <p><?= e($skill['description']) ?></p>

    <h3>Available Tutors</h3>
    <?php if (empty($tutors)): ?>
      <p>No tutors yet. You can <a href="skill_add.php">add your skill</a> if you can teach this.</p>
    <?php else: ?>
      <ul class="list">
        <?php foreach ($tutors as $t): ?>
          <li>
            <strong><?= e($t['first_name'] . ' ' . $t['last_name']) ?></strong>
            <div class="muted">Level: <?= e($t['level']) ?></div>
            <div><?= e($t['description']) ?></div><br>
            <?php if (is_logged_in()): ?>
              <a href="booking_add.php?user_skill_id=<?= (int)$t['user_skill_id'] ?>" class="btn">Request Session</a>
            <?php else: ?>
              <a href="login.php" class="btn">Login to Request</a>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</body>

</html>