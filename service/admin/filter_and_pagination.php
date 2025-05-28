<?php
include_once __DIR__ . '/../db.php';
// Fetch summary data
$totalStudents = $conn->query("SELECT COUNT(*) as total FROM mahasiswa")->fetch_assoc()['total'];
$snbpCount = $conn->query("SELECT COUNT(*) as count FROM mahasiswa WHERE jalur_pendaftaran = 'SNBP'")->fetch_assoc()['count'];
$snbtCount = $conn->query("SELECT COUNT(*) as count FROM mahasiswa WHERE jalur_pendaftaran = 'SNBT'")->fetch_assoc()['count'];
$mandiriCount = $conn->query("SELECT COUNT(*) as count FROM mahasiswa WHERE jalur_pendaftaran = 'Mandiri'")->fetch_assoc()['count'];

// Fetch program study data for chart
$prodiQuery = $conn->query("
    SELECT ps.nama, COUNT(m.nisn) as jumlah
    FROM program_studi ps
    LEFT JOIN mahasiswa m ON ps.kode = m.prodi_1_kode OR ps.kode = m.prodi_2_kode
    GROUP BY ps.kode
    ORDER BY jumlah DESC
    LIMIT 6
");

$prodiLabels = [];
$prodiData = [];
while ($row = $prodiQuery->fetch_assoc()) {
  $prodiLabels[] = $row['nama'];
  $prodiData[] = $row['jumlah'];
}

// Fetch test score distribution data for chart
$scoreQuery = $conn->query("
    SELECT 
        CASE 
            WHEN skor_ujian < 45 THEN 'Below 45'
            WHEN skor_ujian BETWEEN 45 AND 49.9 THEN '45-49.9'
            WHEN skor_ujian BETWEEN 50 AND 54.9 THEN '50-54.9'
            WHEN skor_ujian BETWEEN 55 AND 59.9 THEN '55-59.9'
            WHEN skor_ujian BETWEEN 60 AND 64.9 THEN '60-64.9'
            WHEN skor_ujian BETWEEN 65 AND 69.9 THEN '65-69.9'
            WHEN skor_ujian BETWEEN 70 AND 74.9 THEN '70-74.9'
            WHEN skor_ujian BETWEEN 75 AND 79.9 THEN '75-79.9'
            WHEN skor_ujian BETWEEN 80 AND 84.9 THEN '80-84.9'
            WHEN skor_ujian BETWEEN 85 AND 89.9 THEN '85-89.9'
            WHEN skor_ujian >= 90 THEN '90+'
        END as score_range,
        COUNT(*) as count
    FROM mahasiswa
    GROUP BY score_range
    ORDER BY MIN(skor_ujian)
");

$scoreLabels = [];
$scoreData = [];
while ($row = $scoreQuery->fetch_assoc()) {
  $scoreLabels[] = $row['score_range'];
  $scoreData[] = $row['count'];
}

// Fetch admission status by path data for chart
$pathStatusQuery = $conn->query("
    SELECT 
        jalur_pendaftaran,
        CASE
            WHEN prodi_1_kode = prodi_2_kode THEN 'Lolos P1'
            WHEN status_kelulusan = 'lulus' AND prodi_1_kode != prodi_2_kode THEN 'Lolos P2'
            WHEN status_kelulusan = 'tidak lulus' THEN 'Tidak Lulus'
            ELSE 'Dialihkan'
        END as status,
        COUNT(*) as count
    FROM mahasiswa
    GROUP BY jalur_pendaftaran, status
    ORDER BY jalur_pendaftaran, status
");

$pathStatusData = [];
$allStatuses = ['Lolos P1', 'Lolos P2', 'Dialihkan', 'Tidak Lulus'];
$allPaths = ['SNBP', 'SNBT', 'Mandiri'];

foreach ($allPaths as $path) {
  $pathStatusData[$path] = array_fill_keys($allStatuses, 0);
}

while ($row = $pathStatusQuery->fetch_assoc()) {
  $pathStatusData[$row['jalur_pendaftaran']][$row['status']] = $row['count'];
}
// Fetch status data for pie chart
$statusQuery = $conn->query("
    SELECT 
        CASE
            WHEN prodi_1_kode = prodi_2_kode THEN 'Lolos P1'
            WHEN status_kelulusan = 'lulus' THEN 'Lolos P2'
            WHEN status_kelulusan = 'tidak lulus' THEN 'Tidak Lulus'
            ELSE 'Dialihkan'
        END as status,
        COUNT(*) as jumlah
    FROM mahasiswa
    GROUP BY status
");

$statusLabels = [];
$statusData = [];
while ($row = $statusQuery->fetch_assoc()) {
  $statusLabels[] = $row['status'];
  $statusData[] = $row['jumlah'];
}

// Fetch students for table with pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filterJalur = isset($_GET['jalur']) && $_GET['jalur'] !== 'Semua Jalur' ? $_GET['jalur'] : null;
$filterStatus = isset($_GET['status']) && $_GET['status'] !== 'Semua Status' ? $_GET['status'] : null;

$where = "";
$params = [];
$types = "";

if ($filterJalur) {
  $where .= " WHERE m.jalur_pendaftaran = ?";
  $params[] = $filterJalur;
  $types .= "s";
}

if ($filterStatus) {
  if ($where === "") {
    $where .= " WHERE ";
  } else {
    $where .= " AND ";
  }

  if ($filterStatus === "Lolos P1") {
    $where .= "m.prodi_1_kode = m.prodi_2_kode";
  } elseif ($filterStatus === "Lolos P2") {
    $where .= "m.status_kelulusan = 'lulus' AND m.prodi_1_kode != m.prodi_2_kode";
  } elseif ($filterStatus === "Tidak Lulus") {
    $where .= "m.status_kelulusan = 'tidak lulus'";
  } elseif ($filterStatus === "Dialihkan") {
    $where .= "m.prodi_1_kode != m.prodi_2_kode AND m.status_kelulusan = 'lulus'";
  }
}

$countQuery = "SELECT COUNT(*) as total FROM mahasiswa m" . $where;
$stmt = $conn->prepare($countQuery);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalRecords = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

$query = "
    SELECT 
        m.nisn, 
        m.nama, 
        ps.nama as prodi_nama, 
        m.jalur_pendaftaran,
        CASE
            WHEN m.prodi_1_kode = m.prodi_2_kode THEN 'Lolos P1'
            WHEN m.status_kelulusan = 'lulus' THEN 'Lolos P2'
            WHEN m.status_kelulusan = 'tidak lulus' THEN 'Tidak Lulus'
            ELSE 'Dialihkan'
        END as status
    FROM mahasiswa m
    JOIN program_studi ps ON m.prodi_1_kode = ps.kode
    $where
    ORDER BY m.nisn DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($query);
if (!empty($params)) {
  $params[] = $limit;
  $params[] = $offset;
  $types .= "ii";
  $stmt->bind_param($types, ...$params);
} else {
  $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$students = $stmt->get_result();
