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
    <title>Daftar Peserta - Portal Akademik</title>
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
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Fix for mobile scrolling and overflow issues */
        html, body {
            overflow-x: hidden;
            position: relative;
            height: 100%;
            width: 100%;
        }
        
        /* Improved scrollbar for better user experience */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-emerald-50 to-teal-100">
    <!-- Background Decorations - Fixed position to avoid layout issues -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-emerald-200 to-teal-300 rounded-full opacity-20 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-blue-200 to-indigo-300 rounded-full opacity-20 animate-float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-gradient-to-br from-teal-200 to-cyan-300 rounded-full opacity-30 animate-float" style="animation-delay: -1.5s;"></div>
        <div class="absolute top-1/4 right-1/4 w-24 h-24 bg-gradient-to-br from-emerald-300 to-green-400 rounded-full opacity-25 animate-pulse-slow"></div>
    </div>

    <!-- Main Container with proper padding and overflow handling -->
    <div class="relative min-h-screen py-10 px-4 sm:px-6 md:px-8 lg:px-10">
        <div class="max-w-4xl mx-auto">
            <!-- Logo/Header Section -->
            <div class="text-center mb-8 animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl shadow-lg mb-4 animate-float">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Portal Akademik</h1>
                <p class="text-slate-600">Daftar sebagai Mahasiswa Baru</p>
            </div>

            <!-- Registration Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-6 text-center">
                    <h2 class="text-2xl font-bold text-white mb-2">Pendaftaran Mahasiswa</h2>
                    <p class="text-emerald-100">Lengkapi data diri Anda untuk mendaftar</p>
                </div>

                <!-- Card Body - Improved padding and spacing -->
                <div class="p-4 sm:p-6 md:p-8">
                    <!-- Global Error Message -->
                    <?php if (isset($errors['global'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span><?= $errors['global'] ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Progress Indicator -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between text-sm font-medium text-slate-600 mb-2">
                            <span class="flex items-center">
                                <i class="fas fa-user mr-1"></i>
                                Data Pribadi
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-university mr-1"></i>
                                Akademik
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-credit-card mr-1"></i>
                                Pembayaran
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>

                    <form class="space-y-6" method="POST" enctype="multipart/form-data" id="registration-form">
                        <!-- Personal Information Section - Improved responsive grid -->
                        <div class="bg-slate-50 rounded-xl p-4 sm:p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-user mr-2 text-emerald-500"></i>
                                Data Pribadi
                            </h3>
                            
                            <div class="grid gap-4 sm:gap-6">
                                <!-- Full Name -->
                                <div>
                                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">
                                        <i class="fas fa-signature mr-2 text-primary-500"></i>
                                        Nama Lengkap
                                    </label>
                                    <input 
                                        type="text" 
                                        name="nama" 
                                        id="nama" 
                                        placeholder="Masukkan nama lengkap Anda" 
                                        class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors <?= isset($errors['nama']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                        required 
                                        value="<?= htmlspecialchars($input['nama'] ?? '') ?>" 
                                    />
                                    <?php if (isset($errors['nama'])): ?>
                                    <small class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <?= $errors['nama'] ?>
                                    </small>
                                    <?php endif; ?>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <!-- NISN -->
                                    <div>
                                        <label for="nisn" class="block text-sm font-semibold text-slate-700 mb-2">
                                            <i class="fas fa-id-card mr-2 text-primary-500"></i>
                                            NISN
                                        </label>
                                        <input 
                                            type="text" 
                                            name="nisn" 
                                            id="nisn" 
                                            placeholder="Masukkan NISN" 
                                            class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors <?= isset($errors['nisn']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                            required 
                                            value="<?= htmlspecialchars($input['nisn'] ?? '') ?>" 
                                        />
                                        <?php if (isset($errors['nisn'])): ?>
                                        <small class="text-red-500 text-xs mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            <?= $errors['nisn'] ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Graduation Year -->
                                    <div>
                                        <label for="tahun_lulus" class="block text-sm font-semibold text-slate-700 mb-2">
                                            <i class="fas fa-graduation-cap mr-2 text-primary-500"></i>
                                            Tahun Lulus
                                        </label>
                                        <input 
                                            type="number" 
                                            name="tahun_lulus" 
                                            id="tahun_lulus" 
                                            placeholder="2023" 
                                            min="2020" 
                                            max="2030"
                                            class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors <?= isset($errors['tahun_lulus']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                            required 
                                            value="<?= htmlspecialchars($input['tahun_lulus'] ?? '') ?>" 
                                        />
                                        <?php if (isset($errors['tahun_lulus'])): ?>
                                        <small class="text-red-500 text-xs mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            <?= $errors['tahun_lulus'] ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <!-- Phone Number -->
                                    <div>
                                        <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-2">
                                            <i class="fas fa-phone mr-2 text-primary-500"></i>
                                            Nomor HP
                                        </label>
                                        <input 
                                            type="tel" 
                                            name="no_hp" 
                                            id="no_hp" 
                                            placeholder="+62 812-3456-7890" 
                                            class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors <?= isset($errors['no_hp']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                            required 
                                            value="<?= htmlspecialchars($input['no_hp'] ?? '') ?>" 
                                        />
                                        <?php if (isset($errors['no_hp'])): ?>
                                        <small class="text-red-500 text-xs mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            <?= $errors['no_hp'] ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Birth Date -->
                                    <div>
                                        <label for="tanggal_lahir" class="block text-sm font-semibold text-slate-700 mb-2">
                                            <i class="fas fa-calendar mr-2 text-primary-500"></i>
                                            Tanggal Lahir
                                        </label>
                                        <input 
                                            type="date" 
                                            name="tanggal_lahir" 
                                            id="tanggal_lahir" 
                                            class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors <?= isset($errors['tanggal_lahir']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                            required 
                                            value="<?= htmlspecialchars($input['tanggal_lahir'] ?? '') ?>" 
                                        />
                                        <?php if (isset($errors['tanggal_lahir'])): ?>
                                        <small class="text-red-500 text-xs mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            <?= $errors['tanggal_lahir'] ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information Section -->
                        <div class="bg-blue-50 rounded-xl p-4 sm:p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-university mr-2 text-blue-500"></i>
                                Informasi Akademik
                            </h3>
                            
                            <div class="grid gap-4 sm:gap-6">
                                <!-- Registration Path -->
                                <div>
                                    <label for="jalur_pendaftaran" class="block text-sm font-semibold text-slate-700 mb-2">
                                        <i class="fas fa-route mr-2 text-primary-500"></i>
                                        Jalur Pendaftaran
                                    </label>
                                    <select 
                                        id="jalur_pendaftaran" 
                                        name="jalur_pendaftaran" 
                                        class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?= isset($errors['jalur_pendaftaran']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                        required
                                    >
                                        <option value="" disabled <?= empty($input['jalur_pendaftaran'] ?? '') ? 'selected' : '' ?>>-- Pilih Jalur Pendaftaran --</option>
                                        <option value="Mandiri" <?= ($input['jalur_pendaftaran'] ?? '') === 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                                        <option value="SNBT" <?= ($input['jalur_pendaftaran'] ?? '') === 'SNBT' ? 'selected' : '' ?>>SNBT</option>
                                        <option value="SNBP" <?= ($input['jalur_pendaftaran'] ?? '') === 'SNBP' ? 'selected' : '' ?>>SNBP</option>
                                    </select>
                                    <?php if (isset($errors['jalur_pendaftaran'])): ?>
                                    <small class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <?= $errors['jalur_pendaftaran'] ?>
                                    </small>
                                    <?php endif; ?>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <!-- Program Study 1 -->
                                    <div>
                                        <label for="prodi1" class="block text-sm font-semibold text-slate-700 mb-2">
                                            <i class="fas fa-star mr-2 text-yellow-500"></i>
                                            Pilihan Program Studi 1
                                        </label>
                                        <select 
                                            id="prodi1" 
                                            name="prodi_1_kode" 
                                            class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?= isset($errors['prodi_1_kode']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                            required
                                        >
                                            <option value="" disabled selected>-- Pilih Program Studi --</option>
                                            <?php foreach ($programStudi as $row): ?>
                                                <option value="<?= $row['kode'] ?>" <?= ($input['prodi_1_kode'] ?? '') == $row['kode'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($row['jenjang']) ?> - <?= htmlspecialchars($row['nama']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['prodi_1_kode'])): ?>
                                        <small class="text-red-500 text-xs mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            <?= $errors['prodi_1_kode'] ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Program Study 2 -->
                                    <div>
                                        <label for="prodi2" class="block text-sm font-semibold text-slate-700 mb-2">
                                            <i class="fas fa-star-half-alt mr-2 text-yellow-400"></i>
                                            Pilihan Program Studi 2
                                        </label>
                                        <select 
                                            id="prodi2" 
                                            name="prodi_2_kode" 
                                            class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?= isset($errors['prodi_2_kode']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                            required
                                        >
                                            <option value="" disabled selected>-- Pilih Program Studi --</option>
                                            <?php foreach ($programStudi as $row): ?>
                                                <option value="<?= $row['kode'] ?>" <?= ($input['prodi_2_kode'] ?? '') == $row['kode'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($row['jenjang']) ?> - <?= htmlspecialchars($row['nama']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['prodi_2_kode'])): ?>
                                        <small class="text-red-500 text-xs mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            <?= $errors['prodi_2_kode'] ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="bg-red-50 rounded-xl p-4 sm:p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-shield-alt mr-2 text-red-500"></i>
                                Keamanan Akun
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                                        <i class="fas fa-lock mr-2 text-primary-500"></i>
                                        Password
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="password" 
                                            name="password" 
                                            id="password" 
                                            placeholder="Buat password yang kuat" 
                                            class="w-full p-3 pr-12 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors <?= isset($errors['password']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                            required 
                                        />
                                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                            <i id="password-toggle" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php if (isset($errors['password'])): ?>
                                    <small class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <?= $errors['password'] ?>
                                    </small>
                                    <?php endif; ?>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="konfirmasi_password" class="block text-sm font-semibold text-slate-700 mb-2">
                                        <i class="fas fa-check-circle mr-2 text-primary-500"></i>
                                        Konfirmasi Password
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="password" 
                                            name="konfirmasi_password" 
                                            id="konfirmasi_password" 
                                            placeholder="Konfirmasi password" 
                                            class="w-full p-3 pr-12 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors <?= isset($errors['konfirmasi_password']) ? 'border-red-300 bg-red-50' : '' ?>" 
                                            required 
                                        />
                                        <button type="button" onclick="togglePassword('konfirmasi_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                            <i id="confirm-password-toggle" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php if (isset($errors['konfirmasi_password'])): ?>
                                    <small class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <?= $errors['konfirmasi_password'] ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <h4 class="text-sm font-semibold text-amber-800 mb-2">Persyaratan Password:</h4>
                                <ul class="text-xs text-amber-700 space-y-1">
                                    <li>• Minimal 8 karakter</li>
                                    <li>• Kombinasi huruf besar dan kecil</li>
                                    <li>• Minimal 1 angka</li>
                                    <li>• Hindari informasi pribadi</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="bg-purple-50 rounded-xl p-4 sm:p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-purple-500"></i>
                                Bukti Pembayaran
                            </h3>
                            
                            <div>
                                <label for="bukti_pembayaran" class="block text-sm font-semibold text-slate-700 mb-2">
                                    <i class="fas fa-file-upload mr-2 text-primary-500"></i>
                                    Upload Bukti Pembayaran
                                </label>
                                <div class="relative">
                                    <input 
                                        type="file" 
                                        name="bukti_pembayaran" 
                                        id="bukti_pembayaran" 
                                        accept="image/*,.pdf"
                                        class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" 
                                        required 
                                    />
                                </div>
                                <small class="text-slate-500 text-xs mt-1">
                                    Format yang didukung: JPG, PNG, PDF (Maksimal 5MB)
                                </small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-4 rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:ring-4 focus:ring-emerald-200 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        >
                            <i class="fas fa-user-plus mr-2"></i>
                            Daftar Sekarang
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-8">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-slate-500">atau</span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-slate-600 mb-4">Sudah memiliki akun?</p>
                        <a 
                            href="/sistem-informasi-pendaftaran/mahasiswa/login" 
                            class="inline-flex items-center px-6 py-3 border-2 border-primary-500 text-primary-600 font-semibold rounded-xl hover:bg-primary-50 transition-all duration-200 hover:shadow-md"
                        >
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk ke Akun
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-slate-500 text-sm animate-fade-in pb-8">
                <p>&copy; 2024 Portal Akademik. Semua hak dilindungi.</p>
                <div class="flex flex-wrap justify-center gap-2 sm:gap-4 mt-2">
                    <a href="#" class="hover:text-emerald-600 transition-colors">Bantuan</a>
                    <span class="hidden sm:inline">•</span>
                    <a href="#" class="hover:text-emerald-600 transition-colors">Panduan Pendaftaran</a>
                    <span class="hidden sm:inline">•</span>
                    <a href="#" class="hover:text-emerald-600 transition-colors">Kontak</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay - Improved centered positioning -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 text-center max-w-xs mx-auto">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600 mx-auto mb-4"></div>
            <p class="text-slate-600">Sedang memproses pendaftaran...</p>
        </div>
    </div>

    <script>
        // Password toggle functionality
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(inputId === 'password' ? 'password-toggle' : 'confirm-password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form validation
        document.getElementById('konfirmasi_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword && confirmPassword !== '') {
                this.setCustomValidity('Password tidak cocok');
                this.classList.add('border-red-300', 'bg-red-50');
            } else {
                this.setCustomValidity('');
                this.classList.remove('border-red-300', 'bg-red-50');
            }
        });

        // Prevent same program selection
        document.getElementById('prodi1').addEventListener('change', function() {
            const prodi2Select = document.getElementById('prodi2');
            const selectedValue = this.value;
            
            // Reset prodi 2 if same as prodi 1
            if (prodi2Select.value === selectedValue) {
                prodi2Select.value = '';
                alert('Pilihan program studi 1 dan 2 tidak boleh sama!');
            }
        });

        document.getElementById('prodi2').addEventListener('change', function() {
            const prodi1Select = document.getElementById('prodi1');
            const selectedValue = this.value;
            
            // Reset prodi 1 if same as prodi 2
            if (prodi1Select.value === selectedValue) {
                this.value = '';
                alert('Pilihan program studi 1 dan 2 tidak boleh sama!');
            }
        });

        // Progress bar animation based on form completion
        function updateProgress() {
            const requiredFields = document.querySelectorAll('input[required], select[required]');
            const filledFields = Array.from(requiredFields).filter(field => {
                if (field.type === 'file') {
                    return field.files.length > 0;
                }
                return field.value.trim() !== '';
            });
            const progress = (filledFields.length / requiredFields.length) * 100;
            
            document.getElementById('progress-bar').style.width = `${progress}%`;
        }

        // Add event listeners to all form fields
        document.querySelectorAll('input, select').forEach(field => {
            field.addEventListener('input', updateProgress);
            field.addEventListener('change', updateProgress);
        });

        // File upload validation
        document.getElementById('bukti_pembayaran').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar! Maksimal 5MB.');
                    this.value = '';
                    return;
                }
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung! Gunakan JPG, PNG, atau PDF.');
                    this.value = '';
                    return;
                }
            }
        });

        // Form submission with loading state
        document.getElementById('registration-form').addEventListener('submit', function(e) {
            const loadingOverlay = document.getElementById('loading-overlay');
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitButton.disabled = true;
            
            // Show loading overlay after a short delay
            setTimeout(() => {
                loadingOverlay.classList.remove('hidden');
            }, 500);
        });

        // Initialize progress bar on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
            
            // Add floating animation delay to background elements
            const floatingElements = document.querySelectorAll('.animate-float');
            floatingElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 2}s`;
            });
        });
    </script>
</body>

</html>