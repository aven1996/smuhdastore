-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Okt 2022 pada 13.24
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smuhdastore_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun_login`
--

CREATE TABLE `akun_login` (
  `id_login` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `stt_akun` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `akun_login`
--

INSERT INTO `akun_login` (`id_login`, `username`, `password`, `stt_akun`, `email`) VALUES
(6, 'admin', '$2y$10$zGCDiL6CluaIqfUBcQB67.5nci38m.e8FZcaJd0npF/MGujMkcexi', 'owner', 'admin@smkmuh2klaten.sch.id'),
(9, 'afenafrianto', '$2y$10$j/TKZVTI5ApWxCG6HDDSaOwj4w4QtSkhubD/0cAgBGiHuGDLZD6DS', 'pembeli', 'afenafrianto96@gmail.com'),
(10, 'afrianto96', '$2y$10$60Q0EMAWm6W/0lx4EuKlOOySSttm25JUqfG2dPZ6tXSirLQOkUMb.', 'penjual', 'afrianto96@gmail.com'),
(11, 'unknown', '$2y$10$QWupfHemhMo1ISl6cBpWkOTkzpmkPgOShZRKddFJ.IIuXmJ7VPiIC', 'pembeli', 'unknown@gmail.com'),
(12, 'mahmud', '$2y$10$vqYsO/S9hVgN1RW6KhL47ud1sQR71pHOY/f2Jd7fT/GbkltjEF9re', 'pembeli', 'mahmud@gmail.com'),
(14, 'hamidi', '$2y$10$aFi/qppgNi4EMfbJF05VfOZNXPFZDrVBRFAS501jCzh3j7w4jTHB.', 'penjual', 'hamidi@gmail.com'),
(15, 'Afen', '$2y$10$oG4Eo8tnsy9WnUpwBi5JTuO1WIQtkAsbtatRUl/c5uL.tnByexQEC', 'pembeli', 'avenimation@gmail.com'),
(16, 'Afen61300', '$2y$10$ggJsRB0WOjcPQTs/LhXkme4sBnVWd426pjS1F28Y4IHSZid0qa./e', 'pembeli', 'afen@smkmuh2klaten.sch.id'),
(17, 'Sontoloyo', '$2y$10$aEk1RRdW3QwMTPzDH6Fh1eZ0RdZl7xy6AKFKDHB9phr0yPUhNq4EO', 'pembeli', 'sontoloyo@gmail.com'),
(18, 'Leonel', '$2y$10$Cbg2UMytXiN3SClwMOB2Reb5RNYDyzLy1O3HlSqMYS0vwtKhvemXC', 'penjual', 'leo@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `favorit`
--

CREATE TABLE `favorit` (
  `id_favorit` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `favorit`
--

INSERT INTO `favorit` (`id_favorit`, `id_login`, `id_produk`) VALUES
(9, 12, 10),
(11, 9, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `gambar_produk`
--

CREATE TABLE `gambar_produk` (
  `id_img` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `gambar_produk`
--

INSERT INTO `gambar_produk` (`id_img`, `id_produk`, `gambar`) VALUES
(9, 10, 'peralatan-canggih-dan-animator-profesional-di-balik-sukses-film-animasi-kiko-QTG4nDIoLA_4455.jpg'),
(10, 10, '1_9833.jpg'),
(11, 10, 'webdesign-banner.png'),
(14, 12, 'Es_teh_gelas_jumbo.jpg'),
(15, 12, 'Peluang-Bisnis-Sosis-dan-Analisa-Usahanya.jpg'),
(21, 15, 'IMG_26032021_162516__822_x_430_piksel_.jpg'),
(22, 15, 'image_20200918-1052-mm2g1k.png'),
(23, 16, '37baf046-casio252520edifice25252025253a252520efr-552l.png'),
(24, 17, 'Konektor-RJ45-Cat5E-Fiberhome-458x458.jpg'),
(25, 17, 'belden.1634524942.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `harga_keranjang` int(11) NOT NULL,
  `harga_akhir` int(11) NOT NULL,
  `jumlah_pembelian` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_pembeli`, `id_produk`, `harga_keranjang`, `harga_akhir`, `jumlah_pembelian`) VALUES
