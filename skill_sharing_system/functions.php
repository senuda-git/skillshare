<?php

// safe session start
function safe_session_start()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}
safe_session_start();

// Include config for DB connection
require_once __DIR__ . '/config.php';

/* -------------------------
  Basic helpers
------------------------- */


// Escape HTML special characters in a string
function e($str)
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Redirect helper (cleaner)
function redirect($location)
{
    header("Location: {$location}");
    exit;
}

// Check if user is logged in
function is_logged_in()
{
    return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
}

// Redirect to login page if not logged in
function require_login()
{
    if (!is_logged_in()) {
        redirect('login.php');
        exit;
    }
}

// Logout user
function logout()
{
    safe_session_start();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
    redirect('index.php');
    exit;
}


// Get current logged-in user's ID
function current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

// Get current logged-in user's full name
function current_user_fullname()
{
    return trim(($_SESSION['first_name'] ?? '') . ' ' . trim($_SESSION['last_name'] ?? ''));
}

/* -------------------------
  CSRF token helpers
------------------------- */

// Generate a new CSRF token and store it in the session
function csrf_token(): string
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

// Verify the provided CSRF token against the one stored in the session
function csrf_verify($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/* -------------------------
  Small DB helpers
------------------------- */

// Find user by email
function find_user_by_email($email)
{
    global $mysqli;
    $sql = "SELECT user_id, first_name, last_name, email, password_hash 
            FROM users 
            WHERE email = ? 
            LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

// Find user by ID
function find_user_by_id($id)
{
    global $mysqli;
    $sql = "SELECT user_id, first_name, last_name, email, bio, created_at
            FROM users
            WHERE user_id = ? 
            LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

// List all skills
function list_all_skills(): array
{
    global $mysqli;
    $sql = "SELECT skill_id, skill_name, description
            FROM skills 
            ORDER BY skill_name ASC";
    $res = $mysqli->query($sql);
    return $res->fetch_all(MYSQLI_ASSOC);
}

// List tutors for a specific skill
function list_tutors_for_skill($skill_id)
{
    global $mysqli;
    $sql = "SELECT us.user_skill_id, us.skill_id, us.user_id, us.level, us.skill_description , u.first_name, u.last_name, u.email
            FROM user_skills us, skills s, users u
            WHERE us.skill_id = s.skill_id
            AND us.user_id = u.user_id
            AND us.skill_id = ?
            ORDER BY us.created_at DESC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $skill_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get skills for a specific user
function get_user_skills($user_id)
{
    global $mysqli;
    $sql = "SELECT us.user_skill_id, s.skill_id, s.skill_name, us.level, us.skill_description
            FROM user_skills us, skills s
            WHERE us.skill_id = s.skill_id
            AND us.user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Create a new booking
function create_booking($learner_id, $tutor_id, $skill_id)
{
    global $mysqli;
    $sql = "INSERT INTO bookings (learner_id, tutor_id, skill_id, status, created_at)
            VALUES (?, ?, ?, 'pending', NOW())";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('iii', $learner_id, $tutor_id, $skill_id);
    $stmt->execute();
    return $stmt->insert_id;
}

// Get bookings for a specific user (as learner or tutor)
function get_bookings_for_user($user_id)
{
    global $mysqli;
    $sql = "SELECT b.booking_id, b.learner_id, b.tutor_id, b.skill_id, b.status, b.proposed_time, b.message, b.created_at,
            lu.first_name AS learner_first, lu.last_name AS learner_last,
            tu.first_name AS tutor_first, tu.last_name AS tutor_last,
            s.skill_name
            FROM bookings b
            JOIN users lu ON b.learner_id = lu.user_id
            JOIN users tu ON b.tutor_id = tu.user_id
            JOIN skills s ON b.skill_id = s.skill_id
            WHERE b.learner_id = ? OR b.tutor_id = ?
            ORDER BY b.created_at DESC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ii', $user_id, $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get skill by ID
function get_skill_by_id($skill_id)
{
    global $mysqli;
    $sql = "SELECT skill_id, skill_name, description 
            FROM skills 
            WHERE skill_id = ? 
    LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $skill_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

// Check if user is teaching a specific skill
function user_teaching_skill($user_id, $skill_id)
{
    global $mysqli;
    $sql = "SELECT user_skill_id
            FROM user_skills 
            WHERE user_id = ? AND skill_id = ? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ii', $user_id, $skill_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

// Flash message helpers
function get_flash_message()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

//get current date and time
function expire_bookings()
{
    global $mysqli;
    $sql = "UPDATE bookings 
            SET status = 'expired' 
            WHERE status = 'pending' AND proposed_time < NOW()";
    $stmt = $mysqli->prepare($sql);
    return $stmt->execute();
}

/*----------------------------
------------------------
----------------
---------------*/

function delete_booking($booking_id)
{
    global $mysqli;
    $sql = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $booking_id);
    return $stmt->execute();
}

function update_user_bio($user_id, $new_bio)
{
    global $mysqli;
    $sql = "UPDATE users SET bio = ? WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('si', $new_bio, $user_id);
    return $stmt->execute();
}

function update_user_password($user_id, $new_password_hash)
{
    global $mysqli;
    $sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('si', $new_password_hash, $user_id);
    return $stmt->execute();
}



function delete_skill($skill_id)
{
    global $mysqli;
    $sql = "DELETE FROM skills WHERE skill_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $skill_id);
    return $stmt->execute();
}

function update_skill_description($skill_id, $new_description)
{
    global $mysqli;
    $sql = "UPDATE skills SET description = ? WHERE skill_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('si', $new_description, $skill_id);
    return $stmt->execute();
}

function add_skill_to_user($user_id, $skill_name, $level)
{
    global $mysqli;
    // Check if skill exists
    $sql = "SELECT skill_id FROM skills WHERE skill_name = ? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $skill_name);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $skill_id = (int)$row['skill_id'];
    } else {
        // Create new skill
        $sql2 = "INSERT INTO skills (skill_name, description) VALUES (?, '')";
        $stmt2 = $mysqli->prepare($sql2);
        $stmt2->bind_param('s', $skill_name);
        $stmt2->execute();
        $skill_id = $stmt2->insert_id;
        $stmt2->close();
    }
    $stmt->close();
}



function remove_user_skill($user_skill_id, $user_id)
{
    global $mysqli;
    $sql = "DELETE FROM user_skills WHERE user_skill_id = ? AND user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ii', $user_skill_id, $user_id);
    return $stmt->execute();
}

function get_user_bio($user_id)
{
    global $mysqli;
    $sql = "SELECT bio FROM users WHERE user_id = ? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ? $row['bio'] : null;
}

function get_total_skills_count()
{
    global $mysqli;
    $sql = "SELECT COUNT(*) AS cnt FROM skills";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    return $row ? (int)$row['cnt'] : 0;
}

function get_total_users_count()
{
    global $mysqli;
    $sql = "SELECT COUNT(*) AS cnt FROM users";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    return $row ? (int)$row['cnt'] : 0;
}

function get_total_bookings_count()
{
    global $mysqli;
    $sql = "SELECT COUNT(*) AS cnt FROM bookings";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    return $row ? (int)$row['cnt'] : 0;
}

function get_total_tutors_count()
{
    global $mysqli;
    $sql = "SELECT COUNT(DISTINCT user_id) AS cnt FROM user_skills";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    return $row ? (int)$row['cnt'] : 0;
}

function get_total_learners_count()
{
    global $mysqli;
    $sql = "SELECT COUNT(DISTINCT learner_id) AS cnt FROM bookings";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    return $row ? (int)$row['cnt'] : 0;
}

function get_total_tutoring_sessions_count()
{
    global $mysqli;
    $sql = "SELECT COUNT(*) AS cnt FROM bookings WHERE status = 'accepted'";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    return $row ? (int)$row['cnt'] : 0;
}

function get_total_pending_bookings_count()
{
    global $mysqli;
    $sql = "SELECT COUNT(*) AS cnt FROM bookings WHERE status = 'pending'";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    return $row ? (int)$row['cnt'] : 0;
}

function get_total_rejected_bookings_count()
{
    global $mysqli;
    $sql = "SELECT COUNT(*) AS cnt FROM bookings WHERE status = 'rejected'";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    return $row ? (int)$row['cnt'] : 0;
}

function get_total_approved_bookings_count()
{
    global $mysqli;
    $sql = "SELECT COUNT(*) AS cnt FROM bookings WHERE status = 'approved'";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    return $row ? (int)$row['cnt'] : 0;
}

function get_recent_bookings($limit = 5)
{
    global $mysqli;
    $sql = "SELECT b.booking_id, b.learner_id, b.tutor_id, b.skill_id, b.status, b.message, b.created_at,
            lu.first_name AS learner_first, lu.last_name AS learner_last,
            tu.first_name AS tutor_first, tu.last_name AS tutor_last,
            s.skill_name
            FROM bookings b
            JOIN users lu ON b.learner_id = lu.user_id
            JOIN users tu ON b.tutor_id = tu.user_id
            JOIN skills s ON b.skill_id = s.skill_id
            ORDER BY b.created_at DESC
            LIMIT ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_recent_skills($limit = 5)
{
    global $mysqli;
    $sql = "SELECT skill_id, skill_name, description, created_at
            FROM skills
            ORDER BY created_at DESC
            LIMIT ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function close_db_connection()
{
    global $mysqli;
    if ($mysqli) {
        $mysqli->close();
    }
}

function __destruct()
{
    close_db_connection();
}

function debug_log($message)
{
    error_log($message);
}

function print_debug($message)
{
    echo '<pre>' . e($message) . '</pre>';
}

function dd($variable)
{
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
    exit;
}

function flash_message($message)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_message'] = $message;
}



function clear_flash_message()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['flash_message']);
}

function is_admin()
{
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function require_admin()
{
    if (!is_admin()) {
        redirect('index.php');
    }
}
