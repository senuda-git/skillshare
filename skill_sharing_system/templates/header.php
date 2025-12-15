<?php
// templates/header.php
// Header template included on all pages
?>

<!doctype html>
<html>

<head>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<header class="site-header">
  <div class="nav">
    <a href="index.php" class="brand">SkillShare</a>
    
    <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <label for="nav-toggle" class="nav-toggle-label">
            <span></span>
            <span></span>
            <span></span>
        </label>
    
    <nav class="nav-links">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="skill_add.php">Add Skill</a>
        <a href="booking_status.php">Bookings</a>
        <a href="index.php?action=logout">Logout (<?= e($_SESSION['first_name'] ?? '') ?>)</a>

      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

</body>

</html>