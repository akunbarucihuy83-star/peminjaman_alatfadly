<?php

require_once __DIR__ . "/m_koneksi.php";

class Dashboard
{
    private $db;

    public function __construct()
    {
        global $koneksi;
        $this->db = $koneksi->koneksi;
    }

    public function getAdminStats()
    {
        $sql = "
            SELECT
                (SELECT COUNT(*) FROM alat) AS total_alat,
                (SELECT COUNT(*) FROM kategori_alat) AS total_kategori,
                (SELECT COUNT(*) FROM peminjaman) AS total_peminjaman,
                (SELECT COUNT(*) FROM users WHERE role = 'peminjam') AS total_peminjam
        ";

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Gagal mengambil statistik dashboard: " . mysqli_error($this->db));
        }

        return mysqli_fetch_assoc($result);
    }

    public function getRecentActivities($limit = 4)
    {
        $limit = max(1, (int) $limit);

        $sql = "
            SELECT
                l.aktivitas,
                l.waktu,
                u.nama
            FROM log_aktivitas l
            LEFT JOIN users u ON u.id_user = l.id_user
            ORDER BY l.id_log DESC
            LIMIT $limit
        ";

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Gagal mengambil aktivitas dashboard: " . mysqli_error($this->db));
        }

        return $result;
    }

    public function getPeminjamStats($id_user)
    {
        $id_user = (int) $id_user;

        $sql = "
            SELECT
                (SELECT COUNT(*) FROM alat WHERE jumlah > 0 AND kondisi <> 'Rusak Berat') AS alat_tersedia,
                (SELECT COUNT(*) FROM peminjaman WHERE id_user = $id_user) AS total_peminjaman,
                (SELECT COUNT(*) FROM peminjaman WHERE id_user = $id_user AND status IN ('Dipinjam','Disetujui')) AS sedang_dipinjam,
                (SELECT COUNT(*) FROM peminjaman WHERE id_user = $id_user AND status = 'Dikembalikan') AS selesai
        ";

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Gagal mengambil statistik peminjam: " . mysqli_error($this->db));
        }

        return mysqli_fetch_assoc($result);
    }

    public function getRecentLoansByUser($id_user, $limit = 3)
    {
        $id_user = (int) $id_user;
        $limit = max(1, (int) $limit);

        $sql = "
            SELECT
                p.id_peminjaman,
                p.tanggal_pinjam,
                p.tanggal_kembali,
                p.status,
                a.nama_alat
            FROM peminjaman p
            LEFT JOIN alat a ON a.id_alat = p.id_alat
            WHERE p.id_user = $id_user
            ORDER BY p.id_peminjaman DESC
            LIMIT $limit
        ";

        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            die("Gagal mengambil peminjaman terbaru: " . mysqli_error($this->db));
        }

        return $result;
    }
}

?>
