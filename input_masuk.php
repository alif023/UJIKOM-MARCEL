<?php

require 'koneksi.php';
// ... sisa kode kamu

if (isset($_POST['btn_simpan'])) {
    $id_user         = $_POST['id_user'];
    $plat_nomor      = mysqli_real_escape_string($koneksi, $_POST['plat_nomor']);
    $jenis_kendaraan = mysqli_real_escape_string($koneksi, $_POST['jenis_kendaraan']);
    $warna           = mysqli_real_escape_string($koneksi, $_POST['warna']);
    $pemilik         = mysqli_real_escape_string($koneksi, $_POST['pemilik']);
    $waktu_masuk     = date("Y-m-d H:i:s");

    // Query disesuaikan dengan struktur tabel di atas
    $sql = "INSERT INTO transaksi (plat_nomor, jenis_kendaraan, warna, pemilik, id_user, waktu_masuk, status) 
            VALUES ('$plat_nomor', '$jenis_kendaraan', '$warna', '$pemilik', '$id_user', '$waktu_masuk', 'masuk')";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
                alert('Berhasil! Kendaraan $plat_nomor telah masuk.');
                window.location.href='transaksi.php';
              </script>";
    } else {
        echo "<div class='bg-red-600 text-white p-5'>Error: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Kendaraan - Parkir-In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-slate-100">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-slate-900 text-white p-6 hidden md:block">
            <div class="flex items-center mb-8">
                <i class="bi bi-p-square-fill text-blue-500 text-2xl mr-2"></i>
                <h1 class="text-2xl font-bold">Parkir-In</h1>
            </div>
            <aside class="w-64 bg-slate-900 text-white p-6 hidden md:block flex flex-col">
    <div class="flex items-center mb-8">
        <i class="bi bi-p-square-fill text-blue-500 text-2xl mr-2"></i>
        <h1 class="text-2xl font-bold">Parkir-In</h1>
    </div>
    
    <nav class="space-y-3 flex-1">
        <a href="dashboard.php" class="block py-2 px-3 hover:bg-slate-800 rounded">
            <i class="bi bi-speedometer2 mr-2"></i> Dashboard
        </a>
        <a href="transaksi.php" class="block py-2 px-3 hover:bg-slate-800 rounded">
            <i class="bi bi-table mr-2"></i> Transaksi
        </a>
        <a href="input_masuk.php" class="block py-2 px-3 bg-blue-600 rounded">
            <i class="bi bi-plus-circle mr-2"></i> Input Masuk
        </a>
    </nav>

    <div class="mt-auto pt-10">
        <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')" 
           class="flex items-center py-2 px-3 text-red-400 hover:bg-red-900/30 hover:text-red-500 rounded transition-all">
            <i class="bi bi-box-arrow-right mr-2"></i>
            <span class="font-bold">KELUAR</span>
        </a>
    </div>
</aside>
        </aside>

        <main class="flex-1 p-8">
            <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg border p-8">
                <h2 class="text-2xl font-bold mb-6 text-slate-800">Check-In Kendaraan</h2>
                <form action="" method="POST" class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Plat Nomor</label>
                        <input type="text" name="plat_nomor" required class="w-full border-2 rounded-xl p-3 uppercase font-bold text-xl focus:border-blue-500 outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Pemilik</label>
                            <input type="text" name="pemilik" required class="w-full border-2 rounded-xl p-3">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Warna</label>
                            <input type="text" name="warna" required class="w-full border-2 rounded-xl p-3">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" required class="w-full border-2 rounded-xl p-3">
                            <option value="Motor">Motor</option>
                            <option value="Mobil">Mobil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Petugas Petugas</label>
                        <select name="id_user" class="w-full border-2 rounded-xl p-3">
                            <?php
                            $users = mysqli_query($koneksi, "SELECT * FROM user");
                            while($u = mysqli_fetch_assoc($users)) {
                                echo "<option value='".$u['id_user']."'>".$u['nama_lengkap']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="btn_simpan" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 transition">
                        SIMPAN DATA
                    </button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>