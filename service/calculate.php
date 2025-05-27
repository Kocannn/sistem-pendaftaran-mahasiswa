<?php

/**
 * Calculate admission status and placement based on score and application path
 * 
 * @param string $jalurPendaftaran The application path (SNBP, SNBT, Mandiri)
 * @param float $skorUjian The exam score
 * @param string $prodi1 The first program choice code
 * @param string $prodi2 The second program choice code
 * @return array Contains status info, new jalur (if redirected), and program placement
 */
function calculateAdmission($jalurPendaftaran, $skorUjian, $prodi1, $prodi2)
{
  // Initialize result array
  $result = [
    'status' => '',
    'jalur_baru' => $jalurPendaftaran,
    'prodi_diterima' => $prodi1, // Default to prodi1
    'status_kelulusan' => 'lulus', // Default to lulus
    'prodi_1_kode' => $prodi1,
    'prodi_2_kode' => $prodi2,
    'message' => ''
  ];

  // SNBP Path rules
  if ($jalurPendaftaran === 'SNBP') {
    if ($skorUjian >= 85) {
      $result['status'] = 'Lolos Pilihan 1';
      $result['message'] = 'Selamat! Anda lolos seleksi pada program studi pilihan pertama.';
    } elseif ($skorUjian >= 75.1 && $skorUjian <= 84.9) {
      $result['status'] = 'Lolos Pilihan 2';
      $result['prodi_diterima'] = $prodi2;
      $result['message'] = 'Anda lolos seleksi pada program studi pilihan kedua.';
    } else {
      // Score < 75 - Redirect to SNBT
      $result['status'] = 'Dialihkan ke SNBT';
      $result['jalur_baru'] = 'SNBT';
      $result['message'] = 'Anda dialihkan ke jalur SNBT. Silakan ikuti prosedur selanjutnya.';
    }
  }

  // SNBT Path rules
  elseif ($jalurPendaftaran === 'SNBT') {
    if ($skorUjian >= 65.1 && $skorUjian <= 74.9) {
      $result['status'] = 'Lolos Pilihan 1';
      $result['message'] = 'Selamat! Anda lolos seleksi pada program studi pilihan pertama.';
    } elseif ($skorUjian >= 55.1 && $skorUjian <= 65.0) {
      $result['status'] = 'Lolos Pilihan 2';
      $result['prodi_diterima'] = $prodi2;
      $result['message'] = 'Anda lolos seleksi pada program studi pilihan kedua.';
    } else {
      // Score < 55 - Redirect to Mandiri
      $result['status'] = 'Dialihkan ke Mandiri';
      $result['jalur_baru'] = 'Mandiri';
      $result['message'] = 'Anda dialihkan ke jalur Mandiri. Silakan ikuti prosedur selanjutnya.';
    }
  }

  // Mandiri Path rules
  elseif ($jalurPendaftaran === 'Mandiri') {
    if ($skorUjian >= 50.0 && $skorUjian <= 55.0) {
      $result['status'] = 'Lolos Pilihan 1';
      $result['message'] = 'Selamat! Anda lolos seleksi pada program studi pilihan pertama.';
    } elseif ($skorUjian >= 45.0 && $skorUjian <= 49.9) {
      $result['status'] = 'Lolos Pilihan 2';
      $result['prodi_diterima'] = $prodi2;
      $result['message'] = 'Anda lolos seleksi pada program studi pilihan kedua.';
    } else {
      // Score < 45 - Not admitted
      $result['status'] = 'Tidak Lulus';
      $result['status_kelulusan'] = 'tidak lulus';
      $result['prodi_diterima'] = null;
      $result['message'] = 'Mohon maaf, Anda tidak lolos seleksi.';
    }
  }

  return $result;
}

/**
 * Apply the admission results to update a student record
 * 
 * @param mysqli $conn Database connection
 * @param string $nisn Student ID
 * @param array $admissionResult Result from calculateAdmission function
 * @return bool Success or failure
 */
function applyAdmissionResult($conn, $nisn, $admissionResult)
{
  $query = "UPDATE mahasiswa SET 
                jalur_pendaftaran = ?,
                status_kelulusan = ?,
                prodi_1_kode = ?,
                prodi_2_kode = ?
              WHERE nisn = ?";

  $stmt = $conn->prepare($query);
  $stmt->bind_param(
    "sssss",
    $admissionResult['jalur_baru'],
    $admissionResult['status_kelulusan'],
    $admissionResult['prodi_1_kode'],
    $admissionResult['prodi_2_kode'],
    $nisn
  );

  return $stmt->execute();
}
