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
        <a href="v_dashboard_peminjam.php" class="active">Dashboard</a>
        <a href="v_alat_peminjam.php">Alat</a>
        <a href="v_peminjaman_peminjam.php">Peminjaman</a>
    </div>
    <div class="admin">
        <div class="avatar"><?= strtoupper(substr($_SESSION['username'] ?? 'P', 0, 1)); ?></div>
        <span><?= htmlspecialchars($_SESSION['username'] ?? 'Peminjam'); ?></span>
    </div>
    <button type="button" class="logout-button" aria-label="Logout">Logout</button>
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
