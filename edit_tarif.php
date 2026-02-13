<?php
require 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: kelola_tarif.php");
    exit;
}

$id = intval($_GET['id']);
$data = $koneksi->query("SELECT * FROM tarif WHERE id_tarif = $id")->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

if (isset($_POST['update'])) {
    $jenis = $_POST['jenis_kendaraan'];
    $tarif = $_POST['tarif_per_jam'];

    $stmt = $koneksi->prepare("UPDATE tarif SET jenis_kendaraan=?, tarif_per_jam=? WHERE id_tarif=?");
    $stmt->bind_param("sdi", $jenis, $tarif, $id);

    if ($stmt->execute()) {
        header("Location: kelola_tarif.php");
        exit;
    } else {
        echo "Gagal mengupdate data.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tarif</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-8">

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">
    <h2 class="text-xl font-bold mb-6">Edit Tarif Parkir</h2>

    <form method="POST">
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Jenis Kendaraan</label>
            <select name="jenis_kendaraan" required class="w-full border p-2 rounded">
                <option value="mobil" <?= $data['jenis_kendaraan']=='mobil'?'selected':''; ?>>Mobil</option>
                <option value="lainnya" <?= $data['jenis_kendaraan']=='lainnya'?'selected':''; ?>>Lainnya</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-semibold">Tarif Per Jam</label>
            <input type="number" name="tarif_per_jam"
                   value="<?= $data['tarif_per_jam']; ?>"
                   step="0.01" required
                   class="w-full border p-2 rounded">
        </div>

        <div class="flex justify-between">
            <a href="kelola_tarif.php" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
            <button type="submit" name="update"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Update
            </button>
        </div>
    </form>
</div>

</body>
</html>
