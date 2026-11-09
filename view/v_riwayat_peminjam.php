<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: v_login.php");
    exit;
}

require_once "../model/m_peminjaman.php";

$peminjaman = new Peminjaman();
$keyword = trim($_GET['cari'] ?? '');
$data = $peminjaman->getAllHistory($keyword);
$role = $_SESSION['role'] ?? 'peminjam';
$isAdmin = $role === 'admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjam - Inventaris</title>
    <link rel="stylesheet" href="../asset/riwayat_peminjam.css">

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
        <?php if ($isAdmin): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="v_kategori.php">Kategori</a>
            <a href="v_alat.php">Alat</a>
            <a href="v_peminjaman.php">Peminjaman</a>
            <a href="v_log_aktivitas.php">Log Aktivitas</a>
            <a href="v_user.php">User</a>
        <?php else: ?>
            <a href="v_dashboard_peminjam.php">Dashboard</a>
            <a href="v_alat_peminjam.php">Alat</a>
            <a href="v_peminjaman_peminjam.php">Peminjaman</a>
            <a href="v_riwayat_peminjam.php" class="active">Riwayat Semua</a>
        <?php endif; ?>
    </div>
    <div class="admin">
        <div class="avatar"><?= strtoupper(substr($_SESSION['username'] ?? ($isAdmin ? 'A' : 'P'), 0, 1)); ?></div>
        <span><?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username'] ?? ($isAdmin ? 'Admin' : 'Peminjam')); ?></span>
            <a href="../controller/c_logout.php" class="logout-link">Logout</a>
    </div>
    <a href="../controller/c_logout.php" class="logout-button" aria-label="Logout">Logout</a>
</nav>

<main class="container">
    <div class="welcome">
        <div>
            <h1>Riwayat Semua Peminjam</h1>
            <p>Menampilkan seluruh riwayat peminjaman dari semua peminjam yang tersimpan di database.</p>
        </div>
    </div>

    <div class="box">
        <div class="box-title">
            <div>
                <h3>Daftar Riwayat Peminjaman</h3>
                <p>Contoh: Budi dapat melihat barang yang pernah dipinjam Andi, Siti, Dewi, dan peminjam lainnya.</p>
            </div>
            <form method="get" class="search-form">
                <input type="text" name="cari" class="search" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari peminjam, alat, status...">
                <button type="submit" class="search-button">Cari</button>
            </form>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; ?>
                <?php if (mysqli_num_rows($data) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td class="nomor"><?= $no++; ?></td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar"><?= strtoupper(substr($row['nama_peminjam'] ?? 'P', 0, 1)); ?></div>
                                    <div>
                                        <div class="user-name"><?= htmlspecialchars($row['nama_peminjam']); ?></div>
                                        <small>@<?= htmlspecialchars($row['username']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($row['nama_alat']); ?></td>
                            <td><?= (int)$row['jumlah_pinjam']; ?></td>
                            <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                            <td><?= $row['tanggal_kembali'] ? htmlspecialchars($row['tanggal_kembali']) : '-'; ?></td>
                            <td><span class="status status-<?= strtolower($row['status']); ?>"><?= htmlspecialchars($row['status']); ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="empty">Belum ada riwayat peminjaman.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>
