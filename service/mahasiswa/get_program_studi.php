<?php
require_once __DIR__ . "/../db.php";

$sql = "SELECT kode, jenjang, nama FROM program_studi";
$result = $conn->query($sql);

$programStudi = [];
while ($row = $result->fetch_assoc()) {
    $programStudi[] = $row;
}
