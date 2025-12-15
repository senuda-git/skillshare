<?php
require_once __DIR__ . '/functions.php';
require_login();
$uid = current_user_id();

$user_skill_id = isset($_GET['user_skill_id']) ? (int)$_GET['user_skill_id'] : 0;
if (!$user_skill_id) {
  redirect('dashboard.php');
  exit;
}

// fetch record and confirm ownership
$sql = "SELECT us.user_skill_id, us.user_id, us.level, us.skill_description, s.skill_name
        FROM user_skills us, skills s
        WHERE us.skill_id = s.skill_id AND us.user_skill_id = ? LIMIT 1";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_skill_id);
$stmt->execute();
$record = $stmt->get_result()->fetch_assoc();
$stmt->close();

// check ownership
if (!$record || $record['user_id'] != $uid) {
  redirect('dashboard.php');
  exit;
}

$errors = [];

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['csrf'] ?? '')) {
    $errors[] = 'Invalid request';
  }
  $level = $_POST['level'] ?? $record['level'];
  $description = trim($_POST['skill_description'] ?? '');

  if (empty($errors)) {
    $sql2 = "UPDATE user_skills us 
                 JOIN skills s ON us.skill_id = s.skill_id
                 SET level = ?, description = ?
                 WHERE user_skill_id = ? AND user_id = ?";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->bind_param('ssii', $level, $description, $user_skill_id, $uid);
    if ($stmt2->execute()) {
      flash_message('Skill edited successfully.');
      redirect('dashboard.php');
      exit;
    } else {
      $errors[] = 'Could not update.';
    }
    $stmt2->close();
  }
}

$token = csrf_token();
?>


<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Edit Skill - SkillShare</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/js/script.js">
</head>

<body>
  <?php include 'templates/header.php'; ?>
  <div class="container">
    <h2>Edit: <?= e($record['skill_name']) ?></h2>
    <?php foreach ($errors as $err): ?><p class="error"><?= e($err) ?></p><?php endforeach; ?>

    <form method="post">
      <input type="hidden" name="csrf" value="<?= e($token) ?>">
      <label>Level
        <select name="level">
          <option <?= $record['level'] == 'Beginner' ? 'selected' : '' ?>>Beginner</option>
          <option <?= $record['level'] == 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
          <option <?= $record['level'] == 'Advanced' ? 'selected' : '' ?>>Advanced</option>
        </select>
      </label>

      <label>Your Description
        <textarea name="skill_description"><?= htmlspecialchars($record['skill_description']) ?></textarea>
      </label>

      <button class="btn">Save</button>
    </form>

  </div>
</body>

</html>