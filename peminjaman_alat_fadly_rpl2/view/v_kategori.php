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
include "../model/m_kategori.php";

$kategori = new Kategori();

$dataKategori = $kategori->getAll();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Kategori - Inventaris</title>

    <link rel="stylesheet" href="../asset/kategorii.css?v=20261005">

    <style>
        /* KATEGORI PAGE - OVERRIDE */
        html, body { width:100%; min-height:100%; }
        body { margin:0; font-family:"Segoe UI",Arial,sans-serif; background:#eef2ff; color:#172554; }
        .content { width:calc(100% - 36px) !important; max-width:none !important; margin:28px 18px 40px !important; padding:0 !important; }
        .header-content { display:flex !important; align-items:center !important; justify-content:space-between !important; gap:18px !important; margin-bottom:18px !important; }
        .header-content h1 { margin:0 0 5px !important; font-size:30px !important; color:#172554 !important; }
        .header-content p { margin:0 !important; color:#6366a3 !important; font-size:14px !important; }
        .btn-tambah { display:inline-block !important; text-decoration:none !important; color:#fff !important; background:linear-gradient(135deg,#1e3a8a,#6d28d9) !important; padding:11px 18px !important; border-radius:9px !important; font-weight:700 !important; }
        .table-container { width:100% !important; background:#fff !important; border:1px solid #c7d2fe !important; border-radius:15px !important; box-shadow:0 5px 20px rgba(30,27,75,.10) !important; overflow:hidden !important; }
        .table-container table { width:100% !important; border-collapse:collapse !important; table-layout:fixed !important; }
        .table-container th { background:#dbeafe !important; color:#312e81 !important; text-align:left !important; padding:14px 15px !important; font-size:13px !important; border-bottom:2px solid #a5b4fc !important; }
        .table-container td { padding:15px !important; border-bottom:1px solid #e0e7ff !important; font-size:14px !important; color:#374151 !important; }
        .table-container th:nth-child(1), .table-container td:nth-child(1){width:10% !important;}
        .table-container th:nth-child(2), .table-container td:nth-child(2){width:60% !important;}
        .table-container th:nth-child(3), .table-container td:nth-child(3){width:30% !important;}
        .btn-edit,.btn-hapus { display:inline-block !important; text-decoration:none !important; padding:6px 10px !important; border-radius:6px !important; font-size:12px !important; font-weight:700 !important; margin:2px 5px 2px 0 !important; }
        .btn-edit { background:#dbeafe !important; color:#1e3a8a !important; }
        .btn-hapus { background:#fee2e2 !important; color:#b91c1c !important; }
        @media(max-width:700px){
          .navbar { padding:12px 14px !important; }
          .content { width:calc(100% - 24px) !important; margin:20px 12px 30px !important; }
          .header-content { align-items:flex-start !important; flex-direction:column !important; }
          .header-content h1 { font-size:25px !important; }
          .btn-tambah { width:100% !important; text-align:center !important; }
          .table-container { overflow:hidden !important; }
          .table-container table { min-width:0 !important; }
          .table-container th,.table-container td { padding:10px 7px !important; font-size:12px !important; }
          .table-container th:nth-child(1),.table-container td:nth-child(1){width:12% !important;}
          .table-container th:nth-child(2),.table-container td:nth-child(2){width:48% !important;}
          .table-container th:nth-child(3),.table-container td:nth-child(3){width:40% !important;}
          .btn-edit,.btn-hapus { padding:5px 7px !important; font-size:11px !important; }
        }
    </style>

</head>

<body>


<!-- ==========================================
     NAVBAR
========================================== -->

<nav class="navbar">

    <div class="logo">
        ✦ Inventaris
    </div>

    <div class="nav-menu">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a
            href="v_kategori.php"
            class="active"
        >
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

        <a href="v_user.php">
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


<!-- ==========================================
     CONTENT
========================================== -->

<div class="content">

    <div class="header-content">

        <div>

            <h1>
                Data Kategori
            </h1>

            <p>
                Kelola kategori alat inventaris
            </p>

        </div>


        <a
            href="v_tambah_kategori.php"
            class="btn-tambah"
        >
            + Tambah Kategori
        </a>

    </div>


    <!-- ==========================================
         TABLE
    =========================================== -->

    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>
                        No
                    </th>

                    <th>
                        Nama Kategori
                    </th>

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

            <?php

            $no = 1;

            while (
                $row = mysqli_fetch_assoc($dataKategori)
            ):

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $row['nama_kategori']
                        ); ?>
                    </td>

                    <td>

                        <a
                            href="v_edit_kategori.php?id=<?= $row['id_kategori']; ?>"
                            class="btn-edit"
                        >
                            Edit
                        </a>


                        <a
                            href="../controller/c_kategori.php?hapus_kategori=<?= $row['id_kategori']; ?>"
                            class="btn-hapus"
                            onclick="return confirm('Yakin ingin menghapus kategori ini?');"
                        >
                            Hapus
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>