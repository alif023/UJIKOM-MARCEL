<?php
require 'koneksi.php';

// ==========================================
// LOGIKA PROSES KENDARAAN KELUAR (CHECKOUT)
// ==========================================
if (isset($_GET['aksi']) && $_GET['aksi'] == 'keluar') {
    $id = intval($_GET['id']);
    
    // Ambil data kendaraan berdasarkan id_parkir
    $query = "SELECT * FROM transaksi WHERE id_parkir = $id";
    $hasil = mysqli_query($koneksi, $query);

    if (!$hasil) {
        die("Query Error: " . mysqli_error($koneksi));
    }

    $data = mysqli_fetch_assoc($hasil);

    if ($data && $data['status'] == 'masuk') {
        $waktu_masuk  = strtotime($data['waktu_masuk']);
        $waktu_keluar = time(); 
        $format_keluar = date("Y-m-d H:i:s", $waktu_keluar);

        // Hitung Durasi (Pembulatan ke atas)
        $detik  = $waktu_keluar - $waktu_masuk;
        $jam    = ceil($detik / 3600); 
        if ($jam < 1) $jam = 1;

        // Logika Tarif Sederhana
        $tarif_per_jam = ($data['jenis_kendaraan'] == 'Mobil') ? 5000 : 2000;
        $total_bayar = $jam * $tarif_per_jam;

        // Update status menjadi keluar
        $update = "UPDATE transaksi SET 
                   waktu_keluar = '$format_keluar', 
                   durasi_jam   = '$jam', 
                   biaya_total  = '$total_bayar', 
                   status       = 'keluar' 
                   WHERE id_parkir = $id";
        
        if (mysqli_query($koneksi, $update)) {
            echo "<script>
                    alert('BERHASIL CHECKOUT!\\nPlat: {$data['plat_nomor']}\\nDurasi: $jam Jam\\nTotal Bayar: Rp " . number_format($total_bayar,0,',','.') . "');
                    window.location.href='transaksi.php';
                  </script>";
        } else {
            echo "<div class='bg-red-600 text-white p-4'>Gagal Update: " . mysqli_error($koneksi) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Transaksi - Parkir-In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">
    <aside class="w-64 bg-slate-900 text-white p-6 hidden md:flex flex-col">
        <div class="flex items-center mb-8">
            <i class="bi bi-p-square-fill text-blue-500 text-2xl mr-2"></i>
            <h1 class="text-2xl font-bold">Parkir-In</h1>
        </div>
        <nav class="space-y-3 flex-1">
            <a href="dashboard.php" class="block py-2 px-3 hover:bg-slate-800 rounded text-slate-400">
                <i class="bi bi-speedometer2 mr-2"></i> Dashboard
            </a>
            <a href="input_masuk.php" class="block py-2 px-3 hover:bg-slate-800 rounded text-slate-400">
                <i class="bi bi-plus-circle mr-2"></i> Input Masuk
            </a>
            <a href="transaksi.php" class="block py-2 px-3 bg-blue-600 rounded text-white">
                <i class="bi bi-table mr-2"></i> Transaksi
            </a>
        </nav>
        <div class="border-t border-slate-800 pt-4">
            <a href="logout.php" class="flex items-center py-2 px-3 text-red-400 hover:bg-red-900/20 rounded transition">
                <i class="bi bi-box-arrow-right mr-2"></i> Keluar Akun
            </a>
        </div>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Manajemen Transaksi</h2>
                <p class="text-slate-500 text-sm">Proses kendaraan keluar dan pantau durasi parkir.</p>
            </div>
            <a href="input_masuk.php" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:bg-blue-700 transition flex items-center font-semibold">
                <i class="bi bi-plus-lg mr-2"></i> Kendaraan Masuk
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="p-4 bg-slate-50 border-b font-bold text-slate-700 flex items-center">
                <i class="bi bi-clock-history mr-2 text-orange-500"></i> Kendaraan Sedang Parkir
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="p-4">Plat Nomor</th>
                            <th class="p-4">Jenis</th>
                            <th class="p-4">Waktu Masuk</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php
                        // Ambil hanya yang statusnya 'masuk'
                        $query_tabel = "SELECT * FROM transaksi WHERE status='masuk' ORDER BY id_parkir DESC";
                        $tampil = mysqli_query($koneksi, $query_tabel);
                        
                        if ($tampil && mysqli_num_rows($tampil) > 0) {
                            while ($row = mysqli_fetch_assoc($tampil)) {
                        ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-bold text-blue-700"><?= $row['plat_nomor']; ?></td>
                            <td class="p-4">
                                <span class="px-2 py-1 bg-slate-100 rounded text-xs text-slate-600 font-medium">
                                    <?= $row['jenis_kendaraan']; ?>
                                </span>
                            </td>
                            <td class="p-4 text-sm text-slate-500">
                                <i class="bi bi-calendar3 mr-1"></i> <?= date('d/m H:i', strtotime($row['waktu_masuk'])); ?>
                            </td>
                            <td class="p-4 text-center">
                                <a href="?aksi=keluar&id=<?= $row['id_parkir']; ?>" 
                                   onclick="return confirm('Proses checkout kendaraan <?= $row['plat_nomor']; ?>?')"
                                   class="bg-orange-500 text-white px-4 py-2 rounded-lg font-bold text-xs hover:bg-orange-600 shadow-md transition-all active:scale-95 flex items-center justify-center inline-flex">
                                   <i class="bi bi-box-arrow-up-right mr-1"></i> KELUAR PARKIR
                                </a>
                            </td>
                        </tr>
                        <?php } } else { ?>
                        <tr>
                            <td colspan="4" class="p-16 text-center text-slate-400">
                                <i class="bi bi-inbox text-5xl block mb-3 opacity-20"></i>
                                <p class="italic text-lg">Belum ada kendaraan di dalam area parkir.</p>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-6 text-xs text-slate-400 font-medium italic">* Tarif otomatis: Mobil Rp 5.000/jam, Motor Rp 2.000/jam (minimal 1 jam).</p>
    </main>
</div>

</body>
</html>