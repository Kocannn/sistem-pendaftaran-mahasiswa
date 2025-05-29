<?php
session_start();
require_once __DIR__ . '/../db.php';

// Check if the student is authenticated
if (!isset($_SESSION['auth']) || empty($_SESSION['auth']['nisn'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get the student's NISN
$nisn = $_SESSION['auth']['nisn'];

// Check if the request is a POST request with JSON data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
        exit;
    }
    
    // Extract the score
    $score = $data['score'] ?? 0;
    
    // Update the student's score in the database
    $stmt = $conn->prepare("UPDATE mahasiswa SET skor_ujian = ? WHERE nisn = ?");
    $stmt->bind_param("ds", $score, $nisn);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Score saved successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to save score: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

$conn->close();