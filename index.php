<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pendanaan</title>
    <link rel="stylesheet" href="sustyle.css">
</head>
<body>

<!-- Tombol untuk menambahkan pendanaan umum atau khusus -->
<button onclick="showForm('umum')">+ Pendanaan Umum</button>
<button onclick="showForm('khusus')">+ Pendanaan Khusus</button>
<a href="history.php">Lihat Histori Pendanaan</a>
<br>
<br>
<!-- Tabel Daftar Pendanaan -->
<table border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Pendanaan</th>
            <th>Deskripsi Singkat</th>
            <th>Target Pendanaan</th>
            <th>Nominal</th>
            <th>Code Name</th>
            <th>Tanggal Rilis</th>
            <th>Expiry</th>
            <th>Capaian</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
    <?php
    include 'koneksi.php';
    $result = $conn->query("SELECT * FROM jenis_pendanaan");
    $no = 1;
    $today = date("Y-m-d");

    while ($row = $result->fetch_assoc()) {
        // Format tanggal expiry menjadi dd/mm/yyyy dan tentukan kelas warna berdasarkan status
        $tanggalRilisFormatted = date("d/m/Y", strtotime($row['tanggalRilis']));
        $expiryDate = date_create_from_format('Y-m-d', $row['expiry']);
        $expiryFormatted = $expiryDate->format('d/m/Y');
        $expiryClass = ($row['expiry'] >= $today) ? 'active-expiry' : 'expired-expiry';

        // Format nominal dengan pemisah ribuan
        $nominalFormatted = number_format($row['nominal'], 0, ',', '.');

        echo "<tr>
            <td>{$no}</td>
            <td>{$row['namaPendanaan']}</td>
            <td>{$row['deskripsiSingkat']}</td>
            <td>{$row['tipePendanaan']}</td>
            <td>{$nominalFormatted}</td>
            <td>{$row['codeName']}</td>
            <td>{$tanggalRilisFormatted}</td>
            <td class='{$expiryClass}'>{$expiryFormatted}</td>
            <td></td>
            <td>
                <button onclick=\"editPendanaan({$row['id']}, '{$row['tipePendanaan']}')\">Edit</button>
                <button onclick=\"confirmDelete({$row['id']})\">Hapus</button>
            </td>
        </tr>";
        $no++;
    }
    ?>
    </tbody>
</table>

<!-- Popup form untuk pendanaan umum -->
<div id="formUmum" class="modal">
    <div class="modal-content">
        <span class="close-popup" onclick="closeForm('umum')">&times;</span>
        <h3>Tambah Pendanaan Umum</h3>
        <form method="post" action="simpan_pendanaan.php" onsubmit="return submitForm(event, 'umum')">
            <input type="hidden" name="tipePendanaan" value="umum">
            <label>Nama Pendanaan: <input type="text" name="namaPendanaan" required></label><br>
            <label>Deskripsi Singkat: <textarea name="deskripsiSingkat"></textarea></label><br>
            <label>Nominal: <input type="text" name="nominal" id="nominalUmum" onkeyup="formatThousand(this)" required></label><br>
            <label>Codename: <input type="text" name="codeName" required></label><br>
            <label for="tanggalRilisUmum">Tanggal Rilis</label>
            <input type="date" id="tanggalRilisUmum" name="tanggalRilis" required>
            <label>Expiry: <input type="date" name="expiry"></label><br>
            <button type="submit">Simpan</button>
        </form>
    </div>
</div>

<!-- Popup form untuk pendanaan khusus -->
<div id="formKhusus" class="modal">
    <div class="modal-content popup-content">
        <span class="close-popup" onclick="closeForm('khusus')">&times;</span>
        <h3>Tambah Pendanaan Khusus</h3>
        <form method="post" action="simpan_pendanaan.php" onsubmit="return submitForm(event, 'khusus')">
            <input type="hidden" name="tipePendanaan" value="khusus">
            <label>Nama Pendanaan: <input type="text" name="namaPendanaan" required></label><br>
            <label>Deskripsi Singkat: <textarea name="deskripsiSingkat"></textarea></label><br>
            <label>Nominal: <input type="text" name="nominal" id="nominalKhusus" onkeyup="formatThousand(this)" required></label><br>
            <label>Codename: <input type="text" name="codeName" required></label><br>
            <label for="tanggalRilisKhusus">Tanggal Rilis</label>
            <input type="date" id="tanggalRilisKhusus" name="tanggalRilis" required>
            <label>Expiry: <input type="date" name="expiry"></label><br>
            <!-- Checkbox untuk Jenjang, Klasifikasi, dan Jabatan -->
            <div>
                <h4>Pilih Jenjang</h4>
                <?php
                $result = $conn->query("SELECT * FROM jenjang");
                while ($row = $result->fetch_assoc()) {
                    echo '<label><input type="checkbox" name="jenjang[]" value="' . $row['id'] . '"> ' . $row['namaJenjang'] . '</label><br>';
                }
                ?>
            </div>
            <div>
                <h4>Pilih Klasifikasi</h4>
                <?php
                $result = $conn->query("SELECT * FROM klasifikasi");
                while ($row = $result->fetch_assoc()) {
                    echo '<label><input type="checkbox" name="klasifikasi[]" value="' . $row['id'] . '"> ' . $row['namaKlasifikasi'] . '</label><br>';
                }
                ?>
            </div>
            <div>
                <h4>Pilih Jabatan</h4>
                <?php
                $result = $conn->query("SELECT * FROM jabatan");
                while ($row = $result->fetch_assoc()) {
                    echo '<label><input type="checkbox" name="jabatan[]" value="' . $row['id'] . '"> ' . $row['namaJabatan'] . '</label><br>';
                }
                ?>
            </div>
            <button type="submit">Simpan</button>
        </form>
    </div>
