<?php 
include 'koneksi.php'; 

function eksekusiQuery($koneksi, $sql) {
    $result = mysqli_query($koneksi, $sql);
    return $result;
}

$current_page = basename($_SERVER['PHP_SELF']);

// Logika Filter Tanggal
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-t');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Parkir-In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { width: 260px; position: fixed; top: 0; left: 0; height: 100vh; z-index: 1000; transition: all 0.3s; }
        .main-content { margin-left: 260px; padding: 20px; }
        .nav-link { color: rgba(255,255,255,.8); margin-bottom: 5px; border-radius: 8px; }
        .nav-link:hover, .nav-link.active { color: #fff; background-color: #0d6efd !important; }
        .nav-link i { margin-right: 10px; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        @media print {
            .sidebar, .btn-filter, .btn-print { display: none !important; }
            .main-content { margin-left: 0; padding: 0; }
        }
    </style>
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="sidebar bg-dark text-white p-3 shadow d-flex flex-column">
        <div>
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-p-square-fill fs-2 text-primary me-2"></i>
                <h4 class="mb-0 fw-bold">Parkir-In</h4>
            </div>
            <hr>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a href="transaksi.php" class="nav-link <?= ($current_page == 'transaksi.php') ? 'active' : ''; ?>"><i class="bi bi-receipt"></i> Transaksi</a></li>
                <li class="nav-item"><a href="kelola_user.php" class="nav-link"><i class="bi bi-people"></i> Kelola User</a></li>
                <li class="nav-item"><a href="kelola_tarif.php" class="nav-link"><i class="bi bi-tags"></i> Kelola Tarif</a></li>
                <li class="nav-item"><a href="area_parkir.php" class="nav-link"><i class="bi bi-geo-alt"></i> Area Parkir</a></li>
                <li class="nav-item"><a href="log_aktivitas.php" class="nav-link"><i class="bi bi-clock-history"></i> Log Aktivitas</a></li>
                <li class="nav-item"><a href="laporan_transaksi.php" class="nav-link active"><i class="bi bi-file-earmark-bar-graph"></i> Laporan Transaksi</a></li>
            </ul>
        </div>
        <div class="mt-auto pt-3">
            <hr><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
        </div>
    </div>

    <div class="main-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Laporan Transaksi Parkir</h2>
                <p class="text-muted">Data rekapitulasi pendapatan dan durasi parkir.</p>
            </div>
            <button class="btn btn-success rounded-pill px-4 btn-print" onclick="window.print()">
                <i class="bi bi-printer me-2"></i> Cetak Laporan
            </button>
        </div>

        <div class="card card-custom mb-4 btn-filter">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai; ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i> Filter Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom p-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">ID Transaksi</th>
                                <th>Area</th>
                                <th>Waktu Masuk / Keluar</th>
                                <th>Durasi</th>
                                <th>Total Biaya</th>
                                <th>Petugas</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query Join antar tabel jika perlu, di sini fokus ke database yang disebutkan
                            $sql = "SELECT * FROM transaksi WHERE waktu_masuk BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59' ORDER BY waktu_masuk DESC";
                            $query = eksekusiQuery($koneksi, $sql);
                            
                            $total_pendapatan = 0;

                            if ($query && mysqli_num_rows($query) > 0):
                                while($row = mysqli_fetch_assoc($query)):
                                    $total_pendapatan += $row['biaya_total'];
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-primary">#TRX-<?= $row['id_parkir']; ?></div>
                                    <small class="text-muted">Tarif ID: <?= $row['id_tarif']; ?></small>
                                </td>
                                <td><span class="badge bg-secondary">Area ID: <?= $row['id_area']; ?></span></td>
                                <td>
                                    <div class="small">In: <?= $row['waktu_masuk']; ?></div>
                                    <div class="small text-danger">Out: <?= $row['waktu_keluar'] ?? '-'; ?></div>
                                </td>
                                <td><?= $row['durasi_jam']; ?> Jam</td>
                                <td class="fw-bold">Rp <?= number_format($row['biaya_total'], 0, ',', '.'); ?></td>
                                <td>User ID: <?= $row['id_user']; ?></td>
                                <td class="text-center">
                                    <span class="badge <?= ($row['status'] == 'Selesai') ? 'bg-success' : 'bg-warning'; ?> px-3">
                                        <?= $row['status']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <tr class="table-dark">
                                <td colspan="4" class="text-end fw-bold ps-4">TOTAL PENDAPATAN :</td>
                                <td colspan="3" class="fw-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">Data transaksi tidak ditemukan pada periode ini.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>