(16, 12, 10, 1500000, 1500000, 1),
(18, 12, 10, 1000000, 1000000, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kode_verifikasi`
--

CREATE TABLE `kode_verifikasi` (
  `id_kode_verif` int(11) NOT NULL,
  `kode_verif` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kode_verifikasi`
--

INSERT INTO `kode_verifikasi` (`id_kode_verif`, `kode_verif`) VALUES
(1, '20309695');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kritik_saran`
--

CREATE TABLE `kritik_saran` (
  `id_krisar` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `krisar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kritik_saran`
--

INSERT INTO `kritik_saran` (`id_krisar`, `nama`, `email`, `krisar`) VALUES
(2, 'Apriyanto', 'apriyanto@gmail.com', 'alangkah lebih bagus jika bisa melakukan kirim-kirim luar kota'),
(4, 'Sulis', 'sulis@gmail.com', 'aplikasi yang sangat keren, tapi masih sedikit produknya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lapor_produk`
--

CREATE TABLE `lapor_produk` (
  `id_lapor` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_penjual` int(11) NOT NULL,
  `alasan` varchar(100) NOT NULL,
  `alasan_tambahan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `lapor_produk`
--

INSERT INTO `lapor_produk` (`id_lapor`, `id_produk`, `id_penjual`, `alasan`, `alasan_tambahan`) VALUES
(5, 10, 10, 'Daftar penipuan', 'produk ini teridentifikasi penipuan karena meminta transfer uang dulu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lastseen`
--

CREATE TABLE `lastseen` (
  `id_seen` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `lastseen`
--

INSERT INTO `lastseen` (`id_seen`, `id_login`, `id_produk`) VALUES
(3, 9, 10),
(6, 6, 12),
(7, 6, 10),
(8, 10, 10),
(11, 10, 12),
(12, 12, 10),
(13, 12, 12),
(15, 14, 15),
(16, 14, 10),
(17, 14, 16),
(18, 9, 15);

-- --------------------------------------------------------

--
-- Struktur dari tabel `lastseen_transaksi`
--

CREATE TABLE `lastseen_transaksi` (
  `id_lastseen` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `stt_transaksi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `lastseen_transaksi`
--

INSERT INTO `lastseen_transaksi` (`id_lastseen`, `id_login`, `id_transaksi`, `stt_transaksi`) VALUES
(32, 11, 20, 'selesai'),
(37, 11, 28, 'proses'),
(38, 11, 28, 'selesai'),
(42, 11, 21, 'selesai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `deskripsi_produk` varchar(255) NOT NULL,
  `kategori_produk` varchar(50) NOT NULL,
  `kondisi_produk` varchar(10) NOT NULL,
  `harga_produk` int(11) NOT NULL,
  `stok_produk` int(11) NOT NULL,
  `unixkey` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `id_login`, `nama_produk`, `deskripsi_produk`, `kategori_produk`, `kondisi_produk`, `harga_produk`, `stok_produk`, `unixkey`) VALUES
(10, 10, 'Alat gambar dan laptop gahar', 'kereen abiss', 'Elektronik', 'Bekas', 15000000, 5, 571906),
(12, 10, 'Sosis Bakar Maknyus', 'Sosis bakar murah banget', 'Makanan &amp; Minuman', 'Baru', 10000, 100, 897697),
(15, 14, 'Koffee Bikla Original Rempah dan Jantan', 'Koffee Bikla Original Rempah dan Jantan', 'Makanan &amp; Minuman', 'Baru', 10000, 100, 904736),
(16, 14, 'Jam Tangan Cowok Masa Kini', 'Jam Tangan Cowok Masa Kini Branded Export', 'Aksesoris', 'Baru', 525000, 10, 653830),
(17, 14, 'Kabel Jaringan Merk Belden', 'Kabel dengan kualitas terbaik\r\n1 box + konektor RJ45', 'Elektronik', 'Baru', 1250000, 30, 351783);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_diskon`
--

CREATE TABLE `produk_diskon` (
  `id_diskon` int(11) NOT NULL,
  `id_penjual` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `harga_produk_diskon` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk_diskon`
--

INSERT INTO `produk_diskon` (`id_diskon`, `id_penjual`, `id_produk`, `diskon`, `harga_produk_diskon`) VALUES
(1, 10, 10, 90, 1500000),
(8, 10, 12, 80, 2000),
(10, 14, 16, 40, 315000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_jualcepat`
--

CREATE TABLE `produk_jualcepat` (
  `id_jualcepat` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_penjual` int(11) NOT NULL,
  `mulai` date NOT NULL,
  `berakhir` date NOT NULL,
  `harga_baru` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk_jualcepat`
--

INSERT INTO `produk_jualcepat` (`id_jualcepat`, `id_produk`, `id_penjual`, `mulai`, `berakhir`, `harga_baru`) VALUES
(9, 10, 10, '2021-05-04', '2021-05-04', 1000000),
(10, 16, 14, '2021-05-29', '2021-05-29', 250000),
(11, 15, 14, '2022-05-01', '2022-05-03', 7000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_transaksi`
--

CREATE TABLE `produk_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_penjual` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `nama_pembeli` varchar(50) NOT NULL,
  `alamat_pembeli` varchar(255) NOT NULL,
  `hp_pembeli` varchar(15) NOT NULL,
  `tanggal` date NOT NULL,
  `harga_peritem` int(11) NOT NULL,
  `jml_pembelian` int(11) NOT NULL,
  `stt_transaksi` varchar(20) NOT NULL,
  `penilaian` int(11) NOT NULL,
  `alasan_pembatalan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk_transaksi`
--

INSERT INTO `produk_transaksi` (`id_transaksi`, `id_produk`, `id_penjual`, `id_pembeli`, `nama_pembeli`, `alamat_pembeli`, `hp_pembeli`, `tanggal`, `harga_peritem`, `jml_pembelian`, `stt_transaksi`, `penilaian`, `alasan_pembatalan`) VALUES
(1, 10, 10, 9, 'afen cakep', 'Manjungan ngawen klaten ', '0', '2021-03-19', 20000, 1, 'selesai', 3, ''),
(6, 10, 10, 9, 'Joko', 'Manjungan, Ngawen, Klaten', '0', '2021-03-26', 12000000, 1, 'batal', 0, 'mohon maaf karena anda kurang dipercaya kami batalkan pesanan ini'),
(7, 10, 10, 9, 'Toyib', 'Krangkungan, Manjungan, Ngawen, Klaten', '0', '2021-04-01', 1230000, 2, 'selesai', 3, ''),
(18, 12, 10, 9, 'afenafrianto', 'Kwaren, Ngawen, Klaten, KAB. KLATEN, PROV. JAWA TENGAH', '0', '2021-04-03', 5000, 3, 'selesai', 3, ''),
(19, 10, 10, 9, 'afenafrianto', 'Kwaren, Ngawen, Klaten, KAB. KLATEN, PROV. JAWA TENGAH', '0', '2021-04-03', 1500000, 1, 'selesai', 5, ''),
(20, 10, 10, 11, 'Dimas abib', 'Manjungan Ngawen Klaten', '0', '2021-04-04', 15000000, 1, 'selesai', 0, ''),
(21, 10, 10, 11, 'Dimas', 'Mayungan, Ngawen, Klaten', '0', '2021-04-04', 1500000, 1, 'selesai', 0, ''),
(23, 12, 10, 9, 'afenafrianto', 'Kwaren, Ngawen, Klaten, KAB. KLATEN, PROV. JAWA TENGAH', '085678765234', '2021-04-05', 10000, 2, 'pesan', 0, ''),
(24, 10, 10, 9, 'afenafrianto', 'Kwaren, Ngawen, Klaten, KAB. KLATEN, PROV. JAWA TENGAH', '085678765234', '2021-04-05', 1500000, 1, 'selesai', 0, ''),
(26, 10, 10, 11, 'Sutrisno', 'Senden Ngebong, Danguran, Klaten Selatan, Klaten', '6281123409872', '2021-04-30', 1500000, 1, 'pesan', 0, ''),
(27, 10, 10, 12, 'mahmud', 'Desa Basin, Kec. Kebonarum, KAB. KLATEN, PROV. JAWA TENGAH', '08765876636', '2021-05-04', 1000000, 1, 'selesai', 5, ''),
(28, 10, 10, 11, 'Tukiyem', 'Desa Pepe, Kecamatan Ngawen, Kabupaten Klaten', '087654287987', '2021-05-04', 1000000, 1, 'selesai', 0, ''),
(29, 10, 10, 9, 'afenafrianto', 'Kwaren, Ngawen, Klaten, KAB. KLATEN, PROV. JAWA TENGAH', '085678765234', '2021-05-04', 1000000, 1, 'selesai', 0, ''),
(30, 10, 10, 12, 'mahmud', 'Desa Basin, Kec. Kebonarum, KAB. KLATEN, PROV. JAWA TENGAH', '08765876636', '2021-05-04', 1000000, 1, 'selesai', 4, ''),
(31, 10, 10, 12, 'mahmud', 'Desa Basin, Kec. Kebonarum, KAB. KLATEN, PROV. JAWA TENGAH', '08765876636', '2021-05-04', 1000000, 1, 'selesai', 5, ''),
(32, 12, 10, 12, 'mahmud', 'Desa Basin, Kec. Kebonarum, KAB. KLATEN, PROV. JAWA TENGAH', '08765876636', '2021-05-04', 2000, 1, 'pesan', 0, ''),
(35, 10, 10, 9, 'afenafrianto', 'Kwaren, Ngawen, Klaten, KAB. KLATEN, PROV. JAWA TENGAH', '085678765234', '2022-05-01', 1500000, 2, 'pesan', 0, ''),
(36, 15, 14, 9, 'afenafrianto', 'Kwaren, Ngawen, Klaten, KAB. KLATEN, PROV. JAWA TENGAH', '085678765234', '2022-05-01', 10000, 1, 'pesan', 0, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil`
--

CREATE TABLE `profil` (
  `id_profil` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `nama_profil` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `hp` varchar(15) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `foto_profil` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `profil`
--

INSERT INTO `profil` (`id_profil`, `id_login`, `nama_profil`, `jenis_kelamin`, `hp`, `alamat`, `foto_profil`) VALUES
(1, 9, 'Afrianto', 'Laki-laki', '085678765234', 'Kwaren, Ngawen, Klaten, KAB. KLATEN, PROV. JAWA TENGAH', '11 Dimas Abib.JPG'),
(2, 6, 'Nanik Hidayati', 'Perempuan', '081234987970', 'Jl. Mayor Kusmanto,  Setrang,  Gergunung,  Klaten Utara, Kabupaten Klaten, Jawa Tengah', '31 nanik.jpg'),
(3, 10, 'Afen Afrianto', 'Laki-laki', '6281392728326', 'Dk,  Manjungan,  Ds. Manjungan, Kabupaten Klaten, Jawa Tengah', 'Screenshot_1.jpg'),
(4, 11, '', '', '', '', ''),
(5, 12, 'Mahmud Mudzakir', 'Laki-laki', '08765876636', 'Desa Basin,  Kec. Kebonarum, Kota Bandung, Jawa Barat', '54 Mahmud Mudzakir.jpg'),
(7, 14, 'Hajid Hamidi', 'Laki-laki', '08765876636', 'Sleman,   DIY, Kabupaten Sleman, DI Yogyakarta', 'IMG_9355.JPG'),
(8, 15, 'Afen Afrianto', 'Laki-laki', '', '', ''),
(9, 16, 'Afen Afrianto', 'Laki-laki', '', '', ''),
(10, 17, '', '', '', '', ''),
(11, 18, '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akun_login`
--
ALTER TABLE `akun_login`
  ADD PRIMARY KEY (`id_login`);

--
-- Indeks untuk tabel `favorit`
--
ALTER TABLE `favorit`
  ADD PRIMARY KEY (`id_favorit`),
  ADD KEY `id_login` (`id_login`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `gambar_produk`
--
ALTER TABLE `gambar_produk`
  ADD PRIMARY KEY (`id_img`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_pembeli` (`id_pembeli`);

--
-- Indeks untuk tabel `kode_verifikasi`
--
ALTER TABLE `kode_verifikasi`
  ADD PRIMARY KEY (`id_kode_verif`);

--
-- Indeks untuk tabel `kritik_saran`
--
ALTER TABLE `kritik_saran`
  ADD PRIMARY KEY (`id_krisar`);

--
-- Indeks untuk tabel `lapor_produk`
--
ALTER TABLE `lapor_produk`
  ADD PRIMARY KEY (`id_lapor`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_penjual` (`id_penjual`);

--
-- Indeks untuk tabel `lastseen`
--
ALTER TABLE `lastseen`
  ADD PRIMARY KEY (`id_seen`),
  ADD KEY `id_login` (`id_login`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `lastseen_transaksi`
--
ALTER TABLE `lastseen_transaksi`
  ADD PRIMARY KEY (`id_lastseen`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_login` (`id_login`) USING BTREE;

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_login` (`id_login`);

--
-- Indeks untuk tabel `produk_diskon`
--
ALTER TABLE `produk_diskon`
  ADD PRIMARY KEY (`id_diskon`),
  ADD KEY `id_penjual` (`id_penjual`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `produk_jualcepat`
--
ALTER TABLE `produk_jualcepat`
  ADD PRIMARY KEY (`id_jualcepat`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_penjual` (`id_penjual`);

--
-- Indeks untuk tabel `produk_transaksi`
--
ALTER TABLE `produk_transaksi`
  ADD PRIMARY KEY (`id_transaksi`) USING BTREE,
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_penjual` (`id_penjual`),
  ADD KEY `id_pembeli` (`id_pembeli`);

--
-- Indeks untuk tabel `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`),
  ADD KEY `id_akun_login` (`id_login`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akun_login`
--
ALTER TABLE `akun_login`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `favorit`
--
ALTER TABLE `favorit`
  MODIFY `id_favorit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `gambar_produk`
--
ALTER TABLE `gambar_produk`
  MODIFY `id_img` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `kode_verifikasi`
--
ALTER TABLE `kode_verifikasi`
  MODIFY `id_kode_verif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kritik_saran`
--
ALTER TABLE `kritik_saran`
  MODIFY `id_krisar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `lapor_produk`
--
ALTER TABLE `lapor_produk`
  MODIFY `id_lapor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `lastseen`
--
ALTER TABLE `lastseen`
  MODIFY `id_seen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `lastseen_transaksi`
--
ALTER TABLE `lastseen_transaksi`
  MODIFY `id_lastseen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `produk_diskon`
--
ALTER TABLE `produk_diskon`
  MODIFY `id_diskon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `produk_jualcepat`
--
ALTER TABLE `produk_jualcepat`
  MODIFY `id_jualcepat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `produk_transaksi`
--
ALTER TABLE `produk_transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `favorit`
--
ALTER TABLE `favorit`
  ADD CONSTRAINT `favorit_ibfk_1` FOREIGN KEY (`id_login`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorit_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `gambar_produk`
--
ALTER TABLE `gambar_produk`
  ADD CONSTRAINT `gambar_produk_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_3` FOREIGN KEY (`id_pembeli`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lapor_produk`
--
ALTER TABLE `lapor_produk`
  ADD CONSTRAINT `lapor_produk_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lapor_produk_ibfk_2` FOREIGN KEY (`id_penjual`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lastseen`
--
ALTER TABLE `lastseen`
  ADD CONSTRAINT `lastseen_ibfk_1` FOREIGN KEY (`id_login`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lastseen_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lastseen_transaksi`
--
ALTER TABLE `lastseen_transaksi`
  ADD CONSTRAINT `lastseen_transaksi_ibfk_1` FOREIGN KEY (`id_login`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lastseen_transaksi_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `produk_transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_login`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk_diskon`
--
ALTER TABLE `produk_diskon`
  ADD CONSTRAINT `produk_diskon_ibfk_1` FOREIGN KEY (`id_penjual`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_diskon_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk_jualcepat`
--
ALTER TABLE `produk_jualcepat`
  ADD CONSTRAINT `produk_jualcepat_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_jualcepat_ibfk_2` FOREIGN KEY (`id_penjual`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk_transaksi`
--
ALTER TABLE `produk_transaksi`
  ADD CONSTRAINT `produk_transaksi_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_transaksi_ibfk_2` FOREIGN KEY (`id_penjual`) REFERENCES `akun_login` (`id_login`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_transaksi_ibfk_3` FOREIGN KEY (`id_pembeli`) REFERENCES `akun_login` (`id_login`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `profil`
--
ALTER TABLE `profil`
  ADD CONSTRAINT `profil_ibfk_1` FOREIGN KEY (`id_login`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
