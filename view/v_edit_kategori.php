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

$id = $_GET['id'] ?? 0;

$data = $kategori->getById($id);

if (!$data) {
    die("Data kategori tidak ditemukan.");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Kategori - Inventaris</title>

    <link
        rel="stylesheet"
        href="../asset/kategorii.css?v=20261005form"
    >

</head>

<body>

    <div class="container">

        <div class="form-box">

            <h2>Edit Kategori</h2>

            <form
                action="../controller/c_kategori.php"
                method="POST"
            >

                <!-- ID KATEGORI -->
                <input
                    type="hidden"
                    name="id_kategori"
                    value="<?= $data['id_kategori']; ?>"
                >

                <div class="form-group">

                    <label for="nama_kategori">
                        Nama Kategori
                    </label>

                    <input
                        type="text"
                        id="nama_kategori"
                        name="nama_kategori"
                        value="<?= htmlspecialchars($data['nama_kategori']); ?>"
                        placeholder="Masukkan nama kategori"
                        required
                    >

                </div>

                <div class="form-button">

                    <a
                        href="v_kategori.php"
                        class="btn-back"
                    >
                        Kembali
                    </a>

                    <button
                        type="submit"
                        name="edit_kategori"
                        class="btn-save"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>