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
        <a href="v_dashboard_petugas.php" class="active">Dashboard</a>
        <a href="v_peminjaman.php">Peminjaman</a>
        <a href="v_laporan_petugas.php">Laporan</a>
    </div>

    <div class="admin">
        <div class="avatar"><?= strtoupper(substr($_SESSION['username'] ?? 'P', 0, 1)); ?></div>
        <span><?= htmlspecialchars($_SESSION['username'] ?? 'Petugas'); ?></span>
    </div>
    <button type="button" class="logout-button" aria-label="Logout">Logout</button>
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
