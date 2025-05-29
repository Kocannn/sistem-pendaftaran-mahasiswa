<?php
session_start();
require_once __DIR__ . '/../db.php';

// Check if user is authenticated
if (!isset($_SESSION['auth']) || !isset($_SESSION['auth']['nisn'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check if this is a POST request with score data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $nisn = $_SESSION['auth']['nisn'];
    $score = floatval($_POST['score']);
    
    // Validate score range (0-100)
    if ($score < 0 || $score > 100) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid score value']);
        exit;
    }
    
    try {
        // Update the student's exam score
        $stmt = $conn->prepare("UPDATE mahasiswa SET skor_ujian = ? WHERE nisn = ?");
        $stmt->bind_param("ds", $score, $nisn);
        
        if ($stmt->execute()) {
            // Calculate admission status based on score
            include_once __DIR__ . '/../calculate.php';
            
            // Get student data needed for calculation
            $studentQuery = $conn->prepare("SELECT jalur_pendaftaran, prodi_1_kode, prodi_2_kode FROM mahasiswa WHERE nisn = ?");
            $studentQuery->bind_param("s", $nisn);
            $studentQuery->execute();
            $studentData = $studentQuery->get_result()->fetch_assoc();
            
            // Calculate admission result
            $admissionResult = calculateAdmission(
                $studentData['jalur_pendaftaran'],
                $score,
                $studentData['prodi_1_kode'],
                $studentData['prodi_2_kode']
            );
            
            // Apply the admission result
            applyAdmissionResult($conn, $nisn, $admissionResult);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Score updated successfully',
                'score' => $score,
                'admission_status' => $admissionResult['status'],
                'status_kelulusan' => $admissionResult['status_kelulusan']
            ]);
        } else {
            throw new Exception("Failed to update score");
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
