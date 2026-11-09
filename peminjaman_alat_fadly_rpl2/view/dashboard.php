<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: v_login.php");
    exit;
}
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: v_login.php");
    exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    if (($_SESSION['role'] ?? '') === 'petugas') {
        header("Location: v_dashboard_petugas.php");
    } elseif (($_SESSION['role'] ?? '') === 'peminjam') {
        header("Location: v_dashboard_peminjam.php");
    } else {
        header("Location: v_login.php");
    }
    exit;
}
?>

<?php

require_once "../model/m_dashboard.php";

$dashboard = new Dashboard();
$stats = $dashboard->getAdminStats();
$activities = $dashboard->getRecentActivities(4);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inventaris</title>
    <link rel="stylesheet" href="../asset/dashboardd.css">
<style>.stat-link{color:inherit;text-decoration:none;display:flex}.stat-link:hover{transform:translateY(-2px);}</style>
</head>
<body>
<nav class="navbar">
    <div class="logo"><span>✦</span> Inventaris</div>
    <div class="nav-menu">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="v_kategori.php">Kategori</a>
        <a href="v_alat.php">Alat</a>
        <a href="v_peminjaman.php">Peminjaman</a>
        <a href="v_log_aktivitas.php">Log Aktivitas</a>
        <a href="v_user.php">User</a>
        <a href="../controller/c_logout.php" class="logout-link">Logout</a>
    </div>
    <div class="admin">
        <div class="avatar">
            <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
        </div>
        <span><?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
    </div>
</nav>

<main class="dashboard">
    <section class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></strong></p>
        </div>
        <a href="v_alat.php" class="btn-primary">+ Kelola Alat</a>
    </section>

    <section class="hero">
        <div class="hero-content">
            <span class="hero-icon">✦</span>
            <div>
                <h2>Sistem Inventaris</h2>
                <p>Kelola data alat, kategori, peminjaman, pengguna, dan aktivitas dengan mudah.</p>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stat-card">
            <div class="stat-icon alat-icon">🔧</div>
            <div>
                <p>Total Alat</p>
                <h2><?= (int)$stats['total_alat']; ?></h2>
                <span>Data alat inventaris</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon kategori-icon">📁</div>
            <div>
                <p>Kategori</p>
                <h2><?= (int)$stats['total_kategori']; ?></h2>
                <span>Kategori alat</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon pinjam-icon">📋</div>
            <div>
                <p>Peminjaman</p>
                <h2><?= (int)$stats['total_peminjaman']; ?></h2>
                <span>Total peminjaman</span>
            </div>
        </div>

        <a href="v_riwayat_peminjam.php" class="stat-card stat-link">
            <div class="stat-icon user-icon">👤</div>
            <div>
                <p>Peminjam</p>
                <h2><?= (int)$stats['total_peminjam']; ?></h2>
                <span>Lihat semua riwayat peminjam</span>
            </div>
        </a>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-box">
            <div class="box-header">
                <div>
                    <h2>Aktivitas Terbaru</h2>
                    <p>Aktivitas yang tersimpan di database</p>
                </div>
                <a href="v_log_aktivitas.php">Lihat Semua</a>
            </div>

            <div class="activity-list">
                <?php if (mysqli_num_rows($activities) > 0): ?>
                    <?php while ($activity = mysqli_fetch_assoc($activities)): ?>
                        <div class="activity">
                            <div class="activity-icon">✓</div>
                            <div class="activity-info">
                                <strong><?= htmlspecialchars($activity['aktivitas']); ?></strong>
                                <span><?= htmlspecialchars($activity['nama'] ?? 'Sistem'); ?></span>
                            </div>
                            <small><?= htmlspecialchars($activity['waktu']); ?></small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="activity">
                        <div class="activity-info"><strong>Belum ada aktivitas</strong><span>Data aktivitas belum tersedia.</span></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-box quick-box">
            <div class="box-header">
                <div>
                    <h2>Akses Cepat</h2>
                    <p>Menu yang sering digunakan</p>
                </div>
            </div>

            <div class="quick-menu">
                <a href="v_alat.php" class="quick-item"><div class="quick-icon">🔧</div><div><strong>Data Alat</strong><span>Kelola alat inventaris</span></div><b>›</b></a>
                <a href="v_kategori.php" class="quick-item"><div class="quick-icon">📁</div><div><strong>Kategori</strong><span>Kelola kategori alat</span></div><b>›</b></a>
                <a href="v_peminjaman.php" class="quick-item"><div class="quick-icon">📋</div><div><strong>Peminjaman</strong><span>Kelola peminjaman</span></div><b>›</b></a>
                <a href="v_riwayat_peminjam.php" class="quick-item"><div class="quick-icon">👤</div><div><strong>Peminjam & Riwayat</strong><span>Lihat semua riwayat peminjaman</span></div><b>›</b></a>
                <a href="v_user.php" class="quick-item"><div class="quick-icon">⚙</div><div><strong>Kelola User</strong><span>Tambah, edit, dan hapus user</span></div><b>›</b></a>
            </div>
        </div>
    </section>
</main>
</body>
</html>
