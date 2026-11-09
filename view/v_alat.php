<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: v_login.php");
    exit;
}
if (!in_array(($_SESSION['role'] ?? ''), ['admin', 'petugas'], true)) {
    if (($_SESSION['role'] ?? '') === 'peminjam') {
        header("Location: v_dashboard_peminjam.php");
    } else {
        header("Location: v_login.php");
    }
    exit;
}
?>

<?php

include "../controller/c_login.php";
include "../model/m_alat.php";

$alat = new Alat();
$dataAlat = $alat->tampil();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Alat - Inventaris</title>


    <!-- =========================
         CSS LANGSUNG DI SINI
    ========================== -->

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #eef2ff;
            color: #172554;
        }


        /* =========================
           NAVBAR
        ========================= */

        .navbar {
            height: 70px;

            background: linear-gradient(
                90deg,
                #172554,
                #4c1d95
            );

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 45px;

            color: white;

            box-shadow:
                0 4px 18px rgba(30, 27, 75, 0.30);
        }


        /* LOGO */

        .logo {
            font-size: 22px;

            font-weight: 700;

            display: flex;

            align-items: center;

            gap: 8px;
        }

        .logo span {
            color: #c4b5fd;
        }


        /* MENU */

        .nav-menu {
            display: flex;

            align-items: center;

            gap: 8px;
        }

        .nav-menu a {
            text-decoration: none;

            color: #ddd6fe;

            padding: 10px 15px;

            border-radius: 8px;

            font-size: 14px;

            transition: 0.2s;
        }

        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.12);

            color: white;
        }

        .nav-menu a.active {
            background: rgba(255, 255, 255, 0.20);

            color: white;
        }


        /* ADMIN */

        .admin {
            display: flex;

            align-items: center;

            gap: 10px;

            font-size: 14px;
        }

        .avatar {
            width: 35px;

            height: 35px;

            border-radius: 50%;

            background: #c4b5fd;

            color: #312e81;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 700;
        }


        /* =========================
           CONTAINER
        ========================= */

        .container {
            max-width: 1100px;

            margin: 35px auto;

            padding: 0 20px;
        }


        /* =========================
           HEADER
        ========================= */

        .header {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;
        }

        .header h1 {
            color: #172554;

            font-size: 28px;

            margin-bottom: 6px;
        }

        .header p {
            color: #64748b;

            font-size: 14px;
        }


        /* =========================
           BUTTON TAMBAH
        ========================= */

        .btn-tambah {
            display: inline-block;

            padding: 12px 20px;

            border-radius: 8px;

            background: linear-gradient(
                90deg,
                #172554,
                #4c1d95
            );

            color: white;

            text-decoration: none;

            font-weight: 600;

            transition: 0.2s;
        }

        .btn-tambah:hover {
            transform: translateY(-2px);

            box-shadow:
                0 5px 12px rgba(76, 29, 149, 0.25);
        }


        /* =========================
           TABLE BOX
        ========================= */

        .table-box {
            background: white;

            border-radius: 12px;

            padding: 20px;

            box-shadow:
                0 4px 15px rgba(30, 27, 75, 0.08);

            overflow-x: auto;
        }


        /* =========================
           TABLE
        ========================= */

        table {
            width: 100%;

            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 14px;

            border-bottom: 1px solid #e2e8f0;

            text-align: left;

            font-size: 14px;
        }

        table th {
            background: #eef2ff;

            color: #172554;

            font-weight: 700;
        }

        table td {
            color: #334155;
        }

        table tr:hover td {
            background: #f8faff;
        }


        /* =========================
           AKSI
        ========================= */

        .aksi {
            white-space: nowrap;
        }


        /* EDIT */

        .btn-edit {
            display: inline-block;

            padding: 7px 12px;

            border-radius: 6px;

            background: #ddd6fe;

            color: #4c1d95;

            text-decoration: none;

            font-weight: 600;

            font-size: 13px;
        }

        .btn-edit:hover {
            background: #c4b5fd;
        }


        /* HAPUS */

        .btn-hapus {
            display: inline-block;

            padding: 7px 12px;

            margin-left: 5px;

            border-radius: 6px;

            background: #fee2e2;

            color: #b91c1c;

            text-decoration: none;

            font-weight: 600;

            font-size: 13px;
        }

        .btn-hapus:hover {
            background: #fecaca;
        }


        /* =========================
           DATA KOSONG
        ========================= */

        .kosong {
            text-align: center;

            color: #64748b;

            padding: 30px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .navbar {
                padding: 0 20px;
            }

            .nav-menu {
                gap: 2px;
            }

            .nav-menu a {
                padding: 8px 9px;

                font-size: 12px;
            }

        }


        @media (max-width: 700px) {

            .navbar {
                height: auto;

                padding: 15px;

                flex-direction: column;

                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;

                justify-content: center;
            }

            .header {
                flex-direction: column;

                align-items: flex-start;

                gap: 15px;
            }

            .btn-tambah {
                width: 100%;

                text-align: center;
            }

        }

    </style>


