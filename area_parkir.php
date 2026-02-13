<?php 
include 'koneksi.php'; 

// Fungsi eksekusi query agar lebih aman
function eksekusiQuery($koneksi, $sql) {
    return mysqli_query($koneksi, $sql);
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Area Parkir - Parkir-In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { width: 260px; position: fixed; height: 100vh; z-index: 1000; }
        .main-content { margin-left: 260px; padding: 20px; }
        .card-custom { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; }
    </style>
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="sidebar bg-dark text-white p-3 shadow d-flex flex-column">
        <div class="d-flex align-items-center mb-4">
            <i class="bi bi-p-square-fill fs-2 text-primary me-2"></i>
            <h4 class="mb-0 fw-bold">Parkir-In</h4>
        </div>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a href="dashboard.php" class="nav-link text-white mb-2"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="transaksi.php" class="nav-link text-white mb-2"><i class="bi bi-receipt me-2"></i> Transaksi</a></li>
            <li class="nav-item"><a href="area_parkir.php" class="nav-link active mb-2"><i class="bi bi-geo-alt me-2"></i> Area Parkir</a></li>
        </ul>
    </div>

    <div class="main-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Manajemen Area Parkir</h2>
                <p class="text-muted">Kelola lokasi dan kapasitas slot parkir.</p>
            </div>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahArea">
                <i class="bi bi-plus-lg me-2"></i> Tambah Area
            </button>
        </div>

        <div class="card card-custom">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4">Nama Area</th>
                            <th>Tipe</th>
                            <th>Kapasitas</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Memanggil tabel area_parkir
                        $sql = "SELECT * FROM area_parkir ORDER BY nama_area ASC";
                        $query = eksekusiQuery($koneksi, $sql);

                        if ($query && mysqli_num_rows($query) > 0):
                            while($row = mysqli_fetch_assoc($query)):
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold"><?= $row['nama_area']; ?></div>
                                <small class="text-muted">ID: #<?= isset($row['id']) ? $row['id'] : $row['id_area']; ?></small>
                            </td>
                            <td><span class="badge bg-info-subtle text-info"><?= $row['tipe_kendaraan']; ?></span></td>
                            <td><?= $row['kapasitas']; ?> Slot</td>
                            <td><span class="badge bg-success-subtle text-success"><?= isset($row['status']) ? $row['status'] : 'Tersedia'; ?></span></td>
                            <td class="text-center">
                                <a href="proses_area.php?aksi=hapus&id=<?= isset($row['id']) ? $row['id'] : $row['id_area']; ?>" 
                                   class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus area ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data di tabel area_parkir.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahArea" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Area Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="proses_area.php?aksi=tambah" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Area</label>
                        <input type="text" name="nama_area" class="form-control" placeholder="Gedung A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe Kendaraan</label>
                        <select name="tipe_kendaraan" class="form-select">
                            <option value="Mobil">Mobil</option>
                            <option value="Motor">Motor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kapasitas</label>
                        <input type="number" name="kapasitas" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 py-2">Simpan Area</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>