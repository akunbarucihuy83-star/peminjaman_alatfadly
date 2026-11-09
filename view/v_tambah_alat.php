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

include "../model/m_alat.php";
include_once "../model/m_kategori.php";

$kategoriList = (new Kategori())->getAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Alat - Inventaris</title>

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
        }

        .nav-menu a:hover {
            background: rgba(255,255,255,0.12);
            color: white;
        }

        .nav-menu a.active {
            background: rgba(255,255,255,0.20);
            color: white;
        }

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
           FORM
        ========================= */

        .form-container {
            max-width: 800px;

            margin: 40px auto;

            padding: 0 20px;
        }

        .form-box {
            background: white;

            border-radius: 14px;

            padding: 30px;

            box-shadow:
                0 5px 20px rgba(30, 27, 75, 0.10);
        }

        .form-box h1 {
            color: #172554;

            font-size: 28px;

            margin-bottom: 8px;
        }

        .form-box p {
            color: #64748b;

            font-size: 14px;

            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            margin-bottom: 8px;

            color: #172554;

            font-weight: 600;

            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;

            padding: 12px 14px;

            border: 1px solid #c7d2fe;

            border-radius: 8px;

            background: white;

            color: #172554;

            font-size: 14px;

            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #6d28d9;

            box-shadow:
                0 0 0 3px rgba(109,40,217,0.10);
        }

        /* =========================
           BUTTON
        ========================= */

        .form-action {
            display: flex;

            gap: 10px;

            margin-top: 25px;
        }

        .btn-simpan {
            padding: 12px 22px;

            border: none;

            border-radius: 8px;

            background: linear-gradient(
                90deg,
                #172554,
                #4c1d95
            );

            color: white;

            font-weight: 600;

            cursor: pointer;

            font-size: 14px;
        }

        .btn-simpan:hover {
            opacity: 0.9;
        }

        .btn-kembali {
            display: inline-block;

            padding: 12px 22px;

            border-radius: 8px;

            background: #e0e7ff;

            color: #312e81;

            text-decoration: none;

            font-weight: 600;

            font-size: 14px;
        }

        .btn-kembali:hover {
            background: #c7d2fe;
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

            .form-container {
                margin-top: 25px;
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


<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        ✦ <span>Inventaris</span>
    </div>


    <div class="nav-menu">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="v_kategori.php">
            Kategori
        </a>

        <a href="v_alat.php" class="active">
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

            <a href="../controller/c_logout.php" class="logout-link">Logout</a>
    </div>

    <a href="../controller/c_logout.php" class="logout-button" aria-label="Logout">Logout</a>
</nav>


<!-- FORM -->

<div class="form-container">

    <div class="form-box">

        <h1>
            Tambah Alat
        </h1>

        <p>
            Masukkan data alat baru
        </p>


        <form
            action="../controller/c_alat.php"
            method="POST"
        >

            <input
                type="hidden"
                name="aksi"
                value="tambah"
            >


            <div class="form-group">

                <label>
                    Nama Alat
                </label>

                <input
                    type="text"
                    name="nama_alat"
                    placeholder="Masukkan nama alat"
                    required
                >

            </div>


            <div class="form-group">
                <label for="id_kategori">Kategori Alat</label>
                <select name="id_kategori" id="id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($kategori = mysqli_fetch_assoc($kategoriList)): ?>
                        <option value="<?= (int) $kategori['id_kategori']; ?>">
                            <?= htmlspecialchars($kategori['nama_kategori']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">

                <label>
                    Jumlah
                </label>

                <input
                    type="number"
                    name="jumlah"
                    placeholder="Masukkan jumlah"
                    min="1"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Kondisi
                </label>

                <select
                    name="kondisi"
                    required
                >

                    <option value="">
                        -- Pilih Kondisi --
                    </option>

                    <option value="Baik">
                        Baik
                    </option>

                    <option value="Rusak Ringan">
                        Rusak Ringan
                    </option>

                    <option value="Rusak Berat">
                        Rusak Berat
                    </option>

                </select>

            </div>
            <div class="form-action">

                <a
                    href="v_alat.php"
                    class="btn-kembali"
                >
                    Kembali
                </a>

                <button
                    type="submit"
                    class="btn-simpan"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>


</body>

</html>