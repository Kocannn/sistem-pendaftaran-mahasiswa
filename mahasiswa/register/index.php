<?php
session_start();
require "../../service/mahasiswa/register.php";
require "../../service/mahasiswa/get_program_studi.php";
if (isset($_SESSION['auth'])) {
    header("Location: /mahasiswa");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Peserta </title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md my-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Pendaftaran Mahasiswa</h2>
        <?php if (isset($errors['global'])): ?>
            <p class="text-red-500 text-center mb-2"><?= $errors['global'] ?></p>
        <?php endif; ?>
        <form class="space-y-4" method="POST" enctype="multipart/form-data">
            <div>
                <label for="nama" class="block font-medium">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" class="w-full p-2 border rounded" required value="<?= $input['nama'] ?? '' ?>" />
                <small class="text-red-500"><?= $errors['nama'] ?? '' ?></small>
            </div>
            <div>
                <label for="nisn" class="block font-medium">NISN</label>
                <input type="text" name="nisn" id="nisn" placeholder="NISN" class="w-full p-2 border rounded" required value="<?= $input['nisn'] ?? '' ?>" />
                <small class="text-red-500"><?= $errors['nisn'] ?? '' ?></small>
            </div>
            <div>
                <label for="tahun_lulus" class="block font-medium">Tahun Lulus</label>
                <input type="text" name="tahun_lulus" id="tahun_lulus" placeholder="Tahun Lulus" class="w-full p-2 border rounded" required value="<?= $input['tahun_lulus'] ?? '' ?>" />
                <small class="text-red-500"><?= $errors['tahun_lulus'] ?? '' ?></small>
            </div>
            <div>
                <label for="password" class="block font-medium">Password</label>
                <input type="password" name="password" id="password" placeholder="Buat Password" class="w-full p-2 border rounded" required />
                <small class="text-red-500"><?= $errors['password'] ?? '' ?></small>
            </div>
            <div>
                <label for="konfirmasi_password" class="block font-medium">Konfirmasi Password</label>
                <input type="password" name="konfirmasi_password" id="konfirmasi_password" placeholder="Konfirmasi Password" class="w-full p-2 border rounded" required />
                <small class="text-red-500"><?= $errors['konfirmasi_password'] ?? '' ?></small>
            </div>
            <div>
                <label for="no_hp" class="block font-medium">Nomor HP</label>
                <input type="text" name="no_hp" id="no_hp" placeholder="Nomor HP" class="w-full p-2 border rounded" required value="<?= $input['no_hp'] ?? '' ?>" />
                <small class="text-red-500"><?= $errors['no_hp'] ?? '' ?></small>
            </div>
            <div>
                <label for="tanggal_lahir" class="block font-medium">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full p-2 border rounded" required value="<?= $input['tanggal_lahir'] ?? '' ?>" />
                <small class="text-red-500"><?= $errors['tanggal_lahir'] ?? '' ?></small>
            </div>
            <div>
                <label for="prodi1" class="block font-medium">Pilihan Program Studi 1</label>
                <select id="prodi1" name="prodi_1_kode" class="w-full p-2 border rounded" required>
                    <option value="" disabled selected>-- Pilih Program Studi --</option>
                    <?php foreach ($programStudi as $row): ?>
                        <option value="<?= $row['kode'] ?>" <?= ($input['prodi_1_kode'] == $row['kode'] ? 'selected' : '') ?>>
                            <?= $row['jenjang'] ?> - <?= $row['nama'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-red-500"><?= $errors['prodi_1_kode'] ?? '' ?></small>
            </div>
            <div>
                <label for="prodi2" class="block font-medium">Pilihan Program Studi 2</label>
                <select id="prodi2" name="prodi_2_kode" class="w-full p-2 border rounded" required>
                    <option value="" disabled selected>-- Pilih Program Studi --</option>
                    <?php foreach ($programStudi as $row): ?>
                        <option value="<?= $row['kode'] ?>" <?= ($input['prodi_2_kode'] == $row['kode'] ? 'selected' : '') ?>>
                            <?= $row['jenjang'] ?> - <?= $row['nama'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-red-500"><?= $errors['prodi_2_kode'] ?? '' ?></small>
            </div>
            <div>
                <label for="jalur_pendaftaran" class="block font-medium">Jalur Pendaftaran</label>
                <select id="jalur_pendaftaran" name="jalur_pendaftaran" class="w-full p-2 border rounded" required>
                    <option value="" disabled>-- Pilih Jalur Pendaftaran --</option>
                    <option value="Mandiri" <?= ($input['jalur_pendaftaran'] ?? '') === 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                    <option value="SNBT" <?= ($input['jalur_pendaftaran'] ?? '') === 'SNBT' ? 'selected' : '' ?>>SNBT</option>
                    <option value="SNBP" <?= ($input['jalur_pendaftaran'] ?? '') === 'SNBP' ? 'selected' : '' ?>>SNBP</option>
                </select>
                <small class="text-red-500"><?= $errors['jalur_pendaftaran'] ?? '' ?></small>
            </div>
            <div>
                <label for="bukti_pembayaran" class="block font-medium">Bukti Pembayaran</label>
                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="w-full p-2 border rounded" required />
            </div>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Daftar</button>
        </form>

        <p class="text-sm text-center mt-4">
            Sudah punya akun? <a href="/sistem-informasi-pendaftaran/mahasiswa/login" class="text-blue-500 hover:underline">Login di sini</a>
        </p>
    </div>
</body>

</html>