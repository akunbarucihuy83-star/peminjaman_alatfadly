<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: v_login.php");
    exit;
}
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: dashboard.php");
    exit;
}
?>

<?php

include "../controller/c_login.php";
include "../model/m_user.php";

$user = new User();


if (!isset($_GET['id'])) {

    header("Location: v_user.php");
    exit;

}


$id = (int) $_GET['id'];

$data = $user->getById($id);


if (!$data) {

    header("Location: v_user.php");
    exit;

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit User - Inventaris</title>

    <link
        rel="stylesheet"
        href="../asset/user.css?v=11">

</head>

<body>


<nav class="navbar">

    <div class="logo">
        <span>✦</span> Inventaris
    </div>


    <div class="nav-menu">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="v_kategori.php">
            Kategori
        </a>

        <a href="v_alat.php">
            Alat
        </a>

        <a href="v_peminjaman.php">
            Peminjaman
        </a>

        <a href="v_log_aktivitas.php">
            Log Aktivitas
        </a>

        <a href="v_user.php" class="active">
            User
        </a>

    </div>


    <div class="admin">

        <div class="avatar">
            A
        </div>

        <span>
            Admin
        </span>

    </div>

</nav>


<main class="container">


    <div class="form-box">

        <h1>
            Edit User
        </h1>

        <p>
            Ubah data pengguna.
        </p>


        <form
            action="../controller/c_admin.php"
            method="POST">


            <input
                type="hidden"
                name="id_user"
                value="<?= $data['id_user']; ?>">


            <div class="form-group">

                <label>
                    Nama
                </label>

                <input
                    type="text"
                    name="nama"
                    value="<?= htmlspecialchars(
                        $data['nama']
                    ); ?>"
                    required>

            </div>


            <div class="form-group">

                <label>
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    value="<?= htmlspecialchars(
                        $data['username']
                    ); ?>"
                    required>

            </div>


            <div class="form-group">

                <label>
                    Password Baru
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Kosongkan jika tidak diubah">

            </div>


            <div class="form-group">

                <label>
                    Role
                </label>

                <select
                    name="role"
                    required>

                    <option
                        value="admin"
                        <?= $data['role'] == 'admin'
                            ? 'selected'
                            : ''; ?>>

                        Admin

                    </option>


                    <option
                        value="petugas"
                        <?= $data['role'] == 'petugas'
                            ? 'selected'
                            : ''; ?>>

                        Petugas

                    </option>


                    <option
                        value="peminjam"
                        <?= $data['role'] == 'peminjam'
                            ? 'selected'
                            : ''; ?>>

                        Peminjam

                    </option>

                </select>

            </div>


            <div class="form-action">

                <button
                    type="submit"
                    name="edit"
                    class="button">

                    Simpan Perubahan

                </button>


                <a
                    href="v_user.php"
                    class="btn-cancel">

                    Batal

                </a>

            </div>


        </form>

    </div>


</main>

</body>
</html>