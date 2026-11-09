<?php

require_once __DIR__ . "/../controller/c_login.php";
require_once __DIR__ . "/../model/m_alat.php";
require_once __DIR__ . "/../model/m_peminjaman.php";

if (($_SESSION['role'] ?? '') !== 'peminjam') {
    header("Location: v_dashboard_peminjam.php");
    exit;
}

$alat = new Alat();
$data_alat = $alat->tampil();

$peminjaman = new Peminjaman();
$id_user = (int) ($_SESSION['id_user'] ?? 0);
$data_peminjaman = $peminjaman->getAllHistory();

$pesan = $_GET['pesan'] ?? '';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Saya - Inventaris</title>
    <link rel="stylesheet" href="../asset/peminjamann.css?v=2">

</head>
<body>

<nav class="navbar">
    <div class="logo"><span>✦</span> Inventaris</div>

    <div class="nav-menu">
        <a href="v_dashboard_peminjam.php">Dashboard</a>
        <a href="v_alat_peminjam.php">Alat</a>
        <a href="v_peminjaman_peminjam.php" class="active">Peminjaman</a>
    </div>

    <div class="admin">
        <div class="avatar">
            <?= strtoupper(substr($_SESSION['username'] ?? 'P', 0, 1)); ?>
        </div>
        <span><?= htmlspecialchars($_SESSION['username'] ?? 'Peminjam'); ?></span>
    </div>
</nav>

<main class="container">

    <div class="welcome">
        <div>
            <h1>Peminjaman Saya</h1>
            <p>Ajukan peminjaman alat dan lihat status pengajuan kamu.</p>
        </div>
    </div>

    <?php if ($pesan === 'berhasil'): ?>
        <div class="success">
            Peminjaman berhasil diajukan.
        </div>
    <?php endif; ?>

    <div class="new-loan-wrap">
        <button type="button" class="new-loan-btn" id="btnPeminjamanBaru">
            + Peminjaman Baru
        </button>
    </div>

    <div class="form-box" id="formPeminjaman">
        <h2>Form Peminjaman Baru</h2>
        <p>Isi data berikut untuk mengajukan peminjaman alat.</p>

        <form action="../controller/c_peminjaman.php" method="POST">

            <div class="form-group">
                <label for="id_alat">Pilih Alat</label>
                <select id="id_alat" name="id_alat" required>
                    <option value="">-- Pilih alat --</option>
                    <?php while ($alat_row = mysqli_fetch_assoc($data_alat)): ?>
                        <?php if ((int)$alat_row['jumlah'] > 0): ?>
                            <option value="<?= (int)$alat_row['id_alat']; ?>">
                                <?= htmlspecialchars($alat_row['nama_alat']); ?>
                                (tersedia: <?= (int)$alat_row['jumlah']; ?>)
                            </option>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah_pinjam">Jumlah Pinjam</label>
                <input type="number" id="jumlah_pinjam" name="jumlah_pinjam" min="1" value="1" required>
            </div>

            <div class="form-group">
                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required>
            </div>

            <div class="form-group">
                <label for="tanggal_kembali">Tanggal Kembali</label>
                <input type="date" id="tanggal_kembali" name="tanggal_kembali" required>
            </div>

            <div class="form-group">
                <label for="keperluan">Keperluan</label>
                <textarea id="keperluan" name="keperluan" rows="3" style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #ddd;border-radius:8px;font-size:15px;resize:vertical;" placeholder="Contoh: Praktikum / tugas sekolah"></textarea>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #ddd;border-radius:8px;font-size:15px;resize:vertical;" placeholder="Catatan tambahan (opsional)"></textarea>
            </div>

            <div class="form-action">
                <button type="submit" name="tambah_peminjaman" class="button">
                    Ajukan Peminjaman
                </button>
                <button type="button" class="btn-cancel" id="btnBatal">Batal</button>
            </div>

        </form>
    </div>

    <div class="box riwayat-box" id="riwayatPeminjaman">
        <div class="box-title">
            <div>
                <h3>Riwayat Peminjaman</h3>
                <p>Daftar seluruh riwayat peminjaman semua peminjam.</p>
            </div>
        </div>

        <div class="table-wrap">
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
            <?php while ($row = mysqli_fetch_assoc($data_peminjaman)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">
                                <?= strtoupper(substr($row['nama_peminjam'] ?? 'P', 0, 1)); ?>
                            </div>
                            <div>
                                <div class="user-name"><?= htmlspecialchars($row['nama_peminjam'] ?? '-'); ?></div>
                                <small>@<?= htmlspecialchars($row['username'] ?? '-'); ?></small>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($row['nama_alat'] ?? '-'); ?></td>
                    <td><?= (int)($row['jumlah_pinjam'] ?? 0); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pinjam'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kembali'] ?? '-'); ?></td>
                    <td>
                        <span class="status blue-status">
                            <?= htmlspecialchars($row['status'] ?? '-'); ?>
                        </span>
                    </td>
                </tr>
            <?php endwhile; ?>

            <?php if ($no === 1): ?>
                <tr>
                    <td colspan="8">Belum ada riwayat peminjaman.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>

</main>

<script>
const btnPeminjamanBaru = document.getElementById('btnPeminjamanBaru');
const btnBatal = document.getElementById('btnBatal');
const formPeminjaman = document.getElementById('formPeminjaman');
const newLoanWrap = document.querySelector('.new-loan-wrap');
const riwayatPeminjaman = document.getElementById('riwayatPeminjaman');

// Saat + Peminjaman Baru diklik: tampilkan form, sembunyikan tombol dan riwayat.
btnPeminjamanBaru.addEventListener('click', function () {
    formPeminjaman.classList.add('show');
    newLoanWrap.classList.add('hide');
    riwayatPeminjaman.classList.add('hide');
    formPeminjaman.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

// Saat Batal diklik: sembunyikan form dan tampilkan kembali riwayat.
btnBatal.addEventListener('click', function () {
    formPeminjaman.classList.remove('show');
    newLoanWrap.classList.remove('hide');
    riwayatPeminjaman.classList.remove('hide');
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

const today = new Date().toISOString().split('T')[0];
const pinjam = document.getElementById('tanggal_pinjam');
const kembali = document.getElementById('tanggal_kembali');
pinjam.min = today;
kembali.min = today;
pinjam.addEventListener('change', function () {
    kembali.min = this.value || today;
    if (kembali.value && kembali.value < kembali.min) kembali.value = '';
});
</script>
</body>
</html>
