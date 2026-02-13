<?php
session_start();
include 'koneksi.php';

// PROSES SIMPAN
if (isset($_POST['simpan'])) {
    $plat  = mysqli_real_escape_string($koneksi, $_POST['plat']);
    $jenis = mysqli_real_escape_string($koneksi, $_POST['jenis']);

    if ($plat != '' && $jenis != '') {
        $query = mysqli_query($koneksi, "
            INSERT INTO kendaraan (plat_nomor, jenis_kendaraan, waktu_masuk, status)
            VALUES ('$plat', '$jenis', NOW(), 'Masuk')
        ");

        if ($query) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Gagal menyimpan data!";
        }
    } else {
        $error = "Form tidak boleh kosong!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kendaraan Masuk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

<!-- HEADER -->
<div class="d-flex align-items-center mb-4">
  <a href="dashboard.php" class="btn btn-sm btn-secondary me-2">⬅</a>
  <h5 class="mb-0">🚘 Kendaraan Masuk</h5>
</div>

<!-- ALERT -->
<?php if (!empty($error)) { ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<!-- FORM -->
<div class="card shadow-sm border-0">
  <div class="card-body">

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Plat Nomor</label>
        <input type="text" name="plat" class="form-control"
               placeholder="Contoh: AB 1234 CD" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Jenis Kendaraan</label>
        <select name="jenis" class="form-select" required>
          <option value="">-- Pilih --</option>
          <option value="Motor">Motor</option>
          <option value="Mobil">Mobil</option>
        </select>
      </div>

      <button type="submit" name="simpan" class="btn btn-success w-100">
        ✅ Simpan Kendaraan Masuk
      </button>
    </form>

  </div>
</div>

</div>

</body>
</html>
