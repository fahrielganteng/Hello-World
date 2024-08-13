<?php
session_start(); // Memulai sesi PHP untuk menggunakan variabel session

include "koneksi.php"; // Menyertakan file koneksi.php yang berisi kode untuk menghubungkan ke database

// Mengecek apakah form telah disubmit
if (isset($_POST['submit'])) {
    // Mengambil ID barang dari URL dan mengamankan nilai tersebut
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $nama = trim($_POST['nama_barang']); // Mengambil nama barang dari input form
    $harga = trim($_POST['harga_barang']); // Mengambil harga barang dari input form
    $stok = trim($_POST['stok_barang']); // Mengambil stok barang dari input form

    // Validasi input
    if (empty($nama) || empty($harga) || empty($stok) || !is_numeric($harga) || !is_numeric($stok)) {
        die("Input tidak valid.");
    }

    // Menyusun query SQL menggunakan prepared statements
    $stmt = $db->prepare("UPDATE barang SET nama_barang = ?, harga_barang = ?, stok_barang = ? WHERE id_barang = ?");
    $stmt->bind_param("siii", $nama, $harga, $stok, $id);

    // Menjalankan query SQL pada database
    if ($stmt->execute()) {
        // Jika berhasil, set pesan sukses dalam session dan arahkan ke halaman lihat.php
        $_SESSION['pesan'] = "Berhasil mengedit barang";
        header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
        exit(); // Hentikan eksekusi script setelah redirect
    } else {
        // Jika gagal, tampilkan pesan error dan hentikan eksekusi script
        die("Gagal mengedit barang: " . $stmt->error);
    }

    // Menutup statement
    $stmt->close();

    // Menutup koneksi ke database
    $db->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
</head>
<body>
    <h2>Form Edit Barang</h2>
    <!-- Form untuk mengedit barang -->
    <form action="edit.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="post">
        <?php
        // Mengecek apakah ID barang ada di URL
        if (isset($_GET['id'])) {
            // Mengambil ID barang dari URL dan mengamankan nilai tersebut
            $id = intval($_GET['id']); // Mengambil ID barang dari URL

            // Menyusun query SQL menggunakan prepared statements
            $stmt = $db->prepare("SELECT * FROM barang WHERE id_barang = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Mengecek apakah query mengembalikan hasil data
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc(); // Mengambil data hasil query
        ?>
                <!-- Input form dengan nilai awal diisi dengan data barang yang ada -->
                <label for="nama_barang">Nama Barang:</label><br>
                <input type="text" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required><br><br>
                <label for="harga_barang">Harga Barang:</label><br>
                <input type="number" id="harga_barang" name="harga_barang" value="<?php echo htmlspecialchars($data['harga_barang']); ?>" required><br><br>
                <label for="stok_barang">Stok Barang:</label><br>
                <input type="number" id="stok_barang" name="stok_barang" value="<?php echo htmlspecialchars($data['stok_barang']); ?>" required><br><br>
        <?php
            } else {
                echo "Barang tidak ditemukan.";
            }

            // Menutup statement
            $stmt->close();
        }
        ?>
        <!-- Tombol submit untuk mengirim data ke server -->
        <input type="submit" value="Edit Barang" name="submit">
        <!-- Link untuk kembali ke halaman lihat.php -->
        <a href="lihat.php">Kembali</a>
    </form>
</body>
</html>
