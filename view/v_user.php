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
$dataUser = $user->tampil();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User - Inventaris</title>

    <link rel="stylesheet" href="../asset/user.css?v=10">

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
        <div class="avatar">A</div>
        <span>Admin</span>
            <a href="../controller/c_logout.php" class="logout-link">Logout</a>
    </div>

    <a href="../controller/c_logout.php" class="logout-button" aria-label="Logout">Logout</a>
</nav>


<main class="container">

    <!-- HEADER -->

    <div class="welcome">

        <div>
            <h1>Data User</h1>

            <p>
                Kelola data pengguna sistem inventaris.
            </p>
        </div>

        <a href="v_tambah.php" class="button">
            + Tambah User
        </a>

    </div>


    <!-- PESAN -->

    <?php if (isset($_GET['pesan'])): ?>

        <?php if ($_GET['pesan'] == 'tambah'): ?>

            <div class="pesan sukses">
                User berhasil ditambahkan.
            </div>

        <?php elseif ($_GET['pesan'] == 'hapus'): ?>

            <div class="pesan sukses">
                User berhasil dihapus.
            </div>

        <?php endif; ?>

    <?php endif; ?>


    <!-- BOX DATA USER -->

    <div class="box">

        <div class="box-title">

            <div>
                <h3>Daftar User</h3>

                <p>
                    Daftar pengguna yang terdaftar dalam sistem.
                </p>
            </div>

            <input
                type="text"
                class="search"
                placeholder="Cari user..."
            >

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>

                </thead>


                <tbody>

                <?php
                $no = 1;

                while ($data = mysqli_fetch_assoc($dataUser)):
                ?>

                    <tr>

                        <td class="nomor">
                            <?= $no++; ?>
                        </td>


                        <td>

                            <div class="user-info">

                                <div class="user-avatar">

                                    <?= strtoupper(
                                        substr($data['nama'], 0, 1)
                                    ); ?>

                                </div>

                                <div class="user-name">

                                    <?= htmlspecialchars(
                                        $data['nama']
                                    ); ?>

                                </div>

                            </div>

                        </td>


                        <td class="username">

                            <?= htmlspecialchars(
                                $data['username']
                            ); ?>

                        </td>


                        <td>

                            <?php if ($data['role'] == 'admin'): ?>

                                <span class="role admin-role">
                                    Admin
                                </span>

                            <?php elseif ($data['role'] == 'petugas'): ?>

                                <span class="role petugas-role">
                                    Petugas
                                </span>

                            <?php else: ?>

                                <span class="role user-role">
                                    Peminjam
                                </span>

                            <?php endif; ?>

                        </td>


                        <td class="tanggal">

                            <?= htmlspecialchars(
                                $data['created_at']
                            ); ?>

                        </td>


                        <td>

                            <div class="actions">

                                <a
                                    href="v_edit.php?id=<?= $data['id_user']; ?>"
                                    class="edit">
                                    Edit
                                </a>

                                <a
                                    href="../controller/c_admin.php?hapus=<?= $data['id_user']; ?>"
                                    class="delete"
                                    onclick="return confirm('Yakin ingin menghapus user ini?');">
                                    Hapus
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</main>

</body>
</html>