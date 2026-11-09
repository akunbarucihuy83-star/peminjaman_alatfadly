PETUNJUK PEMASANGAN - RELASI KATEGORI ALAT

1. ZIP ini adalah codingan terbaru dengan form tambah/edit alat yang memakai kategori.
2. Cadangkan database lama sebelum mengubahnya.
3. Untuk database lama yang sudah berisi data:
   - Pilih database peminjaman_alat di phpMyAdmin.
   - Buka tab SQL.
   - Jalankan isi file migrasi_kategori_alat.sql satu kali.
   - Jangan impor peminjaman_alat.sql ke database lama karena tabel sudah ada.
4. Untuk instalasi database BARU/kosong, impor peminjaman_alat.sql. File utama sudah berisi kolom id_kategori, data kategori tambahan, relasi foreign key, dan pemetaan kategori.
5. Setelah migrasi sukses, ganti folder codingan lama dengan isi ZIP ini (simpan cadangan folder lama).
6. Coba halaman Alat, Tambah Alat, dan Edit Alat.

CATATAN:
- Migrasi mengasumsikan tabel alat belum memiliki kolom id_kategori dan belum memiliki index/foreign key fk_alat_kategori.
- Jangan jalankan migrasi lebih dari satu kali.
- Pemetaan kategori untuk alat lama dibuat dari ID yang terlihat di screenshot database yang dikirim. Periksa kembali bila ID kategori di database kamu berbeda.
- PHP belum diuji langsung pada server HP pengguna; ZIP diperiksa secara lokal dan file PHP dicek sintaksnya.
