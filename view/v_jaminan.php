<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: v_login.php');
    exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: v_peminjaman.php');
    exit;
}

require_once '../model/m_peminjaman.php';

$id = (int)($_GET['id'] ?? $_POST['id_peminjaman'] ?? 0);
$peminjaman = new Peminjaman();
$data = $peminjaman->getById($id);

if (!$data) {
    header('Location: v_peminjaman.php?pesan=aksi_gagal');
    exit;
}

if (isset($_POST['simpan_jaminan'])) {
    $jaminan = trim($_POST['jaminan'] ?? '');
    $pilihan = ['KTP', 'Kartu Pelajar', 'SIM', 'Uang Tunai', 'Tidak Ada'];

    if (!in_array($jaminan, $pilihan, true)) {
        $error = 'Pilih jenis jaminan yang tersedia.';
    } elseif ($peminjaman->updateJaminan($id, $jaminan)) {
        header('Location: v_peminjaman.php?pesan=jaminan_berhasil');
        exit;
    } else {
        $error = 'Jaminan gagal disimpan.';
    }
}

$current = $data['jaminan'] ?? 'KTP';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jaminan Peminjaman</title>
    <link rel="stylesheet" href="../asset/peminjamann.css?v=20261005full">
    <style>
        .form-card{max-width:520px;margin:28px auto;background:#fff;border-radius:14px;padding:24px;box-shadow:0 8px 25px rgba(15,23,42,.08)}
        .form-card h2{margin:0 0 8px;color:#1e293b}.form-card p{margin:0 0 20px;color:#64748b;font-size:13px}
        .detail{background:#f8fafc;border-radius:10px;padding:14px;margin-bottom:20px;color:#475569;font-size:13px}.detail strong{color:#1e293b}
        .field{margin-bottom:18px}.field label{display:block;margin-bottom:7px;font-weight:600;color:#334155;font-size:13px}
        .field select{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:8px;background:#fff;font-size:14px}
        .buttons{display:flex;gap:8px}.buttons button,.buttons a{border:0;border-radius:8px;padding:10px 14px;font-weight:600;text-decoration:none;font-size:13px;cursor:pointer}
        .save{background:#4c1d95;color:#fff}.cancel{background:#e2e8f0;color:#334155}.error{background:#fee2e2;color:#991b1b;padding:10px;border-radius:8px;margin-bottom:14px;font-size:13px}
    </style>

<style id="logout-visibility-fix">
.navbar{position:relative!important;overflow:visible!important;padding-right:92px!important}
.navbar .logout-link{display:none!important}
.navbar .logout-button{display:inline-flex!important;align-items:center;justify-content:center;flex:0 0 auto!important;white-space:nowrap!important;background:#ef4444!important;color:#fff!important;text-decoration:none!important;font-size:12px!important;font-weight:700!important;padding:7px 10px!important;border-radius:7px!important;z-index:20!important}
.navbar .logout-button:hover{background:#dc2626!important;color:#fff!important}
.navbar .logout-button{position:absolute!important;right:10px!important;top:50%!important;transform:translateY(-50%)!important;z-index:9999!important;pointer-events:auto!important}
@media(max-width:700px){.navbar{padding-right:88px!important;min-height:48px!important}.navbar .logout-button{right:7px!important;font-size:11px!important;padding:6px 8px!important;z-index:9999!important}}
</style>

</head>
<body>
<nav class="navbar">
    <div class="logo"><span>✦</span> Inventaris</div>
    <div class="nav-menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="v_kategori.php">Kategori</a>
        <a href="v_alat.php">Alat</a>
        <a href="v_peminjaman.php" class="active">Peminjaman</a>
        <a href="v_log_aktivitas.php">Log Aktivitas</a>
        <a href="v_user.php">User</a>
    </div>
    <div class="admin">
        <div class="avatar"><?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?></div>
        <span><?= htmlspecialchars($_SESSION['username'] ?? 'admin'); ?></span>
            <a href="../controller/c_logout.php" class="logout-link">Logout</a>
    </div>
    <a href="../controller/c_logout.php" class="logout-button" aria-label="Logout">Logout</a>
</nav>

<main class="container">
    <div class="welcome">
        <div>
            <h1>Jaminan Peminjaman</h1>
            <p>Kelola jaminan untuk peminjaman yang dipilih.</p>
        </div>
    </div>

    <div class="form-card">
        <h2>Form Jaminan</h2>
        <p>Admin dapat memilih atau mengubah jaminan peminjaman ini.</p>

        <div class="detail">
            <div><strong>Peminjam:</strong> <?= htmlspecialchars($data['nama_peminjam'] ?? '-'); ?></div>
            <div><strong>Alat:</strong> <?= htmlspecialchars($data['nama_alat'] ?? '-'); ?></div>
            <div><strong>Jumlah:</strong> <?= (int)($data['jumlah_pinjam'] ?? 0); ?></div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="id_peminjaman" value="<?= $id; ?>">
            <div class="field">
                <label for="jaminan">Pilih Jaminan</label>
                <select id="jaminan" name="jaminan" required>
                    <?php foreach (['KTP', 'Kartu Pelajar', 'SIM', 'Uang Tunai', 'Tidak Ada'] as $item): ?>
                        <option value="<?= htmlspecialchars($item); ?>" <?= $current === $item ? 'selected' : ''; ?>><?= htmlspecialchars($item); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="buttons">
                <button class="save" type="submit" name="simpan_jaminan">Simpan Jaminan</button>
                <a class="cancel" href="v_peminjaman.php">Kembali</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
