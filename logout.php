<?php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Redirect to login page after logout
header("Location: login.php");
exit();
?>
