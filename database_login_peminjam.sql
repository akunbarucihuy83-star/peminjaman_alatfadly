-- Jalankan file ini setelah database peminjaman_alat di-import.
-- Akun peminjam demo: username peminjam, password 123
USE peminjaman_alat;

INSERT INTO users (nama, username, password, role)
SELECT 'Peminjam', 'peminjam', '123', 'peminjam'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'peminjam');

-- Akun yang sudah ada di database kamu juga tetap bisa digunakan,
-- misalnya: budi / 123, andi / andi123, siti / siti123.
