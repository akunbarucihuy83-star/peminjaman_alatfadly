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

require_once "../model/m_peminjaman.php";
$peminjaman = new Peminjaman();

$keyword = trim($_GET['q'] ?? '');
$data = $peminjaman->getAllHistory($keyword);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Peminjaman - Inventaris</title>
<style>
*{box-sizing:border-box}body{font-family:Arial,sans-serif;background:#f1f5f9;color:#172554;margin:0}.navbar{height:60px;background:linear-gradient(90deg,#20245f,#5420b8);display:flex;align-items:center;padding:0 30px;color:#fff;gap:25px}.logo{font-weight:700}.nav-menu{display:flex;gap:8px;flex:1;justify-content:center}.nav-menu a{color:#fff;text-decoration:none;padding:9px 13px;border-radius:7px;font-size:13px}.nav-menu a:hover,.nav-menu a.active{background:#fff;color:#4d29aa}.admin{font-size:13px}.container{max-width:1150px;margin:30px auto;padding:0 18px}.head{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:20px}.head h1{margin:0 0 6px}.head p{margin:0;color:#64748b}.actions{display:flex;gap:8px}.btn{border:0;text-decoration:none;background:#4c1d95;color:#fff;padding:10px 14px;border-radius:7px;cursor:pointer}.box{background:#fff;padding:20px;border-radius:12px;box-shadow:0 3px 12px rgba(0,0,0,.07)}form{display:flex;gap:8px;margin-bottom:16px}.search{padding:10px;border:1px solid #cbd5e1;border-radius:7px;flex:1}table{width:100%;border-collapse:collapse}th,td{padding:10px;border-bottom:1px solid #e2e8f0;text-align:left;font-size:13px}th{background:#eef2ff}.status{font-weight:700}.print-title{display:none}@media(max-width:700px){.navbar{padding:8px 12px;height:auto;flex-wrap:wrap}.nav-menu{order:3;width:100%;overflow:auto;justify-content:flex-start}.head{flex-direction:column;align-items:flex-start}.table-wrap{overflow:auto}}@media print{body{background:#fff}.navbar,.actions,form{display:none!important}.container{max-width:none;margin:0}.box{box-shadow:none;padding:0}.print-title{display:block;text-align:center;margin-bottom:18px}.print-title h1{margin-bottom:5px}.print-title p{margin:0;color:#555}th,td{font-size:11px}}
</style>
</head>
<body>
<nav class="navbar">
    <div class="logo">✦ Inventaris</div>
    <div class="nav-menu">
        <a href="v_dashboard_petugas.php">Dashboard</a>
        <a href="v_peminjaman.php">Peminjaman</a>
        <a href="v_laporan_petugas.php" class="active">Laporan</a>
        <a href="../controller/c_logout.php">Logout</a>
    </div>
    <div class="admin"><?= htmlspecialchars($_SESSION['username'] ?? 'Petugas'); ?></div>
</nav>

<main class="container">
    <div class="head">
        <div>
            <h1>Laporan Peminjaman</h1>
            <p>Laporan seluruh transaksi peminjaman alat.</p>
        </div>
        <div class="actions">
            <button class="btn" onclick="window.print()">🖨 Cetak Laporan</button>
        </div>
    </div>

    <div class="box">
        <div class="print-title">
            <h1>LAPORAN PEMINJAMAN ALAT</h1>
            <p>Dicetak oleh Petugas - <?= date('d-m-Y H:i'); ?></p>
        </div>

        <form method="get">
            <input class="search" type="search" name="q" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari peminjam, alat, atau status...">
            <button class="btn" type="submit">Cari</button>
        </form>

        <div class="table-wrap">
        <table>
            <thead><tr><th>No</th><th>Peminjam</th><th>Alat</th><th>Jumlah</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th><th>Status</th></tr></thead>
            <tbody>
            <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_peminjam'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['nama_alat'] ?? '-'); ?></td>
                    <td><?= (int)($row['jumlah_pinjam'] ?? 0); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pinjam'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kembali'] ?? '-'); ?></td>
                    <td class="status"><?= htmlspecialchars($row['status'] ?? '-'); ?></td>
                </tr>
            <?php endwhile; ?>
            <?php if($no===1): ?><tr><td colspan="7">Belum ada data.</td></tr><?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</main>
</body>
</html>
