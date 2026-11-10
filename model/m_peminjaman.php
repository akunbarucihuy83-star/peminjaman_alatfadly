<?php

require_once __DIR__ . "/m_koneksi.php";

class Peminjaman
{
    private $db;

    public function __construct()
    {
        global $koneksi;
        $this->db = $koneksi->koneksi;
    }

    public function getAll($keyword = '')
    {
        $keyword = mysqli_real_escape_string($this->db, trim($keyword));
        $where = '';

        if ($keyword !== '') {
            $where = "WHERE u.nama LIKE '%$keyword%'
                      OR u.username LIKE '%$keyword%'
                      OR a.nama_alat LIKE '%$keyword%'
                      OR p.status LIKE '%$keyword%'
                      OR p.jaminan LIKE '%$keyword%'";
        }

        $sql = "
            SELECT
                p.*,
                u.nama AS nama_peminjam,
                u.username,
                a.nama_alat
            FROM peminjaman p
            LEFT JOIN users u ON u.id_user = p.id_user
            LEFT JOIN alat a ON a.id_alat = p.id_alat
            $where
            ORDER BY p.id_peminjaman DESC
        ";

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Gagal mengambil data peminjaman: " . mysqli_error($this->db));
        }

        return $result;
    }


    // Semua riwayat peminjaman dari seluruh peminjam.
    // Dipakai untuk halaman riwayat bersama yang dapat dilihat user yang login.

    public function getByStatus($status, $keyword = '')
    {
        $status = mysqli_real_escape_string($this->db, trim($status));
        $keyword = mysqli_real_escape_string($this->db, trim($keyword));

        $where = "p.status = '$status'";
        if ($keyword !== '') {
            $where .= " AND (
                u.nama LIKE '%$keyword%'
                OR u.username LIKE '%$keyword%'
                OR a.nama_alat LIKE '%$keyword%'
            )";
        }

        $sql = "
            SELECT
                p.*,
                u.nama AS nama_peminjam,
                u.username,
                a.nama_alat
            FROM peminjaman p
            LEFT JOIN users u ON u.id_user = p.id_user
            LEFT JOIN alat a ON a.id_alat = p.id_alat
            WHERE $where
            ORDER BY p.id_peminjaman DESC
        ";

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Gagal mengambil data peminjaman: " . mysqli_error($this->db));
        }

        return $result;
    }

    public function updateStatus($id_peminjaman, $status, $id_petugas = 0)
    {
        $id_peminjaman = (int) $id_peminjaman;
        $allowed = ['Disetujui', 'Dikembalikan'];
        if (!in_array($status, $allowed, true)) {
            die("Status tidak valid.");
        }

        mysqli_begin_transaction($this->db);
        try {
            $cek = mysqli_query(
                $this->db,
                "SELECT id_peminjaman, id_user, id_alat, jumlah_pinjam, status
                 FROM peminjaman WHERE id_peminjaman = $id_peminjaman
                 LIMIT 1 FOR UPDATE"
            );
            if (!$cek || mysqli_num_rows($cek) !== 1) {
                throw new Exception("Data peminjaman tidak ditemukan.");
            }
            $row = mysqli_fetch_assoc($cek);
            $status_lama = $row['status'] ?? '';

            if ($status === 'Disetujui' && $status_lama !== 'Menunggu') {
                throw new Exception("Peminjaman ini sudah diproses.");
            }
            if ($status === 'Dikembalikan' && !in_array($status_lama, ['Disetujui', 'Dipinjam'], true)) {
                throw new Exception("Peminjaman belum dapat dikembalikan.");
            }

            // Stok sudah dikurangi saat pengajuan dibuat. Tambahkan kembali hanya saat pengembalian.
            if ($status === 'Dikembalikan') {
                $id_alat = (int) $row['id_alat'];
                $jumlah = (int) $row['jumlah_pinjam'];
                $stok = mysqli_query($this->db,
                    "UPDATE alat SET jumlah = jumlah + $jumlah WHERE id_alat = $id_alat");
                if (!$stok) {
                    throw new Exception("Gagal mengembalikan stok alat: " . mysqli_error($this->db));
                }
            }

            $status_db = mysqli_real_escape_string($this->db, $status);
            $tanggal_kembali_sql = ($status === 'Dikembalikan') ? ", tanggal_kembali = CURDATE()" : '';
            $ok = mysqli_query($this->db,
                "UPDATE peminjaman SET status = '$status_db' $tanggal_kembali_sql
                 WHERE id_peminjaman = $id_peminjaman");
            if (!$ok) {
                throw new Exception("Gagal memperbarui status: " . mysqli_error($this->db));
            }

            if ($id_petugas > 0) {
                $log = mysqli_real_escape_string($this->db, "$status peminjaman ID $id_peminjaman");
                $ip = mysqli_real_escape_string($this->db, $_SERVER['REMOTE_ADDR'] ?? '');
                mysqli_query($this->db,
                    "INSERT INTO log_aktivitas (id_user, aktivitas, ip_address)
                     VALUES ($id_petugas, '$log', '$ip')");
            }
            mysqli_commit($this->db);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->db);
            die($e->getMessage());
        }
    }

    public function getById($id_peminjaman)
    {
        $id_peminjaman = (int) $id_peminjaman;
        $sql = "
            SELECT p.*, u.nama AS nama_peminjam, u.username, a.nama_alat
            FROM peminjaman p
            LEFT JOIN users u ON u.id_user = p.id_user
            LEFT JOIN alat a ON a.id_alat = p.id_alat
            WHERE p.id_peminjaman = $id_peminjaman
            LIMIT 1
        ";
        $result = mysqli_query($this->db, $sql);
        if (!$result || mysqli_num_rows($result) !== 1) {
            return null;
        }
        return mysqli_fetch_assoc($result);
    }

    public function updateJaminan($id_peminjaman, $jaminan)
    {
        $id_peminjaman = (int) $id_peminjaman;
        $jaminan = mysqli_real_escape_string($this->db, trim($jaminan));
        $allowed = ['KTP', 'Kartu Pelajar', 'SIM', 'Uang Tunai', 'Tidak Ada'];
        if (!in_array($jaminan, $allowed, true) || $id_peminjaman <= 0) {
            return false;
        }
        return mysqli_query($this->db, "UPDATE peminjaman SET jaminan = '$jaminan' WHERE id_peminjaman = $id_peminjaman LIMIT 1");
    }

    public function getAllHistory($keyword = '')
    {
        $keyword = mysqli_real_escape_string($this->db, trim($keyword));
        $where = '';

        if ($keyword !== '') {
            $where = "WHERE u.nama LIKE '%$keyword%'
                      OR u.username LIKE '%$keyword%'
                      OR a.nama_alat LIKE '%$keyword%'
                      OR p.status LIKE '%$keyword%'
                OR p.jaminan LIKE '%$keyword%'";
        }

        $sql = "
            SELECT
                p.id_peminjaman,
                p.jumlah_pinjam,
                p.tanggal_pinjam,
                p.tanggal_kembali,
                p.status,
                p.keperluan,
                p.catatan,
                u.id_user,
                u.nama AS nama_peminjam,
                u.username,
                a.nama_alat
            FROM peminjaman p
            INNER JOIN users u ON u.id_user = p.id_user
            INNER JOIN alat a ON a.id_alat = p.id_alat
            $where
            ORDER BY p.tanggal_pinjam DESC, p.id_peminjaman DESC
        ";

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Gagal mengambil seluruh riwayat peminjaman: " . mysqli_error($this->db));
        }

        return $result;
    }

    public function getAllByUser($id_user)
    {
        $id_user = (int) $id_user;

        $sql = "
            SELECT
                p.*,
                a.nama_alat
            FROM peminjaman p
            LEFT JOIN alat a ON a.id_alat = p.id_alat
            WHERE p.id_user = $id_user
            ORDER BY p.id_peminjaman DESC
        ";

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Gagal mengambil data peminjaman: " . mysqli_error($this->db));
        }

        return $result;
    }

    public function tambah($id_user, $id_alat, $jumlah_pinjam, $tanggal_pinjam, $tanggal_kembali, $jaminan = 'KTP', $keperluan = '', $catatan = '')
    {
        $id_user = (int) $id_user;
        $id_alat = (int) $id_alat;
        $jumlah_pinjam = (int) $jumlah_pinjam;
        $tanggal_pinjam = mysqli_real_escape_string($this->db, $tanggal_pinjam);
        $tanggal_kembali = mysqli_real_escape_string($this->db, $tanggal_kembali);
        $jaminan = mysqli_real_escape_string($this->db, trim($jaminan));
        $keperluan = mysqli_real_escape_string($this->db, $keperluan);
        $catatan = mysqli_real_escape_string($this->db, $catatan);

        if ($id_user <= 0 || $id_alat <= 0 || $jumlah_pinjam < 1) {
            die("Data peminjaman tidak valid.");
        }

        // Kurangi stok saat pengajuan dibuat (stok langsung terpesan), secara atomik
        // agar dua peminjam tidak bisa mengambil stok yang sama.
        mysqli_begin_transaction($this->db);
        try {
            $kurangi = mysqli_query($this->db,
                "UPDATE alat SET jumlah = jumlah - $jumlah_pinjam
                 WHERE id_alat = $id_alat AND kondisi <> 'Rusak Berat'
                   AND jumlah >= $jumlah_pinjam");
            if (!$kurangi || mysqli_affected_rows($this->db) !== 1) {
                throw new Exception("Stok alat tidak mencukupi atau alat sedang tidak tersedia. Silakan pilih jumlah yang lebih kecil.");
            }

            $sql = "INSERT INTO peminjaman
                (id_user, id_alat, jaminan, jumlah_pinjam, tanggal_pinjam, tanggal_kembali, status, keperluan, catatan)
                VALUES ($id_user, $id_alat, '$jaminan', $jumlah_pinjam, '$tanggal_pinjam', '$tanggal_kembali', 'Menunggu', '$keperluan', '$catatan')";
            if (!mysqli_query($this->db, $sql)) {
                throw new Exception("Gagal mengajukan peminjaman: " . mysqli_error($this->db));
            }

            $log_text = mysqli_real_escape_string($this->db,
                "Mengajukan peminjaman alat ID $id_alat (jumlah $jumlah_pinjam)");
            $ip = mysqli_real_escape_string($this->db, $_SERVER['REMOTE_ADDR'] ?? '');
            mysqli_query($this->db,
                "INSERT INTO log_aktivitas (id_user, aktivitas, ip_address)
                 VALUES ($id_user, '$log_text', '$ip')");

            mysqli_commit($this->db);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->db);
            die($e->getMessage());
        }
    }

}
?>
