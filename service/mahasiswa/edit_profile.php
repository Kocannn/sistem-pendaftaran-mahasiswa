<?php
session_start();  // Make sure session is started
require_once __DIR__ . "/../db.php";

$errors = [];
$input = [
    'nama' => $_POST['nama'] ?? '',
    'tahun_lulus' => $_POST['tahun_lulus'] ?? '',
    'no_hp' => $_POST['no_hp'] ?? '',
    'tanggal_lahir' => $_POST['tanggal_lahir'] ?? '',
    'prodi_1_kode' => $_POST['prodi_1_kode'] ?? '',
    'prodi_2_kode' => $_POST['prodi_2_kode'] ?? '',
    'jalur_pendaftaran' => $_POST['jalur_pendaftaran'] ?? ''
];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit_profil'])) {
    foreach ($input as $key => $_) {
        $input[$key] = $_POST[$key] ?? '';
    }

    // validasi
    if (empty($input['nama'])) $errors['nama'] = 'Nama wajib diisi';
    if (!preg_match('/^\d{4}$/', $input['tahun_lulus'])) $errors['tahun_lulus'] = 'Tahun lulus tidak valid';
    if (!preg_match('/^08\d{8,11}$/', $input['no_hp'])) $errors['no_hp'] = 'No HP tidak valid';
    if (!DateTime::createFromFormat('Y-m-d', $input['tanggal_lahir'])) $errors['tanggal_lahir'] = 'Tanggal lahir tidak valid';
    if (empty($input['prodi_1_kode'])) $errors['prodi_1_kode'] = 'Prodi 1 wajib diisi';
    if (empty($input['prodi_2_kode'])) $errors['prodi_2_kode'] = 'Prodi 2 wajib diisi';
    if (empty($input['jalur_pendaftaran'])) $errors['jalur_pendaftaran'] = 'Jalur pendaftaran wajib diisi';

    if (empty($errors)) {
        $foto_profil_path = null;

        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
            $foto_profil = $_FILES['foto_profil'];
            $save_path = __DIR__ . "/../../uploads/" . basename($foto_profil['name']);
            $foto_profil_path = "/sistem-informasi-pendaftaran/uploads/" . basename($foto_profil['name']);

            if (!move_uploaded_file($foto_profil["tmp_name"], $save_path)) {
                $errors['foto_profil'] = "Gagal upload foto profil.";
            }
        }

        if (empty($errors)) {
            try {
                if ($foto_profil_path !== null) {
                    $stmt = $conn->prepare("UPDATE mahasiswa SET tahun_lulus = ?, nama = ?, no_hp = ?, tanggal_lahir = ?, prodi_1_kode = ?, prodi_2_kode = ?, jalur_pendaftaran = ?, foto_profil = ? WHERE nisn = ?");
                    $stmt->bind_param(
                        "sssssssss",
                        $input['tahun_lulus'],
                        $input['nama'],
                        $input['no_hp'],
                        $input['tanggal_lahir'],
                        $input['prodi_1_kode'],
                        $input['prodi_2_kode'],
                        $input['jalur_pendaftaran'],
                        $foto_profil_path,
                        $_SESSION['auth']['nisn']
                    );
                } else {
                    $stmt = $conn->prepare("UPDATE mahasiswa SET tahun_lulus = ?, nama = ?, no_hp = ?, tanggal_lahir = ?, prodi_1_kode = ?, prodi_2_kode = ?, jalur_pendaftaran = ? WHERE nisn = ?");
                    $stmt->bind_param(
                        "ssssssss",
                        $input['tahun_lulus'],
                        $input['nama'],
                        $input['no_hp'],
                        $input['tanggal_lahir'],
                        $input['prodi_1_kode'],
                        $input['prodi_2_kode'],
                        $input['jalur_pendaftaran'],
                        $_SESSION['auth']['nisn']
                    );
                }

                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Update Profile Berhasil";
                    header("Location: /sistem-informasi-pendaftaran/mahasiswa/?page=beranda");
                    exit(); // Important: prevent further execution
                } else {
                    $errors['global'] = "Update profile gagal: " . $stmt->error;
                }
                
                $stmt->close();
            } catch (Exception $e) {
                $errors['global'] = "Update profile gagal: " . $e->getMessage();
            }
        }
    }
    
    // If we reach here, there were errors
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_input'] = $input;
    header("Location: /sistem-informasi-pendaftaran/mahasiswa/?page=edit_profile");
    exit();
}

// For direct access to this script without POST
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['edit_profil'])) {
    header("Location: /sistem-informasi-pendaftaran/mahasiswa/?page=beranda");
    exit();
}
