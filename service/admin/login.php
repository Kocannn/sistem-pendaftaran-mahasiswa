<?php
// Start session at the very beginning
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure no output before headers
ob_start();

// Include database connection
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get credentials from POST data
  $name = isset($_POST['name']) ? trim($_POST['name']) : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  if (empty($name) || empty($password)) {
    $_SESSION['login_error'] = 'Username dan password wajib diisi';
    header("Location: /sistem-informasi-pendaftaran/admin/login");
    exit;
  } else {
    try {
      // Query to check admin
      $stmt = $conn->prepare("SELECT id, nama, password FROM admins WHERE nama = ?");
      $stmt->bind_param("s", $name);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $admin['password'])) {
          // Set session variables
          $_SESSION['admin_id'] = $admin['id'];
          $_SESSION['admin_nama'] = $admin['nama'];
          
          // Redirect to admin dashboard
          header("Location: /sistem-informasi-pendaftaran/admin");
          exit;
        } else {
          $_SESSION['login_error'] = 'Username atau password salah';
          header("Location: /sistem-informasi-pendaftaran/admin/login");
          exit;
        }
      } else {
        $_SESSION['login_error'] = 'Username atau password salah';
        header("Location: /sistem-informasi-pendaftaran/admin/login");
        exit;
      }

      $stmt->close();
    } catch (Exception $e) {
      $_SESSION['login_error'] = 'Error: ' . $e->getMessage();
      header("Location: /sistem-informasi-pendaftaran/admin/login");
      exit;
    }
  }
}

// End output buffering
ob_end_flush();
