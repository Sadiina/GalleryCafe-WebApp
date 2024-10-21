<?php
// Start the session to access session variables and functions
session_start();

// Unset all session variables to clear any stored data
session_unset();

// Destroy the session to completely remove session data and session ID
session_destroy();

// Redirect the user to the homepage after logging out
header("Location: ../index.php");

// Exit the script to ensure no further code is executed
exit();
?>
