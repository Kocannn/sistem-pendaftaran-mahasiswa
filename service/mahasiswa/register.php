<?php
require_once __DIR__ . "/../db.php";

$errors = [];
$input = [
    'nama' => $_POST['nama'] ?? '',
    'nisn' => $_POST['nisn'] ?? '',
    'tahun_lulus' => $_POST['tahun_lulus'] ?? '',
    'no_hp' => $_POST['no_hp'] ?? '',
    'tanggal_lahir' => $_POST['tanggal_lahir'] ?? '',
    'prodi_1_kode' => $_POST['prodi_1_kode'] ?? '',
    'prodi_2_kode' => $_POST['prodi_2_kode'] ?? '',
    'jalur_pendaftaran' => $_POST['jalur_pendaftaran'] ?? '',
    'password' => '',
    'konfirmasi_password' => '',
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($input as $key => $_) {
        $input[$key] = $_POST[$key] ?? '';
    }

    // validasi
    if (empty($input['nama'])) $errors['nama'] = 'Nama wajib diisi';
    if (!preg_match('/^\d{10}$/', $input['nisn'])) $errors['nisn'] = 'NISN harus 10 digit angka';
    if (!preg_match('/^\d{4}$/', $input['tahun_lulus'])) $errors['tahun_lulus'] = 'Tahun lulus tidak valid';
    if (strlen($input['password']) < 8) $errors['password'] = 'Password minimal 6 karakter';
    if ($input['password'] !== $input['konfirmasi_password']) $errors['konfirmasi_password'] = 'Konfirmasi password tidak sama';
    if (!preg_match('/^08\d{8,11}$/', $input['no_hp'])) $errors['no_hp'] = 'No HP tidak valid';
    if (!DateTime::createFromFormat('Y-m-d', $input['tanggal_lahir'])) $errors['tanggal_lahir'] = 'Tanggal lahir tidak valid';
    if (empty($input['prodi_1_kode'])) $errors['prodi_1_kode'] = 'Prodi 1 wajib diisi';
    if (empty($input['jalur_pendaftaran'])) $errors['jalur_pendaftaran'] = 'Jalur pendaftaran wajib diisi';


    if (empty($errors)) {
        $bukti_pembayaran = $_FILES['bukti_pembayaran'];
        $save_path = __DIR__ . "/../../uploads/" . basename($bukti_pembayaran['name']);
        $bukti_pembayaran_path = "/uploads/" . basename($bukti_pembayaran['name']);

        if (!move_uploaded_file($bukti_pembayaran["tmp_name"], $save_path)) {
            $errors['bukti_pembayaran'] = "Gagal upload bukti pembayaran.";
        } else {
            $password_hash = password_hash($input['password'], PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO mahasiswa (nisn, tahun_lulus, nama, no_hp, tanggal_lahir, prodi_1_kode, prodi_2_kode, jalur_pendaftaran, password, bukti_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "ssssssssss",
                $input['nisn'],
                $input['tahun_lulus'],
                $input['nama'],
                $input['no_hp'],
                $input['tanggal_lahir'],
                $input['prodi_1_kode'],
                $input['prodi_2_kode'],
                $input['jalur_pendaftaran'],
                $password_hash,
                $bukti_pembayaran_path
            );

            try {
                if ($stmt->execute()) {
                    echo "
                        <script>
                            alert('Pendaftaran Berhasil');
                            window.location.href = '/mahasiswa/login';
                        </script>
                    ";
                } else {
                    echo "Registrasi gagal: " . $stmt->error;
                }
            } catch (Exception $e) {
                if ($e->getCode() === 1062) {
                    $errors['nisn'] = "NISN sudah terdaftar";
                    exit();
                }
                $errors['global'] = "Registrasi gagal: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
