<?php
require_once __DIR__ . '/functions.php';
require_login();
$uid = current_user_id();
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['csrf'] ?? '')) {
    $messages[] = 'Invalid request.';
  } else {
    $booking_id = (int)($_POST['booking_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($booking_id && in_array($action, ['approved', 'rejected'])) {
      // ensure current user is the tutor for that booking
      $sql = "SELECT tutor_id FROM bookings WHERE booking_id = ? LIMIT 1";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param('i', $booking_id);
      $stmt->execute();
      $row = $stmt->get_result()->fetch_assoc();
      $stmt->close();
      if ($row && $row['tutor_id'] == $uid) {
        $newStatus = $action === 'approved' ? 'approved' : 'rejected';
        $sql2 = "UPDATE bookings 
                         SET status = ? 
                         WHERE booking_id = ?";
        $stmt2 = $mysqli->prepare($sql2);
        $stmt2->bind_param('si', $newStatus, $booking_id);
        if ($stmt2->execute()) {
          $messages[] = "Booking updated to: $newStatus";
        } else {
          $messages[] = 'Could not update booking.';
        }
        $stmt2->close();
      } else {
        $messages[] = 'Unauthorized action.';
      }
    } else {
      $messages[] = 'Invalid data.';
    }
  }
}

// show list of bookings related to current user
$bookings = get_bookings_for_user($uid);
$token = csrf_token();
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Bookings - SkillShare</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <?php include 'templates/header.php'; ?>
  <div class="container">
    <h2>Bookings (My requests & incoming) </h2>
        <?php foreach ($messages as $m): ?><p class="info"><?= e($m) ?></p><?php endforeach; ?>

        <?php if (empty($bookings)): ?>
          <p>No bookings found.</p>
        <?php else: ?>
          <table class="table">
            <thead>
              <tr>
                <th>Skill</th>
                <th>From</th>
                <th>To</th>
                <th>Message</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bookings as $b): ?>
                <tr>
                  <td><?= e($b['skill_name']) ?></td>
                  <td><?= e($b['learner_first'] . ' ' . $b['learner_last']) ?></td>
                  <td><?= e($b['tutor_first'] . ' ' . $b['tutor_last']) ?></td>
                  <td><?= e(substr($b['message'], 0, 60)) ?><?= strlen($b['message']) > 60 ? '...' : '' ?></td>
                  <td><?= e(ucfirst($b['status'])) ?></td>
                  <td>
                    <?php if ($b['tutor_id'] == $uid && $b['status'] == 'pending'): ?>
                      <form method="post" style="display:inline">
                        <input type="hidden" name="csrf" value="<?= e($token) ?>">
                        <input type="hidden" name="booking_id" value="<?= (int)$b['booking_id'] ?>">
                        <button name="action" value="approved" class="btn small success">Approved</button>
                        <button name="action" value="rejected" class="btn small danger">Rejected</button>
                      </form>
                    <?php else: ?>
                      --------
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
  </div>
</body>

</html>