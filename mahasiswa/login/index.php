<?php
session_start();
if (isset($_SESSION['auth'])) {
    header("Location: /sistem-informasi-pendaftaran/mahasiswa");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Mahasiswa - Portal Akademik</title>
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
        /* Fix scrolling issue */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden;
        }
        
        /* Ensure content can scroll */
        body {
            overflow-y: auto;
            position: relative;
        }
        
        /* Ensure background decorations don't affect scrolling */
        .background-decorations {
            position: fixed;
            z-index: 0;
        }
        
        /* Ensure main container allows scrolling */
        .main-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            width: 100%;
            padding: 2rem 1rem;
        }
        
        /* Additional responsive adjustments */
        @media (max-height: 800px) {
            .content-wrapper {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <!-- Background Decorations -->
    <div class="background-decorations absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-primary-200 to-primary-300 rounded-full opacity-20 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-indigo-200 to-purple-300 rounded-full opacity-20 animate-float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-gradient-to-br from-blue-200 to-cyan-300 rounded-full opacity-30 animate-float" style="animation-delay: -1.5s;"></div>
    </div>

    <!-- Main Container -->
    <div class="main-container flex items-center justify-center">
        <div class="content-wrapper w-full max-w-md py-4">
            <!-- Logo/Header Section -->
            <div class="text-center mb-8 animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl shadow-lg mb-4 animate-float">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Portal Akademik</h1>
                <p class="text-slate-600">Sistem Informasi Pendaftaran Mahasiswa</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 text-center">
                    <h2 class="text-2xl font-bold text-white mb-2">Selamat Datang</h2>
                    <p class="text-primary-100">Masuk ke akun mahasiswa Anda</p>
                </div>

                <!-- Card Body -->
                <div class="p-8">
                    <form class="space-y-6" method="post" action="../../service/mahasiswa/login.php">
                        <!-- NISN Field -->
                        <div class="space-y-2">
                            <label for="nisn" class="block text-sm font-semibold text-slate-700">
                                <i class="fas fa-id-card mr-2 text-primary-500"></i>
                                NISN
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="nisn" 
                                    name="nisn"
                                    class="w-full p-4 pl-12 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-slate-50 focus:bg-white" 
                                    placeholder="Masukkan NISN Anda"
                                    required
                                />
                                <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-semibold text-slate-700">
                                <i class="fas fa-lock mr-2 text-primary-500"></i>
                                Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password"
                                    class="w-full p-4 pl-12 pr-12 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-slate-50 focus:bg-white" 
                                    placeholder="Masukkan password Anda"
                                    required
                                />
                                <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                                <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <i id="password-toggle" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500">
                                <span class="text-slate-600">Ingat saya</span>
                            </label>
                            <a href="#" class="text-primary-600 hover:text-primary-700 font-medium hover:underline transition-colors">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold py-4 rounded-xl hover:from-primary-600 hover:to-primary-700 focus:ring-4 focus:ring-primary-200 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        >
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk ke Dashboard
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

                    <!-- Action Links -->
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="text-slate-600 mb-2">Belum memiliki akun?</p>
                            <a 
                                href="/sistem-informasi-pendaftaran/mahasiswa/register" 
                                class="inline-flex items-center justify-center w-full px-6 py-3 border-2 border-primary-500 text-primary-600 font-semibold rounded-xl hover:bg-primary-50 transition-all duration-200 hover:shadow-md"
                            >
                                <i class="fas fa-user-plus mr-2"></i>
                                Daftar Sekarang
                            </a>
                        </div>
                        
                        <!-- Admin Link -->
                        <div class="text-center">
                            <p class="text-slate-600 mb-2">Masuk sebagai Admin?</p>
                            <a 
                                href="/sistem-informasi-pendaftaran/admin/login" 
                                class="inline-flex items-center justify-center w-full px-6 py-3 border-2 border-gray-500 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200 hover:shadow-md"
                            >
                                <i class="fas fa-user-shield mr-2"></i>
                                Login Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-slate-500 text-sm animate-fade-in">
                <p>&copy; 2024 Portal Akademik. Semua hak dilindungi.</p>
                <div class="flex justify-center space-x-4 mt-2">
                    <a href="#" class="hover:text-primary-600 transition-colors">Bantuan</a>
                    <span>•</span>
                    <a href="#" class="hover:text-primary-600 transition-colors">Kebijakan Privasi</a>
                    <span>•</span>
                    <a href="#" class="hover:text-primary-600 transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay (Optional) -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto mb-4"></div>
            <p class="text-slate-600">Sedang memproses login...</p>
        </div>
    </div>

    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle');
            
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

        // Form submission with loading state
        document.querySelector('form').addEventListener('submit', function(e) {
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

        // Input focus animations
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });

        // Add floating animation to background elements
        document.addEventListener('DOMContentLoaded', function() {
            const floatingElements = document.querySelectorAll('.animate-float');
            floatingElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 2}s`;
            });
        });
    </script>
</body>

</html>