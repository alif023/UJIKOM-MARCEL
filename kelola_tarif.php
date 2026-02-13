<?php
require 'koneksi.php';

$success = "";
$error = "";

// ==========================
// PROSES TAMBAH
// ==========================
if (isset($_POST['tambah'])) {

    $jenis = trim($_POST['jenis_kendaraan']);
    $tarif = floatval($_POST['tarif_per_jam']);

    if ($jenis == "" || $tarif <= 0) {
        $error = "Data tidak valid!";
    } else {

        $stmt = $koneksi->prepare("INSERT INTO tarif (jenis_kendaraan, tarif_per_jam) VALUES (?, ?)");

        if (!$stmt) {
            die("Prepare INSERT gagal: " . $koneksi->error);
        }

        $stmt->bind_param("sd", $jenis, $tarif);
        $stmt->execute();
        $stmt->close();

        $success = "Tarif berhasil ditambahkan!";
    }
}

// ==========================
// PROSES EDIT
// ==========================
if (isset($_POST['edit'])) {

    $id = intval($_POST['id']);
    $jenis = trim($_POST['jenis_kendaraan']);
    $tarif = floatval($_POST['tarif_per_jam']);

    if ($jenis == "" || $tarif <= 0) {
        $error = "Data tidak valid!";
    } else {

        $stmt = $koneksi->prepare("UPDATE tarif SET jenis_kendaraan=?, tarif_per_jam=? WHERE id_tarif=?");

        if (!$stmt) {
            die("Prepare UPDATE gagal: " . $koneksi->error);
        }

        $stmt->bind_param("sdi", $jenis, $tarif, $id);
        $stmt->execute();
        $stmt->close();

        $success = "Tarif berhasil diperbarui!";
    }
}

// ==========================
// PROSES HAPUS
// ==========================
if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']);

    $stmt = $koneksi->prepare("DELETE FROM tarif WHERE id_tarif=?");

    if (!$stmt) {
        die("Prepare DELETE gagal: " . $koneksi->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: kelola_tarif.php");
    exit;
}

// ==========================
// AMBIL DATA
// ==========================
$tarif = $koneksi->query("SELECT * FROM tarif ORDER BY id_tarif DESC");

if (!$tarif) {
    die("Query gagal: " . $koneksi->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Tarif Parkir</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">

<main class="flex-1 p-8">

    <h2 class="text-2xl font-bold mb-6">Kelola Tarif Parkir</h2>

    <?php if($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Tombol Tambah -->
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
        class="mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Tarif
    </button>

    <!-- Tabel -->
    <div class="bg-white rounded shadow">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Jenis Kendaraan</th>
                    <th class="p-3 text-left">Tarif / Jam</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($tarif->num_rows > 0): ?>
                <?php while($row = $tarif->fetch_assoc()): ?>
                <tr class="border-t">
                    <td class="p-3 capitalize">
                        <?= htmlspecialchars($row['jenis_kendaraan']) ?>
                    </td>
                    <td class="p-3">
                        Rp <?= number_format($row['tarif_per_jam'],0,',','.') ?>
                    </td>
                    <td class="p-3 text-center space-x-2">

                        <button onclick='editTarif(
                            <?= $row["id_tarif"] ?>,
                            <?= json_encode($row["jenis_kendaraan"]) ?>,
                            <?= $row["tarif_per_jam"] ?>
                        )'
                        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                        Edit
                        </button>

                        <a href="?hapus=<?= $row['id_tarif'] ?>"
                           onclick="return confirm('Yakin hapus tarif ini?')"
                           class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                           Hapus
                        </a>

                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="p-4 text-center text-gray-400">
                        Belum ada data tarif.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>
</div>

<!-- MODAL TAMBAH -->
<div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
<div class="bg-white p-6 rounded w-96">
<h3 class="font-bold mb-4">Tambah Tarif</h3>

<form method="POST">
<input type="text" name="jenis_kendaraan"
class="w-full border p-2 mb-3 rounded"
placeholder="Jenis Kendaraan" required>

<input type="number" name="tarif_per_jam"
class="w-full border p-2 mb-3 rounded"
placeholder="Tarif per jam" required>

<div class="text-right">
<button type="button"
onclick="document.getElementById('modalTambah').classList.add('hidden')"
class="mr-2">Batal</button>

<button type="submit" name="tambah"
class="bg-blue-600 text-white px-4 py-2 rounded">
Simpan
</button>
</div>
</form>
</div>
</div>

<!-- MODAL EDIT -->
<div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
<div class="bg-white p-6 rounded w-96">
<h3 class="font-bold mb-4">Edit Tarif</h3>

<form method="POST">
<input type="hidden" name="id" id="edit_id">

<input type="text" name="jenis_kendaraan"
id="edit_jenis"
class="w-full border p-2 mb-3 rounded" required>

<input type="number" name="tarif_per_jam"
id="edit_tarif"
class="w-full border p-2 mb-3 rounded" required>

<div class="text-right">
<button type="button"
onclick="document.getElementById('modalEdit').classList.add('hidden')"
class="mr-2">Batal</button>

<button type="submit" name="edit"
class="bg-yellow-500 text-white px-4 py-2 rounded">
Update
</button>
</div>
</form>
</div>
</div>

<script>
function editTarif(id, jenis, tarif) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_jenis').value = jenis;
    document.getElementById('edit_tarif').value = tarif;
    document.getElementById('modalEdit').classList.remove('hidden');
}
</script>

</body>
</html>
