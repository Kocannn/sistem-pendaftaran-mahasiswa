<?php
include "../service/mahasiswa/auth_gate.php";
include "../service/mahasiswa/get_program_studi.php";
include "../service/mahasiswa/get_profile.php";
$conn->close();
$page = $_GET['page'] ?? 'beranda';

// Get form errors and input if they exist
$errors = $_SESSION['form_errors'] ?? [];
$input_data = $_SESSION['form_input'] ?? [];

// Clear session data after retrieving
unset($_SESSION['form_errors']);
unset($_SESSION['form_input']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-72 bg-white shadow-xl border-r border-slate-200">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Dashboard</h2>
                        <p class="text-sm text-slate-500">Portal Mahasiswa</p>
                    </div>
                </div>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="/sistem-informasi-pendaftaran/mahasiswa/?page=beranda" class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 hover:bg-primary-50 group <?= $page === 'beranda' ? 'bg-primary-500 text-white shadow-lg' : 'text-slate-600 hover:text-primary-600' ?>">
                            <i class="fas fa-home text-lg <?= $page === 'beranda' ? 'text-white' : 'text-slate-400 group-hover:text-primary-500' ?>"></i>
                            <span class="font-medium">Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="/sistem-informasi-pendaftaran/mahasiswa/?page=password" class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 hover:bg-primary-50 group <?= $page === 'password' ? 'bg-primary-500 text-white shadow-lg' : 'text-slate-600 hover:text-primary-600' ?>">
                            <i class="fas fa-lock text-lg <?= $page === 'password' ? 'text-white' : 'text-slate-400 group-hover:text-primary-500' ?>"></i>
                            <span class="font-medium">Ubah Password</span>
                        </a>
                    </li>
                </ul>
                
                <div class="mt-8 pt-4 border-t border-slate-200">
                    <form method="post" action="../service/mahasiswa/logout.php">
                        <button type="submit" value="logout" name="logout" class="flex items-center space-x-3 w-full p-3 rounded-xl transition-all duration-200 text-red-600 hover:bg-red-50 group">
                            <i class="fas fa-sign-out-alt text-lg text-red-400 group-hover:text-red-500"></i>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 flex items-center justify-center">
            <div class="w-full max-w-6xl">
                <?php if (isset($_SESSION['exam_message'])): ?>
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                    <p><?= $_SESSION['exam_message']; ?></p>
                    <?php unset($_SESSION['exam_message']); ?>
                </div>
                <?php endif; ?>
                
                <?php if ($page === 'beranda'): ?>
                <!-- Beranda Page -->
                <div class="beranda-page">
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Selamat Datang, <?= htmlspecialchars($profil['nama']) ?>!</h1>
                        <p class="text-slate-600">Kelola profil dan informasi akademik Anda</p>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-8">
                        <!-- Profile Card -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6">
                                    <h2 class="text-xl font-semibold text-white flex items-center">
                                        <i class="fas fa-user-circle mr-3"></i>
                                        Profil Mahasiswa
                                    </h2>
                                </div>
                                
                                <div class="p-6">
                                    <!-- Profile Photo -->
                                    <div class="flex justify-center mb-6">
                                        <div class="relative">
                                            <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-slate-100">
                                                <img src="<?= empty($profil['foto_profil']) ? '/sistem-informasi-pendaftaran/assets/img/profile.webp' : $profil['foto_profil'] ?>" alt="foto profile" class="w-full h-full object-cover">
                                            </div>
                                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Profile Information -->
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-2">NISN</label>
                                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-slate-800"><?= htmlspecialchars($profil['nisn']) ?></div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-slate-800"><?= htmlspecialchars($profil['nama']) ?></div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Lulus</label>
                                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-slate-800"><?= htmlspecialchars($profil['tahun_lulus']) ?></div>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-2">No HP</label>
                                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-slate-800"><?= htmlspecialchars($profil['no_hp']) ?></div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir</label>
                                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-slate-800"><?= date('d F Y', strtotime($profil['tanggal_lahir'])) ?></div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-2">Jalur Pendaftaran</label>
                                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-slate-800">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                        <?= htmlspecialchars($profil['jalur_pendaftaran']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Program Studi -->
                                    <div class="mt-6 pt-6 border-t border-slate-200">
                                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Program Studi Pilihan</h3>
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilihan 1</label>
                                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-3">
                                                    <div class="text-green-800 font-medium"><?= htmlspecialchars($profil['prodi_1_jenjang']) ?> - <?= htmlspecialchars($profil['prodi_1_nama']) ?></div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilihan 2</label>
                                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-3">
                                                    <div class="text-blue-800 font-medium"><?= htmlspecialchars($profil['prodi_2_jenjang']) ?> - <?= htmlspecialchars($profil['prodi_2_nama']) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="mt-6 pt-6 border-t border-slate-200">
                                        <a href="/sistem-informasi-pendaftaran/mahasiswa/?page=edit_profile" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <i class="fas fa-edit mr-2"></i>
                                            Edit Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Card -->
                        <div class="space-y-6">
                            <!-- Test Score Card -->
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-4">
                                    <h3 class="text-lg font-semibold text-white flex items-center">
                                        <i class="fas fa-chart-line mr-2"></i>
                                        Status Ujian
                                    </h3>
                                </div>
                                <div class="p-6">
                                    <div class="text-center mb-4">
                                        <?php if (empty($profil['skor_ujian'])): ?>
                                            <div class="text-lg font-semibold text-amber-600 mb-1">Anda belum mengikuti ujian</div>
                                            <div class="text-sm text-slate-500">Status Ujian</div>
                                        <?php else: ?>
                                            <div class="text-3xl font-bold text-slate-800 mb-1"><?= number_format($profil['skor_ujian'], 1) ?></div>
                                            <div class="text-sm text-slate-500">Skor Ujian</div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($profil['skor_ujian'])): ?>
                                    
                                    <div class="text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        <?php if ($profil['status_kelulusan'] === 'Tidak Lulus'): ?>
                                            bg-red-100 text-red-800
                                        <?php else: ?>
                                             bg-green-100 text-green-800
                                        <?php endif; ?>">
                                            <i class="fas <?= $profil['status_kelulusan'] === 'Tidak Lulus' ? 'fa-times-circle' :'fa-check-circle'  ?> mr-1"></i>
                                            <?= $profil['status_kelulusan'] ?>
                                        </span>
                                        
                                        <?php if ($profil['status_kelulusan'] !== 'Tidak Lulus'): ?>
                                            <div class="mt-3">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-award mr-1"></i>
                                                    <?php 
                                                    // Check if prodi_diterima matches prodi_1_kode or prodi_2_kode to determine which choice was accepted
                                                    if (isset($profil['prodi_diterima']) && $profil['prodi_diterima'] == $profil['prodi_1_kode']): 
                                                        echo 'Lolos Pilihan 1';
                                                    elseif (isset($profil['prodi_diterima']) && $profil['prodi_diterima'] == $profil['prodi_2_kode']): 
                                                        echo 'Lolos Pilihan 2'; 
                                                    elseif (isset($profil['jalur_baru']) && $profil['jalur_baru'] != $profil['jalur_pendaftaran']): 
                                                        echo "Dialihkan ke {$profil['jalur_baru']}";
                                                    else:
                                                        echo "Diterima";
                                                    endif; 
                                                    ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php else: ?>
                                    <div class="text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Belum Ada Hasil
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-4">
                                    <h3 class="text-lg font-semibold text-white flex items-center">
                                        <i class="fas fa-bolt mr-2"></i>
                                        Aksi Cepat
                                    </h3>
                                </div>
                                <div class="p-6 space-y-3">
                                    <?php if (empty($profil['skor_ujian'])): ?>
                                    <a href="/sistem-informasi-pendaftaran/mahasiswa/exam" class="flex items-center justify-between p-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                        <span class="font-medium">Ambil Tes</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                    <?php else: ?>
                                    <div class="flex items-center justify-between p-3 bg-gray-100 text-gray-600 rounded-xl">
                                        <span class="font-medium">Anda telah menyelesaikan ujian</span>
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    </div>
                                    <?php endif; ?>
                                    <a href="/sistem-informasi-pendaftaran/mahasiswa/?page=password" class="flex items-center justify-between p-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-all duration-200">
                                        <span class="font-medium">Ubah Password</span>
                                        <i class="fas fa-arrow-right text-slate-400"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php elseif ($page === 'edit_profile'): ?>
                <!-- Edit Profile Page -->
                <div class="edit-profile-page">
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Edit Profil</h1>
                        <p class="text-slate-600">Perbarui informasi profil Anda</p>
                    </div>

                    <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?= $_SESSION['success_message']; ?>
                        <?php unset($_SESSION['success_message']); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($errors['global'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= $errors['global']; ?>
                    </div>
                    <?php endif; ?>

                    <form action="../service/mahasiswa/edit_profile.php" method="post" enctype="multipart/form-data">
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden max-w-2xl">
                            <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6">
                                <h2 class="text-xl font-semibold text-white flex items-center">
                                    <i class="fas fa-user-edit mr-3"></i>
                                    Edit Informasi Profil
                                </h2>
                            </div>
                            
                            <div class="p-6">
                                <!-- Profile Photo Upload -->
                                <div class="flex justify-center mb-8">
                                    <div class="relative">
                                        <label for="foto_profil" class="cursor-pointer group">
                                            <div class="w-32 h-32 rounded-full border-4 border-slate-200 overflow-hidden bg-slate-100 group-hover:border-primary-300 transition-colors">
                                                <img id="preview" src="<?= empty($profil['foto_profil']) ? '/sistem-informasi-pendaftaran/assets/img/profile.webp' : $profil['foto_profil'] ?>" alt="foto profile" class="w-full h-full object-cover">
                                            </div>
                                            <div class="absolute inset-0 rounded-full bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                                                <i class="fas fa-camera text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                            </div>
                                        </label>
                                        <input type="file" id="foto_profil" name="foto_profil" hidden accept="image/*">
                                    </div>
                                </div>

                                <!-- Form Fields -->
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                                        <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required value="<?= htmlspecialchars($input_data['nama'] ?? $profil['nama']) ?>" />
                                        <?php if (isset($errors['nama'])): ?>
                                        <small class="text-red-500 text-xs mt-1"><?= $errors['nama'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Lulus</label>
                                        <input type="text" name="tahun_lulus" id="tahun_lulus" placeholder="2023" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required value="<?= htmlspecialchars($input_data['tahun_lulus'] ?? $profil['tahun_lulus']) ?>" />
                                        <?php if (isset($errors['tahun_lulus'])): ?>
                                        <small class="text-red-500 text-xs mt-1"><?= $errors['tahun_lulus'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">No HP</label>
                                        <input type="text" name="no_hp" id="no_hp" placeholder="+62 812-3456-7890" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required value="<?= htmlspecialchars($input_data['no_hp'] ?? $profil['no_hp']) ?>" />
                                        <?php if (isset($errors['no_hp'])): ?>
                                        <small class="text-red-500 text-xs mt-1"><?= $errors['no_hp'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required value="<?= htmlspecialchars($input_data['tanggal_lahir'] ?? $profil['tanggal_lahir']) ?>" />
                                        <?php if (isset($errors['tanggal_lahir'])): ?>
                                        <small class="text-red-500 text-xs mt-1"><?= $errors['tanggal_lahir'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jalur Pendaftaran</label>
                                        <select id="jalur_pendaftaran" name="jalur_pendaftaran" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required>
                                            <option value="" disabled>-- Pilih Jalur Pendaftaran --</option>
                                            <option value="Mandiri" <?= $profil['jalur_pendaftaran'] === 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                                            <option value="SNBT" <?= $profil['jalur_pendaftaran'] === 'SNBT' ? 'selected' : '' ?>>SNBT</option>
                                            <option value="SNBP" <?= $profil['jalur_pendaftaran'] === 'SNBP' ? 'selected' : '' ?>>SNBP</option>
                                        </select>
                                        <?php if (isset($errors['jalur_pendaftaran'])): ?>
                                        <small class="text-red-500 text-xs mt-1"><?= $errors['jalur_pendaftaran'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php 
                                    // Get program study data
                                    require_once "../service/mahasiswa/get_program_studi.php"; 
                                    ?>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Prodi 1</label>
                                        <select id="prodi1" name="prodi_1_kode" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required>
                                            <option value="" disabled>-- Pilih Program Studi --</option>
                                            <?php foreach ($programStudi as $prodi): ?>
                                            <option value="<?= $prodi['kode'] ?>" <?= $profil['prodi_1_kode'] === $prodi['kode'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($prodi['jenjang']) ?> - <?= htmlspecialchars($prodi['nama']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['prodi_1_kode'])): ?>
                                        <small class="text-red-500 text-xs mt-1"><?= $errors['prodi_1_kode'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Prodi 2</label>
                                        <select id="prodi2" name="prodi_2_kode" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required>
                                            <option value="" disabled>-- Pilih Program Studi --</option>
                                            <?php foreach ($programStudi as $prodi): ?>
                                            <option value="<?= $prodi['kode'] ?>" <?= $profil['prodi_2_kode'] === $prodi['kode'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($prodi['jenjang']) ?> - <?= htmlspecialchars($prodi['nama']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['prodi_2_kode'])): ?>
                                        <small class="text-red-500 text-xs mt-1"><?= $errors['prodi_2_kode'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-4 mt-8 pt-6 border-t border-slate-200">
                                    <button type="submit" name="edit_profil" value="edit_profil" class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-xl font-medium hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        <i class="fas fa-save mr-2"></i>
                                        Simpan Perubahan
                                    </button>
                                    <a href="/sistem-informasi-pendaftaran/mahasiswa/?page=beranda" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                                        Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php elseif ($page === 'password'): ?>
                <!-- Change Password Page -->
                <div class="password-page">
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Ubah Password</h1>
                        <p class="text-slate-600">Perbarui password untuk keamanan akun Anda</p>
                    </div>

                    <form action="../service/mahasiswa/ubah_password.php" method="POST">
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden max-w-md">
                            <div class="bg-gradient-to-r from-red-500 to-pink-600 p-6">
                                <h2 class="text-xl font-semibold text-white flex items-center">
                                    <i class="fas fa-shield-alt mr-3"></i>
                                    Keamanan Akun
                                </h2>
                            </div>
                            
                            <div class="p-6">
                                <input type="hidden" name="nisn" value="<?= htmlspecialchars($_SESSION['auth']['nisn']) ?>" />
                                
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password Lama</label>
                                        <div class="relative">
                                            <input type="password" name="old_password" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors pr-10" required placeholder="Masukkan password lama" />
                                            <i class="fas fa-lock absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                                        <div class="relative">
                                            <input type="password" name="new_password" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors pr-10" required placeholder="Masukkan password baru" />
                                            <i class="fas fa-key absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                                        <div class="relative">
                                            <input type="password" name="confirm_password" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors pr-10" required placeholder="Konfirmasi password baru" />
                                            <i class="fas fa-check-circle absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Tips -->
                                <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                    <h4 class="text-sm font-semibold text-amber-800 mb-2">Tips Keamanan:</h4>
                                    <ul class="text-xs text-amber-700 space-y-1">
                                        <li>• Gunakan minimal 8 karakter</li>
                                        <li>• Kombinasikan huruf besar, kecil, dan angka</li>
                                        <li>• Jangan gunakan informasi pribadi</li>
                                    </ul>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-4 mt-6">
                                    <button type="submit" class="flex-1 bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-3 rounded-xl font-medium hover:from-red-600 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        <i class="fas fa-shield-alt mr-2"></i>
                                        Ubah Password
                                    </button>
                                    <a href="?page=beranda" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                                        Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Image preview functionality
        const input = document.getElementById('foto_profil');
        const preview = document.getElementById('preview');

        if (input && preview) {
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
        }

        // Simple page navigation simulation (replace with actual PHP logic)
        // function showPage(page) {
        //     // Hide all pages
        //     document.querySelectorAll('.beranda-page, .edit-profile-page, .password-page').forEach(el => {
        //         el.classList.add('hidden');
        //     });
            
        //     // Show selected page
        //     const targetPage = document.querySelector(`.${page}-page`);
        //     if (targetPage) {
        //         targetPage.classList.remove('hidden');
        //     }
        // }

        // Initialize page based on URL parameter (simulate PHP behavior)
        // Handle navigation clicks
        
    </script>
</body>

</html>