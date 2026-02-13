<?php 
include 'koneksi.php'; 

function eksekusiQuery($koneksi, $sql) {
    $result = mysqli_query($koneksi, $sql);
    return $result;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - Parkir-In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { width: 260px; position: fixed; top: 0; left: 0; height: 100vh; z-index: 1000; transition: all 0.3s; }
        .main-content { margin-left: 260px; padding: 20px; }
        .nav-link { color: rgba(255,255,255,.8); margin-bottom: 5px; border-radius: 8px; }
        .nav-link:hover, .nav-link.active { color: #fff; background-color: #0d6efd !important; }
        .nav-link i { margin-right: 10px; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .timeline-item { border-left: 2px solid #e9ecef; padding-left: 20px; position: relative; padding-bottom: 20px; }
        .timeline-item::before { 
            content: ""; position: absolute; left: -7px; top: 0; 
            width: 12px; height: 12px; border-radius: 50%; background: #0d6efd; 
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
                <li class="nav-item"><a href="kelola_user.php" class="nav-link <?= ($current_page == 'kelola_user.php') ? 'active' : ''; ?>"><i class="bi bi-people"></i> Kelola User</a></li>
                <li class="nav-item"><a href="kelola_tarif.php" class="nav-link <?= ($current_page == 'kelola_tarif.php') ? 'active' : ''; ?>"><i class="bi bi-tags"></i> Kelola Tarif</a></li>
                <li class="nav-item"><a href="area_parkir.php" class="nav-link <?= ($current_page == 'area_parkir.php') ? 'active' : ''; ?>"><i class="bi bi-geo-alt"></i> Area Parkir</a></li>
                <li class="nav-item"><a href="log_aktivitas.php" class="nav-link <?= ($current_page == 'log_aktivitas.php') ? 'active' : ''; ?>"><i class="bi bi-clock-history"></i> Log Aktivitas</a></li>
                <li class="nav-item"><a href="laporan_transaksi.php" class="nav-link <?= ($current_page == 'laporan_transaksi.php') ? 'active' : ''; ?>"><i class="bi bi-file-earmark-bar-graph"></i> Laporan Transaksi</a></li>
            </ul>
        </div>
        <div class="mt-auto pt-3">
            <hr>
            <a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
        </div>
    </div>

    <div class="main-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Log Aktivitas Sistem</h2>
                <p class="text-muted">Riwayat tindakan pengguna dan sistem secara real-time.</p>
            </div>
            <button class="btn btn-outline-primary rounded-pill px-4" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh Log
            </button>
        </div>

        <div class="card card-custom">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start">Waktu</th>
                                <th class="border-0">User ID</th>
                                <th class="border-0">Aktivitas</th>
                                <th class="border-0 rounded-end text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query mengambil data log, diurutkan dari yang terbaru
                            $sql = "SELECT * FROM log_aktivitas ORDER BY waktu_aktivitas DESC";
                            $query = eksekusiQuery($koneksi, $sql);

                            if ($query && mysqli_num_rows($query) > 0):
                                while($row = mysqli_fetch_assoc($query)):
                                    // Menentukan warna badge berdasarkan kata kunci aktivitas
                                    $badge_class = "bg-primary";
                                    if(strpos(strtolower($row['aktivitas']), 'hapus') !== false) $badge_class = "bg-danger";
                                    if(strpos(strtolower($row['aktivitas']), 'tambah') !== false) $badge_class = "bg-success";
                                    if(strpos(strtolower($row['aktivitas']), 'login') !== false) $badge_class = "bg-info";
                            ?>
                            <tr>
                                <td style="width: 200px;">
                                    <div class="small fw-bold text-dark">
                                        <?= date('d M Y', strtotime($row['waktu_aktivitas'])); ?>
                                    </div>
                                    <div class="small text-muted">
                                        <?= date('H:i:s', strtotime($row['waktu_aktivitas'])); ?> WIB
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 me-2">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                        <span class="fw-medium">User #<?= $row['id_user']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark"><?= $row['aktivitas']; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $badge_class; ?> bg-opacity-10 text-<?= str_replace('bg-', '', $badge_class); ?> px-3 py-2">
                                        Recorded
                                    </span>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                    Belum ada log aktivitas tercatat.
                                </td>
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