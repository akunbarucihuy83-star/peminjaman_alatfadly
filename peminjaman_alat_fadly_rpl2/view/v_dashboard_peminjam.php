<?php
include "../controller/c_login.php";
require_once "../model/m_dashboard.php";

if (($_SESSION['role'] ?? '') !== 'peminjam') {
    header("Location: dashboard.php");
    exit;
}

$id_user = (int) ($_SESSION['id_user'] ?? 0);
$dashboard = new Dashboard();
$stats = $dashboard->getPeminjamStats($id_user);
$recentLoans = $dashboard->getRecentLoansByUser($id_user, 3);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjam - Inventaris</title>
    <link rel="stylesheet" href="../asset/dashboard_peminjam.css">
</head>
<body>
<nav class="navbar">
    <div class="logo"><span>✦</span> Inventaris</div>
    <div class="nav-menu">
        <a href="v_dashboard_peminjam.php" class="active">Dashboard</a>
        <a href="v_alat_peminjam.php">Alat</a>
        <a href="v_peminjaman_peminjam.php">Peminjaman</a>
        <a href="../controller/c_logout.php" class="logout-link">Logout</a>
    </div>
    <div class="admin">
        <div class="avatar"><?= strtoupper(substr($_SESSION['username'] ?? 'P', 0, 1)); ?></div>
        <span><?= htmlspecialchars($_SESSION['username'] ?? 'Peminjam'); ?></span>
    </div>
</nav>

<main class="dashboard">
    <section class="dashboard-header">
        <div>
            <h1>Dashboard Peminjam</h1>
            <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username'] ?? 'Peminjam'); ?></strong></p>
        </div>
        <a href="v_peminjaman_peminjam.php" class="btn-primary">+ Ajukan Peminjaman</a>
    </section>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-icon">📋</div>
            <div>
                <h2>Selamat Datang di Sistem Inventaris</h2>
                <p>Kamu dapat melihat alat yang tersedia, mengajukan peminjaman, dan melihat status peminjaman melalui halaman ini.</p>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stat-card"><div class="stat-icon alat-icon">🔧</div><div><p>Alat Tersedia</p><h2><?= (int)$stats['alat_tersedia']; ?></h2><span>Jenis alat tersedia</span></div></div>
        <div class="stat-card"><div class="stat-icon pinjam-icon">📋</div><div><p>Peminjaman Saya</p><h2><?= (int)$stats['total_peminjaman']; ?></h2><span>Total pengajuan</span></div></div>
        <div class="stat-card"><div class="stat-icon proses-icon">⏳</div><div><p>Sedang Dipinjam</p><h2><?= (int)$stats['sedang_dipinjam']; ?></h2><span>Masih aktif</span></div></div>
        <div class="stat-card"><div class="stat-icon selesai-icon">✓</div><div><p>Selesai</p><h2><?= (int)$stats['selesai']; ?></h2><span>Sudah dikembalikan</span></div></div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-box">
            <div class="box-header">
                <div><h2>Peminjaman Terbaru</h2><p>Data langsung dari database</p></div>
                <a href="v_peminjaman_peminjam.php">Lihat Semua</a>
            </div>
            <div class="loan-list">
                <?php if (mysqli_num_rows($recentLoans) > 0): ?>
                    <?php while ($loan = mysqli_fetch_assoc($recentLoans)): ?>
                        <div class="loan-item">
                            <div class="loan-icon">🔧</div>
                            <div class="loan-info">
                                <strong><?= htmlspecialchars($loan['nama_alat'] ?? '-'); ?></strong>
                                <span><?= htmlspecialchars($loan['tanggal_pinjam']); ?></span>
                            </div>
                            <?php
                            $status = $loan['status'] ?? '-';
                            $statusClass = $status === 'Dikembalikan' ? 'status-selesai' : 'status-dipinjam';
                            ?>
                            <span class="status <?= $statusClass; ?>"><?= htmlspecialchars($status); ?></span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="loan-item"><div class="loan-info"><strong>Belum ada peminjaman</strong><span>Silakan klik + Ajukan Peminjaman.</span></div></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-box">
            <div class="box-header"><div><h2>Akses Cepat</h2><p>Menu peminjam</p></div></div>
            <div class="quick-menu">
                <a href="v_alat_peminjam.php" class="quick-item"><div class="quick-icon">🔧</div><div><strong>Lihat Alat</strong><span>Lihat alat yang tersedia</span></div><b>›</b></a>
                <a href="v_peminjaman_peminjam.php" class="quick-item"><div class="quick-icon">📋</div><div><strong>Peminjaman</strong><span>Ajukan peminjaman alat</span></div><b>›</b></a>
            </div>
        </div>
    </section>
</main>
</body>
</html>
