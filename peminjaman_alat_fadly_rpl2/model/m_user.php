<?php

include_once "m_koneksi.php";

class User
{
    // =========================
    // TAMPIL USER
    // =========================
    public function tampil()
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $query = "SELECT * FROM users ORDER BY id_user DESC";

        return mysqli_query($db, $query);
    }


    // =========================
    // AMBIL USER BERDASARKAN ID
    // =========================
    public function getById($id)
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $id = (int) $id;

        $query = "SELECT * FROM users
                  WHERE id_user = $id";

        $hasil = mysqli_query($db, $query);

        if (!$hasil) {
            die("Gagal mengambil data user: " . mysqli_error($db));
        }

        return mysqli_fetch_assoc($hasil);
    }


    // =========================
    // TAMBAH USER
    // =========================
    public function tambah($nama, $username, $password, $role)
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $nama = mysqli_real_escape_string($db, $nama);
        $username = mysqli_real_escape_string($db, $username);
        $password = mysqli_real_escape_string($db, $password);
        $role = mysqli_real_escape_string($db, $role);

        $query = "INSERT INTO users
                  (nama, username, password, role)
                  VALUES
                  ('$nama', '$username', '$password', '$role')";

        return mysqli_query($db, $query);
    }


    // =========================
    // EDIT USER
    // =========================
    public function edit($id, $nama, $username, $password, $role)
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $id = (int) $id;

        $nama = mysqli_real_escape_string($db, $nama);
        $username = mysqli_real_escape_string($db, $username);
        $role = mysqli_real_escape_string($db, $role);

        if ($password !== '') {

            $password = mysqli_real_escape_string(
                $db,
                $password
            );

            $query = "UPDATE users SET
                        nama = '$nama',
                        username = '$username',
                        password = '$password',
                        role = '$role'
                      WHERE id_user = $id";

        } else {

            $query = "UPDATE users SET
                        nama = '$nama',
                        username = '$username',
                        role = '$role'
                      WHERE id_user = $id";
        }

        return mysqli_query($db, $query);
    }


    // =========================
    // HAPUS USER
    // =========================
    public function hapus($id)
    {
        global $koneksi;

        $db = $koneksi->koneksi;

        $id = (int) $id;

        $query = "DELETE FROM users
                  WHERE id_user = $id";

        return mysqli_query($db, $query);
    }
}

?>