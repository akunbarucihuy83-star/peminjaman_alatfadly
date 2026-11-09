<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: v_login.php");
    exit;
}

if (($_SESSION['role'] ?? '') !== 'petugas') {
    if (($_SESSION['role'] ?? '') === 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: v_dashboard_peminjam.php");
    }
    exit;
}

require_once "../model/m_dashboard.php";
require_once "../model/m_peminjaman.php";

$dashboard = new Dashboard();
$stats = $dashboard->getAdminStats();
$activities = $dashboard->getRecentActivities(5);

$peminjaman = new Peminjaman();
$menunggu = $peminjaman->getByStatus('Menunggu');
$disetujui = $peminjaman->getByStatus('Disetujui');
$dapatMenunggu = $menunggu ? mysqli_num_rows($menunggu) : 0;
$dapatDisetujui = $disetujui ? mysqli_num_rows($disetujui) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Inventaris</title>
    <link rel="stylesheet" href="../asset/dashboard_petugas.css?v=2">
</head>
<body>

<nav class="navbar">
    <div class="logo"><span>✦</span> Inventaris</div>

    <div class="nav-menu">
        <a href="v_dashboard_petugas.php" class="active">Dashboard</a>
        <a href="v_peminjaman.php">Peminjaman</a>
        <a href="v_laporan_petugas.php">Laporan</a>
        <a href="../controller/c_logout.php">Logout</a>
    </div>

    <div class="admin">
        <div class="avatar"><?= strtoupper(substr($_SESSION['username'] ?? 'P', 0, 1)); ?></div>
        <span><?= htmlspecialchars($_SESSION['username'] ?? 'Petugas'); ?></span>
    </div>
</nav>

<main class="dashboard">
    <section class="dashboard-header">
        <div>
            <h1>Dashboard Petugas</h1>
            <p>Kelola persetujuan peminjaman, pantau pengembalian, dan cetak laporan.</p>
        </div>
        <a href="v_peminjaman.php" class="btn-primary">Cek Peminjaman</a>
    </section>

    <section class="hero">
        <div class="hero-content">
            <span class="hero-icon">✓</span>
            <div>
                <h2>Tugas Petugas</h2>
                <p>Petugas tidak melakukan CRUD alat. Fokus pada persetujuan peminjaman, pemantauan pengembalian, dan laporan.</p>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stat-card">
            <div class="stat-icon pinjam-icon">⏳</div>
            <div>
                <p>Menunggu Persetujuan</p>
                <h2><?= $dapatMenunggu; ?></h2>
                <span>Pengajuan yang perlu dicek</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon alat-icon">📦</div>
            <div>
                <p>Sedang Dipinjam</p>
                <h2><?= $dapatDisetujui; ?></h2>
                <span>Perlu dipantau pengembaliannya</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon kategori-icon">🔧</div>
            <div>
                <p>Total Alat</p>
                <h2><?= (int)$stats['total_alat']; ?></h2>
                <span>Informasi inventaris</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon user-icon">👤</div>
            <div>
                <p>Total Peminjaman</p>
                <h2><?= (int)$stats['total_peminjaman']; ?></h2>
                <span>Semua transaksi</span>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-box">
            <div class="box-header">
                <div>
                    <h2>Menu Petugas</h2>
                    <p>Sesuai pembagian tugas aplikasi.</p>
                </div>
            </div>

            <div class="quick-menu">
                <a href="v_peminjaman.php?mode=persetujuan" class="quick-item">
                    <div class="quick-icon">✓</div>
                    <div><strong>Menyetujui Peminjaman</strong><span>Cek pengajuan yang masih menunggu.</span></div>
                    <b>›</b>
                </a>

                <a href="v_peminjaman.php?mode=pengembalian" class="quick-item">
                    <div class="quick-icon">↩</div>
                    <div><strong>Memantau Pengembalian</strong><span>Pantau peminjaman yang sudah disetujui dan status pengembaliannya.</span></div>
                    <b>›</b>
                </a>

                <a href="v_laporan_petugas.php" class="quick-item">
                    <div class="quick-icon">🖨</div>
                    <div><strong>Mencetak Laporan</strong><span>Buka laporan peminjaman dan gunakan tombol cetak.</span></div>
                    <b>›</b>
                </a>
            </div>
        </div>

        <div class="dashboard-box">
            <div class="box-header">
                <div>
                    <h2>Aktivitas Terbaru</h2>
                    <p>Aktivitas sistem.</p>
                </div>
            </div>

            <div class="activity-list">
                <?php if ($activities && mysqli_num_rows($activities) > 0): ?>
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
                        <div class="activity-info"><strong>Belum ada aktivitas</strong></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
</body>
</html>