</div>

<!-- Popup form untuk edit pendanaan -->
<!-- Popup form untuk edit pendanaan -->
<div id="formEdit" class="modal">
    <div class="modal-content">
        <span class="close-popup" onclick="closeForm('edit')">&times;</span>
        <h3>Edit Pendanaan</h3>
        <form id="editForm" method="post" action="edit_pendanaan.php">
            <input type="hidden" name="id" id="editId">
            <label>Nama Pendanaan: <input type="text" name="namaPendanaan" id="editNamaPendanaan" required></label><br>
            <label>Deskripsi Singkat: <textarea name="deskripsiSingkat" id="editDeskripsiSingkat"></textarea></label><br>
            <label>Nominal: <input type="text" name="nominal" id="editNominal" onkeyup="formatThousand(this)" required></label><br>
            <label>Codename: <input type="text" name="codeName" id="editCodeName" required></label><br>
            <label for="editTanggalRilis">Tanggal Rilis</label>
            <input type="date" id="editTanggalRilis" name="tanggalRilis" required>
            <label>Expiry: <input type="date" name="expiry" id="editExpiry"></label><br>

            <!-- Checkbox untuk Jenjang -->
            <div>
                <h4>Pilih Jenjang</h4>
                <?php
                $result = $conn->query("SELECT * FROM jenjang");
                while ($row = $result->fetch_assoc()) {
                    echo '<label><input type="checkbox" name="jenjang[]" value="' . $row['id'] . '"> ' . $row['namaJenjang'] . '</label><br>';
                }
                ?>
            </div>

            <!-- Checkbox untuk Klasifikasi -->
            <div>
                <h4>Pilih Klasifikasi</h4>
                <?php
                $result = $conn->query("SELECT * FROM klasifikasi");
                while ($row = $result->fetch_assoc()) {
                    echo '<label><input type="checkbox" name="klasifikasi[]" value="' . $row['id'] . '"> ' . $row['namaKlasifikasi'] . '</label><br>';
                }
                ?>
            </div>

            <!-- Checkbox untuk Jabatan -->
            <div>
                <h4>Pilih Jabatan</h4>
                <?php
                $result = $conn->query("SELECT * FROM jabatan");
                while ($row = $result->fetch_assoc()) {
                    echo '<label><input type="checkbox" name="jabatan[]" value="' . $row['id'] . '"> ' . $row['namaJabatan'] . '</label><br>';
                }
                ?>
            </div>

            <button type="submit">Simpan</button>
        </form>
    </div>
</div>


<script>
// Fungsi untuk menampilkan form popup
function showForm(type) {
    document.getElementById('form' + type.charAt(0).toUpperCase() + type.slice(1)).style.display = 'flex';
}

// Fungsi untuk menutup form popup
function closeForm(type) {
    document.getElementById('form' + type.charAt(0).toUpperCase() + type.slice(1)).style.display = 'none';
}

// Fungsi untuk edit pendanaan
function editPendanaan(id, tipePendanaan) {
    fetch(`get_pendanaan.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editId').value = data.id; // Set ID
            document.getElementById('editNamaPendanaan').value = data.namaPendanaan;
            document.getElementById('editDeskripsiSingkat').value = data.deskripsiSingkat;
            document.getElementById('editNominal').value = data.nominal; // No format here for editing
            document.getElementById('editCodeName').value = data.codeName;
            document.getElementById('editTanggalRilis').value = data.tanggalRilis;
            document.getElementById('editExpiry').value = data.expiry;
            showForm('Edit');
        })
        .catch(error => console.error('Error:', error));
}

// Fungsi untuk mengkonfirmasi penghapusan data
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch(`hapus_pendanaan.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                alert(data.message); // Tampilkan pesan hasil penghapusan
                location.reload(); // Refresh halaman segera setelah pengguna menutup alert
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data.');
                location.reload(); // Tetap refresh jika terjadi error, untuk keamanan
            });
    }
}
// Fungsi format angka ribuan untuk tampilan
function formatThousand(input) {
    const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
    input.value = value ? parseInt(value).toLocaleString() : ''; // Format with thousands separator
}

// Fungsi untuk menangani pengiriman form
function submitForm(event, type) {
    event.preventDefault(); // Prevent default form submission
    const form = event.target; // Get the form element
    const nominalInput = form.querySelector('input[name="nominal"]');
    
    // Remove thousands separator before sending to the server
    nominalInput.value = nominalInput.value.replace(/\./g, '');

    // Submit the form
    form.submit();
}
</script>

</body>
</html>
