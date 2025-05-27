<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIPMB - Sistem Informasi Penerimaan Mahasiswa Baru</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'navy': {
              50: '#f0f4ff',
              100: '#e0e9ff',
              200: '#c7d6fe',
              300: '#a5b8fc',
              400: '#8b93f8',
              500: '#7c6df2',
              600: '#6d4de6',
              700: '#5d3dcb',
              800: '#4c32a4',
              900: '#1e1b4b',
              950: '#0f0c2e'
            }
          }
        }
      }
    }
  </script>
  <style>
    .glass-effect {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .floating-animation {
      animation: float 6s ease-in-out infinite;
    }
    
    .pulse-animation {
      animation: pulse 4s ease-in-out infinite;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    
    @keyframes pulse {
      0%, 100% { opacity: 0.3; }
      50% { opacity: 0.7; }
    }
    
    .gradient-text {
      background: linear-gradient(135deg, #3b82f6, #1e40af);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .nav-blur {
      backdrop-filter: blur(20px);
      background: rgba(15, 12, 46, 0.95);
    }

    .hero-pattern {
      background-image: 
        radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(30, 27, 75, 0.1) 0%, transparent 50%);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-navy-950 via-navy-900 to-blue-900 text-white">
  <!-- Background decorative elements -->
  <div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl floating-animation"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-navy-600/10 rounded-full blur-3xl floating-animation" style="animation-delay: -3s;"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-400/5 rounded-full blur-2xl pulse-animation"></div>
  </div>

  <!-- Navbar -->
  <nav class="fixed w-full z-50 nav-blur border-b border-white/10" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-navy-600 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
            <span class="text-2xl font-bold gradient-text">SIPMB</span>
          </div>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-8 items-center">
          <a href="#beranda" class="text-blue-100 hover:text-white font-medium transition-colors duration-300 relative group">
            Beranda
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-400 transition-all duration-300 group-hover:w-full"></span>
          </a>
          <a href="#jadwal" class="text-blue-100 hover:text-white font-medium transition-colors duration-300 relative group">
            Jadwal Pendaftaran
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-400 transition-all duration-300 group-hover:w-full"></span>
          </a>
          <a href="#cara-pendaftaran" class="text-blue-100 hover:text-white font-medium transition-colors duration-300 relative group">
            Cara Pendaftaran
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-400 transition-all duration-300 group-hover:w-full"></span>
          </a>
          <a href="#faq" class="text-blue-100 hover:text-white font-medium transition-colors duration-300 relative group">
            FAQ
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-400 transition-all duration-300 group-hover:w-full"></span>
          </a>
        </div>

        <!-- Login/Daftar -->
        <div class="hidden md:flex items-center space-x-3">
          <a href="login.html" class="px-6 py-2 border border-blue-400 text-blue-300 rounded-xl hover:bg-blue-400 hover:text-white transition-all duration-300 font-medium">
            Login
          </a>
          <a href="register.html" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-navy-700 text-white rounded-xl hover:from-blue-700 hover:to-navy-800 transition-all duration-300 font-medium shadow-lg">
            Daftar
          </a>
        </div>

        <!-- Hamburger menu (mobile) -->
        <div class="flex items-center md:hidden">
          <button @click="open = !open" type="button" class="text-blue-100 hover:text-white focus:outline-none transition-colors duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="md:hidden glass-effect border-t border-white/10">
      <div class="px-4 py-4 space-y-3">
        <a href="#beranda" class="block text-blue-100 hover:text-white transition-colors duration-300">Beranda</a>
        <a href="#jadwal" class="block text-blue-100 hover:text-white transition-colors duration-300">Jadwal Pendaftaran</a>
        <a href="#cara-pendaftaran" class="block text-blue-100 hover:text-white transition-colors duration-300">Cara Pendaftaran</a>
        <a href="#faq" class="block text-blue-100 hover:text-white transition-colors duration-300">FAQ</a>
        <div class="pt-3 space-y-2">
          <a href="login.html" class="block text-center border border-blue-400 text-blue-300 py-2 rounded-xl hover:bg-blue-400 hover:text-white transition-all duration-300">Login</a>
          <a href="register.html" class="block text-center bg-gradient-to-r from-blue-600 to-navy-700 text-white py-2 rounded-xl hover:from-blue-700 hover:to-navy-800 transition-all duration-300">Daftar</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="relative pt-24 pb-20 hero-pattern" id="beranda">
    <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center justify-between">
      <!-- Teks Hero -->
      <div class="lg:w-1/2 mb-12 lg:mb-0">
        <div class="space-y-6">
          <div class="inline-flex items-center px-4 py-2 glass-effect rounded-full text-sm text-blue-200 mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Pendaftaran Mahasiswa Baru 2025
          </div>
          
          <h1 class="text-5xl lg:text-6xl font-bold leading-tight">
            <span class="text-white">Wujudkan</span><br>
            <span class="gradient-text">Impian Kuliah</span><br>
            <span class="text-white">di Universitas</span><br>
            <span class="text-blue-300">Pilihanmu</span>
          </h1>
          
          <p class="text-xl text-blue-100 leading-relaxed max-w-lg">
            Pendaftaran Mahasiswa Baru kini lebih mudah, cepat, dan dapat dilakukan secara online dari mana saja dengan sistem yang terintegrasi.
          </p>
          
          <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <a href="register.html" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-navy-700 text-white rounded-xl font-semibold shadow-lg hover:from-blue-700 hover:to-navy-800 transform hover:scale-105 transition-all duration-300">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
              </svg>
              Daftar Sekarang
            </a>
            <a href="#cara-pendaftaran" class="inline-flex items-center justify-center px-8 py-4 glass-effect text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Pelajari Cara Daftar
            </a>
          </div>
        </div>
      </div>

      <!-- Ilustrasi Hero -->
      <div class="lg:w-1/2 relative">
        <div class="relative">
          <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-navy-600/20 rounded-2xl blur-2xl transform rotate-6"></div>
          <img src="https://plus.unsplash.com/premium_photo-1713296256430-3c828fca19ae?w=900&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NzF8fHVuaXZlcnNpdHl8ZW58MHx8MHx8fDA%3D" alt="Ilustrasi Mahasiswa" class="relative rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-500" />
        </div>
        
        <!-- Floating Stats -->
        <div class="absolute -top-6 -left-6 glass-effect rounded-xl p-4 floating-animation">
          <div class="text-2xl font-bold text-white">50K+</div>
          <div class="text-sm text-blue-200">Mahasiswa Terdaftar</div>
        </div>
        
        <div class="absolute -bottom-6 -right-6 glass-effect rounded-xl p-4 floating-animation" style="animation-delay: -2s;">
          <div class="text-2xl font-bold text-white">95%</div>
          <div class="text-sm text-blue-200">Tingkat Kelulusan</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Cara Pendaftaran Section -->
  <section class="py-20 px-6 relative" x-data="{ tab: 'snbp' }" id="cara-pendaftaran">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-white mb-4">Cara Pendaftaran</h2>
        <p class="text-xl text-blue-200 max-w-2xl mx-auto">Pilih jalur pendaftaran yang sesuai dengan kondisi dan kemampuan Anda</p>
      </div>

      <!-- Tab Navigation -->
      <div class="flex justify-center mb-12">
        <div class="glass-effect rounded-2xl p-2 inline-flex space-x-2">
          <button @click="tab = 'snbp'" :class="tab === 'snbp' ? 'bg-gradient-to-r from-blue-600 to-navy-700 text-white shadow-lg' : 'text-blue-200 hover:text-white'" class="px-6 py-3 rounded-xl font-semibold transition-all duration-300">
            SNBP
          </button>
          <button @click="tab = 'snbt'" :class="tab === 'snbt' ? 'bg-gradient-to-r from-blue-600 to-navy-700 text-white shadow-lg' : 'text-blue-200 hover:text-white'" class="px-6 py-3 rounded-xl font-semibold transition-all duration-300">
            SNBT
          </button>
          <button @click="tab = 'mandiri'" :class="tab === 'mandiri' ? 'bg-gradient-to-r from-blue-600 to-navy-700 text-white shadow-lg' : 'text-blue-200 hover:text-white'" class="px-6 py-3 rounded-xl font-semibold transition-all duration-300">
            Mandiri
          </button>
        </div>
      </div>

      <!-- SNBP Steps -->
      <div x-show="tab === 'snbp'" x-transition class="grid md:grid-cols-2 gap-6">
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">1</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Login ke Portal SNPMB</h3>
              <p class="text-blue-200 leading-relaxed">Gunakan akun belajar.id dari sekolah untuk login ke portal SNPMB dan akses sistem pendaftaran.</p>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">2</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Pengisian Data</h3>
              <p class="text-blue-200 leading-relaxed">Siswa mengisi data diri lengkap dan prestasi yang dimiliki di sistem SNPMB dengan teliti.</p>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">3</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Pemilihan Prodi</h3>
              <p class="text-blue-200 leading-relaxed">Pilih maksimal dua program studi dari PTN tujuan sesuai dengan minat dan kemampuan.</p>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">4</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Finalisasi</h3>
              <p class="text-blue-200 leading-relaxed">Finalisasi pendaftaran dan cetak bukti pendaftaran sebagai tanda telah terdaftar.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- SNBT Steps -->
      <div x-show="tab === 'snbt'" x-transition class="grid md:grid-cols-2 gap-6">
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">1</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Registrasi Akun SNPMB</h3>
              <p class="text-blue-200 leading-relaxed">Buat akun di portal SNPMB untuk mengikuti ujian SNBT dan lengkapi data diri.</p>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">2</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Pembayaran UTBK</h3>
              <p class="text-blue-200 leading-relaxed">Lakukan pembayaran biaya UTBK melalui bank yang ditunjuk sesuai instruksi.</p>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">3</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Cetak Kartu Ujian</h3>
              <p class="text-blue-200 leading-relaxed">Cetak kartu ujian dan hadir di lokasi UTBK sesuai jadwal yang telah ditentukan.</p>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">4</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Pengisian Pilihan Prodi</h3>
              <p class="text-blue-200 leading-relaxed">Setelah UTBK, pilih jurusan dan universitas tujuan di portal SNPMB.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Mandiri Steps -->
      <div x-show="tab === 'mandiri'" x-transition class="grid md:grid-cols-2 gap-6">
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">1</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Buka Website Universitas</h3>
              <p class="text-blue-200 leading-relaxed">Masuk ke halaman pendaftaran jalur mandiri universitas tujuan yang dipilih.</p>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">2</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Registrasi & Pengisian Data</h3>
              <p class="text-blue-200 leading-relaxed">Isi biodata, pilihan program studi, dan unggah dokumen yang dibutuhkan.</p>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">3</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Pembayaran Biaya Seleksi</h3>
              <p class="text-blue-200 leading-relaxed">Bayar biaya pendaftaran mandiri melalui virtual account atau bank yang ditunjuk.</p>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 group">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-navy-600 rounded-xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">4</div>
            <div>
              <h3 class="font-bold text-xl text-white mb-3">Tes atau Seleksi</h3>
              <p class="text-blue-200 leading-relaxed">Ikuti tes mandiri sesuai jadwal atau seleksi berdasarkan nilai rapor/UTBK.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Jadwal Pendaftaran Section -->
  <section class="py-20 px-6 relative" id="jadwal">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-white mb-4">Jadwal Pendaftaran</h2>
        <p class="text-xl text-blue-200 max-w-2xl mx-auto">Jangan sampai terlewat! Catat tanggal penting untuk setiap jalur pendaftaran</p>
      </div>

      <div class="glass-effect rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gradient-to-r from-blue-600 to-navy-700">
              <tr>
                <th class="px-6 py-4 text-left text-white font-semibold">Jalur</th>
                <th class="px-6 py-4 text-left text-white font-semibold">Kegiatan</th>
                <th class="px-6 py-4 text-left text-white font-semibold">Tanggal</th>
              </tr>
            </thead>
            <tbody class="text-white">
              <!-- SNBP -->
              <tr class="border-b border-white/10 hover:bg-white/5 transition-colors duration-300">
                <td class="px-6 py-4 font-semibold text-blue-300">SNBP</td>
                <td class="px-6 py-4">Pendaftaran</td>
                <td class="px-6 py-4">14 – 28 Februari 2025</td>
              </tr>
              <tr class="border-b border-white/10 hover:bg-white/5 transition-colors duration-300">
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Pengumuman Hasil</td>
                <td class="px-6 py-4">26 Maret 2025</td>
              </tr>

              <!-- SNBT -->
              <tr class="border-b border-white/10 hover:bg-white/5 transition-colors duration-300">
                <td class="px-6 py-4 font-semibold text-blue-300">SNBT</td>
                <td class="px-6 py-4">Pendaftaran UTBK</td>
                <td class="px-6 py-4">21 Maret – 5 April 2025</td>
              </tr>
              <tr class="border-b border-white/10 hover:bg-white/5 transition-colors duration-300">
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Pelaksanaan UTBK</td>
                <td class="px-6 py-4">29 April – 6 Mei 2025</td>
              </tr>
              <tr class="border-b border-white/10 hover:bg-white/5 transition-colors duration-300">
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Pengumuman Hasil</td>
                <td class="px-6 py-4">13 Juni 2025</td>
              </tr>

              <!-- Mandiri -->
              <tr class="border-b border-white/10 hover:bg-white/5 transition-colors duration-300">
                <td class="px-6 py-4 font-semibold text-blue-300">Mandiri</td>
                <td class="px-6 py-4">Pendaftaran</td>
                <td class="px-6 py-4">15 Juni – 10 Juli 2025</td>
              </tr>
              <tr class="border-b border-white/10 hover:bg-white/5 transition-colors duration-300">
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Ujian Seleksi Mandiri</td>
                <td class="px-6 py-4">15 – 20 Juli 2025</td>
              </tr>
              <tr class="hover:bg-white/5 transition-colors duration-300">
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Pengumuman</td>
                <td class="px-6 py-4">25 Juli 2025</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="py-20 px-6 relative" id="faq">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-white mb-4">Frequently Asked Questions</h2>
        <p class="text-xl text-blue-200 max-w-2xl mx-auto">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
      </div>

      <div class="space-y-4" x-data="{ selected: null }">
        <!-- FAQ 1 -->
        <div class="glass-effect rounded-2xl overflow-hidden">
          <button @click="selected !== 1 ? selected = 1 : selected = null" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-white/5 transition-colors duration-300">
            <span class="text-lg font-semibold text-white">Apa itu jalur SNBT?</span>
            <svg x-show="selected !== 1" class="w-6 h-6 text-blue-300 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <svg x-show="selected === 1" class="w-6 h-6 text-blue-300 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
            </svg>
          </button>
          <div x-show="selected === 1" x-transition class="px-8 pb-6 text-blue-200 leading-relaxed">
            Jalur SNBT adalah Seleksi Nasional Berdasarkan Tes yang diselenggarakan oleh pemerintah untuk masuk perguruan tinggi negeri. Seleksi ini menggunakan hasil UTBK (Ujian Tulis Berbasis Komputer) sebagai dasar penilaian.
          </div>
        </div>

        <!-- FAQ 2 -->
        <div class="glass-effect rounded-2xl overflow-hidden">
          <button @click="selected !== 2 ? selected = 2 : selected = null" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-white/5 transition-colors duration-300">
            <span class="text-lg font-semibold text-white">Bagaimana jika saya lupa password akun?</span>
            <svg x-show="selected !== 2" class="w-6 h-6 text-blue-300 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <svg x-show="selected === 2" class="w-6 h-6 text-blue-300 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
            </svg>
          </button>
          <div x-show="selected === 2" x-transition class="px-8 pb-6 text-blue-200 leading-relaxed">
            Silakan klik "Lupa Password" pada halaman login dan ikuti instruksi untuk mereset kata sandi Anda melalui email. Pastikan email yang digunakan masih aktif dan dapat diakses.
          </div>
        </div>

        <!-- FAQ 3 -->
        <div class="glass-effect rounded-2xl overflow-hidden">
          <button @click="selected !== 3 ? selected = 3 : selected = null" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-white/5 transition-colors duration-300">
            <span class="text-lg font-semibold text-white">Apakah saya bisa mendaftar lebih dari satu jalur?</span>
            <svg x-show="selected !== 3" class="w-6 h-6 text-blue-300 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <svg x-show="selected === 3" class="w-6 h-6 text-blue-300 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
            </svg>
          </button>
          <div x-show="selected === 3" x-transition class="px-8 pb-6 text-blue-200 leading-relaxed">
            Ya, Anda dapat mendaftar di lebih dari satu jalur selama masing-masing jalur masih dalam masa pendaftaran. Namun, pastikan Anda memenuhi persyaratan untuk setiap jalur yang dipilih.
          </div>
        </div>

        <!-- FAQ 4 -->
        <div class="glass-effect rounded-2xl overflow-hidden">
          <button @click="selected !== 4 ? selected = 4 : selected = null" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-white/5 transition-colors duration-300">
            <span class="text-lg font-semibold text-white">Berapa biaya pendaftaran untuk setiap jalur?</span>
            <svg x-show="selected !== 4" class="w-6 h-6 text-blue-300 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <svg x-show="selected === 4" class="w-6 h-6 text-blue-300 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
            </svg>
          </button>
          <div x-show="selected === 4" x-transition class="px-8 pb-6 text-blue-200 leading-relaxed">
            SNBP: Gratis. SNBT: Rp 200.000 untuk UTBK. Jalur Mandiri: Bervariasi tergantung universitas, umumnya berkisar Rp 300.000 - Rp 500.000.
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 px-6 relative">
    <div class="max-w-4xl mx-auto text-center">
      <div class="glass-effect rounded-3xl p-12">
        <h2 class="text-4xl font-bold text-white mb-6">Siap Memulai Perjalanan Akademik Anda?</h2>
        <p class="text-xl text-blue-200 mb-8 max-w-2xl mx-auto">Jangan tunda lagi! Daftarkan diri Anda sekarang dan wujudkan impian kuliah di universitas pilihan.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="register.html" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-navy-700 text-white rounded-xl font-semibold shadow-lg hover:from-blue-700 hover:to-navy-800 transform hover:scale-105 transition-all duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Daftar Sekarang
          </a>
          <a href="login.html" class="inline-flex items-center justify-center px-8 py-4 glass-effect text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Sudah Punya Akun? Login
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-navy-950/50 backdrop-blur-lg border-t border-white/10 py-12 mt-20">
    <div class="max-w-7xl mx-auto px-4">
      <div class="grid md:grid-cols-4 gap-8 mb-8">
        <div class="col-span-2">
          <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-navy-600 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
            <span class="text-2xl font-bold gradient-text">SIPMB</span>
          </div>
          <p class="text-blue-200 mb-4 max-w-md">Sistem Informasi Penerimaan Mahasiswa Baru yang memudahkan calon mahasiswa dalam proses pendaftaran ke perguruan tinggi.</p>
          <div class="flex space-x-4">
            <a href="#" class="w-10 h-10 glass-effect rounded-lg flex items-center justify-center text-blue-300 hover:text-white transition-colors duration-300">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
            </a>
            <a href="#" class="w-10 h-10 glass-effect rounded-lg flex items-center justify-center text-blue-300 hover:text-white transition-colors duration-300">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/></svg>
            </a>
            <a href="#" class="w-10 h-10 glass-effect rounded-lg flex items-center justify-center text-blue-300 hover:text-white transition-colors duration-300">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/></svg>
            </a>
          </div>
        </div>
        
        <div>
          <h3 class="text-white font-semibold mb-4">Menu Utama</h3>
          <ul class="space-y-2">
            <li><a href="#beranda" class="text-blue-200 hover:text-white transition-colors duration-300">Beranda</a></li>
            <li><a href="#jadwal" class="text-blue-200 hover:text-white transition-colors duration-300">Jadwal</a></li>
            <li><a href="#cara-pendaftaran" class="text-blue-200 hover:text-white transition-colors duration-300">Cara Daftar</a></li>
            <li><a href="#faq" class="text-blue-200 hover:text-white transition-colors duration-300">FAQ</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-white font-semibold mb-4">Kontak</h3>
          <ul class="space-y-2">
            <li class="text-blue-200">Email: info@sipmb.ac.id</li>
            <li class="text-blue-200">Telepon: (021) 123-4567</li>
            <li class="text-blue-200">WhatsApp: +62 812-3456-7890</li>
          </ul>
        </div>
      </div>
      
      <div class="border-t border-white/10 pt-8 text-center">
        <p class="text-blue-300">&copy; 2025 SIPMB. Semua Hak Dilindungi.</p>
      </div>
    </div>
  </footer>

  <script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Navbar background on scroll
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('nav');
      if (window.scrollY > 50) {
        navbar.classList.add('backdrop-blur-xl');
      } else {
        navbar.classList.remove('backdrop-blur-xl');
      }
    });
  </script>
</body>
</html>