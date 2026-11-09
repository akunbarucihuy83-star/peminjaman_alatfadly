<?php
require_once __DIR__ . '/../controller/c_peminjam.php';
require_once __DIR__ . '/../model/m_alat.php';

$alat = new Alat();
$dataAlat = $alat->tampil();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alat - Peminjam</title>
    <link rel="stylesheet" href="../asset/dashboard_peminjam.css">
    <style>
        /* ===== HALAMAN ALAT PEMINJAM ===== */
        .content {
            width: 100%;
            max-width: 1250px;
            margin: 0 auto;
            padding: 40px 25px 60px;
        }

        .header-content {
            margin-bottom: 25px;
        }

        .header-content h1 {
            margin: 0 0 7px;
            font-size: 32px;
            line-height: 1.2;
            color: #172554;
            font-weight: 700;
        }

        .header-content p {
            margin: 0;
            color: #6366a3;
            font-size: 15px;
        }

        .table-container {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 8px 25px rgba(30, 27, 75, .10);
            border: 1px solid #e0e7ff;
            overflow-x: auto;
        }

        .table-container table {
            width: 100%;
            min-width: 0;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: auto;
        }

        .table-container th,
        .table-container td {
            padding: 14px 15px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            vertical-align: middle;
            font-size: 14px;
        }

        .table-container th {
            background: #eef2ff;
            color: #172554;
            font-weight: 700;
        }

        .table-container th:first-child { border-radius: 8px 0 0 0; }
        .table-container th:last-child { border-radius: 0 8px 0 0; }

        .table-container td {
            color: #334155;
            background: #fff;
        }

        .table-container tbody tr:hover td {
            background: #f8faff;
        }

        .table-container tbody tr:last-child td {
            border-bottom: 0;
        }

        .status {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .tersedia {
            background: #dcfce7;
            color: #166534;
        }

        .habis {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty {
            text-align: center !important;
            color: #64748b !important;
            padding: 30px !important;
        }

        @media (max-width: 768px) {
            .content {
                padding: 25px 15px 40px;
            }

            .header-content h1 {
                font-size: 26px;
            }

            .header-content p {
                font-size: 13px;
            }

            .table-container {
                padding: 12px;
                border-radius: 12px;
            }

            .table-container table {
                width: 100%;
                table-layout: fixed;
            }

            .table-container th,
            .table-container td {
                padding: 11px 7px;
                font-size: 12px;
                word-break: break-word;
            }

            .table-container th:nth-child(1),
            .table-container td:nth-child(1) { width: 9%; }
            .table-container th:nth-child(2),
            .table-container td:nth-child(2) { width: 29%; }
            .table-container th:nth-child(3),
            .table-container td:nth-child(3) { width: 15%; }
            .table-container th:nth-child(4),
            .table-container td:nth-child(4) { width: 22%; }
            .table-container th:nth-child(5),
            .table-container td:nth-child(5) { width: 25%; }

            .status {
                padding: 5px 7px;
                font-size: 10px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 0 12px !important;
            }

            .logo {
                font-size: 16px !important;
            }

            .nav-menu {
                gap: 2px !important;
            }

            .nav-menu a {
                padding: 8px 7px !important;
                font-size: 11px !important;
            }

            .admin span {
                display: none;
            }

            .avatar {
                width: 32px !important;
                height: 32px !important;
            }

            .content {
                padding: 22px 10px 35px;
            }

            .header-content h1 {
                font-size: 23px;
            }

            .table-container {
                padding: 7px;
            }

            .table-container th,
            .table-container td {
                padding: 9px 4px;
                font-size: 10px;
            }

            .status {
                padding: 4px 5px;
                font-size: 9px;
            }
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="logo"><span>✦</span> Inventaris</div>
    <div class="nav-menu">
        <a href="v_dashboard_peminjam.php">Dashboard</a>
        <a href="v_alat_peminjam.php" class="active">Alat</a>
        <a href="v_peminjaman_peminjam.php">Peminjaman</a>
    </div>
    <div class="admin">
        <div class="avatar"><?= strtoupper(substr($_SESSION['username'] ?? 'P', 0, 1)); ?></div>
        <span><?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username'] ?? 'Peminjam'); ?></span>
    </div>
</nav>

<div class="content">
    <div class="header-content">
        <h1>Daftar Alat</h1>
        <p>Daftar alat yang tersedia untuk dipinjam.</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($dataAlat && mysqli_num_rows($dataAlat) > 0): ?>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($dataAlat)): ?>
                    <?php $jumlah = (int)($row['jumlah'] ?? 0); ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_alat'] ?? '-'); ?></td>
                        <td><?= $jumlah; ?></td>
                        <td><?= htmlspecialchars($row['kondisi'] ?? '-'); ?></td>
                        <td>
                            <?php if ($jumlah > 0): ?>
                                <span class="status tersedia">Tersedia</span>
                            <?php else: ?>
                                <span class="status habis">Tidak Tersedia</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="empty">Belum ada data alat.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
