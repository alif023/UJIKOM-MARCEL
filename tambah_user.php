<?php
include 'koneksi.php';

$id_user = "";
$nama_lengkap = "";
$username = "";
$role = "";
$is_edit = false;

// Cek apakah sedang mode EDIT
if (isset($_GET['id'])) {
    $is_edit = true;
    $id = $_GET['id'];
    $result = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id'");
    $data = mysqli_fetch_assoc($result);
    
    if ($data) {
        $id_user = $data['id_user'];
        $nama_lengkap = $data['nama_lengkap'];
        $username = $data['username'];
        $role = $data['role'];
    }
}

// Logika Simpan (Tambah / Update)
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_lengkap'];
    $user = $_POST['username'];
    $rl = $_POST['role'];
    $pass = $_POST['password'];

    if ($is_edit) {
        // Jika password diisi, update password juga. Jika kosong, biarkan yang lama.
        if (!empty($pass)) {
            $query = "UPDATE user SET nama_lengkap='$nama', username='$user', role='$rl', password='$pass' WHERE id_user='$id_user'";
        } else {
            $query = "UPDATE user SET nama_lengkap='$nama', username='$user', role='$rl' WHERE id_user='$id_user'";
        }
    } else {
        // Logika Tambah Baru
        $query = "INSERT INTO user (nama_lengkap, username, password, role) VALUES ('$nama', '$user', '$pass', '$rl')";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: user.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Edit' : 'Tambah' ?> User - Parkir-In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">

<div class="flex min-h-screen">
    <aside class="w-64 bg-white border-r border-slate-200 p-6 hidden md:block">
        <div class="mb-10">
            <h1 class="text-2xl font-black text-blue-600 italic">PARKIR-IN</h1>
            <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Sistem Manajemen Parkir</p>
        </div>
        <nav class="space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 p-3 rounded-xl text-slate-600 hover:bg-slate-100 transition-all">Dashboard</a>
            <a href="user.php" class="flex items-center gap-3 p-3 rounded-xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-100">Kelola Pengguna</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="max-w-2xl mx-auto">
            <a href="user.php" class="text-blue-600 text-sm font-bold flex items-center gap-2 mb-6">
                ← Kembali ke Daftar
            </a>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h1 class="text-2xl font-bold text-slate-800 mb-2"><?= $is_edit ? 'Update' : 'Tambah' ?> Pengguna</h1>
                <p class="text-slate-500 text-sm mb-8">Silakan lengkapi form di bawah ini.</p>

               <form action="tambah_user.php" method="POST" class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?= $nama_lengkap ?>" required 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                        <input type="text" name="username" value="<?= $username ?>" required 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password <?= $is_edit ? '<span class="text-xs font-normal text-slate-400">(Kosongkan jika tidak diubah)</span>' : '' ?></label>
                        <input type="password" name="password" <?= $is_edit ? '' : 'required' ?> 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Role / Jabatan</label>
                        <select name="role" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all appearance-none">
                            <option value="petugas" <?= $role == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                            <option value="admin" <?= $role == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="owner" <?= $role == 'owner' ? 'selected' : '' ?>>Owner</option>
                        </select>
                    </div>

                    <button type="submit" name="simpan" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all mt-4">
                        <?= $is_edit ? 'Simpan Perubahan' : 'Daftarkan User' ?>
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>

</body>
</html> 