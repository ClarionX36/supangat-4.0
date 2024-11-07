<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Pendanaan Dihapus</title>
    <link rel="stylesheet" href="hstyle.css">
</head>
<body>

<!-- Tombol Back to Home -->
<a href="index.php" id="backButton">Back to Home</a>

<h1>Riwayat Penghapusan Pendanaan</h1>

<!-- Input untuk pencarian -->
<input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari berdasarkan nama pendanaan...">

<table border="1" id="dataTable">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Pendanaan</th>
            <th>Deskripsi Singkat</th>
            <th>Nominal</th>
            <th>Code Name</th>
            <th>Tanggal Rilis</th>
            <th>Expiry</th>
            <th>Tipe Pendanaan</th>
            <th>Tanggal Penghapusan</th>
            <th>Detail Khusus</th>
        </tr>
    </thead>
    <tbody>
    <?php
    include 'koneksi.php';
    $result = $conn->query("SELECT * FROM riwayat_penghapusan ORDER BY deleted_at DESC");
    $no = 1;

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$no}</td>
            <td>{$row['nama_pendanaan']}</td>
            <td>{$row['deskripsi_singkat']}</td>
            <td>" . number_format($row['nominal'], 0, ',', '.') . "</td>
            <td>{$row['code_name']}</td>
            <td>" . date("d/m/Y", strtotime($row['tanggal_rilis'])) . "</td>
            <td>" . date("d/m/Y", strtotime($row['expiry'])) . "</td>
            <td>{$row['tipe_pendanaan']}</td>
            <td>" . date("d/m/Y H:i", strtotime($row['deleted_at'])) . "</td>
            <td>";
        
        if ($row['tipe_pendanaan'] === 'khusus') {
            $riwayatId = $row['id'];
            $detailResult = $conn->query("SELECT jk.namaJenjang, kl.namaKlasifikasi, jb.namaJabatan 
                FROM riwayat_pendanaan_khusus rpk
                LEFT JOIN jenjang jk ON rpk.jenjang_id = jk.id
                LEFT JOIN klasifikasi kl ON rpk.klasifikasi_id = kl.id
                LEFT JOIN jabatan jb ON rpk.jabatan_id = jb.id
                WHERE rpk.riwayat_penghapusan_id = $riwayatId");

            $jenjangArray = [];
            $klasifikasiArray = [];
            $jabatanArray = [];

            while ($detailRow = $detailResult->fetch_assoc()) {
                if (!empty($detailRow['namaJenjang']) && !in_array($detailRow['namaJenjang'], $jenjangArray)) {
                    $jenjangArray[] = $detailRow['namaJenjang'];
                }
                if (!empty($detailRow['namaKlasifikasi']) && !in_array($detailRow['namaKlasifikasi'], $klasifikasiArray)) {
                    $klasifikasiArray[] = $detailRow['namaKlasifikasi'];
                }
                if (!empty($detailRow['namaJabatan']) && !in_array($detailRow['namaJabatan'], $jabatanArray)) {
                    $jabatanArray[] = $detailRow['namaJabatan'];
                }
            }

            echo "<ul>";
            echo "<li>Jenjang: " . (empty($jenjangArray) ? "N/A" : implode(', ', $jenjangArray)) . "</li>";
            echo "<li>Klasifikasi: " . (empty($klasifikasiArray) ? "N/A" : implode(', ', $klasifikasiArray)) . "</li>";
            echo "<li>Jabatan: " . (empty($jabatanArray) ? "N/A" : implode(', ', $jabatanArray)) . "</li>";
            echo "</ul>";
        } else {
            echo "N/A";
        }

        echo "</td></tr>";
        $no++;
    }
    ?>
    </tbody>
</table>

<script>
    function searchTable() {
        let input = document.getElementById("searchInput");
        let filter = input.value.toUpperCase();
        let table = document.getElementById("dataTable");
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                let txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }       
        }
    }
</script>

</body>
</html>
