<?php
// Destroy all session data to log out the user
session_start();
session_destroy();

// Redirect to homepage
header("Location: index.php");
exit;
