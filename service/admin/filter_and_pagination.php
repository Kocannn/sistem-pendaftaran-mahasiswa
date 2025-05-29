<?php
include_once __DIR__ . '/../db.php';
// Fetch summary data
$totalStudents = $conn->query("SELECT COUNT(*) as total FROM mahasiswa")->fetch_assoc()['total'];
$snbpCount = $conn->query("SELECT COUNT(*) as count FROM mahasiswa WHERE jalur_pendaftaran = 'SNBP'")->fetch_assoc()['count'];
$snbtCount = $conn->query("SELECT COUNT(*) as count FROM mahasiswa WHERE jalur_pendaftaran = 'SNBT'")->fetch_assoc()['count'];
$mandiriCount = $conn->query("SELECT COUNT(*) as count FROM mahasiswa WHERE jalur_pendaftaran = 'Mandiri'")->fetch_assoc()['count'];

// Fetch program study data for chart - IMPROVED VERSION
$prodiQuery = $conn->query("
    SELECT 
        ps.kode,
        ps.nama,
        ps.jenjang,
        COUNT(DISTINCT CASE WHEN m.prodi_1_kode = ps.kode THEN m.nisn END) AS pilihan_1_count,
        COUNT(DISTINCT CASE WHEN m.prodi_2_kode = ps.kode THEN m.nisn END) AS pilihan_2_count,
        ps.daya_tampung
    FROM 
        program_studi ps
    LEFT JOIN 
        mahasiswa m ON ps.kode = m.prodi_1_kode OR ps.kode = m.prodi_2_kode
    GROUP BY 
        ps.kode
    ORDER BY 
        (COUNT(DISTINCT CASE WHEN m.prodi_1_kode = ps.kode THEN m.nisn END) + 
         COUNT(DISTINCT CASE WHEN m.prodi_2_kode = ps.kode THEN m.nisn END)) DESC
    LIMIT 10
");

$prodiLabels = [];
$prodiPilihan1 = [];
$prodiPilihan2 = [];
$prodiTotalPeminat = [];
$prodiDayaTampung = [];

while ($row = $prodiQuery->fetch_assoc()) {
  $prodiLabels[] = $row['jenjang'] . ' - ' . $row['nama'];
  $prodiPilihan1[] = (int)$row['pilihan_1_count'];
  $prodiPilihan2[] = (int)$row['pilihan_2_count'];
  $prodiTotalPeminat[] = (int)$row['pilihan_1_count'] + (int)$row['pilihan_2_count'];
  $prodiDayaTampung[] = (int)$row['daya_tampung'];
}

// Fetch status data for pie chart - UPDATED FOR NEW ENUM VALUES
$statusQuery = $conn->query("
    SELECT 
        status_kelulusan as status,
        COUNT(*) as jumlah
    FROM mahasiswa
    GROUP BY status_kelulusan
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
        m.status_kelulusan
    FROM 
        mahasiswa m
    LEFT JOIN program_studi ps ON m.prodi_1_kode = ps.kode
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
?>
