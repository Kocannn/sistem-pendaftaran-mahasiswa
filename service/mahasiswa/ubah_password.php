<?php
session_start();
require_once __DIR__ . '/../db.php';

// Ambil input
$nisn = $_POST['nisn'] ?? '';
$old_password = $_POST['old_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validasi dasar
if ($new_password !== $confirm_password) {
    echo "<script>
            alert('Password baru dan konfirmasi password tidak cocok.');
            window.location.href = '/mahasiswa?page=password';
        </script>";
} else {
    $stmt = $conn->prepare("SELECT password FROM mahasiswa WHERE nisn = ?");
    $stmt->bind_param("s", $nisn);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);

    if ($stmt->fetch()) {
        // Cek apakah password lama cocok
        if (password_verify($old_password, $hashedPassword)) {
            $stmt->close();

            // Hash password baru
            $newHashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password baru
            $update = $conn->prepare("UPDATE mahasiswa SET password = ? WHERE nisn = ?");
            $update->bind_param("ss", $newHashedPassword, $nisn);

            if ($update->execute()) {
                echo "<script>
                    alert('Password berhasil diubah.');
                    window.location.href = '/mahasiswa?page=password';
                </script>";
                exit();
            } else {
                echo "<script>
                        alert('Gagal mengubah password. Coba lagi.');
                        window.location.href = '/mahasiswa?page=password';
                    </script>";
            }
            $update->close();
        } else {
            echo "<script>
                    alert('Password lama salah.');
                    window.location.href = '/mahasiswa?page=password';
                </script>";
        }
    } else {
        echo "<script>
                alert('Mahasiswa tidak ditemukan.');
                window.location.href = '/mahasiswa?page=password';
            </script>";
    }
    $stmt->close();
}
