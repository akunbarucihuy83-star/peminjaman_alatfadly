-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Nov 2026 pada 00.58
-- Versi server: 10.4.6-MariaDB
-- Versi PHP: 8.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_alat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alat`
--

CREATE TABLE `alat` (
  `id_alat` int(11) NOT NULL,
  `kode_alat` varchar(25) DEFAULT NULL,
  `nama_alat` varchar(25) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') DEFAULT 'Baik',
  `lokasi` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `alat`
--

INSERT INTO `alat` (`id_alat`, `kode_alat`, `nama_alat`, `id_kategori`, `jumlah`, `kondisi`, `lokasi`, `keterangan`, `created_at`) VALUES
(1, 'ELK001', 'Laptop', 1, 10, 'Baik', 'Ruang IT', 'Laptop untuk kegiatan', '2026-09-08 02:49:13'),
(2, 'ELK002', 'Proyektor', 10, 5, 'Baik', 'Ruang Multimedia', 'Proyektor LCD', '2026-09-08 02:49:13'),
(3, 'KMR001', 'Kamera DSLR', 2, 4, 'Baik', 'Ruang Multimedia', 'Kamera dokumentasi', '2026-09-08 02:49:13'),
(4, 'PRK001', 'Bor Tangan', 3, 3, 'Baik', 'Gudang', 'Bor listrik', '2026-09-08 02:49:13'),
(5, 'OLA001', 'Bola Basket', 4, 8, 'Baik', 'Gudang Olahraga', 'Bola basket standar', '2026-09-08 02:49:13'),
(6, 'AUD001', 'Speaker', 5, 6, 'Baik', 'Ruang Audio', 'Speaker aktif', '2026-09-08 02:49:13'),
(7, 'KOM001', 'PC Desktop', 6, 7, 'Baik', 'Laboratorium Komputer', 'Komputer desktop', '2026-09-08 02:49:13'),
(8, 'KBR001', 'Vacuum Cleaner', 7, 3, 'Baik', 'Gudang Kebersihan', 'Vacuum cleaner', '2026-09-08 02:49:13'),
(9, 'LAB001', 'Mikroskop', 8, 5, 'Baik', 'Laboratorium', 'Mikroskop digital', '2026-09-08 02:49:13'),
(10, 'OUT001', 'Tenda Camping', 9, 6, 'Rusak Berat', 'Gudang Outdoor', 'Tenda kapasitas 4 orang', '2026-09-08 02:49:13'),
(12, NULL, 'Proyektor', 10, 1, 'Baik', NULL, NULL, '2026-09-23 00:45:30'),
(13, NULL, 'bola voli', 11, 12, 'Baik', NULL, NULL, '2026-10-08 01:14:45'),
(14, NULL, 'raket', 9, 12, 'Baik', NULL, NULL, '2026-10-18 14:00:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_alat`
--

