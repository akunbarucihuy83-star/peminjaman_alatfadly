<?php

include_once "m_koneksi.php";

class Kategori
{

    // =========================================
    // TAMPIL DATA KATEGORI
    // =========================================

    public function getAll()
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $query = "
            SELECT *
            FROM kategori_alat
            ORDER BY id_kategori DESC
        ";

        $hasil = mysqli_query($db, $query);

        if (!$hasil) {
            die(
                "Gagal mengambil data kategori: "
                . mysqli_error($db)
            );
        }

        return $hasil;
    }


    // =========================================
    // AMBIL KATEGORI BERDASARKAN ID
    // =========================================

    public function getById($id)
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $id = (int) $id;

        $query = "
            SELECT *
            FROM kategori_alat
            WHERE id_kategori = $id
        ";

        $hasil = mysqli_query($db, $query);

        if (!$hasil) {
            die(
                "Gagal mengambil kategori: "
                . mysqli_error($db)
            );
        }

        return mysqli_fetch_assoc($hasil);
    }


    // =========================================
    // TAMBAH KATEGORI
    // =========================================

    public function tambah($nama_kategori)
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $nama_kategori = mysqli_real_escape_string(
            $db,
            $nama_kategori
        );

        $query = "
            INSERT INTO kategori_alat
            (nama_kategori)
            VALUES
            ('$nama_kategori')
        ";

        $hasil = mysqli_query($db, $query);

        if (!$hasil) {
            die(
                "Gagal menambahkan kategori: "
                . mysqli_error($db)
            );
        }

        return $hasil;
    }


    // =========================================
    // EDIT KATEGORI
    // =========================================

    public function ubah($id_kategori, $nama_kategori)
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $id_kategori = (int) $id_kategori;

        $nama_kategori = mysqli_real_escape_string(
            $db,
            $nama_kategori
        );

        $query = "
            UPDATE kategori_alat
            SET nama_kategori = '$nama_kategori'
            WHERE id_kategori = $id_kategori
        ";

        $hasil = mysqli_query($db, $query);

        if (!$hasil) {
            die(
                "Gagal mengubah kategori: "
                . mysqli_error($db)
            );
        }

        return $hasil;
    }


    // =========================================
    // HAPUS KATEGORI
    // =========================================

    public function hapus($id_kategori)
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $id_kategori = (int) $id_kategori;

        $query = "
            DELETE FROM kategori_alat
            WHERE id_kategori = $id_kategori
        ";

        $hasil = mysqli_query($db, $query);

        if (!$hasil) {
            die(
                "Gagal menghapus kategori: "
                . mysqli_error($db)
            );
        }

        return $hasil;
    }

}

?>