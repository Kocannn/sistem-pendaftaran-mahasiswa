<?php
// Start session to check authentication status
session_start();

// Redirect logged-in users to their dashboard
if (isset($_SESSION['auth'])) {
    header("Location: /sistem-informasi-pendaftaran/mahasiswa");
    exit();
}

// Redirect logged-in admins to admin dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: /sistem-informasi-pendaftaran/admin");
    exit();
}

// Connect to database to fetch program study data
require_once __DIR__ . '/service/db.php';

// Query to get program study data with statistics
$query = "SELECT 
    ps.kode,
    ps.jenjang, 
    ps.nama, 
    ps.daya_tampung,
    COUNT(DISTINCT CASE WHEN m.prodi_1_kode = ps.kode THEN m.nisn END) AS pilihan_1_count,
    COUNT(DISTINCT CASE WHEN m.prodi_2_kode = ps.kode THEN m.nisn END) AS pilihan_2_count
FROM 
    program_studi ps
LEFT JOIN 
    mahasiswa m ON ps.kode = m.prodi_1_kode OR ps.kode = m.prodi_2_kode
GROUP BY 
    ps.kode
ORDER BY 
    ps.jenjang, ps.nama";

$programStudi = [];
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $totalPendaftar = $row['pilihan_1_count'] + $row['pilihan_2_count'];
        $sisaKuota = max(0, $row['daya_tampung'] - $totalPendaftar);
        $persentaseTerisi = ($row['daya_tampung'] > 0) ? min(100, ($totalPendaftar / $row['daya_tampung']) * 100) : 0;
        
        $row['total_pendaftar'] = $totalPendaftar;
        $row['sisa_kuota'] = $sisaKuota;
        $row['persentase_terisi'] = $persentaseTerisi;
        
        $programStudi[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIPMB - Portal Penerimaan Mahasiswa Baru</title>
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
            'fade-in': 'fadeIn 0.6s ease-in-out',
            'slide-up': 'slideUp 0.8s ease-out',
            'float': 'float 6s ease-in-out infinite',
            'pulse-slow': 'pulse 3s ease-in-out infinite',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            slideUp: {
              '0%': { transform: 'translateY(30px)', opacity: '0' },
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
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50">
  <!-- Navbar -->
  <nav class="bg-white/90 backdrop-blur-md shadow-lg sticky top-0 z-50" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-graduation-cap text-white text-lg"></i>
            </div>
            <div>
              <a href="#" class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">SIPMB</a>
              <p class="text-xs text-slate-500 -mt-1">Portal Akademik</p>
            </div>
          </div>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-8 items-center">
          <a href="#beranda" class="text-slate-700 font-medium hover:text-primary-600 transition-colors duration-200 relative group">
            Beranda
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-200 group-hover:w-full"></span>
          </a>
          <a href="#prodi" class="text-slate-700 font-medium hover:text-primary-600 transition-colors duration-200 relative group">
            Program Studi
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-200 group-hover:w-full"></span>
          </a>
          <a href="#jadwal" class="text-slate-700 font-medium hover:text-primary-600 transition-colors duration-200 relative group">
            Jadwal Pendaftaran
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-200 group-hover:w-full"></span>
          </a>
          <a href="#cara-pendaftaran" class="text-slate-700 font-medium hover:text-primary-600 transition-colors duration-200 relative group">
            Cara Pendaftaran
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-200 group-hover:w-full"></span>
          </a>
          <a href="#faq" class="text-slate-700 font-medium hover:text-primary-600 transition-colors duration-200 relative group">
            FAQ
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-200 group-hover:w-full"></span>
          </a>
        </div>

        <!-- Login/Daftar -->
        <div class="hidden md:flex items-center space-x-3">
          <a href="/sistem-informasi-pendaftaran/mahasiswa/login" class="px-4 py-2 border-2 border-primary-600 text-primary-600 rounded-xl font-medium hover:bg-primary-50 transition-all duration-200">
            <i class="fas fa-sign-in-alt mr-2"></i>Login
          </a>
          <a href="/sistem-informasi-pendaftaran/mahasiswa/register" class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-medium hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-lg hover:shadow-xl">
            <i class="fas fa-user-plus mr-2"></i>Daftar
          </a>
        </div>

        <!-- Hamburger menu (mobile) -->
        <div class="flex items-center md:hidden">
          <button @click="open = !open" type="button" class="text-slate-700 hover:text-primary-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="md:hidden bg-white border-t border-slate-200">
      <div class="px-4 py-4 space-y-3">
        <a href="#beranda" class="block text-slate-700 hover:text-primary-600 py-2">Beranda</a>
        <a href="#prodi" class="block text-slate-700 hover:text-primary-600 py-2">Program Studi</a>
        <a href="#jadwal" class="block text-slate-700 hover:text-primary-600 py-2">Jadwal Pendaftaran</a>
        <a href="#cara-pendaftaran" class="block text-slate-700 hover:text-primary-600 py-2">Cara Pendaftaran</a>
        <a href="#faq" class="block text-slate-700 hover:text-primary-600 py-2">FAQ</a>
        <div class="pt-4 space-y-2">
          <a href="/sistem-informasi-pendaftaran/mahasiswa/login" class="block text-center border-2 border-primary-600 text-primary-600 py-2 rounded-xl font-medium">Login</a>
          <a href="/sistem-informasi-pendaftaran/mahasiswa/register" class="block text-center bg-gradient-to-r from-primary-500 to-primary-600 text-white py-2 rounded-xl font-medium">Daftar</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="relative overflow-hidden py-20 lg:py-32" id="beranda">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-primary-200 to-primary-300 rounded-full opacity-20 animate-float"></div>
      <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-blue-200 to-indigo-300 rounded-full opacity-20 animate-float" style="animation-delay: -3s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center justify-between">
      <!-- Hero Text -->
      <div class="lg:w-1/2 mb-12 lg:mb-0 animate-fade-in">
        <div class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-800 rounded-full text-sm font-medium mb-6">
          <i class="fas fa-star mr-2"></i>
          Pendaftaran Mahasiswa Baru 2025
        </div>
        <h1 class="text-4xl lg:text-6xl font-bold text-slate-800 leading-tight mb-6">
          Wujudkan Impian
          <span class="bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">Akademikmu</span>
        </h1>
        <p class="text-xl text-slate-600 mb-8 leading-relaxed">
          Bergabunglah dengan ribuan mahasiswa yang telah memilih masa depan cerah bersama kami. Pendaftaran online yang mudah, cepat, dan dapat diakses dari mana saja.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="/sistem-informasi-pendaftaran/mahasiswa/register" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            <i class="fas fa-rocket mr-2"></i>
            Daftar Sekarang
          </a>
          <a href="#cara-pendaftaran" class="inline-flex items-center justify-center px-8 py-4 border-2 border-slate-300 text-slate-700 rounded-xl font-semibold hover:border-primary-300 hover:text-primary-600 transition-all duration-200">
            <i class="fas fa-info-circle mr-2"></i>
            Pelajari Lebih Lanjut
          </a>
        </div>
      </div>

      <!-- Hero Image -->
      <div class="lg:w-1/2 animate-slide-up">
        <div class="relative">
          <img src="https://plus.unsplash.com/premium_photo-1713296256430-3c828fca19ae?w=900&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NzF8fHVuaXZlcnNpdHl8ZW58MHx8MHx8fDA%3D" alt="Ilustrasi Mahasiswa" class="rounded-2xl shadow-2xl transform transition duration-500 hover:scale-105" />
          <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center animate-pulse-slow">
            <i class="fas fa-award text-white text-2xl"></i>
          </div>
          <div class="absolute -top-6 -right-6 w-20 h-20 bg-gradient-to-r from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center animate-float">
            <i class="fas fa-trophy text-white text-xl"></i>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="text-center">
          <div class="text-4xl font-bold text-primary-600 mb-2">15+</div>
          <div class="text-slate-600">Program Studi</div>
        </div>
        <div class="text-center">
          <div class="text-4xl font-bold text-primary-600 mb-2">5000+</div>
          <div class="text-slate-600">Mahasiswa Aktif</div>
        </div>
        <div class="text-center">
          <div class="text-4xl font-bold text-primary-600 mb-2">95%</div>
          <div class="text-slate-600">Tingkat Kelulusan</div>
        </div>
        <div class="text-center">
          <div class="text-4xl font-bold text-primary-600 mb-2">100+</div>
          <div class="text-slate-600">Dosen Berkualitas</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Program Studi Section - MODIFIED TO TABLE -->
  <section class="py-20 bg-gradient-to-br from-slate-50 to-blue-50" id="prodi">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-slate-800 mb-4">Program Studi Tersedia</h2>
        <p class="text-xl text-slate-600 max-w-3xl mx-auto">Pilih program studi yang sesuai dengan minat dan bakatmu. Setiap program dirancang untuk menghasilkan lulusan yang kompeten dan siap kerja.</p>
      </div>

      <!-- Table Container with shadow and rounded corners -->
      <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-12">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead>
              <tr class="bg-gradient-to-r from-primary-600 to-primary-700">
                <th class="px-6 py-4 text-left text-sm font-semibold text-white">Program Studi</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-white">Jenjang</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-white">Daya Tampung</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
              <?php if (empty($programStudi)): ?>
                <tr>
                  <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                    <div class="flex flex-col items-center">
                      <i class="fas fa-database text-slate-400 text-2xl mb-2"></i>
                      Tidak ada data program studi tersedia
                    </div>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($programStudi as $index => $prodi): ?>
                  <tr class="<?= $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' ?> hover:bg-blue-50 transition-colors duration-150">
                    <td class="px-6 py-4">
                      <div class="flex items-center">
                        <!-- Icon based on program type -->
                        <?php 
                        $icons = [
                          'S1' => '<i class="fas fa-laptop-code text-blue-500 bg-blue-100 p-2 rounded-lg"></i>',
                          'D3' => '<i class="fas fa-code text-emerald-500 bg-emerald-100 p-2 rounded-lg"></i>',
                          'D4' => '<i class="fas fa-chart-line text-purple-500 bg-purple-100 p-2 rounded-lg"></i>',
                          'S2' => '<i class="fas fa-microscope text-amber-500 bg-amber-100 p-2 rounded-lg"></i>',
                        ];
                        $icon = $icons[$prodi['jenjang']] ?? '<i class="fas fa-university text-gray-500 bg-gray-100 p-2 rounded-lg"></i>';
                        echo '<div class="flex-shrink-0 mr-3">' . $icon . '</div>';
                        ?>
                        <div>
                          <p class="font-medium text-slate-900"><?= htmlspecialchars($prodi['nama']) ?></p>
                          <p class="text-xs text-slate-500">Kode: <?= htmlspecialchars($prodi['kode']) ?></p>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        <?php 
                        switch($prodi['jenjang']) {
                          case 'S1': echo 'bg-blue-100 text-blue-800'; break;
                          case 'D3': echo 'bg-emerald-100 text-emerald-800'; break;
                          case 'D4': echo 'bg-purple-100 text-purple-800'; break;
                          case 'S2': echo 'bg-amber-100 text-amber-800'; break;
                          default: echo 'bg-gray-100 text-gray-800';
                        }
                        ?>">
                        <?= htmlspecialchars($prodi['jenjang']) ?>
                      </span>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-slate-800"><?= $prodi['daya_tampung'] ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="py-20" id="faq">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-slate-800 mb-4">Frequently Asked Questions</h2>
        <p class="text-xl text-slate-600 max-w-3xl mx-auto">Temukan jawaban atas pertanyaan umum seputar pendaftaran mahasiswa baru.</p>
      </div>

      <div class="space-y-4">
        <!-- FAQ Item -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-800">Apa syarat untuk mendaftar?</h3>
            <button class="text-primary-600 hover:text-primary-700 focus:outline-none" aria-expanded="false">
              <i class="fas fa-plus fa-sm"></i>
            </button>
          </div>
          <div class="px-6 py-4 border-t border-slate-200 hidden">
            <p class="text-slate-600">Syarat untuk mendaftar antara lain memiliki ijazah pendidikan sebelumnya, mengisi formulir pendaftaran, dan membayar biaya pendaftaran.</p>
          </div>
        </div>

        <!-- FAQ Item -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-800">Bagaimana cara mengisi formulir pendaftaran?</h3>
            <button class="text-primary-600 hover:text-primary-700 focus:outline-none" aria-expanded="false">
              <i class="fas fa-plus fa-sm"></i>
            </button>
          </div>
          <div class="px-6 py-4 border-t border-slate-200 hidden">
            <p class="text-slate-600">Formulir pendaftaran dapat diisi secara online melalui portal ini. Pastikan Anda mengisi data dengan benar dan lengkap.</p>
          </div>
        </div>

        <!-- FAQ Item -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-800">Kapan jadwal pendaftaran dibuka?</h3>
            <button class="text-primary-600 hover:text-primary-700 focus:outline-none" aria-expanded="false">
              <i class="fas fa-plus fa-sm"></i>
            </button>
          </div>
          <div class="px-6 py-4 border-t border-slate-200 hidden">
            <p class="text-slate-600">Jadwal pendaftaran dibuka mulai tanggal 1 Januari hingga 31 Maret setiap tahunnya. Pastikan untuk mendaftar dalam rentang waktu tersebut.</p>
          </div>
        </div>

        <!-- FAQ Item -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-800">Apa itu SIPMB?</h3>
            <button class="text-primary-600 hover:text-primary-700 focus:outline-none" aria-expanded="false">
              <i class="fas fa-plus fa-sm"></i>
            </button>
          </div>
          <div class="px-6 py-4 border-t border-slate-200 hidden">
            <p class="text-slate-600">SIPMB adalah Sistem Informasi Pendaftaran Mahasiswa Baru, sebuah platform yang memudahkan calon mahasiswa untuk mendaftar secara online.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-white py-8 mt-16">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex flex-col md:flex-row md:justify-between">
        <div class="mb-8 md:mb-0">
          <a href="#" class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">SIPMB</a>
          <p class="text-slate-600 text-sm mt-2">Portal Penerimaan Mahasiswa Baru</p>
        </div>
        <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
          <div>
            <h4 class="text-sm font-semibold text-slate-800 mb-4">Navigasi</h4>
            <ul class="text-slate-600 text-sm space-y-2">
              <li><a href="#beranda" class="hover:text-primary-600 transition-colors duration-200">Beranda</a></li>
              <li><a href="#prodi" class="hover:text-primary-600 transition-colors duration-200">Program Studi</a></li>
              <li><a href="#jadwal" class="hover:text-primary-600 transition-colors duration-200">Jadwal Pendaftaran</a></li>
              <li><a href="#cara-pendaftaran" class="hover:text-primary-600 transition-colors duration-200">Cara Pendaftaran</a></li>
              <li><a href="#faq" class="hover:text-primary-600 transition-colors duration-200">FAQ</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-slate-800 mb-4">Informasi</h4>
            <ul class="text-slate-600 text-sm space-y-2">
              <li><a href="#" class="hover:text-primary-600 transition-colors duration-200">Tentang Kami</a></li>
              <li><a href="#" class="hover:text-primary-600 transition-colors duration-200">Kontak</a></li>
              <li><a href="#" class="hover:text-primary-600 transition-colors duration-200">Kebijakan Privasi</a></li>
              <li><a href="#" class="hover:text-primary-600 transition-colors duration-200">Syarat dan Ketentuan</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-slate-800 mb-4">Akademik</h4>
            <ul class="text-slate-600 text-sm space-y-2">
              <li><a href="#" class="hover:text-primary-600 transition-colors duration-200">Kalender Akademik</a></li>
              <li><a href="#" class="hover:text-primary-600 transition-colors duration-200">Berita Acara</a></li>
              <li><a href="#" class="hover:text-primary-600 transition-colors duration-200">Pengumuman</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-slate-800 mb-4">Follow Us</h4>
            <div class="flex space-x-4">
              <a href="#" class="text-slate-600 hover:text-primary-600 transition-colors duration-200">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="text-slate-600 hover:text-primary-600 transition-colors duration-200">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="text-slate-600 hover:text-primary-600 transition-colors duration-200">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#" class="text-slate-600 hover:text-primary-600 transition-colors duration-200">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="border-t border-slate-200 mt-8 pt-6 text-center">
        <p class="text-slate-600 text-sm">&copy; 2023 SIPMB. All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>

</html>