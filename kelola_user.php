<?php
include 'koneksi.php';

// Logika Hapus User
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $hapus = mysqli_query($koneksi, "DELETE FROM user WHERE id_user = '$id'");
    if($hapus) {
        header("Location: user.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Parkir-In</title>
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
            <p class="text-[10px] font-bold text-slate-400 uppercase mb-4 tracking-widest">Menu Utama</p>
            <a href="dashboard.php" class="flex items-center gap-3 p-3 rounded-xl text-slate-600 hover:bg-slate-100 transition-all">
                Dashboard
            </a>
            <a href="user.php" class="flex items-center gap-3 p-3 rounded-xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-100">
                Kelola Pengguna
            </a>
            <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-slate-600 hover:bg-slate-100 transition-all">
                Laporan Transaksi
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <div class="text-sm text-slate-400">
                Halaman / <span class="font-bold text-slate-800">Kelola User</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-slate-600">Administrator</span>
                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">A</div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Manajemen Pengguna</h1>
                <p class="text-slate-500 text-sm">Kelola data admin, petugas, dan owner website Parkir-In</p>
            </div>
            <button onclick="window.location.href='tambah_user.php'" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah User
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Username</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM user ORDER BY id_user DESC");
                    if(mysqli_num_rows($query) > 0) {
                        while ($user = mysqli_fetch_assoc($query)) :
                    ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4">
                            <div class="font-medium text-slate-700"><?= $user['nama_lengkap']; ?></div>
                            <div class="text-[10px] text-slate-400 italic">ID: #<?= $user['id_user']; ?></div>
                        </td>
                        <td class="p-4 text-slate-600 text-sm"><?= $user['username']; ?></td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter
                                <?= $user['role'] == 'admin' ? 'bg-indigo-100 text-indigo-700' : ($user['role'] == 'owner' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700'); ?>">
                                <?= $user['role']; ?>
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="user.php?hapus=<?= $user['id_user']; ?>" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                                   class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus User">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    } else {
                        echo "<tr><td colspan='4' class='p-8 text-center text-slate-400'>Belum ada data user.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>