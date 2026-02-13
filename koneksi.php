<?php
// konfigurasi koneksi
$host = "localhost";        // biasanya localhost
$username = "root";         // username database (default: root)
$password = "";             // password database
$database = "parkir"; // ganti dengan nama database kamu

// membuat koneksi
$koneksi = new mysqli($host, $username, $password, $database);

// cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// jika berhasil
// echo "Berhasil terhubung ke database!";
?>
