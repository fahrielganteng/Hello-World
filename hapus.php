


<?php
session_start(); // Memulai sesi PHP untuk menggunakan variabel session

include "koneksi.php"; // Menyertakan file koneksi.php yang berisi kode untuk menghubungkan ke database

// Mengecek apakah parameter 'id' ada di URL dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Mengambil ID barang dari parameter URL dan memastikan tipe data adalah integer

    // Menyusun query SQL menggunakan prepared statements
    $stmt = $db->prepare("DELETE FROM barang WHERE id_barang = ?");
    $stmt->bind_param("i", $id);

    // Menjalankan query SQL pada database
    if ($stmt->execute()) {
        // Jika berhasil, set pesan sukses dalam session dan arahkan ke halaman lihat.php
        $_SESSION['pesan'] = "Berhasil menghapus barang";
        header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
        exit(); // Hentikan eksekusi script setelah redirect
    } else {
        // Jika gagal, tampilkan pesan error dan hentikan eksekusi script
        die("Gagal menghapus barang: " . $stmt->error);
    }

    // Menutup statement
    $stmt->close();
} else {
    die("ID tidak valid.");
}

// Menutup koneksi ke database
$db->close();
?>
