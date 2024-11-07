<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM jenis_pendanaan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row); // Kirim data dalam format JSON
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }

    $stmt->close();
}
$conn->close();
?>
