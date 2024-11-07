<?php
$servername = "localhost";  // Nama server, biasanya "localhost"
$username = "tunf4484_supangat";         // Username database
$password = "Gopr!007";             // Password database
$dbname = "tunf4484_supangat40";  // Nama database yang digunakan

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
