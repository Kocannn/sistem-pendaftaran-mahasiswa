<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIPMB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script
      defer
      src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>
  </head>
  <body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md" x-data="{ open: false }">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center">
            <a href="#" class="text-3xl font-bold text-blue-600">SIPMB</a>
          </div>

          <!-- Desktop Menu -->
          <div class="hidden md:flex space-x-6 items-center">
            <a
              href="#beranda"
              class="text-gray-700 font-bold hover:text-blue-500"
              >Beranda</a
            >
            <a
              href="#jadwal"
              class="text-gray-700 font-bold hover:text-blue-500"
              >Jadwal Pendaftaran</a
            >
            <a
              href="#cara pendaftaran"
              class="text-gray-700 font-bold hover:text-blue-500"
              >Cara Pendaftaran</a
            >
            <a href="#faq" class="text-gray-700 font-bold hover:text-blue-500"
              >FaQ</a
            >
          </div>

          <!-- Login/Daftar -->
          <div class="hidden md:flex items-center space-x-3">
            <a
              href="login.html"
              class="px-4 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition"
              >Login</a
            >
            <a
              href="register.html"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
              >Daftar</a
            >
          </div>

          <!-- Hamburger menu (mobile) -->
          <div class="flex items-center md:hidden">
            <button
              @click="open = !open"
              type="button"
              class="text-gray-700 hover:text-blue-600 focus:outline-none"
            >
              <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  :class="{'hidden': open, 'inline-flex': !open }"
                  class="inline-flex"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  :class="{'hidden': !open, 'inline-flex': open }"
                  class="hidden"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div x-show="open" class="md:hidden px-4 pb-4 space-y-2">
        <a href="#beranda" class="block text-gray-700 hover:text-blue-500"
          >Beranda</a
        >
        <a href="#jadwal" class="block text-gray-700 hover:text-blue-500"
          >Jadwal Pendaftaran</a
        >
        <a href="#jalur" class="block text-gray-700 hover:text-blue-500"
          >Cara Pendaftaran</a
        >
        <a href="#faq" class="block text-gray-700 hover:text-blue-500">FaQ</a>
        <a
          href="login.html"
          class="block mt-2 text-blue-600 border border-blue-600 text-center py-2 rounded hover:bg-blue-600 hover:text-white transition"
          >Login</a
        >
        <a
          href="register.html"
          class="block bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700 transition"
          >Daftar</a
        >
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-blue-100 py-20">
      <div
        class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between"
      >
        <!-- Teks Hero -->
        <div class="md:w-1/2 mb-10 md:mb-0">
          <h1 class="text-4xl font-extrabold text-blue-800 leading-tight mb-4">
            Daftarkan Dirimu<br />
            di Universitas PilihanMu
          </h1>
          <p class="text-lg text-gray-700 mb-6">
            Pendaftaran Mahasiswa Baru kini lebih cepat, mudah, dan dapat
            dilakukan secara online dari mana saja.
          </p>
          <a
            href="register.html"
            class="inline-block bg-blue-600 text-white px-6 py-3 rounded shadow hover:bg-blue-700 transition"
          >
            Daftar Sekarang
          </a>
        </div>

        <!-- Gambar Pada Hero -->
        <div class="md:w-1/2">
          <img
            src="https://plus.unsplash.com/premium_photo-1713296256430-3c828fca19ae?w=900&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NzF8fHVuaXZlcnNpdHl8ZW58MHx8MHx8fDA%3D"
            alt="Ilustrasi Mahasiswa"
            class="rounded-lg shadow-lg transform transition duration-300 hover:scale-105"
          />
        </div>
      </div>
    </section>

    <!-- Cara Pendaftaran Section -->
    <section
      class="bg-gray-100 py-16 px-6"
      x-data="{ tab: 'snbp' }"
      id="cara pendaftaran"
    >
      <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-blue-800 mb-8">
          Cara Pendaftaran
        </h2>

        <!-- Tab Navigasi -->
        <div class="flex justify-center mb-8 space-x-4">
          <button
            @click="tab = 'snbp'"
            :class="tab === 'snbp' ? 'bg-blue-600 text-white' : 'bg-white text-blue-600'"
            class="px-4 py-2 rounded shadow"
          >
            SNBP
          </button>
          <button
            @click="tab = 'snbt'"
            :class="tab === 'snbt' ? 'bg-blue-600 text-white' : 'bg-white text-blue-600'"
            class="px-4 py-2 rounded shadow"
          >
            SNBT
          </button>
          <button
            @click="tab = 'mandiri'"
            :class="tab === 'mandiri' ? 'bg-blue-600 text-white' : 'bg-white text-blue-600'"
            class="px-4 py-2 rounded shadow"
          >
            Mandiri
          </button>
        </div>

        <!-- Alur SNBP  -->
        <div x-show="tab === 'snbp'" class="space-y-6">
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              1. Login ke Portal SNPMB
            </h3>
            <p class="text-gray-700">
              Gunakan akun belajar.id dari sekolah untuk login ke portal SNPMB.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              2. Pengisian Data
            </h3>
            <p class="text-gray-700">
              Siswa mengisi data diri dan prestasi yang dimiliki di sistem
              SNPMB.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              3. Pemilihan Prodi
            </h3>
            <p class="text-gray-700">
              Pilih dua program studi dari PTN tujuan.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              4. Finalisasi
            </h3>
            <p class="text-gray-700">Finalisasi dan cetak bukti pendaftaran.</p>
          </div>
        </div>

        <!-- Alur SNBT -->
        <div x-show="tab === 'snbt'" class="space-y-6">
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              1. Registrasi Akun SNPMB
            </h3>
            <p class="text-gray-700">
              Buat akun di portal SNPMB untuk mengikuti ujian SNBT.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              2. Pembayaran UTBK
            </h3>
            <p class="text-gray-700">
              Lakukan pembayaran biaya UTBK melalui bank yang ditunjuk.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              3. Cetak Kartu Ujian
            </h3>
            <p class="text-gray-700">
              Cetak kartu dan hadir di lokasi UTBK sesuai jadwal.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              4. Pengisian Pilihan Prodi
            </h3>
            <p class="text-gray-700">
              Setelah UTBK, pilih jurusan dan universitas tujuan di portal
              SNPMB.
            </p>
          </div>
        </div>

        <!-- Alur Mandiri -->
        <div x-show="tab === 'mandiri'" class="space-y-6">
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              1. Buka Website Universitas
            </h3>
            <p class="text-gray-700">
              Masuk ke halaman pendaftaran jalur mandiri universitas tujuan.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              2. Registrasi & Pengisian Data
            </h3>
            <p class="text-gray-700">
              Isi biodata, pilihan program studi, dan unggah dokumen yang
              dibutuhkan.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              3. Pembayaran Biaya Seleksi
            </h3>
            <p class="text-gray-700">
              Bayar biaya pendaftaran mandiri melalui virtual account/bank.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg text-blue-800 mb-2">
              4. Tes atau Seleksi
            </h3>
            <p class="text-gray-700">
              Ikuti tes mandiri sesuai jadwal atau seleksi berdasarkan nilai
              rapor/UTBK.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Jadwal Pendaftaran -->
    <section class="bg-white py-16 px-6" id="jadwal">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-blue-800 mb-10">
          Jadwal Pendaftaran
        </h2>

        <div class="overflow-x-auto">
          <table class="w-full table-auto border border-gray-200">
            <thead class="bg-blue-600 text-white">
              <tr>
                <th class="px-4 py-3 text-left">Jalur</th>
                <th class="px-4 py-3 text-left">Kegiatan</th>
                <th class="px-4 py-3 text-left">Tanggal</th>
              </tr>
            </thead>
            <tbody class="text-gray-800">
              <!-- SNBP -->
              <tr class="bg-gray-50">
                <td class="px-4 py-3 font-semibold">SNBP</td>
                <td class="px-4 py-3">Pendaftaran</td>
                <td class="px-4 py-3">14 – 28 Februari 2025</td>
              </tr>
              <tr class="bg-white">
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3">Pengumuman Hasil</td>
                <td class="px-4 py-3">26 Maret 2025</td>
              </tr>

              <!-- SNBT -->
              <tr class="bg-gray-50">
                <td class="px-4 py-3 font-semibold">SNBT</td>
                <td class="px-4 py-3">Pendaftaran UTBK</td>
                <td class="px-4 py-3">21 Maret – 5 April 2025</td>
              </tr>
              <tr class="bg-white">
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3">Pelaksanaan UTBK</td>
                <td class="px-4 py-3">29 April – 6 Mei 2025</td>
              </tr>
              <tr class="bg-gray-50">
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3">Pengumuman Hasil</td>
                <td class="px-4 py-3">13 Juni 2025</td>
              </tr>

              <!-- Mandiri -->
              <tr class="bg-white">
                <td class="px-4 py-3 font-semibold">Mandiri</td>
                <td class="px-4 py-3">Pendaftaran</td>
                <td class="px-4 py-3">15 Juni – 10 Juli 2025</td>
              </tr>
              <tr class="bg-gray-50">
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3">Ujian Seleksi Mandiri</td>
                <td class="px-4 py-3">15 – 20 Juli 2025</td>
              </tr>
              <tr class="bg-white">
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3">Pengumuman</td>
                <td class="px-4 py-3">25 Juli 2025</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="bg-white py-16 px-6" id="faq">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-blue-800 mb-10">FaQ</h2>

        <div class="space-y-4" x-data="{ selected: null }">
          <!-- FAQ 1 -->
          <div
            class="border border-gray-200 rounded-lg"
            @click="selected !== 1 ? selected = 1 : selected = null"
          >
            <button
              class="w-full px-6 py-4 flex justify-between items-center text-left"
            >
              <span class="text-lg font-medium text-gray-800"
                >Apa itu jalur SNBT?</span
              >
              <svg
                x-show="selected !== 1"
                class="w-6 h-6 text-gray-600"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 4v16m8-8H4"
                />
              </svg>
              <svg
                x-show="selected === 1"
                class="w-6 h-6 text-gray-600"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M20 12H4"
                />
              </svg>
            </button>
            <div x-show="selected === 1" class="px-6 pb-4 text-gray-600">
              Jalur SNBT adalah Seleksi Nasional Berdasarkan Tes yang
              diselenggarakan oleh pemerintah untuk masuk perguruan tinggi
              negeri.
            </div>
          </div>

          <!-- FAQ 2 -->
          <div
            class="border border-gray-200 rounded-lg"
            @click="selected !== 2 ? selected = 2 : selected = null"
          >
            <button
              class="w-full px-6 py-4 flex justify-between items-center text-left"
            >
              <span class="text-lg font-medium text-gray-800"
                >Bagaimana jika saya lupa password akun?</span
              >
              <svg
                x-show="selected !== 2"
                class="w-6 h-6 text-gray-600"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 4v16m8-8H4"
                />
              </svg>
              <svg
                x-show="selected === 2"
                class="w-6 h-6 text-gray-600"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M20 12H4"
                />
              </svg>
            </button>
            <div x-show="selected === 2" class="px-6 pb-4 text-gray-600">
              Silakan klik “Lupa Password” pada halaman login dan ikuti
              instruksi untuk mereset kata sandi Anda melalui email.
            </div>
          </div>

          <!-- FAQ 3 -->
          <div
            class="border border-gray-200 rounded-lg"
            @click="selected !== 3 ? selected = 3 : selected = null"
          >
            <button
              class="w-full px-6 py-4 flex justify-between items-center text-left"
            >
              <span class="text-lg font-medium text-gray-800"
                >Apakah saya bisa mendaftar lebih dari satu jalur?</span
              >
              <svg
                x-show="selected !== 3"
                class="w-6 h-6 text-gray-600"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 4v16m8-8H4"
                />
              </svg>
              <svg
                x-show="selected === 3"
                class="w-6 h-6 text-gray-600"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M20 12H4"
                />
              </svg>
            </button>
            <div x-show="selected === 3" class="px-6 pb-4 text-gray-600">
              Ya, Anda dapat mendaftar di lebih dari satu jalur selama
              masing-masing jalur masih dalam masa pendaftaran.
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="bg-blue-600 text-white py-6 mt-16">
      <div class="max-w-7xl mx-auto px-4 text-center">
        <p>&copy; 2025 SIPMB. Semua Hak Dilindungi.</p>
      </div>
    </footer>
  </body>
</html>
