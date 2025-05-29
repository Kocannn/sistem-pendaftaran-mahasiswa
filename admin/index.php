<?php
include_once __DIR__ . '/../service/db.php';
include_once __DIR__ . '/../service/admin/logout.php';
include_once __DIR__ . '/../service/admin/filter_and_pagination.php';
include_once __DIR__ . '/../service/calculate.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["admin_id"])) {
  header("Location: /sistem-informasi-pendaftaran/admin/login");
  exit();
}

if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
  handleLogout();
  header("Location: /sistem-informasi-pendaftaran/admin/login");
  exit();
}

// Handle admission calculation if requested
if (isset($_POST['calculate_admission']) && $_POST['calculate_admission'] === 'true') {
  $studentsQuery = $conn->query("
    SELECT 
      nisn, jalur_pendaftaran, skor_ujian, prodi_1_kode, prodi_2_kode 
    FROM 
      mahasiswa
    LIMIT 50
  ");

  $processedCount = 0;
  $successCount = 0;

  while ($student = $studentsQuery->fetch_assoc()) {
    $processedCount++;
    $admissionResult = calculateAdmission(
      $student['jalur_pendaftaran'],
      $student['skor_ujian'],
      $student['prodi_1_kode'],
      $student['prodi_2_kode']
    );

    if (applyAdmissionResult($conn, $student['nisn'], $admissionResult)) {
      $successCount++;
    }
  }

  $_SESSION['calculation_message'] = "Perhitungan selesai: $successCount dari $processedCount mahasiswa berhasil diproses.";
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Portal Akademik</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            'slide-in': 'slideIn 0.3s ease-out',
            'bounce-subtle': 'bounceSubtle 2s ease-in-out infinite',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            slideIn: {
              '0%': { transform: 'translateX(-10px)', opacity: '0' },
              '100%': { transform: 'translateX(0)', opacity: '1' },
            },
            bounceSubtle: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-5px)' },
            }
          }
        }
      }
    }
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-dark-50 via-admin-50 to-purple-100 font-inter min-h-screen">
  <div class="flex h-screen overflow-hidden">
    <!-- Enhanced Sidebar -->
    <div id="sidebar" class="hidden md:flex md:flex-shrink-0 transition-all duration-300">
      <div class="flex flex-col w-72">
        <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-white/95 backdrop-blur-sm border-r border-admin-200 shadow-xl">
          <!-- Logo Section -->
          <div class="flex items-center flex-shrink-0 px-6 mb-8">
            <div class="flex items-center">
              <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-admin-600 to-admin-700 rounded-xl shadow-lg">
                <i class="fas fa-shield-alt text-white text-xl"></i>
              </div>
              <div class="ml-4">
                <h1 class="text-xl font-bold text-dark-800">Admin Portal</h1>
                <p class="text-sm text-dark-500">Sistem Akademik</p>
              </div>
            </div>
          </div>

          <!-- Navigation Menu -->
          <nav class="flex-1 px-4 space-y-2">
            <div class="mb-6">
              <p class="px-3 text-xs font-semibold text-dark-400 uppercase tracking-wider mb-3">Menu Utama</p>
              
              <a href="#" class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl bg-gradient-to-r from-admin-500 to-admin-600 text-white shadow-lg transform transition-all duration-200 hover:scale-105">
                <i class="fas fa-chart-pie mr-3 text-admin-100"></i>
                <span>Dashboard</span>
                <i class="fas fa-chevron-right ml-auto text-admin-200"></i>
              </a>


            </div>

          </nav>

          <!-- User Profile Section -->
          <div class="flex-shrink-0 border-t border-admin-200 p-4 bg-gradient-to-r from-admin-50 to-purple-50">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-admin-500 to-admin-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-tie text-white"></i>
                  </div>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-semibold text-dark-700">Administrator</p>
                  <p class="text-xs text-dark-500">admin@university.ac.id</p>
                </div>
              </div>
              <button
                onclick="handleLogout()"
                class="ml-2 p-2 rounded-lg text-dark-400 hover:text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200"
                title="Logout">
                <i class="fas fa-sign-out-alt"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
      <!-- Enhanced Top Navigation -->
      <div class="relative z-10 flex-shrink-0 flex h-20 bg-white/95 backdrop-blur-sm shadow-lg border-b border-admin-200">
        <button
          onclick="toggleMobileMenu()"
          class="px-4 border-r border-admin-200 text-dark-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-admin-500 md:hidden hover:bg-admin-50 transition-colors">
          <i class="fas fa-bars text-xl"></i>
        </button>
        
        <div class="flex-1 px-6 flex justify-between items-center">
          <div class="flex-1 flex items-center">
            <div>
              <h1 class="text-2xl font-bold text-dark-800">Dashboard Penerimaan</h1>
              <p class="text-sm text-dark-500 mt-1">Sistem Informasi Pendaftaran Mahasiswa Baru</p>
            </div>
          </div>

          <!-- Notification Area -->
          <?php if (isset($_SESSION['calculation_message'])): ?>
            <div class="mr-4 px-4 py-3 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 rounded-xl shadow-sm animate-slide-in">
              <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                <span class="text-sm font-medium"><?= $_SESSION['calculation_message'] ?></span>
              </div>
            </div>
            <?php unset($_SESSION['calculation_message']); ?>
          <?php endif; ?>

          <div class="ml-4 flex items-center space-x-4">
            <!-- Quick Actions -->
            <div class="hidden lg:flex items-center space-x-2">
              <button class="p-2 rounded-lg text-dark-400 hover:text-admin-600 hover:bg-admin-50 transition-all duration-200" title="Notifikasi">
                <i class="fas fa-bell"></i>
              </button>
              <button class="p-2 rounded-lg text-dark-400 hover:text-admin-600 hover:bg-admin-50 transition-all duration-200" title="Pesan">
                <i class="fas fa-envelope"></i>
              </button>
            </div>

            <!-- Mobile Logout Button -->
            <button
              onclick="handleLogout()"
              class="md:hidden p-2 rounded-lg text-dark-400 hover:text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200"
              title="Logout">
              <i class="fas fa-sign-out-alt"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Dashboard Content -->
      <main class="flex-1 relative overflow-y-auto focus:outline-none">
        <div class="py-8">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            
            <!-- Enhanced Summary Cards -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
              <!-- Total Pendaftar -->
              <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                <div class="p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-dark-500 truncate">Total Pendaftar</dt>
                        <dd class="text-2xl font-bold text-dark-900"><?= $totalStudents ?></dd>
                        <dd class="text-xs text-green-600 font-medium">
                          <i class="fas fa-arrow-up mr-1"></i>Mahasiswa baru
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Jalur SNBP -->
              <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                <div class="p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-medal text-white text-xl"></i>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-dark-500 truncate">Jalur SNBP</dt>
                        <dd class="text-2xl font-bold text-dark-900"><?= $snbpCount ?></dd>
                        <dd class="text-xs text-green-600 font-medium">
                          <i class="fas fa-check-circle mr-1"></i>Prestasi Akademik
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Jalur SNBT -->
              <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                <div class="p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clipboard-check text-white text-xl"></i>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-dark-500 truncate">Jalur SNBT</dt>
                        <dd class="text-2xl font-bold text-dark-900"><?= $snbtCount ?></dd>
                        <dd class="text-xs text-blue-600 font-medium">
                          <i class="fas fa-pencil-alt mr-1"></i>Tes Berbasis Komputer
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Jalur Mandiri -->
              <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                <div class="p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-admin-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-university text-white text-xl"></i>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-dark-500 truncate">Jalur Mandiri</dt>
                        <dd class="text-2xl font-bold text-dark-900"><?= $mandiriCount ?></dd>
                        <dd class="text-xs text-purple-600 font-medium">
                          <i class="fas fa-star mr-1"></i>Seleksi Mandiri
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Enhanced Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
              <!-- Bar Chart -->
              <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl p-8 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                  <div>
                    <h3 class="text-xl font-bold text-dark-900">Peminat per Program Studi</h3>
                    <p class="text-sm text-dark-500 mt-1">Perbandingan jumlah pendaftar dan daya tampung program studi</p>
                  </div>
                  <div class="p-2 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                    <i class="fas fa-chart-bar text-blue-600"></i>
                  </div>
                </div>
                <div style="height: 350px;">
                  <canvas id="barChart"></canvas>
                </div>
              </div>

              <!-- Pie Chart -->
              <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl p-8 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                  <div>
                    <h3 class="text-xl font-bold text-dark-900">Status Kelulusan</h3>
                    <p class="text-sm text-dark-500 mt-1">Persentase status penerimaan mahasiswa</p>
                  </div>
                  <div class="p-2 bg-gradient-to-r from-green-50 to-green-100 rounded-lg">
                    <i class="fas fa-chart-pie text-green-600"></i>
                  </div>
                </div>
                <div style="height: 350px;">
                  <canvas id="pieChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Enhanced Data Table Section -->
            <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20">
              <!-- Table Header -->
              <div class="px-8 py-6 border-b border-admin-200 bg-gradient-to-r from-admin-50 to-purple-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                  <div>
                    <h3 class="text-xl font-bold text-dark-900">Daftar Mahasiswa</h3>
                    <p class="text-sm text-dark-500 mt-1">Kelola data pendaftar dan status penerimaan</p>
                  </div>
                  
                  <div class="mt-4 sm:mt-0 sm:ml-4 flex flex-wrap gap-3">
                    <!-- Filters -->
                    <form method="GET" action="" class="flex flex-wrap gap-3">
                      <select name="jalur" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-admin-300 focus:outline-none focus:ring-admin-500 focus:border-admin-500 sm:text-sm rounded-xl bg-white shadow-sm" onchange="this.form.submit()">
                        <option <?= !isset($_GET['jalur']) || $_GET['jalur'] === 'Semua Jalur' ? 'selected' : '' ?>>Semua Jalur</option>
                        <option <?= isset($_GET['jalur']) && $_GET['jalur'] === 'SNBP' ? 'selected' : '' ?>>SNBP</option>
                        <option <?= isset($_GET['jalur']) && $_GET['jalur'] === 'SNBT' ? 'selected' : '' ?>>SNBT</option>
                        <option <?= isset($_GET['jalur']) && $_GET['jalur'] === 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                      </select>
                      <select name="status" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-admin-300 focus:outline-none focus:ring-admin-500 focus:border-admin-500 sm:text-sm rounded-xl bg-white shadow-sm" onchange="this.form.submit()">
                        <option <?= !isset($_GET['status']) || $_GET['status'] === 'Semua Status' ? 'selected' : '' ?>>Semua Status</option>
                        <option <?= isset($_GET['status']) && $_GET['status'] === 'Lolos P1' ? 'selected' : '' ?>>Lolos P1</option>
                        <option <?= isset($_GET['status']) && $_GET['status'] === 'Lolos P2' ? 'selected' : '' ?>>Lolos P2</option>
                        <option <?= isset($_GET['status']) && $_GET['status'] === 'Dialihkan' ? 'selected' : '' ?>>Dialihkan</option>
                        <option <?= isset($_GET['status']) && $_GET['status'] === 'Tidak Lulus' ? 'selected' : '' ?>>Tidak Lulus</option>
                      </select>
                    </form>

                  </div>
                </div>
              </div>

              <!-- Enhanced Table -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-admin-200">
                  <thead class="bg-gradient-to-r from-dark-50 to-admin-50">
                    <tr>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-dark-600 uppercase tracking-wider">
                        <i class="fas fa-id-card mr-2"></i>No. Pendaftaran
                      </th>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-dark-600 uppercase tracking-wider">
                        <i class="fas fa-user mr-2"></i>Nama
                      </th>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-dark-600 uppercase tracking-wider">
                        <i class="fas fa-graduation-cap mr-2"></i>Program Studi
                      </th>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-dark-600 uppercase tracking-wider">
                        <i class="fas fa-route mr-2"></i>Jalur
                      </th>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-dark-600 uppercase tracking-wider">
                        <i class="fas fa-check-circle mr-2"></i>Status
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-admin-100">
                    <?php while ($student = $students->fetch_assoc()): ?>
                      <tr class="hover:bg-admin-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-dark-900">
                          <div class="flex items-center">
                            <div class="w-2 h-2 bg-admin-500 rounded-full mr-3"></div>
                            <?= htmlspecialchars($student['nisn']) ?>
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-900 font-medium">
                          <?= htmlspecialchars($student['nama']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-700">
                          <?= htmlspecialchars($student['prodi_nama']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full shadow-sm
                            <?= $student['jalur_pendaftaran'] === 'SNBP' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                ($student['jalur_pendaftaran'] === 'SNBT' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
                                'bg-purple-100 text-purple-800 border border-purple-200') ?>">
                            <i class="fas fa-<?= $student['jalur_pendaftaran'] === 'SNBP' ? 'medal' : ($student['jalur_pendaftaran'] === 'SNBT' ? 'clipboard-check' : 'university') ?> mr-1"></i>
                            <?= htmlspecialchars($student['jalur_pendaftaran']) ?>
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full shadow-sm
                            <?= $student['status_kelulusan'] === 'Lulus Pilihan 1' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                ($student['status_kelulusan'] === 'Lulus Pilihan 2' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 
                                'bg-red-100 text-red-800 border border-red-200') ?>">
                            <i class="fas fa-<?= $student['status_kelulusan'] === 'Lulus Pilihan 1' || $student['status_kelulusan'] === 'Lulus Pilihan 2' ? 'check-circle' : 'times-circle' ?> mr-1"></i>
                            <?= htmlspecialchars($student['status_kelulusan']) ?>
                          </span>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>

              <!-- Enhanced Pagination -->
              <div class="bg-gradient-to-r from-dark-50 to-admin-50 px-6 py-4 flex items-center justify-between border-t border-admin-200">
                <div class="flex-1 flex justify-between sm:hidden">
                  <a href="?page=<?= max(1, $page - 1) ?><?= isset($_GET['jalur']) ? '&jalur=' . $_GET['jalur'] : '' ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>" 
                     class="relative inline-flex items-center px-4 py-2 border border-admin-300 text-sm font-medium rounded-lg text-dark-700 bg-white hover:bg-admin-50 transition-colors">
                    <i class="fas fa-chevron-left mr-2"></i>Previous
                  </a>
                  <a href="?page=<?= min($totalPages, $page + 1) ?><?= isset($_GET['jalur']) ? '&jalur=' . $_GET['jalur'] : '' ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>" 
                     class="ml-3 relative inline-flex items-center px-4 py-2 border border-admin-300 text-sm font-medium rounded-lg text-dark-700 bg-white hover:bg-admin-50 transition-colors">
                    Next<i class="fas fa-chevron-right ml-2"></i>
                  </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-dark-700">
                      Menampilkan <span class="font-semibold"><?= $offset + 1 ?></span> sampai <span class="font-semibold"><?= min($offset + $limit, $totalRecords) ?></span> dari <span class="font-semibold"><?= $totalRecords ?></span> hasil
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px">
                      <a href="?page=<?= max(1, $page - 1) ?><?= isset($_GET['jalur']) ? '&jalur=' . $_GET['jalur'] : '' ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>"
                         class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-admin-300 bg-white text-sm font-medium text-dark-500 hover:bg-admin-50 transition-colors">
                        <i class="fas fa-chevron-left"></i>
                      </a>
                      
                      <span class="relative inline-flex items-center px-4 py-2 border border-admin-300 bg-admin-50 text-sm font-medium text-admin-700">
                        <?= $page ?>
                      </span>
                      
                      <a href="?page=<?= min($totalPages, $page + 1) ?><?= isset($_GET['jalur']) ? '&jalur=' . $_GET['jalur'] : '' ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>"
                         class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-admin-300 bg-white text-sm font-medium text-dark-500 hover:bg-admin-50 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                      </a>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Enhanced Logout Modal -->
  <div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 transition-opacity">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-2xl rounded-2xl bg-white">
      <div class="mt-3 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-red-100 to-red-200">
          <i class="fas fa-sign-out-alt text-red-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-dark-900 mt-4">Konfirmasi Logout</h3>
        <div class="mt-3 px-7 py-3">
          <p class="text-sm text-dark-600">
            Apakah Anda yakin ingin keluar dari sistem admin? Sesi Anda akan berakhir dan Anda akan diarahkan ke halaman login.
          </p>
        </div>
        <div class="flex justify-center space-x-4 mt-6">
          <button
            onclick="cancelLogout()"
            class="px-6 py-2 bg-dark-200 text-dark-800 text-sm font-medium rounded-xl shadow-sm hover:bg-dark-300 focus:outline-none focus:ring-2 focus:ring-dark-300 transition-all duration-200">
            <i class="fas fa-times mr-2"></i>Batal
          </button>
          <button
            onclick="confirmLogout()"
            class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-medium rounded-xl shadow-sm hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
            <i class="fas fa-check mr-2"></i>Ya, Logout
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Enhanced Chart Configuration
    Chart.defaults.font.family = 'Inter';
    Chart.defaults.color = '#64748b';

    // Bar Chart - Peminat per Program Studi
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($prodiLabels) ?>,
        datasets: [
          {
            label: 'Pilihan Pertama',
            data: <?= json_encode($prodiPilihan1) ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1,
            borderRadius: 6,
            borderSkipped: false,
            stack: 'Stack 0'
          },
          {
            label: 'Pilihan Kedua',
            data: <?= json_encode($prodiPilihan2) ?>,
            backgroundColor: 'rgba(147, 197, 253, 0.8)',
            borderColor: 'rgba(147, 197, 253, 1)',
            borderWidth: 1,
            borderRadius: 6,
            borderSkipped: false,
            stack: 'Stack 0'
          },
          {
            label: 'Daya Tampung',
            data: <?= json_encode($prodiDayaTampung) ?>,
            type: 'line',
            backgroundColor: 'rgba(236, 72, 153, 0.2)',
            borderColor: 'rgba(236, 72, 153, 1)',
            borderWidth: 2,
            pointBackgroundColor: 'rgba(236, 72, 153, 1)',
            pointBorderColor: '#fff',
            pointBorderWidth: 1,
            pointRadius: 4,
            fill: false,
            tension: 0.1
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',  // Horizontal bar chart
        plugins: {
          legend: {
            display: true,
            position: 'top',
            labels: {
              usePointStyle: true,
              font: {
                size: 11,
                weight: 'bold'
              }
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: '#ec4899',
            borderWidth: 1,
            cornerRadius: 8,
            callbacks: {
              afterTitle: function(context) {
                return 'Kode Prodi: ' + context[0].label.split(' - ')[0];
              }
            }
          }
        },
        scales: {
          y: {
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            },
            ticks: {
              color: '#64748b',
              font: {
                size: 11
              }
            }
          },
          x: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            },
            ticks: {
              color: '#64748b',
              font: {
                size: 10
              }
            },
            title: {
              display: true,
              text: 'Jumlah Pendaftar',
              font: {
                size: 12,
                weight: 'bold'
              }
            }
          }
        }
      }
    });

    // Pie Chart - Status Kelulusan
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
      type: 'doughnut',
      data: {
        labels: <?= json_encode($statusLabels) ?>,
        datasets: [{
          data: <?= json_encode($statusData) ?>,
          backgroundColor: [
            'rgba(16, 185, 129, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(239, 68, 68, 0.8)'
          ],
          borderColor: [
            'rgba(16, 185, 129, 1)',
            'rgba(59, 130, 246, 1)',
            'rgba(245, 158, 11, 1)',
            'rgba(239, 68, 68, 1)'
          ],
          borderWidth: 3,
          hoverOffset: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '60%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 20,
              usePointStyle: true,
              font: {
                size: 12,
                weight: '500'
              }
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: '#ec4899',
            borderWidth: 1,
            cornerRadius: 8,
          }
        }
      }
    });

    // Mobile menu toggle
    function toggleMobileMenu() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('hidden');
      sidebar.classList.toggle('fixed');
      sidebar.classList.toggle('inset-0');
      sidebar.classList.toggle('z-50');
    }

    // Enhanced Logout functionality
    function handleLogout() {
      document.getElementById('logoutModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function cancelLogout() {
      document.getElementById('logoutModal').classList.add('hidden');
      document.body.style.overflow = 'auto';
    }

    function confirmLogout() {
      const modal = document.getElementById('logoutModal');
      modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-2xl rounded-2xl bg-white">
          <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-admin-100 to-admin-200">
              <i class="fas fa-spinner fa-spin text-admin-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-dark-900 mt-4">Sedang Logout...</h3>
            <p class="text-sm text-dark-600 mt-2">Mohon tunggu sebentar</p>
          </div>
        </div>
      `;

      const form = document.createElement('form');
      form.method = 'post';
      form.action = window.location.href;

      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'logout';
      input.value = 'true';

      form.appendChild(input);
      document.body.appendChild(form);

      setTimeout(() => {
        form.submit();
      }, 1000);
    }

    // Close modal when clicking outside
    document.getElementById('logoutModal').addEventListener('click', function(e) {
      if (e.target === this) {
        cancelLogout();
      }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        cancelLogout();
      }
      if (e.ctrlKey && e.key === 'l') {
        e.preventDefault();
        handleLogout();
      }
    });

    // Add loading states to buttons
    document.querySelectorAll('button[type="submit"]').forEach(button => {
      button.addEventListener('click', function() {
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        this.disabled = true;
        
        setTimeout(() => {
          this.innerHTML = originalText;
          this.disabled = false;
        }, 3000);
      });
    });

    // Smooth animations on load
    document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.grid > div');
      cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
          card.style.transition = 'all 0.5s ease-out';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, index * 100);
      });
    });
  </script>
</body>

</html>