<style id="logout-visibility-fix">
.navbar{position:relative!important;overflow:visible!important;padding-right:92px!important}
.navbar .logout-link{display:none!important}
.navbar .logout-button{display:inline-flex!important;align-items:center;justify-content:center;flex:0 0 auto!important;white-space:nowrap!important;background:#ef4444!important;color:#fff!important;text-decoration:none!important;font-size:12px!important;font-weight:700!important;padding:7px 10px!important;border-radius:7px!important;z-index:20!important}
.navbar .logout-button:hover{background:#dc2626!important;color:#fff!important}
.navbar .logout-button{position:absolute!important;right:10px!important;top:50%!important;transform:translateY(-50%)!important}
@media(max-width:700px){.navbar{padding-right:88px!important;min-height:48px!important}.navbar .logout-button{right:7px!important;font-size:11px!important;padding:6px 8px!important}}
</style>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar">

    <div class="logo">

        ✦ <span>Inventaris</span>

    </div>


    <div class="nav-menu">

        <a href="<?= ($_SESSION['role'] ?? '') === 'petugas' ? 'v_dashboard_petugas.php' : 'dashboard.php'; ?>">
            Dashboard
        </a>

        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
        <a href="v_kategori.php">
            Kategori
        </a>
        <?php endif; ?>

        <a href="v_alat.php"
           class="active">

            Alat

        </a>

        <a href="v_peminjaman.php">
            Peminjaman
        </a>

        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
        <a href="v_log_aktivitas.php">
            Log Aktivitas
        </a>

        <a href="v_user.php">
            User
        </a>
        <?php endif; ?>

    </div>


    <div class="admin">

        <div class="avatar">
            <?= strtoupper(substr($_SESSION['username'] ?? 'P', 0, 1)); ?>
        </div>

        <span>
            <?= htmlspecialchars($_SESSION['username'] ?? 'Petugas'); ?>
        </span>

            <a href="../controller/c_logout.php" class="logout-link">Logout</a>
    </div>

    <a href="../controller/c_logout.php" class="logout-button" aria-label="Logout">Logout</a>
</nav>



<!-- =========================
     CONTENT
========================= -->

<div class="container">


    <div class="header">

        <div>

            <h1>
                Data Alat
            </h1>

            <p>
                Kelola data alat inventaris
            </p>

        </div>


        <a href="v_tambah_alat.php"
           class="btn-tambah">

            + Tambah Alat

        </a>

    </div>



    <!-- =========================
         TABLE
    ========================= -->

    <div class="table-box">

        <table>

            <thead>

                <tr>

                    <th>
                        No
                    </th>

                    <th>
                        Nama Alat
                    </th>

                    <th>
                        Kategori
                    </th>

                    <th>
                        Jumlah
                    </th>

                    <th>
                        Kondisi
                    </th>

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                <?php

                $no = 1;

                if (mysqli_num_rows($dataAlat) > 0) {

                    while ($row = mysqli_fetch_assoc($dataAlat)) {

                ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>


                    <td>
                        <?= htmlspecialchars($row['nama_alat']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['jumlah']); ?>
                    </td>


                    <td>
                        <?= htmlspecialchars($row['kondisi']); ?>
                    </td>

                    <td class="aksi">

                        <!-- EDIT -->

                        <a
                            href="v_edit_alat.php?id=<?= $row['id_alat']; ?>"
                            class="btn-edit"
                        >

                            Edit

                        </a>


                        <!-- HAPUS -->

                        <a
                            href="../controller/c_alat.php?hapus=<?= $row['id_alat']; ?>"
                            class="btn-hapus"
                            onclick="return confirm('Yakin ingin menghapus alat ini?');"
                        >

                            Hapus

                        </a>

                    </td>

                </tr>

                <?php

                    }

                } else {

                ?>

                <tr>

                    <td colspan="6"
                        class="kosong">

                        Belum ada data alat.

                    </td>

                </tr>

                <?php

                }

                ?>

            </tbody>

        </table>

    </div>

</div>


</body>

</html>