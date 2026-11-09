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

<style id="logout-visibility-fix">
.navbar{position:relative!important;overflow:visible!important;padding-right:92px!important}
.navbar .logout-link{display:none!important}
.navbar .logout-button{display:inline-flex!important;align-items:center;justify-content:center;flex:0 0 auto!important;white-space:nowrap!important;background:#ef4444!important;color:#fff!important;text-decoration:none!important;font-size:12px!important;font-weight:700!important;padding:7px 10px!important;border-radius:7px!important;z-index:20!important}
.navbar .logout-button:hover{background:#dc2626!important;color:#fff!important}
.navbar .logout-button{position:absolute!important;right:10px!important;top:50%!important;transform:translateY(-50%)!important}
@media(max-width:700px){.navbar{padding-right:88px!important;min-height:48px!important}.navbar .logout-button{right:7px!important;font-size:11px!important;padding:6px 8px!important}}
</style>

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
    </div>
    <div class="admin">
        <div class="avatar">
            <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
        </div>
        <span><?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
    </div>
    <button type="button" class="logout-button" aria-label="Logout">Logout</button>
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

<!-- Dialog konfirmasi logout -->
<div id="logoutConfirmModal" class="logout-modal" aria-hidden="true">
  <div class="logout-dialog" role="dialog" aria-modal="true" aria-labelledby="logoutDialogTitle" aria-describedby="logoutDialogText">
    <div class="logout-symbol" aria-hidden="true">?</div>
    <h2 id="logoutDialogTitle">Konfirmasi Logout</h2>
    <p id="logoutDialogText">Apakah Anda yakin ingin logout?</p>
    <div class="logout-actions">
      <button type="button" class="logout-cancel" id="cancelLogout">Batal</button>
      <a href="../controller/c_logout.php" class="logout-confirm">Ya, Logout</a>
    </div>
  </div>
</div>
<style id="logout-confirm-style">
.logout-modal{display:none;position:fixed;inset:0;z-index:99999;background:rgba(15,23,42,.58);align-items:center;justify-content:center;padding:18px;box-sizing:border-box}
.logout-modal.is-open{display:flex}
.logout-dialog{width:min(100%,360px);background:#fff;color:#1f2937;border-radius:16px;padding:26px 22px 20px;box-shadow:0 18px 55px rgba(0,0,0,.25);text-align:center;font-family:Arial,sans-serif;box-sizing:border-box;animation:logoutPop .16s ease-out}
.logout-symbol{width:48px;height:48px;margin:0 auto 12px;border-radius:50%;display:grid;place-items:center;background:#fff1f2;color:#e11d48;font-size:27px;font-weight:700}
.logout-dialog h2{font-size:21px;margin:0 0 9px;color:#111827}
.logout-dialog p{font-size:14px;line-height:1.5;color:#64748b;margin:0 0 22px}
.logout-actions{display:flex;gap:10px}
.logout-actions button,.logout-actions a{flex:1;min-height:42px;border:0;border-radius:9px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;font-weight:700;cursor:pointer;box-sizing:border-box}
.logout-cancel{background:#e5e7eb;color:#374151}
.logout-confirm{background:#dc2626;color:#fff}
.logout-confirm:hover{background:#b91c1c}
.logout-button{cursor:pointer;border:0}
@keyframes logoutPop{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
@media(prefers-reduced-motion:reduce){.logout-dialog{animation:none}}
</style>
<script>
(function(){
  const modal = document.getElementById('logoutConfirmModal');
  const openButtons = document.querySelectorAll('.logout-button');
  const cancel = document.getElementById('cancelLogout');
  if (!modal) return;
  function openModal(){
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden','false');
    if (cancel) cancel.focus();
  }
  function closeModal(){
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden','true');
  }
  openButtons.forEach(function(button){
    button.addEventListener('click', function(event){
      event.preventDefault();
      openModal();
    });
  });
  if (cancel) cancel.addEventListener('click', closeModal);
  modal.addEventListener('click', function(event){
    if (event.target === modal) closeModal();
  });
  document.addEventListener('keydown', function(event){
    if (event.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
  });
})();
</script>

</body>
</html>
