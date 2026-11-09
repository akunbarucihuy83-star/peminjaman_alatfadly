<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../view/v_login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

/*
|--------------------------------------------------------------------------
| LOGIN TANPA PILIH ROLE
|--------------------------------------------------------------------------
| Sistem otomatis menentukan akses berdasarkan username/password.
*/

/* LOGIN ADMIN */
if ($username === 'admin' && $password === '12345') {
    $_SESSION['login'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = 'admin';

    header("Location: ../view/dashboard.php");
    exit;
}

/* LOGIN PETUGAS */
if ($username === 'petugas' && $password === '12345') {
    $_SESSION['login'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = 'petugas';

    header("Location: ../view/v_dashboard_petugas.php");
    exit;
}

/* LOGIN PEMINJAM DARI DATABASE */
require_once __DIR__ . "/../model/m_koneksi.php";

global $koneksi;
$db = $koneksi->koneksi;

$username_db = mysqli_real_escape_string($db, $username);
$password_db = mysqli_real_escape_string($db, $password);

$query = "
    SELECT id_user, nama, username, password, role
    FROM users
    WHERE username = '$username_db'
      AND password = '$password_db'
    LIMIT 1
";

$hasil = mysqli_query($db, $query);

if ($hasil && mysqli_num_rows($hasil) === 1) {
    $user = mysqli_fetch_assoc($hasil);

    if ($user['role'] === 'peminjam') {
        $_SESSION['login'] = true;
        $_SESSION['id_user'] = (int) $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = 'peminjam';

        header("Location: ../view/v_dashboard_peminjam.php");
        exit;
    }
}

/* LOGIN GAGAL */
echo "<script>
    alert('Username atau password salah!');
    window.location='../view/v_login.php';
</script>";
exit;

?>