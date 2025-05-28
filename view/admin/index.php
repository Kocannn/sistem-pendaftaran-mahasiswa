<?php
include_once __DIR__ . '/../../service/db.php';
include_once __DIR__ . '/../../service/admin/logout.php';
include_once __DIR__ . '/../../service/admin/filter_and_pagination.php';
include_once __DIR__ . '/../../service/calculate.php';
session_start();

if (!isset($_SESSION["admin_id"])) {
  header("Location: /admin/login");
  exit();
}
if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
  handleLogout();
  header("Location: /admin/");
  exit();
}
// Handle admission calculation if requested
if (isset($_POST['calculate_admission']) && $_POST['calculate_admission'] === 'true') {
  // Get all students that need calculation
  $studentsQuery = $conn->query("
    SELECT 
      nisn, jalur_pendaftaran, skor_ujian, prodi_1_kode, prodi_2_kode 
    FROM 
      mahasiswa
    LIMIT 50 -- Limiting to avoid too many calculations at once
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

  // Store result message in session to display after redirect
  $_SESSION['calculation_message'] = "Perhitungan selesai: $successCount dari $processedCount mahasiswa berhasil diproses.";

  // Redirect to avoid form resubmission
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Sistem Penerimaan Mahasiswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'inter': ['Inter', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-inter">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div id="sidebar" class="hidden md:flex md:flex-shrink-0">
      <div class="flex flex-col w-64">
        <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-white border-r border-gray-200">
          <!-- Logo -->
          <div class="flex items-center flex-shrink-0 px-4">
            <div class="flex items-center">
              <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
              <h1 class="ml-3 text-xl font-bold text-gray-900">Admin Panel</h1>
            </div>
          </div>

          <!-- Navigation -->
          <nav class="mt-8 flex-1 px-2 space-y-1">
            <a href="#" class="bg-blue-50 border-r-4 border-blue-600 text-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-l-md">
              <svg class="text-blue-500 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
              </svg>
              Dashboard
            </a>
          </nav>

          <!-- User Profile with Logout -->
          <div class="flex-shrink-0 border-t border-gray-200 p-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                    <span class="text-sm font-medium text-white">A</span>
                  </div>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-gray-700">Admin User</p>
                  <p class="text-xs text-gray-500">admin@university.ac.id</p>
                </div>
              </div>
              <!-- Logout Button -->
              <button
                onclick="handleLogout()"
                class="ml-2 p-1 rounded-md text-gray-400 hover:text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors"
                title="Logout">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
      <!-- Top Navigation -->
      <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow border-b border-gray-200">
        <button
          onclick="toggleMobileMenu()"
          class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 md:hidden">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
          </svg>
        </button>
        <div class="flex-1 px-4 flex justify-between items-center">
          <div class="flex-1 flex">
            <h1 class="text-2xl font-semibold text-gray-900">Dashboard Penerimaan Mahasiswa</h1>
          </div>

          <!-- Notification Area -->
          <?php if (isset($_SESSION['calculation_message'])): ?>
            <div class="mr-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
              <?= $_SESSION['calculation_message'] ?>
              <?php unset($_SESSION['calculation_message']); ?>
            </div>
          <?php endif; ?>

          <div class="ml-4 flex items-center md:ml-6 space-x-3">

            <!-- Mobile Logout Button -->
            <button
              onclick="handleLogout()"
              class="md:hidden bg-white p-1 rounded-full text-gray-400 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
              title="Logout">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Dashboard Content -->
      <main class="flex-1 relative overflow-y-auto focus:outline-none">
        <div class="py-6">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
              <!-- Total Pendaftar -->
              <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Pendaftar</dt>
                        <dd class="text-lg font-medium text-gray-900"><?= $totalStudents ?></dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Jalur SNBP -->
              <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Jalur SNBP</dt>
                        <dd class="text-lg font-medium text-gray-900"><?= $snbpCount ?></dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Jalur SNBT -->
              <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Jalur SNBT</dt>
                        <dd class="text-lg font-medium text-gray-900"><?= $snbtCount ?></dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Jalur Mandiri -->
              <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Jalur Mandiri</dt>
                        <dd class="text-lg font-medium text-gray-900"><?= $mandiriCount ?></dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
              <!-- Bar Chart - Program Study Interest -->
              <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Peminat per Program Studi</h3>
                <div style="height: 300px;">
                  <canvas id="barChart"></canvas>
                </div>
              </div>

              <!-- Pie Chart - Admission Status -->
              <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status Kelulusan</h3>
                <div style="height: 300px;">
                  <canvas id="pieChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Additional Statistics Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
              <!-- Line Chart - Score Distribution -->
              <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Distribusi Nilai Ujian</h3>
                <div style="height: 300px;">
                  <canvas id="scoreChart"></canvas>
                </div>
              </div>

              <!-- Stacked Bar Chart - Status by Path -->
              <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status Kelulusan per Jalur</h3>
                <div style="height: 300px;">
                  <canvas id="pathStatusChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Filters and Table -->
            <div class="bg-white shadow rounded-lg">
              <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                  <h3 class="text-lg font-medium text-gray-900">Daftar Mahasiswa</h3>
                  <div class="mt-3 sm:mt-0 sm:ml-4 flex space-x-3">
                    <form method="GET" action="" class="flex space-x-3">
                      <select name="jalur" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" onchange="this.form.submit()">
                        <option <?= !isset($_GET['jalur']) || $_GET['jalur'] === 'Semua Jalur' ? 'selected' : '' ?>>Semua Jalur</option>
                        <option <?= isset($_GET['jalur']) && $_GET['jalur'] === 'SNBP' ? 'selected' : '' ?>>SNBP</option>
                        <option <?= isset($_GET['jalur']) && $_GET['jalur'] === 'SNBT' ? 'selected' : '' ?>>SNBT</option>
                        <option <?= isset($_GET['jalur']) && $_GET['jalur'] === 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                      </select>

                      <!-- Filter Status -->
                      <select name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" onchange="this.form.submit()">
                        <option <?= !isset($_GET['status']) || $_GET['status'] === 'Semua Status' ? 'selected' : '' ?>>Semua Status</option>
                        <option <?= isset($_GET['status']) && $_GET['status'] === 'Lolos P1' ? 'selected' : '' ?>>Lolos P1</option>
                        <option <?= isset($_GET['status']) && $_GET['status'] === 'Lolos P2' ? 'selected' : '' ?>>Lolos P2</option>
                        <option <?= isset($_GET['status']) && $_GET['status'] === 'Dialihkan' ? 'selected' : '' ?>>Dialihkan</option>
                        <option <?= isset($_GET['status']) && $_GET['status'] === 'Tidak Lulus' ? 'selected' : '' ?>>Tidak Lulus</option>
                      </select>

                    </form>

                    <!-- Calculate Admissions -->
                    <form method="POST" action="" class="flex">
                      <button type="submit" name="calculate_admission" value="true" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Hitung Kelulusan
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pendaftaran</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($student = $students->fetch_assoc()): ?>
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($student['nisn']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($student['nama']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($student['prodi_nama']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        <?= $student['jalur_pendaftaran'] === 'SNBP' ? 'bg-green-100 text-green-800' : ($student['jalur_pendaftaran'] === 'SNBT' ? 'bg-yellow-100 text-yellow-800' :
                          'bg-purple-100 text-purple-800') ?>">
                        <?= htmlspecialchars($student['jalur_pendaftaran']) ?>
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        <?= $student['status'] === 'Lolos P1' ? 'bg-green-100 text-green-800' : ($student['status'] === 'Lolos P2' ? 'bg-blue-100 text-blue-800' : ($student['status'] === 'Dialihkan' ? 'bg-orange-100 text-orange-800' :
                          'bg-red-100 text-red-800')) ?>">
                        <?= htmlspecialchars($student['status']) ?>
                      </span>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
              <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
              <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Menampilkan <span class="font-medium"><?= $offset + 1 ?></span> sampai <span class="font-medium"><?= min($offset + $limit, $totalRecords) ?></span> dari <span class="font-medium"><?= $totalRecords ?></span> hasil
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <a href="?page=<?= max(1, $page - 1) ?><?= isset($_GET['jalur']) ? '&jalur=' . $_GET['jalur'] : '' ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>"
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </a>


                  <a href="?page=<?= min($totalPages, $page + 1) ?><?= isset($_GET['jalur']) ? '&jalur=' . $_GET['jalur'] : '' ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>"
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                  </a>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Logout Confirmation Modal -->
  <div id="logoutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
          <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mt-4">Konfirmasi Logout</h3>
        <div class="mt-2 px-7 py-3">
          <p class="text-sm text-gray-500">
            Apakah Anda yakin ingin keluar dari sistem? Anda akan diarahkan ke halaman login.
          </p>
        </div>
        <div class="flex justify-center space-x-3 mt-4">
          <button
            onclick="cancelLogout()"
            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
            Batal
          </button>
          <button
            onclick="confirmLogout()"
            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
            Ya, Logout
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Bar Chart - Peminat per Program Studi
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($prodiLabels) ?>,
        datasets: [{
          label: 'Jumlah Peminat',
          data: <?= json_encode($prodiData) ?>,
          backgroundColor: [
            'rgba(59, 130, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(139, 92, 246, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(6, 182, 212, 0.8)'
          ],
          borderColor: [
            'rgba(59, 130, 246, 1)',
            'rgba(16, 185, 129, 1)',
            'rgba(245, 158, 11, 1)',
            'rgba(139, 92, 246, 1)',
            'rgba(239, 68, 68, 1)',
            'rgba(6, 182, 212, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });

    // Score Distribution Chart
    const scoreCtx = document.getElementById('scoreChart').getContext('2d');
    const scoreChart = new Chart(scoreCtx, {
      type: 'line',
      data: {
        labels: <?= json_encode($scoreLabels) ?>,
        datasets: [{
          label: 'Jumlah Mahasiswa',
          data: <?= json_encode($scoreData) ?>,
          backgroundColor: 'rgba(59, 130, 246, 0.2)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 2,
          pointBackgroundColor: 'rgba(59, 130, 246, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 1,
          pointRadius: 4,
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        },
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            callbacks: {
              title: function(context) {
                return 'Range Nilai: ' + context[0].label;
              },
              label: function(context) {
                return 'Jumlah: ' + context.raw + ' mahasiswa';
              }
            }
          }
        }
      }
    });

    // Status by Path Chart
    const pathStatusCtx = document.getElementById('pathStatusChart').getContext('2d');
    const pathStatusChart = new Chart(pathStatusCtx, {
      type: 'bar',
      data: {
        labels: ['SNBP', 'SNBT', 'Mandiri'],
        datasets: [{
            label: 'Lolos P1',
            data: [
              <?= $pathStatusData['SNBP']['Lolos P1'] ?>,
              <?= $pathStatusData['SNBT']['Lolos P1'] ?>,
              <?= $pathStatusData['Mandiri']['Lolos P1'] ?>
            ],
            backgroundColor: 'rgba(16, 185, 129, 0.8)',
            borderColor: 'rgba(16, 185, 129, 1)',
            borderWidth: 1
          },
          {
            label: 'Lolos P2',
            data: [
              <?= $pathStatusData['SNBP']['Lolos P2'] ?>,
              <?= $pathStatusData['SNBT']['Lolos P2'] ?>,
              <?= $pathStatusData['Mandiri']['Lolos P2'] ?>
            ],
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1
          },
          {
            label: 'Dialihkan',
            data: [
              <?= $pathStatusData['SNBP']['Dialihkan'] ?>,
              <?= $pathStatusData['SNBT']['Dialihkan'] ?>,
              <?= $pathStatusData['Mandiri']['Dialihkan'] ?>
            ],
            backgroundColor: 'rgba(245, 158, 11, 0.8)',
            borderColor: 'rgba(245, 158, 11, 1)',
            borderWidth: 1
          },
          {
            label: 'Tidak Lulus',
            data: [
              <?= $pathStatusData['SNBP']['Tidak Lulus'] ?>,
              <?= $pathStatusData['SNBT']['Tidak Lulus'] ?>,
              <?= $pathStatusData['Mandiri']['Tidak Lulus'] ?>
            ],
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: 'rgba(239, 68, 68, 1)',
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            stacked: true,
          },
          y: {
            stacked: true,
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        },
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return context.dataset.label + ': ' + context.raw + ' mahasiswa';
              }
            }
          }
        }
      }
    });
    // Pie Chart - Status Kelulusan
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
      type: 'pie',
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
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 20,
              usePointStyle: true
            }
          }
        }
      }
    });

    // Mobile menu toggle
    function toggleMobileMenu() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('hidden');
    }

    // Logout functionality
    function handleLogout() {
      document.getElementById('logoutModal').classList.remove('hidden');
    }

    function cancelLogout() {
      document.getElementById('logoutModal').classList.add('hidden');
    }

    function confirmLogout() {
      // Show loading state
      const modal = document.getElementById('logoutModal');
      modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
              <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Sedang Logout...</h3>
            <p class="text-sm text-gray-500 mt-2">Mohon tunggu sebentar</p>
          </div>
        </div>
      `;

      // Create and submit a form to the same page with logout parameter
      const form = document.createElement('form');
      form.method = 'post';
      form.action = window.location.href;

      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'logout';
      input.value = 'true';

      form.appendChild(input);
      document.body.appendChild(form);

      // Small delay to show the loading state
      setTimeout(() => {
        form.submit();
      }, 800);
    }

    // Filter functionality
    document.querySelectorAll('select').forEach(select => {
      select.addEventListener('change', function() {
        console.log('Filter changed:', this.value);
        // Implement filter logic here
      });
    });

    // Edit and Delete functionality
    document.querySelectorAll('button').forEach(button => {
      if (button.textContent.includes('Edit')) {
        button.addEventListener('click', function() {
          alert('Edit functionality - akan membuka modal edit');
        });
      } else if (button.textContent.includes('Hapus')) {
        button.addEventListener('click', function() {
          if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            alert('Data berhasil dihapus');
          }
        });
      }
    });

    // Close modal when clicking outside
    document.getElementById('logoutModal').addEventListener('click', function(e) {
      if (e.target === this) {
        cancelLogout();
      }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
      // ESC to close modal
      if (e.key === 'Escape') {
        cancelLogout();
      }

      // Ctrl+L for logout
      if (e.ctrlKey && e.key === 'l') {
        e.preventDefault();
        handleLogout();
      }
    });
  </script>
</body>

</html>