CREATE TABLE `kategori_alat` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kategori_alat`
--

INSERT INTO `kategori_alat` (`id_kategori`, `nama_kategori`, `deskripsi`, `created_at`) VALUES
(1, 'Elektronik', 'Peralatan elektronik', '2026-09-08 02:48:48'),
(2, 'Kamera', 'Peralatan fotografi dan dokumentasi', '2026-09-08 02:48:48'),
(3, 'Perkakas', 'Peralatan untuk pekerjaan dan perbaikan', '2026-09-08 02:48:48'),
(4, 'Alat Olahraga', 'Peralatan untuk kegiatan olahraga', '2026-09-08 02:48:48'),
(5, 'Audio', 'Peralatan audio dan suara', '2026-09-08 02:48:48'),
(6, 'Komputer', 'Peralatan komputer dan jaringan', '2026-09-08 02:48:48'),
(7, 'Alat Kebersihan', 'Peralatan untuk menjaga kebersihan', '2026-09-08 02:48:48'),
(8, 'Alat Laboratorium', 'Peralatan untuk kegiatan laboratorium', '2026-09-08 02:48:48'),
(9, 'Alat Outdoor', 'Peralatan untuk kegiatan luar ruangan', '2026-09-08 02:48:48'),
(10, 'Alat Presentasi', 'Peralatan untuk kegiatan presentasi', '2026-09-08 02:48:48'),
(11, 'bola voli', NULL, '2026-10-07 03:33:00'),
(12, 'alat olahraga outdoor ', NULL, '2026-10-18 14:02:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `waktu`, `ip_address`) VALUES
(1, NULL, 'Login ke sistem', '2026-09-08 02:50:03', '127.0.0.1'),
(2, NULL, 'Menambahkan data alat Laptop', '2026-09-08 02:50:03', '127.0.0.1'),
(3, NULL, 'Menambahkan kategori Elektronik', '2026-09-08 02:50:03', '127.0.0.1'),
(4, NULL, 'Login ke sistem', '2026-09-08 02:50:03', '127.0.0.2'),
(5, NULL, 'Menyetujui peminjaman Proyektor', '2026-09-08 02:50:03', '127.0.0.2'),
(6, 3, 'Mengajukan peminjaman Laptop', '2026-09-08 02:50:03', '127.0.0.3'),
(7, 4, 'Mengajukan peminjaman Proyektor', '2026-09-08 02:50:03', '127.0.0.4'),
(8, 5, 'Mengajukan peminjaman Kamera DSLR', '2026-09-08 02:50:03', '127.0.0.5'),
(9, 6, 'Mengajukan peminjaman Bor Tangan', '2026-09-08 02:50:03', '127.0.0.6'),
(10, 7, 'Mengembalikan Bola Basket', '2026-09-08 02:50:03', '127.0.0.7'),
(11, 3, 'Mengajukan peminjaman alat ID 12 (jumlah 1)', '2026-09-28 03:18:45', '127.0.0.1'),
(12, 3, 'Mengajukan peminjaman alat ID 12 (jumlah 1)', '2026-10-05 04:30:41', '127.0.0.1'),
(13, 3, 'Mengajukan peminjaman alat ID 12 (jumlah 1)', '2026-10-07 01:02:55', '127.0.0.1'),
(14, 3, 'Mengajukan peminjaman alat ID 5 (jumlah 1)', '2026-10-07 01:03:48', '127.0.0.1'),
(15, 3, 'Mengajukan peminjaman alat ID 9 (jumlah 1)', '2026-10-07 01:04:17', '127.0.0.1'),
(16, 3, 'Mengajukan peminjaman alat ID 5 (jumlah 1)', '2026-10-08 01:12:46', '127.0.0.1'),
(17, 3, 'Mengajukan peminjaman alat ID 8 (jumlah 1)', '2026-10-08 01:13:23', '127.0.0.1'),
(18, 3, 'Mengajukan peminjaman alat ID 13 (jumlah 1)', '2026-10-08 01:16:34', '127.0.0.1'),
(19, 3, 'Mengajukan peminjaman alat ID 13 (jumlah 1)', '2026-10-09 02:24:26', '127.0.0.1'),
(20, 3, 'Mengajukan peminjaman alat ID 14 (jumlah 1)', '2026-10-18 14:01:14', '192.168.175.225');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_alat` int(11) NOT NULL,
  `jaminan` varchar(100) NOT NULL DEFAULT 'KTP',
  `jumlah_pinjam` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak','Dipinjam','Dikembalikan') DEFAULT 'Menunggu',
  `keperluan` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `id_alat`, `jaminan`, `jumlah_pinjam`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `keperluan`, `catatan`, `created_at`) VALUES
