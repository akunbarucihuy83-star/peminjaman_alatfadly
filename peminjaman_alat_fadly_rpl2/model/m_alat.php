<?php
include_once "m_koneksi.php";

class Alat
{
    public function tampil()
    {
        global $koneksi;
        $db = $koneksi->koneksi;
        $query = "SELECT a.*, k.nama_kategori
                  FROM alat a
                  LEFT JOIN kategori_alat k ON a.id_kategori = k.id_kategori
                  ORDER BY a.id_alat DESC";
        $hasil = mysqli_query($db, $query);
        if (!$hasil) {
            die("Gagal menampilkan data alat: " . mysqli_error($db));
        }
        return $hasil;
    }

    public function getKategori()
    {
        global $koneksi;
        $db = $koneksi->koneksi;
        $query = "SELECT id_kategori, nama_kategori FROM kategori_alat ORDER BY nama_kategori ASC";
        $hasil = mysqli_query($db, $query);
        if (!$hasil) {
            die("Gagal mengambil kategori alat: " . mysqli_error($db));
        }
        return $hasil;
    }

    public function getById($id)
    {
        global $koneksi;
        $db = $koneksi->koneksi;
        $id = (int) $id;
        $query = "SELECT a.*, k.nama_kategori
                  FROM alat a
                  LEFT JOIN kategori_alat k ON a.id_kategori = k.id_kategori
                  WHERE a.id_alat = $id";
        $hasil = mysqli_query($db, $query);
        if (!$hasil) {
            die("Gagal mengambil data alat: " . mysqli_error($db));
        }
        return mysqli_fetch_assoc($hasil);
    }

    public function tambah($nama_alat, $jumlah, $kondisi, $id_kategori)
    {
        global $koneksi;
        $db = $koneksi->koneksi;
        $nama_alat = mysqli_real_escape_string($db, trim($nama_alat));
        $jumlah = (int) $jumlah;
        $kondisi = mysqli_real_escape_string($db, $kondisi);
        $id_kategori = (int) $id_kategori;
        $query = "INSERT INTO alat (nama_alat, id_kategori, jumlah, kondisi)
                  VALUES ('$nama_alat', $id_kategori, $jumlah, '$kondisi')";
        $hasil = mysqli_query($db, $query);
        if (!$hasil) {
            die("Gagal menambahkan alat: " . mysqli_error($db));
        }
        return $hasil;
    }

    public function edit($id, $nama_alat, $jumlah, $kondisi, $id_kategori)
    {
        global $koneksi;
        $db = $koneksi->koneksi;
        $id = (int) $id;
        $nama_alat = mysqli_real_escape_string($db, trim($nama_alat));
        $jumlah = (int) $jumlah;
        $kondisi = mysqli_real_escape_string($db, $kondisi);
        $id_kategori = (int) $id_kategori;
        $query = "UPDATE alat
                  SET nama_alat = '$nama_alat',
                      id_kategori = $id_kategori,
                      jumlah = $jumlah,
                      kondisi = '$kondisi'
                  WHERE id_alat = $id";
        $hasil = mysqli_query($db, $query);
        if (!$hasil) {
            die("Gagal mengedit alat: " . mysqli_error($db));
        }
        return $hasil;
    }

    public function hapus($id)
    {
        global $koneksi;
        $db = $koneksi->koneksi;
        $id = (int) $id;
        $query = "DELETE FROM alat WHERE id_alat = $id";
        $hasil = mysqli_query($db, $query);
        if (!$hasil) {
            die("Gagal menghapus alat: " . mysqli_error($db));
        }
        return $hasil;
    }
}
?>
