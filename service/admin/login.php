<?php
// Ensure no output before headers
ob_start();

// Include database connection
require_once __DIR__ . '/../db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Get credentials from POST data
  $name = isset($_POST['name']) ? trim($_POST['name']) : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  if (empty($name) || empty($password)) {
    $response['message'] = 'Username dan password wajib diisi';
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
          echo "Login berhasil";
          $_SESSION['admin_id'] = $admin['id'];
          $_SESSION['admin_nama'] = $admin['nama'];
        }
      }

      $stmt->close();
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
}