(1, 3, 1, 'KTP', 1, '2026-09-01', '2026-09-03', 'Dikembalikan', 'Presentasi tugas', 'Dikembalikan dengan baik', '2026-09-08 02:49:40'),
(2, 4, 2, 'KTP', 1, '2026-09-02', '2026-09-04', 'Dikembalikan', 'Presentasi kelas', 'Kondisi baik', '2026-09-08 02:49:40'),
(3, 5, 3, 'KTP', 1, '2026-09-03', '2026-10-07', 'Dikembalikan', 'Dokumentasi acara', 'Masih digunakan', '2026-09-08 02:49:40'),
(4, 6, 4, 'KTP', 1, '2026-09-04', '2026-10-07', 'Dikembalikan', 'Perbaikan fasilitas', 'Menunggu pengambilan', '2026-09-08 02:49:40'),
(5, 7, 5, 'KTP', 2, '2026-09-05', '2026-09-07', 'Dikembalikan', 'Latihan olahraga', 'Sudah dikembalikan', '2026-09-08 02:49:40'),
(6, 8, 6, 'Uang Tunai', 2, '2026-09-06', '2026-10-07', 'Dikembalikan', 'Acara sekolah', 'Digunakan untuk acara', '2026-09-08 02:49:40'),
(7, 9, 7, 'KTP', 1, '2026-09-07', '2026-10-07', 'Dikembalikan', 'Praktikum komputer', 'Menunggu persetujuan', '2026-09-08 02:49:40'),
(8, 10, 8, 'KTP', 1, '2026-09-08', NULL, 'Disetujui', 'Kegiatan kebersihan', 'Menunggu persetujuan', '2026-09-08 02:49:40'),
(9, 3, 9, 'KTP', 2, '2026-09-08', NULL, 'Disetujui', 'Praktikum sains', 'Akan digunakan besok', '2026-09-08 02:49:40'),
(10, 4, 10, 'KTP', 1, '2026-09-08', NULL, 'Ditolak', 'Kegiatan outdoor', 'Stok alat tidak mencukupi', '2026-09-08 02:49:40'),
(11, 3, 12, 'KTP', 1, '2026-09-28', '2026-10-07', 'Dikembalikan', '', '', '2026-09-28 03:18:45'),
(12, 3, 12, 'KTP', 1, '2026-10-05', '2026-10-07', 'Dikembalikan', 'tugas', 'tugas', '2026-10-05 04:30:41'),
(13, 3, 12, 'KTP', 1, '2026-10-07', '2026-10-30', 'Disetujui', 'sekolah', 'kerkom', '2026-10-07 01:02:55'),
(14, 3, 5, 'KTP', 1, '2026-10-07', '2026-10-30', 'Menunggu', 'tugas', 'tugas', '2026-10-07 01:03:48'),
(15, 3, 9, 'KTP', 1, '2026-10-07', '2026-10-07', 'Dikembalikan', 'tugas', 'tugas sekolah', '2026-10-07 01:04:17'),
(16, 3, 5, 'KTP', 1, '2026-10-08', '2026-10-16', 'Menunggu', 'tugas', 'tugas', '2026-10-08 01:12:46'),
(17, 3, 8, 'KTP', 1, '2026-10-09', '2026-10-17', 'Menunggu', 'praktikum', 'tugas', '2026-10-08 01:13:23'),
(18, 3, 13, 'KTP', 1, '2026-10-08', '2026-10-09', 'Menunggu', 'tugas sekolah', 'buat praktek', '2026-10-08 01:16:34'),
(19, 3, 13, 'KTP', 1, '2026-10-09', '2026-10-09', 'Disetujui', 'praktek', 'praktek', '2026-10-09 02:24:26'),
(20, 3, 14, 'KTP', 1, '2026-10-18', '2026-10-19', 'Disetujui', 'olahraga', 'biar sehat', '2026-10-18 14:01:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `role` enum('admin','petugas','peminjam') NOT NULL DEFAULT 'peminjam',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(3, 'Budi Santoso', 'budi', '123', 'peminjam', '2026-09-08 02:47:53'),
(4, 'Andi Pratama', 'andi', 'andi123', 'peminjam', '2026-09-08 02:47:53'),
(5, 'Siti Rahma', 'siti', 'siti123', 'peminjam', '2026-09-08 02:47:53'),
(6, 'Dewi Lestari', 'dewi', '12345', 'peminjam', '2026-09-08 02:47:53'),
(7, 'Rizky Maulana', 'rizky', 'rizky123', 'peminjam', '2026-09-08 02:47:53'),
(8, 'Fajar Nugraha', 'fajar', 'fajar123', 'peminjam', '2026-09-08 02:47:53'),
(9, 'Nabila Putri', 'nabila', 'nabila123', 'peminjam', '2026-09-08 02:47:53'),
(10, 'Yoga Saputra', 'yoga', 'yoga123', 'peminjam', '2026-09-08 02:47:53'),
(14, 'sybau', 'sybau', '123', 'peminjam', '2026-09-18 16:26:17'),
(16, 'Hayolo', 'Hayolo1111', '123', 'peminjam', '2026-09-20 06:36:29'),
(21, 'Alfian', 'alfi', '123', 'peminjam', '2026-10-05 04:40:58');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id_alat`),
  ADD UNIQUE KEY `kode_alat` (`kode_alat`),
  ADD KEY `fk_alat_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `kategori_alat`
--
ALTER TABLE `kategori_alat`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_log_user` (`id_user`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `fk_peminjaman_user` (`id_user`),
  ADD KEY `fk_peminjaman_alat` (`id_alat`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alat`
--
ALTER TABLE `alat`
  MODIFY `id_alat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `kategori_alat`
--
ALTER TABLE `kategori_alat`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD CONSTRAINT `fk_alat_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_alat` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_alat` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id_alat`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
