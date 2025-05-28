<?php
function handleLogout()
{

  // Start the session
  if (isset($_SESSION) && session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Destroy the session
  session_destroy();

  // Clear the session cookie
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
  }
}
