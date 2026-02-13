<?php
include 'koneksi.php';
$aksi = $_GET['aksi'];

if ($aksi == 'tambah') {
    $nama = $_POST['nama_area'];
    $tipe = $_POST['tipe_kendaraan'];
    $kapasitas = $_POST['kapasitas'];

    $sql = "INSERT INTO area_parkir (nama_area, tipe_kendaraan, kapasitas) VALUES ('$nama', '$tipe', '$kapasitas')";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: area_parkir.php");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>