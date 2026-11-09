-- MIGRASI DATABASE LAMA: peminjaman_alat
-- Jalankan hanya SATU KALI pada database yang sudah memiliki tabel alat dan kategori_alat.
-- Cadangkan database terlebih dahulu. Jangan jalankan peminjaman_alat.sql pada database lama.

ALTER TABLE `alat`
  ADD COLUMN `id_kategori` INT(11) DEFAULT NULL AFTER `nama_alat`;

-- Mengaitkan alat lama ke kategori berdasarkan ID yang terlihat pada database saat ini.
UPDATE `alat` SET `id_kategori` = 1  WHERE `id_alat` = 1;
UPDATE `alat` SET `id_kategori` = 10 WHERE `id_alat` = 2;
UPDATE `alat` SET `id_kategori` = 2  WHERE `id_alat` = 3;
UPDATE `alat` SET `id_kategori` = 3  WHERE `id_alat` = 4;
UPDATE `alat` SET `id_kategori` = 4  WHERE `id_alat` = 5;
UPDATE `alat` SET `id_kategori` = 5  WHERE `id_alat` = 6;
UPDATE `alat` SET `id_kategori` = 6  WHERE `id_alat` = 7;
UPDATE `alat` SET `id_kategori` = 7  WHERE `id_alat` = 8;
UPDATE `alat` SET `id_kategori` = 8  WHERE `id_alat` = 9;
UPDATE `alat` SET `id_kategori` = 9  WHERE `id_alat` = 10;
UPDATE `alat` SET `id_kategori` = 10 WHERE `id_alat` = 12;
UPDATE `alat` SET `id_kategori` = 11 WHERE `id_alat` = 13;

ALTER TABLE `alat`
  ADD KEY `fk_alat_kategori` (`id_kategori`);

ALTER TABLE `alat`
  ADD CONSTRAINT `fk_alat_kategori`
  FOREIGN KEY (`id_kategori`) REFERENCES `kategori_alat` (`id_kategori`)
  ON DELETE SET NULL ON UPDATE CASCADE;
