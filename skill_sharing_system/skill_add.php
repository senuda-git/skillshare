<?php
require_once __DIR__ . '/functions.php';
require_login();
$uid = current_user_id();
$errors = [];

// load existing skills for quick selection
$all_skills = list_all_skills();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['csrf'] ?? '')) {
    $errors[] = 'Invalid request.';
  }

  $skill_id = isset($_POST['skill_choice']) ? (int)$_POST['skill_choice'] : 0;
  $level = $_POST['level'] ?? 'Beginner';
  $user_skill_description = trim($_POST['skill_description'] ?? '');

  // validate
  if ($skill_id <= 0) {
    $errors[] = "Please select a skill.";
  }

  // validate description
  if ($skill_description === '') {
    $errors[] = "Please enter a personal description of what you teach.";
  }

  // prevent duplicate skill addition
  if (user_teaching_skill($uid, $skill_id)) {
    $errors[] = "You already teach this skill.";
  }

  if (empty($errors)) {
    global $myssqli;
    // insert into user_skills
    $sql3 = "INSERT INTO user_skills (user_id, skill_id, level, skill_description) 
             VALUES (?, ?, ?, ?)";
    $stmt3 = $mysqli->prepare($sql3);
    $stmt3->bind_param('iiss', $uid, $skill_id, $level, $user_skill_description);

    if ($stmt3->execute()) {
      flash_message('Skill added successfully.');
      redirect('dashboard.php');
      exit;
    } else {
      $errors[] = 'Could not add skill (maybe already added).';
    }
    $stmt3->close();
  }
}

$token = csrf_token();
?>


<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Add Skill - SkillShare</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style> button.btn.primary.full-width{background: rgba(255, 255, 255, 0.25);}</style>
</head>

<body>
  <?php include 'templates/header.php'; ?>
  <div class="container container-narrow">
  <div class="card">
    <h2>Add a Skill You Teach</h2>
    <?php
    foreach ($errors as $err): ?>
      <p class="error"><?= e($err) ?></p>
    <?php endforeach;
     if ($msg = get_flash_message()): ?>
      <p class="success"><?= e($msg) ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <input type="hidden" name="csrf" value="<?= e($token) ?>">

      <label>Choose existing skill:
        <select name="skill_choice">
          <option value="">-- choose --</option>
          <?php foreach ($all_skills as $sk): ?>
            <option value="<?= (int)$sk['skill_id'] ?>">
              <?= e($sk['skill_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>
      <br>

      <label>Level
        <select name="level">
          <option>Beginner</option>
          <option>Intermediate</option>
          <option>Advanced</option>
        </select>
      </label>
      <br>
      <label>Description (what you teach)
        <textarea name="skill_description" rows="5" placeholder="Example: I focus on ... and have experiences..."><?= e($_POST['skill_description'] ?? '') ?></textarea>
      </label>
      <br>
      <button type="submit" class="btn primary full-width" style="background:rgba(255, 255, 255, 0.5)">Add Skill</button>
    </form>
  </div>
</body>

</html>