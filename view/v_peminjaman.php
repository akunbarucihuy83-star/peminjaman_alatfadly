<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: v_login.php");
    exit;
}

$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['admin', 'petugas'], true)) {
    header("Location: v_dashboard_peminjam.php");
    exit;
}

require_once "../model/m_peminjaman.php";

$peminjaman = new Peminjaman();
$keyword = trim($_GET['q'] ?? '');
$mode = $_GET['mode'] ?? 'persetujuan';

$menunggu = $peminjaman->getByStatus('Menunggu', $keyword);
$aktif = $peminjaman->getByStatus('Disetujui', $keyword);
$dipinjam = $peminjaman->getByStatus('Dipinjam', $keyword);
$dikembalikan = $peminjaman->getByStatus('Dikembalikan', $keyword);
$ditolak = $peminjaman->getByStatus('Ditolak', $keyword);

$pesan = $_GET['pesan'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman - Inventaris</title>
    <link rel="stylesheet" href="../asset/peminjamann.css?v=20261005full">
    <style>
        .tabs{display:flex;gap:8px;flex-wrap:wrap;margin:18px 0}
        .tab{padding:9px 13px;border-radius:8px;background:#eef2ff;color:#334155;text-decoration:none;font-size:13px;font-weight:600}
        .tab.active{background:#4c1d95;color:#fff}
        .notice{padding:12px 15px;border-radius:8px;margin-bottom:15px;background:#dcfce7;color:#166534}
        .action-form{display:flex;gap:6px;flex-wrap:wrap}
        .action-form button{border:0;border-radius:7px;padding:7px 10px;color:#fff;cursor:pointer;font-size:12px;font-weight:600}
        .approve{background:#16a34a}.return-btn{background:#2563eb}.return-btn:hover{opacity:.9}.guarantee-btn{background:#7c3aed;text-decoration:none;display:inline-flex;align-items:center;padding:7px 10px;color:#fff;border-radius:7px;font-size:12px;font-weight:600}.guarantee-btn:hover{opacity:.9}.back-btn{background:#64748b;text-decoration:none;display:inline-flex;align-items:center;padding:7px 10px;color:#fff;border-radius:7px;font-size:12px;font-weight:600}
        .info-box{padding:12px 15px;background:#f8fafc;border-radius:9px;margin-bottom:16px;color:#475569;font-size:13px}
        .empty{text-align:center;color:#64748b;padding:20px}
        @media(max-width:700px){.table-wrapper{overflow-x:auto}.action-form{min-width:145px}}
        @media print{.navbar,.tabs,.search,.no-print,.notice{display:none!important}.container{padding:0}.box{box-shadow:none}}
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
        <?php if ($role === 'admin'): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="v_kategori.php">Kategori</a>
            <a href="v_alat.php">Alat</a>
            <a href="v_peminjaman.php" class="active">Peminjaman</a>
            <a href="v_log_aktivitas.php">Log Aktivitas</a>
            <a href="v_user.php">User</a>
        <?php else: ?>
            <a href="v_dashboard_petugas.php">Dashboard</a>
            <a href="v_peminjaman.php" class="active">Peminjaman</a>
            <a href="v_laporan_petugas.php">Laporan</a>
        <?php endif; ?>
        <a href="../controller/c_logout.php" class="logout-link">Logout</a>
    </div>
    <div class="admin">
        <div class="avatar"><?= strtoupper(substr($_SESSION['username'] ?? 'P', 0, 1)); ?></div>
        <span><?= htmlspecialchars($_SESSION['username'] ?? ($role === 'admin' ? 'Admin' : 'Petugas')); ?></span>
    </div>
    <a href="../controller/c_logout.php" class="logout-button" aria-label="Logout">Logout</a>
</nav>

<main class="container">
    <div class="welcome">
        <div>
            <h1><?= $role === 'petugas' ? 'Peminjaman Petugas' : 'Peminjaman'; ?></h1>
            <p><?= $role === 'petugas' ? 'Setujui pengajuan dan pantau status pengembalian.' : 'Kelola data peminjaman.'; ?></p>
        </div>
    </div>

    <?php if ($pesan === 'jaminan_berhasil'): ?>
        <div class="notice">Jaminan berhasil disimpan.</div>
    <?php elseif ($pesan === 'berhasil'): ?>
        <div class="notice">Status peminjaman berhasil diperbarui.</div>
    <?php elseif ($pesan === 'aksi_gagal'): ?>
        <div class="notice" style="background:#fee2e2;color:#991b1b">Aksi tidak valid.</div>
    <?php endif; ?>

    <div class="tabs no-print">
        <a class="tab <?= $mode === 'persetujuan' ? 'active' : ''; ?>" href="v_peminjaman.php?mode=persetujuan">Menyetujui Peminjaman</a>
        <a class="tab <?= $mode === 'pengembalian' ? 'active' : ''; ?>" href="v_peminjaman.php?mode=pengembalian">Memantau Pengembalian</a>
    </div>

    <?php if ($mode === 'pengembalian'): ?>
        <div class="box">
            <div class="box-title">
                <div><h3>Pemantauan Pengembalian</h3><p>Setelah peminjaman disetujui, admin atau petugas dapat memantau dan menandai alat yang sudah dikembalikan.</p></div>
                <form method="get" action="v_peminjaman.php" class="no-print">
                    <input type="hidden" name="mode" value="pengembalian">
                    <input class="search" type="search" name="q" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari peminjaman...">
                </form>
            </div>

            <div class="info-box">
                <strong>Disetujui:</strong> <?= $aktif ? mysqli_num_rows($aktif) : 0; ?> &nbsp; |
                <strong>Dipinjam:</strong> <?= $dipinjam ? mysqli_num_rows($dipinjam) : 0; ?> &nbsp; |
                <strong>Dikembalikan:</strong> <?= $dikembalikan ? mysqli_num_rows($dikembalikan) : 0; ?>
            </div>

            <div class="table-wrapper">
            <table>
                <thead><tr><th>No</th><th>Peminjam</th><th>Alat</th><th>Jumlah</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th class="no-print">Aksi</th></tr></thead>
                <tbody>
                <?php
                $no = 1;
                foreach ([$aktif, $dipinjam, $dikembalikan] as $result):
                    if ($result) while ($row = mysqli_fetch_assoc($result)):
                        $status = $row['status'] ?? '-';
                        $cls = $status === 'Dikembalikan' ? 'green-status' : 'blue-status';
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_peminjam'] ?? '-'); ?></td>
                        <td><?= htmlspecialchars($row['nama_alat'] ?? '-'); ?></td>
                        <td><?= (int)$row['jumlah_pinjam']; ?></td>
                        <td><?= !empty($row['tanggal_pinjam']) ? date('d M Y', strtotime($row['tanggal_pinjam'])) : '-'; ?></td>
                        <td><?= !empty($row['tanggal_kembali']) ? date('d M Y', strtotime($row['tanggal_kembali'])) : '-'; ?></td>
                        <td><span class="status <?= $cls; ?>"><?= htmlspecialchars($status); ?></span></td>
                        <td class="no-print">
                            <div class="action-form">
                                <?php if (in_array($status, ['Disetujui', 'Dipinjam'], true)): ?>
                                    <form action="../controller/c_petugas.php" method="post">
                                        <input type="hidden" name="id_peminjaman" value="<?= (int)$row['id_peminjaman']; ?>">
                                        <button class="return-btn" type="submit" name="aksi_peminjaman" value="kembali"
                                                onclick="return confirm('Tandai alat ini sudah dikembalikan?');">
                                            Kembali
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="color:#64748b;font-size:11px;">Selesai</span>
                                <?php endif; ?>
                                <?php if ($role === 'admin'): ?>
                                    <a class="guarantee-btn" href="v_jaminan.php?id=<?= (int)$row['id_peminjaman']; ?>">Jaminan</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; endforeach; ?>
                <?php if ($no === 1): ?><tr><td colspan="8" class="empty">Belum ada data yang perlu dipantau.</td></tr><?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>

    <?php else: ?>
        <div class="box">
            <div class="box-title">
                <div><h3>Pengajuan Menunggu Persetujuan</h3><p>Petugas dapat menyetujui pengajuan peminjaman.</p></div>
                <form method="get" action="v_peminjaman.php" class="no-print">
                    <input type="hidden" name="mode" value="persetujuan">
                    <input class="search" type="search" name="q" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari peminjaman...">
                </form>
            </div>

            <div class="table-wrapper">
            <table>
                <thead><tr><th>No</th><th>Peminjam</th><th>Alat</th><th>Jumlah</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th class="no-print">Aksi</th></tr></thead>
                <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($menunggu)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_peminjam'] ?? '-'); ?></td>
                        <td><?= htmlspecialchars($row['nama_alat'] ?? '-'); ?></td>
                        <td><?= (int)$row['jumlah_pinjam']; ?></td>
                        <td><?= !empty($row['tanggal_pinjam']) ? date('d M Y', strtotime($row['tanggal_pinjam'])) : '-'; ?></td>
                        <td><?= !empty($row['tanggal_kembali']) ? date('d M Y', strtotime($row['tanggal_kembali'])) : '-'; ?></td>
                        <td><span class="status yellow-status">Menunggu</span></td>
                        <td class="no-print">
                            <div class="action-form">
                                <form action="../controller/c_petugas.php" method="post">
                                    <input type="hidden" name="id_peminjaman" value="<?= (int)$row['id_peminjaman']; ?>">
                                    <button class="approve" type="submit" name="aksi_peminjaman" value="setujui">Setujui</button>
                                </form>
                                <?php if ($role === 'admin'): ?>
                                    <a class="guarantee-btn" href="v_jaminan.php?id=<?= (int)$row['id_peminjaman']; ?>">Jaminan</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($no === 1): ?><tr><td colspan="8" class="empty">Tidak ada pengajuan yang menunggu persetujuan.</td></tr><?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
