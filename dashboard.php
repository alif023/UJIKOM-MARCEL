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
    <title>Admin Dashboard Parkir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        .nav-link {
            color: rgba(255,255,255,.8);
            margin-bottom: 5px;
            border-radius: 8px;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background-color: #0d6efd !important;
        }
        .nav-link i {
            margin-right: 10px;
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
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <!-- TAMBAHAN MENU TRANSAKSI -->
                <li class="nav-item">
                    <a href="transaksi.php" class="nav-link <?= ($current_page == 'transaksi.php') ? 'active' : ''; ?>">
                        <i class="bi bi-receipt"></i> Transaksi
                    </a>
                </li>

                <li class="nav-item">
                    <a href="kelola_user.php" class="nav-link">
                        <i class="bi bi-people"></i> Kelola User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kelola_tarif.php" class="nav-link">
                        <i class="bi bi-tags"></i> Kelola Tarif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="area_parkir.php" class="nav-link">
                        <i class="bi bi-geo-alt"></i> Area Parkir
                    </a>
                </li>
                <li class="nav-item">
                    <a href="log_aktivitas.php" class="nav-link">
                        <i class="bi bi-clock-history"></i> Log Aktivitas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="laporan_transaksi.php" class="nav-link">
                        <i class="bi bi-file-earmark-bar-graph"></i> Laporan Transaksi
                    </a>
                </li>
            </ul>
        </div>

        <div class="mt-auto pt-3">
            <hr>
            <a href="logout.php" class="nav-link text-danger">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </a>
        </div>
    </div>

    <div class="main-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Ringkasan Hari Ini</h2>
            <div class="text-muted"><?= date('l, d F Y'); ?></div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-3 rounded-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary text-white rounded-3 p-3">
                            <i class="bi bi-car-front-fill fs-3"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0 small">Kendaraan Parkir</p>
                            <h4 class="mb-0 fw-bold">
                                <?php 
                                $res = eksekusiQuery($koneksi, "SELECT COUNT(*) as total FROM kendaraan WHERE status='Parkir'");
                                if (!$res) $res = eksekusiQuery($koneksi, "SELECT COUNT(*) as total FROM kendaraan");
                                echo mysqli_fetch_assoc($res)['total'] ?? 0;
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-3 rounded-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success text-white rounded-3 p-3">
                            <i class="bi bi-cash-stack fs-3"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0 small">Laporan Transaksi (Total)</p>
                            <h4 class="mb-0 fw-bold">
                                <?php 
                                $res = eksekusiQuery($koneksi, "SELECT SUM(biaya) as total FROM kendaraan");
                                $total = ($res) ? mysqli_fetch_assoc($res)['total'] : 0;
                                echo "Rp " . number_format($total ?? 0, 0, ',', '.');
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mt-2">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">Live Monitoring Parkir</h5>
                    <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Plat Nomor</th>
                                <th>Waktu Masuk</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM kendaraan WHERE status='Parkir' ORDER BY id DESC LIMIT 5";
                            $query = eksekusiQuery($koneksi, $sql);
                            if (!$query) $query = eksekusiQuery($koneksi, "SELECT * FROM kendaraan ORDER BY id DESC LIMIT 5");

                            if ($query && mysqli_num_rows($query) > 0):
                                while($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td><span class="badge bg-secondary py-2 px-3 fs-6"><?= $row['plat_nomor']; ?></span></td>
                                <td><small class="text-muted"><?= $row['jam_masuk']; ?></small></td>
                                <td><span class="badge bg-warning text-dark px-3">Aktif</span></td>
                                <td class="text-center">
                                    <a href="transaksi.php" class="btn btn-sm btn-light border rounded-pill px-3">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                                <tr><td colspan="4" class="text-center text-muted py-4">
                                    Belum ada data kendaraan masuk hari ini.
                                </td></tr>
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
