-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Feb 2024 pada 14.27
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ukk_ilham`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `cover` varchar(250) NOT NULL,
  `id_buku` varchar(10) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `pengarang` varchar(50) NOT NULL,
  `penerbit` varchar(50) NOT NULL,
  `thn_terbit` date NOT NULL,
  `jml_halaman` int(11) NOT NULL,
  `deskripsi` varchar(250) NOT NULL,
  `isi_buku` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`cover`, `id_buku`, `judul`, `kategori`, `pengarang`, `penerbit`, `thn_terbit`, `jml_halaman`, `deskripsi`, `isi_buku`) VALUES
('65c36e9a88fcc.png', 'bis001', 'nunchi', 'bisnis', 'euny hong', 'py gramedia', '2024-02-15', 0, 'rahasia hidup bahagia dan sukses dari korea', 'Nunchi Seni Membaca Pikiran dan Perasaan Orang LainÑRahasia Hidup Bahagia dan Sukses dari Korea (Euny Hong) (z-lib.org).pdf'),
('65cb97b3eef98.jpeg', 'KB0001', 'si juki', 'bisnis', 'faza meonk', 'pt gramedia', '2024-02-13', 146, 'yuk ikuti dan tertawa bersama rekaman perjalanan juki', 'Si Juki Komik Strip (Faza Meonk) (z-lib.org).pdf'),
('65cb98c7e1977.jpeg', 'KB0002', 'berani tidak di sukai', 'informatika', 'ichiro kisimi', 'pt gramedia', '2024-02-13', 350, 'fenomena dari jepang', 'Berani Tidak Disukai (Ichiro Kishimi, Fumitake Koga) (z-lib.org).pdf'),
('65cb99f01ffff.png', 'KB0003', 'rich dad poor dad', 'bisnis', 'Robert T. Kiyosaki', 'pt gramedia', '2024-02-13', 386, 'robert kiyosaki telah mencabar dan mengubah cara berfikir puluhan juta orang di seluruh dunia berkenan wang', 'Rich Dad Poor Dad (Robert T. Kiyosaki) (z-lib.org).pdf'),
('65cb9a891d90d.png', 'KB0004', 'sejarah dunia yang di sembunyikan', 'informatika', 'Jonathan Black', 'pt gramedia', '2024-02-13', 633, 'senilai seluruh perpustakaan dalam satu buku', 'Sejarah Dunia yang Disembunyikan (Jonathan Black) (z-lib.org).pdf'),
('65cb9b0b5333c.png', 'KB0005', 'sebuah seni untuk bersikap bodo amat', 'bisnis', 'mark manson', 'pt gramedia', '2024-02-13', 256, 'pendekatan yang waras dan menjalani hidup yang lain', 'Sebuah Seni untuk Bersikap Bodo Amat (Mark Manson) (z-lib.org).pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_buku`
--

INSERT INTO `kategori_buku` (`kategori`) VALUES
('bisnis'),
('informatika');

-- --------------------------------------------------------

--
-- Struktur dari tabel `member`
--

CREATE TABLE `member` (
  `nisn` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `member`
--

INSERT INTO `member` (`nisn`, `nama`, `password`, `kelas`, `jurusan`, `alamat`) VALUES
('1234', 'ilham', '123', 'X', 'Rekayasa Perangkat Lunak', 'tapos');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(10) NOT NULL,
  `id_buku` varchar(10) NOT NULL,
  `nisn` varchar(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `status` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `sebagai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`, `sebagai`) VALUES
(1, 'iil', 'ilham', '123', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `kategori` (`kategori`);

--
-- Indeks untuk tabel `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`kategori`);

--
-- Indeks untuk tabel `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`nisn`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `nisn` (`nisn`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`kategori`) REFERENCES `kategori_buku` (`kategori`);

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `peminjaman_ibfk_3` FOREIGN KEY (`nisn`) REFERENCES `member` (`nisn`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
