<?php
include "../service/mahasiswa/auth_gate.php";
include "../service/mahasiswa/get_program_studi.php";
include "../service/mahasiswa/get_profile.php";
include "../service/mahasiswa/edit_profile.php";
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php $page = $_GET['page'] ?? 'beranda'; ?>
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow p-4">
            <h2 class="text-xl font-bold mb-4">Dashboard</h2>
            <ul class="space-y-2">
                <li><a href="?page=beranda" class="block p-2 rounded hover:bg-gray-200 <?= $page === 'beranda' ? 'text-blue-600 font-bold' : '' ?>">Beranda</a></li>
                <li><a href="?page=password" class="block p-2 rounded hover:bg-gray-200 <?= $page === 'password' ? 'text-blue-600 font-bold' : '' ?>">Ubah Password</a></li>
                <li>
                    <form method="post" action="../service/mahasiswa/logout.php">
                        <button type="submit" value="logout" name="logout" class="w-full text-left p-2 rounded hover:bg-red-100 text-red-600">Logout</button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-50">
            <?php if ($page === 'beranda'): ?>
                <h1 class="text-2xl font-bold mb-6">Beranda</h1>
                <div class="flex gap-4">
                    <div class="flex flex-1 flex-col gap-4 bg-white p-6 rounded shadow max-w-lg">
                        <div class="border-2 w-36 h-36 rounded-full self-center">
                            <img src="<?= $profil['foto_profil'] ?? "/sistem-informasi-pendaftaran/assets/img/profile.webp" ?>" alt="foto profile" class="rounded-full w-full h-full object-cover">
                        </div>
                        <hr>
                        <div>
                            <label class="block font-semibold">NISN</label>
                            <div class="w-full p-2 border rounded"><?= $profil['nisn'] ?></div>
                        </div>
                        <div>
                            <label class="block font-semibold">Nama Lengkap</label>
                            <div class="w-full p-2 border rounded"><?= $profil['nama'] ?></div>
                        </div>
                        <div>
                            <label class="block font-semibold">Tahun Lulus</label>
                            <div class="w-full p-2 border rounded"><?= $profil['tahun_lulus'] ?></div>
                        </div>
                        <div>
                            <label class="block font-semibold">No HP</label>
                            <div class="w-full p-2 border rounded"><?= $profil['no_hp'] ?></div>
                        </div>
                        <div>
                            <label class="block font-semibold">Tanggal Lahir</label>
                            <div class="w-full p-2 border rounded"><?= $profil['tanggal_lahir'] ?></div>
                        </div>
                        <div>
                            <label class="block font-semibold">Jalur Pendaftaran</label>
                            <div class="w-full p-2 border rounded"><?= $profil['jalur_pendaftaran'] ?></div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block font-semibold">Prodi 1</label>
                                <div class="w-full p-2 border rounded"><?= $profil['prodi_1_jenjang'] . ' - ' . $profil['prodi_1_nama'] ?></div>
                            </div>
                            <div class="w-1/2">
                                <label class="block font-semibold">Prodi 2</label>
                                <div class="w-full p-2 border rounded"><?= $profil['prodi_2_jenjang'] . ' - ' . $profil['prodi_2_nama'] ?></div>
                            </div>
                        </div>
                        <a href="?page=edit_profile" class="bg-blue-600 text-white text-center px-4 py-2 rounded hover:bg-blue-700">Edit Profile</a>
                    </div>
                    <div class="flex flex-1 flex-col gap-4 bg-white p-6 rounded shadow max-w-lg self-start">
                        <div>
                            <label class="block font-semibold">Skor Ujian</label>
                            <div class="w-full p-2 border rounded"><?= $profil['skor_ujian'] ?? "0.0" ?></div>
                        </div>
                        <div>
                            <label class="block font-semibold">Status Kelulusan</label>
                            <div class="w-full p-2 border rounded"><?= $profil['status_kelulusan'] ?? "belum melakukan tes" ?></div>
                        </div>
                        <a href="#" class="bg-blue-600 text-white text-center px-4 py-2 rounded hover:bg-blue-700">Ambil Tes</a>
                    </div>
                </div>
            <?php elseif ($page === 'edit_profile'): ?>
                <h1 class="text-2xl font-bold mb-6">Edit Profil</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="flex flex-col gap-4 bg-white p-6 rounded shadow max-w-lg">
                        <div class="border-2 w-36 h-36 rounded-full self-center">
                            <label for="foto_profil" class="cursor-pointer">
                                <img id="preview" src="<?= $profil['foto_profil'] ?? '/sistem-informasi-pendaftaran/assets/img/profile.webp' ?>" alt="foto profile" class="rounded-full w-full h-full object-cover">
                            </label>
                            <input type="file" id="foto_profil" name="foto_profil" hidden>
                        </div>
                        <hr>
                        <div>
                            <label class="block font-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" class="w-full p-2 border rounded" required value="<?= $input['nama'] === '' ? $profil['nama'] :  $input['nama'] ?>" />
                            <small class="text-red-500"><?= $errors['nama'] ?? '' ?></small>
                        </div>
                        <div>
                            <label class="block font-semibold">Tahun Lulus</label>
                            <input type="text" name="tahun_lulus" id="tahun_lulus" placeholder="Tahun Lulus" class="w-full p-2 border rounded" required value="<?= $input['tahun_lulus'] === '' ? $profil['tahun_lulus'] :  $input['tahun_lulus'] ?>" />
                            <small class="text-red-500"><?= $errors['tahun_lulus'] ?? '' ?></small>
                        </div>
                        <div>
                            <label class="block font-semibold">No HP</label>
                            <input type="text" name="no_hp" id="no_hp" placeholder="Nomor HP" class="w-full p-2 border rounded" required value="<?= $input['no_hp'] === '' ? $profil['no_hp'] :  $input['no_hp'] ?>" />
                            <small class="text-red-500"><?= $errors['no_hp'] ?? '' ?></small>
                        </div>
                        <div>
                            <label class="block font-semibold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full p-2 border rounded" required value="<?= $input['tanggal_lahir'] === '' ? $profil['tanggal_lahir'] :  $input['tanggal_lahir'] ?>" />
                            <small class="text-red-500"><?= $errors['tanggal_lahir'] ?? '' ?></small>
                        </div>
                        <div>
                            <label class="block font-semibold">Jalur Pendaftaran</label>
                            <select id="jalur_pendaftaran" name="jalur_pendaftaran" class="w-full p-2 border rounded" required>
                                <option value="" disabled>-- Pilih Jalur Pendaftaran --</option>
                                <option value="Mandiri" <?= (($input['jalur_pendaftaran'] === '') ? $profil['jalur_pendaftaran'] : '') === 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                                <option value="SNBT" <?= (($input['jalur_pendaftaran'] === '') ? $profil['jalur_pendaftaran'] : '') === 'SNBT' ? 'selected' : '' ?>>SNBT</option>
                                <option value="SNBP" <?= (($input['jalur_pendaftaran'] === '') ? $profil['jalur_pendaftaran'] : '') === 'SNBP' ? 'selected' : '' ?>>SNBP</option>
                            </select>
                            <small class="text-red-500"><?= $errors['jalur_pendaftaran'] ?? '' ?></small>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block font-semibold">Prodi 1</label>
                                <select id="prodi1" name="prodi_1_kode" class="w-full p-2 border rounded" required>
                                    <option value="" disabled selected>-- Pilih Program Studi --</option>
                                    <?php
                                    $selectedProdi1 = isset($input['prodi_1_kode']) && $input['prodi_1_kode'] !== ''
                                        ? $input['prodi_1_kode']
                                        : $profil['prodi_1_kode'];
                                    ?>
                                    <?php foreach ($programStudi as $row): ?>
                                        <option value="<?= $row['kode'] ?>" <?= $selectedProdi1 == $row['kode'] ? 'selected' : '' ?>>
                                            <?= $row['jenjang'] ?> - <?= $row['nama'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-red-500"><?= $errors['prodi_1_kode'] ?? '' ?></small>
                            </div>
                            <div class="w-1/2">
                                <label class="block font-semibold">Prodi 2</label>
                                <select id="prodi1" name="prodi_2_kode" class="w-full p-2 border rounded" required>
                                    <option value="" disabled selected>-- Pilih Program Studi --</option>
                                    <?php
                                    $selectedProdi2 = isset($input['prodi_2_kode']) && $input['prodi_2_kode'] !== ''
                                        ? $input['prodi_2_kode']
                                        : $profil['prodi_2_kode'];
                                    ?>
                                    <?php foreach ($programStudi as $row): ?>
                                        <option value="<?= $row['kode'] ?>" <?= $selectedProdi2 == $row['kode'] ? 'selected' : '' ?>>
                                            <?= $row['jenjang'] ?> - <?= $row['nama'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-red-500"><?= $errors['prodi_1_kode'] ?? '' ?></small>
                            </div>
                        </div>
                        <button type="submit" name="edit_profil" value="edit_profil" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            <?php elseif ($page === 'password'): ?>
                <!-- Form Ubah Password -->
                <h1 class="text-2xl font-bold mb-6">Ubah Password</h1>
                <form action="../service/mahasiswa/ubah_password.php" method="POST" class="space-y-4 bg-white p-6 rounded shadow max-w-md">
                    <input type="hidden" name="nisn" value="<?= $profil['nisn'] ?>" />
                    <div>
                        <label class="block font-semibold">Password Lama</label>
                        <input type="password" name="old_password" class="w-full p-2 border rounded" required />
                    </div>
                    <div>
                        <label class="block font-semibold">Password Baru</label>
                        <input type="password" name="new_password" class="w-full p-2 border rounded" required />
                    </div>
                    <div>
                        <label class="block font-semibold">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="w-full p-2 border rounded" required />
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-green-700">Ubah Password</button>
                </form>
            <?php endif; ?>
        </main>
    </div>
    <script>
        const input = document.getElementById('foto_profil');
        const preview = document.getElementById('preview');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>