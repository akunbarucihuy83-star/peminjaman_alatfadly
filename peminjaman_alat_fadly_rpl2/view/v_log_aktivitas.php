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
include "../model/m_log_aktivitas.php";

$log = new LogAktivitas();

$dataLog = $log->tampil();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Log Aktivitas - Inventaris</title>

    <link
        rel="stylesheet"
        href="../asset/log_aktivitass.css"
    >

</head>

<body>


<!-- =========================
     NAVBAR
========================= -->

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

        <a
            href="v_log_aktivitas.php"
            class="active"
        >
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


<!-- =========================
     CONTENT
========================= -->

<div class="content">

    <div class="header-content">

        <div>

            <h1>
                Log Aktivitas
            </h1>

            <p>
                Riwayat aktivitas pengguna pada sistem
            </p>

        </div>

    </div>


    <!-- =========================
         TABLE
    ========================= -->

    <div class="table-container">

        <div class="table-header">

            <h3>
                Riwayat Aktivitas
            </h3>

            <input
                type="text"
                id="searchLog"
                placeholder="Cari aktivitas..."
            >

        </div>


        <table id="tableLog">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Pengguna</th>

                    <th>Aktivitas</th>

                    <th>Waktu</th>

                </tr>

            </thead>


            <tbody>

            <?php

            $no = 1;

            if (mysqli_num_rows($dataLog) > 0):

                while ($row = mysqli_fetch_assoc($dataLog)):

                    /*
                     * Karena tabel log kamu tidak memiliki
                     * key username, kita gunakan username
                     * dari session.
                     */

                    $namaUser = $_SESSION['username'] ?? 'Admin';

            ?>

                <tr>

                    <!-- NO -->

                    <td>
                        <?= $no++; ?>
                    </td>


                    <!-- PENGGUNA -->

                    <td>

                        <div class="user-log">

                            <div class="avatar-log">

                                <?= strtoupper(
                                    substr(
                                        $namaUser,
                                        0,
                                        1
                                    )
                                ); ?>

                            </div>

                            <span>

                                <?= htmlspecialchars(
                                    $namaUser
                                ); ?>

                            </span>

                        </div>

                    </td>


                    <!-- AKTIVITAS -->

                    <td>

                        <?= htmlspecialchars(
                            $row['aktivitas'] ?? ''
                        ); ?>

                    </td>


                    <!-- WAKTU -->

                    <td>

                        <?= !empty($row['waktu'])
                            ? date(
                                'd-m-Y H:i',
                                strtotime($row['waktu'])
                            )
                            : '-';
                        ?>

                    </td>

                </tr>

            <?php

                endwhile;

            else:

            ?>

                <tr>

                    <td
                        colspan="4"
                        class="empty"
                    >
                        Belum ada aktivitas.
                    </td>

                </tr>

            <?php

            endif;

            ?>

            </tbody>

        </table>

    </div>

</div>


<!-- =========================
     SEARCH
========================= -->

<script>

const searchInput =
    document.getElementById("searchLog");

const table =
    document.getElementById("tableLog");

searchInput.addEventListener(
    "keyup",
    function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            table.querySelectorAll(
                "tbody tr"
            );

        rows.forEach(function (row) {

            const text =
                row.innerText.toLowerCase();

            if (text.includes(keyword)) {

                row.style.display = "";

            } else {

                row.style.display = "none";

            }

        });

    }
);

</script>


</body>

</html>