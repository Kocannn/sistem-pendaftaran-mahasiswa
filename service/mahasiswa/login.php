<?php
session_start();
require __DIR__ . "/../db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nisn = isset($_POST['nisn']) ? $_POST['nisn'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $stmt = $conn->prepare("SELECT nisn, nama, password FROM mahasiswa WHERE nisn = ?");
    $stmt->bind_param("s", $nisn);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($db_nisn, $db_nama, $hashed_password);
        $stmt->fetch();
        var_dump($db_nisn);
        if (password_verify($password, $hashed_password)) {
            $_SESSION['auth'] = [
                'nisn' => $db_nisn,
                'nama' => $db_nama
            ];
            header("Location: /sistem-informasi-pendaftaran/mahasiswa");
            exit();
        } else {
            echo "<script>
                    alert('NISN atau Password salah');
                    window.location.href = '/sistem-informasi-pendaftaran/mahasiswa/login';
                </script>";
        }
    } else {
        echo "<script>
                alert('NISN atau Password salah');
                window.location.href = '/sistem-informasi-pendaftaran/mahasiswa/login';
            </script>";
    }

    $stmt->close();
    $conn->close();
}
