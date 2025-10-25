<?php
// This code is for securely ending the session to prevent hijacking
session_start();
session_destroy();

// Redirect to homepage
header("Location: index.php");
exit;
