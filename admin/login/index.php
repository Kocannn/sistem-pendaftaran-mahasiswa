<?php
// Starting the session is all we need here
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../service/admin/login.php';

if (isset($_SESSION["admin_id"])) {
  header("Location: /sistem-informasi-pendaftaran/admin");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Portal Akademik</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            admin: {
              50: '#fdf2f8',
              100: '#fce7f3',
              200: '#fbcfe8',
              300: '#f9a8d4',
              400: '#f472b6',
              500: '#ec4899',
              600: '#db2777',
              700: '#be185d',
              800: '#9d174d',
              900: '#831843',
            },
            dark: {
              50: '#f8fafc',
              100: '#f1f5f9',
              200: '#e2e8f0',
              300: '#cbd5e1',
              400: '#94a3b8',
              500: '#64748b',
              600: '#475569',
              700: '#334155',
              800: '#1e293b',
              900: '#0f172a',
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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-gradient-to-br from-dark-50 via-admin-50 to-purple-100 relative font-inter">
  <!-- Background Decorations -->
  <div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-admin-200 to-purple-300 rounded-full opacity-20 animate-float"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-dark-200 to-admin-300 rounded-full opacity-20 animate-float" style="animation-delay: -3s;"></div>
    <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-gradient-to-br from-admin-300 to-pink-400 rounded-full opacity-30 animate-float" style="animation-delay: -1.5s;"></div>
    <div class="absolute top-1/4 right-1/4 w-24 h-24 bg-gradient-to-br from-dark-300 to-admin-400 rounded-full opacity-25 animate-pulse-slow"></div>
  </div>

  <!-- Main Container -->
  <div class="flex min-h-screen items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
      <!-- Logo/Header Section -->
      <div class="text-center animate-fade-in">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-r from-admin-600 to-admin-700 shadow-lg animate-float">
          <i class="fas fa-shield-alt text-white text-2xl"></i>
        </div>
        <h1 class="mt-6 text-3xl font-bold tracking-tight text-dark-800">Admin Portal</h1>
        <p class="mt-2 text-dark-600">Panel Administrasi Sistem</p>
      </div>

      <!-- Security Badge -->
      <div class="animate-slide-up">
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-3 shadow-lg border border-white/20">
          <div class="flex items-center justify-center space-x-2 text-sm">
            <i class="fas fa-lock text-admin-600"></i>
            <span class="text-dark-700 font-medium">Akses Terbatas - Administrator Only</span>
            <i class="fas fa-user-shield text-admin-600"></i>
          </div>
        </div>
      </div>

      <!-- Login Card -->
      <div class="overflow-hidden rounded-2xl bg-white/90 backdrop-blur-sm shadow-xl border border-white/20 animate-slide-up">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-admin-600 to-admin-700 px-6 py-6 text-center">
          <h2 class="text-2xl font-bold text-white flex items-center justify-center">
            <i class="fas fa-user-cog mr-3"></i>
            Administrator Login
          </h2>
          <p class="mt-2 text-admin-100">Masuk ke panel administrasi sistem</p>
        </div>

        <!-- Card Body -->
        <div class="px-6 py-8 sm:px-8">
          <!-- Error message display -->
          <?php if (isset($_SESSION['login_error'])): ?>
          <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-200">
            <div class="flex items-center">
              <i class="fas fa-exclamation-triangle mr-2"></i>
              <div><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
            </div>
          </div>
          <?php endif; ?>

          <form class="space-y-6" method="post" action="/sistem-informasi-pendaftaran/service/admin/login.php" id="loginForm">
            <!-- Username Field -->
            <div>
              <label for="name" class="block text-sm font-medium text-dark-700">
                <i class="fas fa-user-shield mr-2 text-admin-500"></i>
                Username Administrator
              </label>
              <div class="relative mt-1">
                <input
                  type="text"
                  id="name"
                  name="name"
                  required
                  class="block w-full rounded-lg border border-dark-300 bg-dark-50 px-4 py-3 pl-11 transition-all duration-200 focus:border-admin-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-admin-500/50"
                  placeholder="Masukkan username admin">
                <i class="fas fa-user-tie absolute left-4 top-1/2 -translate-y-1/2 transform text-dark-400"></i>
              </div>
            </div>

            <!-- Password Field -->
            <div>
              <label for="password" class="block text-sm font-medium text-dark-700">
                <i class="fas fa-key mr-2 text-admin-500"></i>
                Password
              </label>
              <div class="relative mt-1">
                <input
                  type="password"
                  id="password"
                  name="password"
                  required
                  class="block w-full rounded-lg border border-dark-300 bg-dark-50 px-4 py-3 pl-11 pr-10 transition-all duration-200 focus:border-admin-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-admin-500/50"
                  placeholder="Masukkan password admin">
                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 transform text-dark-400"></i>
                <button
                  type="button"
                  onclick="togglePassword()"
                  class="absolute right-3 top-1/2 -translate-y-1/2 transform rounded-full p-1 text-dark-400 hover:bg-dark-100 hover:text-dark-600 focus:outline-none focus:ring-2 focus:ring-admin-500/50">
                  <i id="password-toggle" class="fas fa-eye"></i>
                </button>
              </div>
            </div>

            <!-- Security Features -->
            <div class="space-y-4">
              <!-- Remember Me & Session Info -->
              <div class="flex flex-wrap items-center justify-between gap-2">
                <label class="flex cursor-pointer items-center space-x-2">
                  <input type="checkbox" class="h-4 w-4 rounded border-dark-300 text-admin-600 focus:ring-admin-500">
                  <span class="text-sm text-dark-600">Sesi aman</span>
                </label>
                <div class="flex items-center text-xs text-dark-500">
                  <i class="fas fa-clock mr-1"></i>
                  <span>Sesi berakhir dalam 30 menit</span>
                </div>
              </div>

              <!-- Security Notice -->
              <div class="rounded-lg bg-amber-50 p-3 border border-amber-200">
                <div class="flex items-start space-x-2">
                  <i class="fas fa-shield-alt text-amber-600 mt-0.5"></i>
                  <div class="text-xs text-amber-800">
                    <p class="font-medium mb-1">Keamanan Tinggi</p>
                    <p>Aktivitas login akan dicatat dan dimonitor untuk keamanan sistem.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Login Button -->
            <button
              type="submit"
              class="group relative flex w-full justify-center rounded-lg border border-transparent bg-gradient-to-r from-admin-600 to-admin-700 px-4 py-3 text-sm font-medium text-white shadow-sm hover:from-admin-700 hover:to-admin-800 focus:outline-none focus:ring-2 focus:ring-admin-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i id="login-icon" class="fas fa-sign-in-alt text-admin-300 group-hover:text-admin-200"></i>
              </span>
              <span id="login-text">Masuk ke Admin Panel</span>
              <span id="loading-text" class="hidden">
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Memverifikasi...
              </span>
            </button>
          </form>

          <!-- Admin Info Section -->
          <div class="mt-6 pt-6 border-t border-dark-200">
            <div class="grid grid-cols-2 gap-4 text-center">
              <div class="bg-dark-50 rounded-lg p-3">
                <i class="fas fa-users text-admin-600 text-lg mb-1"></i>
                <p class="text-xs text-dark-600 font-medium">Kelola User</p>
              </div>
              <div class="bg-dark-50 rounded-lg p-3">
                <i class="fas fa-chart-bar text-admin-600 text-lg mb-1"></i>
                <p class="text-xs text-dark-600 font-medium">Analytics</p>
              </div>
            </div>
          </div>

          <!-- Support Section -->
          <div class="mt-6 pt-4 border-t border-dark-200">
            <div class="text-center">
              <p class="text-sm text-dark-500 mb-2">Butuh bantuan teknis?</p>
              <div class="flex justify-center space-x-4 text-xs">
                <a href="#" class="text-admin-600 hover:text-admin-700 font-medium transition-colors flex items-center">
                  <i class="fas fa-headset mr-1"></i>
                  IT Support
                </a>
                <a href="#" class="text-admin-600 hover:text-admin-700 font-medium transition-colors flex items-center">
                  <i class="fas fa-book mr-1"></i>
                  Dokumentasi
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center text-xs text-dark-500 animate-fade-in">
        <p>&copy; 2024 Admin Portal. Semua hak dilindungi.</p>
        <div class="mt-2 flex flex-wrap justify-center gap-2 sm:gap-4">
          <span class="flex items-center">
            <i class="fas fa-shield-check mr-1 text-admin-600"></i>
            Sistem Aman
          </span>
          <span class="hidden sm:inline">•</span>
          <span class="flex items-center">
            <i class="fas fa-lock mr-1 text-admin-600"></i>
            SSL Encrypted
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Loading Overlay -->
  <div id="loading-overlay" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity">
    <div class="rounded-xl bg-white p-6 shadow-xl">
      <div class="mx-auto mb-4 h-12 w-12 animate-spin rounded-full border-b-2 border-admin-600"></div>
      <p class="text-center text-dark-600">Memverifikasi kredensial admin...</p>
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
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const loadingOverlay = document.getElementById('loading-overlay');
      const submitButton = this.querySelector('button[type="submit"]');
      const loginText = document.getElementById('login-text');
      const loadingText = document.getElementById('loading-text');
      const loginIcon = document.getElementById('login-icon');

      // Show loading state
      loginText.classList.add('hidden');
      loadingText.classList.remove('hidden');
      loginIcon.classList.add('fa-spin');
      submitButton.disabled = true;
      submitButton.classList.add('opacity-75');

      // Show loading overlay
      setTimeout(() => {
        loadingOverlay.classList.remove('hidden');
      }, 300);
    });

    // Enhanced input focus animations
    document.querySelectorAll('input').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.classList.add('scale-[1.02]', 'transition-transform', 'duration-200');
        
        // Add glow effect
        this.style.boxShadow = '0 0 0 3px rgba(236, 72, 153, 0.1)';
      });

      input.addEventListener('blur', function() {
        this.parentElement.classList.remove('scale-[1.02]');
        this.style.boxShadow = '';
      });
    });

    // Security indicator animation
    function updateSecurityIndicator() {
      const indicators = document.querySelectorAll('.security-indicator');
      indicators.forEach((indicator, index) => {
        setTimeout(() => {
          indicator.classList.add('animate-pulse');
        }, index * 200);
      });
    }

    // Initialize security animations
    document.addEventListener('DOMContentLoaded', function() {
      updateSecurityIndicator();
      
      // Add floating animation delays
      const floatingElements = document.querySelectorAll('.animate-float');
      floatingElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 2}s`;
      });
    });

    // Session timeout warning (demo)
    let sessionWarningShown = false;
    setTimeout(() => {
      if (!sessionWarningShown) {
        const timeElement = document.querySelector('.text-dark-500 span');
        if (timeElement) {
          timeElement.textContent = 'Sesi berakhir dalam 25 menit';
          timeElement.classList.add('text-amber-600', 'font-medium');
        }
        sessionWarningShown = true;
      }
    }, 5000);
  </script>
</body>

</html>