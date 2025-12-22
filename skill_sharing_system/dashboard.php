<?php
require_once __DIR__ . '/functions.php';
require_login();
$exp = expire_bookings();
$uid = current_user_id();
$user = find_user_by_id($uid);
$my_skills = get_user_skills($uid);
$bookings = get_bookings_for_user($uid);
$skill_count = count($my_skills);
$booking_count = count($bookings);
$msg = get_flash_message();
$token = csrf_token();
?>

<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Dashboard - SkillShare</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/js/script.js">
</head>

<body>
  <?php include 'templates/header.php'; ?>

  <div class="container">
    <h2>Welcome, <?= e($user['first_name'] . ' ' . $user['last_name']) ?></h2>
    <p class="text-muted">Manage your teaching skills and booking requests.</p>
    </div>

    <?php if ($msg): ?>
      <p class="card status-approved" style="transform: translateZ(0px)"><?= e($msg) ?></p> 
    <?php endif; ?>
    
    <div class="card" style="transform: translateZ(0px);">
    <div class="skills-section">
      <h3>My Skills (I teach)</h3>
      <?php if (empty($my_skills)): ?>
        <p>No skills yet. <br><br>
          <a href="skill_add.php">Add a skill</a>.
        </p>
      <?php else: ?>
        <ul class="tutor-skills-list">
          <?php foreach ($my_skills as $s): ?>
            <li>
              <strong><?= e($s['skill_name']) ?></strong> — <?= e($s['level']) ?>
              <div class="small"><?= e($s['skill_description']) ?></div>
              <br><br>
              <a href="skill_edit.php?user_skill_id=<?= (int)$s['user_skill_id'] ?>" class="btn" style="background:rgba(255, 255, 255, 0.3)">Edit</a></t>
              <a href="skill_view.php?skill_id=<?= (int)$s['skill_id'] ?>" class="btn" style="background:rgba(255, 255, 255, 0.3)">View Tutors who tutoring <?= e($s['skill_name']) ?></a>
              
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
    </div>
    </div>

    <!-- booking section outgoing -->
    <div class="card" style="transform: translateZ(0px);">
    <div class="bookings-section-requests">
      <h3>Incoming Requests</h3>
      <?php if (empty($bookings)): ?>
        <p>No booking requests yet.</p>
      <?php else: ?>
        <table class="table">
          <thead>
            <tr>
              <th>Skill</th>
              <th>Learner</th>
              <th>Proposed Time</th>
              <th>Message</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $b): ?>
              <?php if ($b['tutor_id'] != $uid)continue; ?>
              <tr>
                <td><?= e($b['skill_name']) ?></td>
                <td><?= e($b['learner_first'] . ' ' . $b['learner_last']) ?></td>
                <td><?= e(date('M d, g:i A', strtotime($b['proposed_time']))) ?></td>
                <td><?= e(substr($b['message'], 0, 60)) ?><?= strlen($b['message']) > 60 ? '...' : '' ?></td>
                <td><?= e(ucfirst($b['status'])) ?></td>
                <td>
                  <?php if ($b['status'] == 'pending'): ?>
                    <form method="post" action="booking_status.php" style="display:inline">
                      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                      <input type="hidden" name="booking_id" value="<?= (int)$b['booking_id'] ?>">
                      <button name="action" value="approved" class="btn small success">Approved</button>
                      <button name="action" value="rejected" class="btn small danger">Rejected</button>
                    </form>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
    </div>

    <!-- booking section incoming -->
    <div class="card" style="transform: translateZ(0px);">
    <div class="bookings-section-incoming">
    <h3>My Sent Requests</h3>
      <?php if (empty($bookings)): ?>
        <p>No bookings, Find a Tutor.</p>
      <?php else: ?>
        <table class="table">
          <thead>
            <tr>
              <th>Skill</th>
              <th>Tutor</th>
              <th>Proposed Time</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $b): ?>
              <tr>
                <td><?= e($b['skill_name']) ?></td>
                <td><?= e($b['tutor_first'] . ' ' . $b['tutor_last']) ?></td>
                <td><?= e(date('M d, g:i A', strtotime($b['proposed_time']))) ?></td>
                <td><?= e(ucfirst($b['status'])) ?></td>
                <td>
                  <?php if ($b['tutor_id'] == $uid && $b['status'] == 'pending'): ?>
                    <form method="post" action="booking_status.php" style="display:inline">
                      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                      <input type="hidden" name="booking_id" value="<?= (int)$b['booking_id'] ?>">
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
    </div>

  </div>
</body>

</html>