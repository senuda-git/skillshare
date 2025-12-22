<?php
require_once __DIR__ . '/functions.php';
require_login();
$uid = current_user_id();
$errors = [];

$user_skill_id = isset($_GET['user_skill_id']) ? (int)$_GET['user_skill_id'] : 0;
if (!$user_skill_id) {
  header('Location: index.php');
  exit;
}

// fetch tutor info for this user_skill
$sql = "SELECT us.user_skill_id, us.user_id AS tutor_id, s.skill_id, s.skill_name, u.first_name, u.last_name
        FROM user_skills us, skills s, users u
        WHERE us.user_id = u.user_id AND us.skill_id = s.skill_id AND us.user_skill_id = ? LIMIT 1";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_skill_id);
$stmt->execute();
$record = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$record) {
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['csrf'] ?? '')) $errors[] = 'Invalid request.';
  $message = trim($_POST['message'] ?? '');
  if ($message === '') $errors[] = 'Please write a short message for the tutor.';

  // prevent user booking themselves as learner
  if ($record['tutor_id'] == $uid) {
    $errors[] = 'You cannot request a session with yourself.';
  }


  $proposed_time_input = $_POST['proposed_time'] ?? '';
  if (!empty($proposed_time_input)) {
    $proposed_ts = strtotime($proposed_time_input);
    $now = time();
    $two_hours_from_now = $now + (2 * 3600); // 3600 seconds in an hour

    //Prevent booking on past
    if($proposed_ts < $now){
      $errors[] = "You cannot go into past.";
    }

    // Check 2-hour advance time
    if ($proposed_ts < $two_hours_from_now) {
      $errors[] = 'Please book at least 2 hours in advance.';
    }

    // Check working Hours
    $hour = (int)date('H', $proposed_ts);
    $start_hour = 8;  // 8 AM
    $end_hour = 17;   // 5 PM

    if ($hour < $start_hour || $hour >= $end_hour) {
      $errors[] = "Sessions can only be booked during business hours (0{$start_hour}:00 to {$end_hour}:00).";
    }

    //Prevent booking on weekends
    $day_of_week = date('N', $proposed_ts); // 1 (Mon) to 7 (Sun)
    if ($day_of_week > 5) {
      $errors[] = 'Sessions are only available Monday through Friday.';
    }
  }


  // insert booking
  if (empty($errors)) {
    $sql2 = "INSERT INTO bookings (learner_id, tutor_id, skill_id, message, proposed_time) VALUES (?, ?, ?, ?, ?)";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->bind_param('iiiss', $uid, $record['tutor_id'], $record['skill_id'], $message, $_POST['proposed_time']);
    if ($stmt2->execute()) {
      header('Location: booking_status.php?created=1');
      exit;
    } else {
      $errors[] = 'Could not create booking.';
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
  <title>Request Session - SkillShare</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <?php include 'templates/header.php'; ?>
  <div class="container">
    <h2>Request Session with <?= e($record['first_name'] . ' ' . $record['last_name']) ?> — <?= e($record['skill_name']) ?></h2>
    <?php foreach ($errors as $err): ?><p class="error"><?= e($err) ?></p><?php endforeach; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= e($token) ?>">
      <label>Your message to the tutor <textarea name="message"><?= e($_POST['message'] ?? 'Hi, I would like to learn about...') ?></textarea></label><br>
      <label>Proposed time <input type="datetime-local" name="proposed_time"></label><br>
      <button class="btn">Send Request</button>
    </form>
  </div>
</body>

</html>