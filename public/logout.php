<?php
// Step 1: Start session
session_start();

// Step 2: Check user type (before we destroy the session)
$userType = '';
if (isset($_SESSION['user_id'])) {
    $userType = 'user';
} elseif (isset($_SESSION['customer_id'])) {
    $userType = 'customer';
}

// Step 3: Clear all session variables
$_SESSION = [];

// Step 4: Delete session cookie if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Step 5: Destroy the session completely
session_destroy();

// Step 6: Optional small delay (for safety)
usleep(300000); // 0.3 second

// Step 7: Redirect based on who logged out
if ($userType === 'user') {
    header("Location: ../admin/adminlogin.php?logout=1");
    exit();
} elseif ($userType === 'customer') {
    header("Location: login.php?logout=1");
    exit();
} else {
    // Fallback (if somehow neither session existed)
    header("Location: ../public/publiclogin.php?logout=1");
    exit();
}
?>
