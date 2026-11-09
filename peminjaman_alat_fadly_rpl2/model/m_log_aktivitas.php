<?php

include_once "m_koneksi.php";

class LogAktivitas
{
    // TAMPIL DATA LOG
    public function tampil()
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $query = "
            SELECT *
            FROM log_aktivitas
            ORDER BY id_log DESC
        ";

        $hasil = mysqli_query($db, $query);

        if (!$hasil) {
            die("Gagal menampilkan log: " . mysqli_error($db));
        }

        return $hasil;
    }


    // TAMBAH LOG
    public function tambah($username, $aktivitas)
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $username = mysqli_real_escape_string(
            $db,
            $username
        );

        $aktivitas = mysqli_real_escape_string(
            $db,
            $aktivitas
        );

        $query = "
            INSERT INTO log_aktivitas
            (username, aktivitas)
            VALUES
            ('$username', '$aktivitas')
        ";

        return mysqli_query($db, $query);
    }
}

?>