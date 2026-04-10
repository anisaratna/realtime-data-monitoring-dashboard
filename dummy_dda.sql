-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Des 2025 pada 03.51
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dummy_dda`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `forumcomment`
--

CREATE TABLE `forumcomment` (
  `id` int(10) NOT NULL,
  `id_topik` int(5) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `tgl_comment` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_forumtopic`
--

CREATE TABLE `m_forumtopic` (
  `id_topik` int(5) NOT NULL,
  `id_unitkerja` varchar(20) NOT NULL,
  `nama_topik` varchar(500) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `tgl_buat` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_kondef`
--

CREATE TABLE `m_kondef` (
  `id` int(11) NOT NULL,
  `id_tabel` int(11) NOT NULL,
  `kondef` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `m_kondef`
--

INSERT INTO `m_kondef` (`id`, `id_tabel`, `kondef`) VALUES
(1, 10, 'kondef biropem');

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_master_tabel_usulan`
--

CREATE TABLE `m_master_tabel_usulan` (
  `id` int(11) NOT NULL,
  `judul_ind` varchar(200) NOT NULL,
  `judul_en` varchar(200) NOT NULL,
  `file_tabel` varchar(50) NOT NULL,
  `is_setujui` int(11) NOT NULL,
  `id_unitkerja` varchar(20) NOT NULL,
  `addby` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `m_master_tabel_usulan`
--

INSERT INTO `m_master_tabel_usulan` (`id`, `judul_ind`, `judul_en`, `file_tabel`, `is_setujui`, `id_unitkerja`, `addby`) VALUES
(3, 'JUDUL 1         \r\n', 'TITLE 1\r\n', '256_kemenag.xlsx', 1, 'kemenag', 'kemenag'),
(4, 'JUDUL 2', 'TITLE 2', '725_ptun.xlsx', 0, 'ptun', 'ptun'),
(11, 'JUDUL 3', 'TITLE 3', '58_dinpendidikan.xlsx', 0, 'dinpendidikan', 'dinpendidikan'),
(26, 'JUDUL 4', 'TITLE 4', '426_dkp.xlsx', 1, 'dkp', 'dkp');

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_metadata`
--

CREATE TABLE `m_metadata` (
  `id_metadata` int(7) NOT NULL,
  `konsep_definisi` varchar(200) NOT NULL,
  `rumusan` varchar(200) NOT NULL,
  `kegunaan` varchar(100) NOT NULL,
  `dihasilkan_dari` varchar(50) NOT NULL,
  `frekuensi` varchar(15) NOT NULL,
  `pengumpulan_data` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_unitkerja`
--

CREATE TABLE `m_unitkerja` (
  `id_unitkerja` varchar(20) NOT NULL,
  `unitkerja_ind` varchar(150) NOT NULL,
  `unitkerja_en` varchar(150) NOT NULL,
  `user_wali` varchar(15) NOT NULL,
  `user_spv` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `m_unitkerja`
--

INSERT INTO `m_unitkerja` (`id_unitkerja`, `unitkerja_ind`, `unitkerja_en`, `user_wali`, `user_spv`) VALUES
('biropem', 'Biro Pemerintahan, Otonomi Daerah dan Kerjasama Provinsi Jawa Tengah', 'Biro Pemerintahan, Otonomi Daerah dan Kerjasama Provinsi Jawa Tengah', 'tim6', 'herpas'),
('bkd', 'Badan Kepegawaian Daerah Provinsi Jawa Tengah', 'Badan Kepegawaian Daerah Provinsi Jawa Tengah', 'tim6', 'herpas'),
('bkkbn', 'BKKBN Provinsi Jawa Tengah', 'BKKBN Provinsi Jawa Tengah', 'tim2', 'puguh.raharjo'),
('bkn', 'BKN Kanreg I Yogyakarta', 'BKN Kanreg I Yogyakarta', 'tim6', 'medha'),
('bpjs', 'BPJS Kesehatan Divisi Regional VI Jawa Tengah', 'BPJS Kesehatan Divisi Regional VI Jawa Tengah', 'tim2', 'herpas'),
('bpn', 'Kanwil Badan Pertanahan Nasioanal  Jawa Tengah', 'Kanwil Badan Pertanahan Nasioanal  Jawa Tengah', 'tim6', 'puguh.raharjo'),
('bpom', 'Balai POM Semarang', 'Balai POM Semarang', 'tim2', 'herpas'),
('bulog', 'Perum Bulog Divre Jawa Tengah', 'Perum Bulog Divre Jawa Tengah', 'tim3', 'puguh.raharjo'),
('dinkes', 'Dinas Kesehatan Provinsi Jawa Tengah', 'Dinas Kesehatan Provinsi Jawa Tengah', 'tim2', 'herpas'),
('dinpendidikan', 'Dinas Pendidikan dan Kebudayaan Provinsi Jawa Tengah', 'Dinas Pendidikan dan Kebudayaan Provinsi Jawa Tengah', 'tim2', 'medha'),
('dinsos', 'Dinas Sosial  Provinsi Jawa Tengah', 'Dinas Sosial  Provinsi Jawa Tengah', 'tim2', 'puguh.raharjo'),
('disdukcapil', 'Dinas Pemberdayaan Masyarakat, Desa, Kependudukan dan Catatan Sipil  Jateng', 'Dinas Pemberdayaan Masyarakat, Desa, Kependudukan dan Catatan Sipil  Jateng', 'tim6', 'herpas'),
('esdm', 'Dinas ESDM Provinsi Jawa Tengah', 'Dinas ESDM Provinsi Jawa Tengah', 'tim4', 'herpas'),
('kejaksaan', 'Kejaksaan Tinggi Jawa Tengah', 'Kejaksaan Tinggi Jawa Tengah', 'tim2', 'puguh.raharjo'),
('kemenag', 'Kanwil Kementerian Agama Provinsi Jawa Tengah', 'Ministry of Religious Affairs of Central Java Province', 'tim2', 'puguh.raharjo'),
('kemenkumham', 'Kanwil Kementrian Hukum dan  HAM Jawa Tengah', 'Kanwil Kementrian Hukum dan  HAM Jawa Tengah', 'tim1', 'medha'),
('lingkunganhidup', 'Dinas Lingkungan Hidup dan Kehutanan  Jawa Tengah', 'Dinas Lingkungan Hidup dan Kehutanan  Jawa Tengah', 'tim3', 'puguh.raharjo'),
('lldikti', 'Lembaga Layanan Pendidikan Tinggi Wilayah VI Semarang', 'Higher Education Service Institution Region VI Semarang', 'tim2', 'puguh.raharjo'),
('ojk', 'Otoritas Jasa Keuangan', 'Otoritas Jasa Keuangan', 'tim5', 'medha'),
('pemudaor', 'Dinas Kepemudaan,Olahraga dan Pariwisata Jawa Tengah', 'Dinas Kepemudaan,Olahraga dan Pariwisata Jawa Tengah', 'tim5', 'herpas'),
('pengadilanagama', 'Pengadilan Tinggi Agama Jawa Tengah', 'Pengadilan Tinggi Agama Jawa Tengah', 'tim2', 'puguh.raharjo'),
('pengtinggi', 'Pengadilan Tinggi Provinsi Jawa Tengah', 'Pengadilan Tinggi Provinsi Jawa Tengah', 'tim2', 'herpas'),
('pertamina', 'PERTAMINA unit Pemasaran IV Semarang', 'PERTAMINA unit Pemasaran IV Semarang', 'tim4', 'puguh.raharjo'),
('pertanian', 'Dinas Pertanian dan Perkebunan Provinsi Jawa Tengah', 'Dinas Pertanian dan Perkebunan Provinsi Jawa Tengah', 'tim3', 'medha'),
('pln', 'PT. PLN Distribusi Jawa Tengah', 'PT. PLN Distribusi Jawa Tengah', 'tim4', 'puguh.raharjo'),
('polda', 'Polda Jawa Tengah', 'Polda Jawa Tengah', 'tim2', 'medha'),
('ptpn_ix', 'PTP Nusantara I Regional 3', 'PTP Nusantara I Regional 3', 'tim3', 'medha'),
('ptun', 'Pengadilan Tata usaha Negara ( PTUN ) Semarang', 'Pengadilan Tata usaha Negara ( PTUN ) Semarang', 'tim2', 'puguh.raharjo'),
('sekdprd', 'Sekretariat DPRD Provinsi Jawa Tengah', 'Sekretariat DPRD Provinsi Jawa Tengah', 'tim6', 'herpas'),
('taspen', 'PT. Taspen (Persero) Cabang Utama Semarang', 'PT. Taspen (Persero) Cabang Utama Semarang', 'tim6', 'puguh.raharjo'),
('ternaksehat', 'Dinas Peternakan dan Kesehatan Provinsi Jawa tengah', 'Dinas Peternakan dan Kesehatan Provinsi Jawa tengah', 'tim3', 'puguh.raharjo');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_instansi`
--

CREATE TABLE `tr_instansi` (
  `id` int(1) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `kepsek` varchar(100) NOT NULL,
  `nip_kepsek` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tr_instansi`
--

INSERT INTO `tr_instansi` (`id`, `nama`, `alamat`, `kepsek`, `nip_kepsek`, `logo`) VALUES
(1, 'BPS Provinsi Jawa Tengah', 'Jl. Pahlawan No. 6 Semarang', 'Endang Tri Wahyuningsih', '-', 'logo2.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_admin`
--

CREATE TABLE `t_admin` (
  `id` int(2) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(75) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(25) NOT NULL,
  `level` enum('Super Admin','Admin','spv','lo','walidata','kominfo') NOT NULL,
  `id_unitkerja` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `t_admin`
--

INSERT INTO `t_admin` (`id`, `username`, `password`, `nama`, `nip`, `level`, `id_unitkerja`, `email`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', '19900145 845634 2 005', 'Admin', 'bps', 'ratnannisa04@gmail.com'),
(10, 'diseminasi', 'b97b56f8f1bc963166237237227610c6', 'Seksi Diseminasi dan Layanan Statistik', '1960812441676', 'Admin', 'bps', 'arsalsabilla03@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_list_tabel`
--

CREATE TABLE `t_list_tabel` (
  `id` int(11) NOT NULL,
  `judul_ind` varchar(200) NOT NULL,
  `judul_en` varchar(200) NOT NULL,
  `link_tabel` varchar(200) NOT NULL,
  `link_sebelumnya` varchar(200) NOT NULL,
  `id_unitkerja` varchar(15) NOT NULL,
  `is_confirm` varchar(50) NOT NULL,
  `catatan` varchar(150) NOT NULL,
  `is_periksa` int(1) NOT NULL,
  `catatan_periksa` varchar(300) NOT NULL,
  `kondef` longtext NOT NULL,
  `tahun` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `t_list_tabel`
--

INSERT INTO `t_list_tabel` (`id`, `judul_ind`, `judul_en`, `link_tabel`, `link_sebelumnya`, `id_unitkerja`, `is_confirm`, `catatan`, `is_periksa`, `catatan_periksa`, `kondef`, `tahun`) VALUES
(5, 'Judul 1', 'Title 1', 'https://docs.google.com/spreadsheets/d/115CYeXZLx5o', 'https://docs.google.com/spreadsheets/d/115CYeXZLx5o', 'bpn', '2', '', 0, '-', '-Banyaknya PPAT di Kota Pekalongan :    \r\n', '2025'),
(10, 'Judul 2', 'Title 2', 'https://docs.google.com/spreadsheets/d/1pRVBzQrRSJs', 'https://docs.google.com/spreadsheets/d/1pRVBzQrRSJs', 'biropem', '1', 'Data sudah lengkap dan OK', 0, '-', 'Keputusan Mendagri tentang Pemberian dan pemutakhiran', '2025'),
(11, 'Judul 3', 'Title 3', 'https://docs.google.com/spreadsheets/d/10INiMNE', 'https://docs.google.com/spreadsheets/d/10INiMN', 'sekdprd', '1', '', 1, '-', '-Data yang diisi adalah data per Januari ', '2025'),
(18, 'Judul 4', 'Title 4', 'https://docs.google.com/spreadsheets/d/1a9SQFxcGg4j', 'https://docs.google.com/spreadsheets/d/1a9SQFxcGg4j6r', 'bkd', '1', 'Data Lengkap dan OK ', 0, '-', '-', '2025'),
(19, 'Judul 5', 'Title 5', 'https://docs.google.com/spreadsheets/d/17Blss-AId0F3', 'https://docs.google.com/spreadsheets/d/17Blss-A', 'bkn', '1', '', 0, '-', '-', '2025'),
(21, 'Judul 6', 'Title 6', 'https://docs.google.com/spreadsheets/d/17PI5Ce3d4eydK2', 'https://docs.google.com/spreadsheets/d/17PI5Ce3d4e', 'taspen', '2', '', 0, '-', 'Merupakan Pegawai Negeri Sipil (PNS) Pemerintah Daerah di wilayah Jawa Tengah ', '2025'),
(24, 'Judul 7', 'Title 7', 'https://docs.google.com/spreadsheets/d/1pcHrYPjw8e1Rw', 'https://docs.google.com/spreadsheets/d/1pcHrYPjw', 'disdukcapil', '2', '', 0, '-', '-', '2025'),
(34, 'Judul 8', 'Title 8', 'https://docs.google.com/spreadsheets/d/1pSpmHYP6HN', 'https://docs.google.com/spreadsheets/d/1pSpmHYP6HNUC', 'kemenag', '1', '', 0, '-', '-', '2025'),
(35, 'Judul 9', 'Title 9', 'https://docs.google.com/spreadsheets/d/1Oj4OmERUYY9SAd2', 'https://docs.google.com/spreadsheets/d/1Oj4OmERUYY9', 'dinpendidikan', '2', '', 1, '-', 'Jumlah Sekolah, Guru, dan Murid Sekolah Dasar (SD) ', '2025'),
(39, 'Judul 10', 'Title 10', 'https://docs.google.com/spreadsheets/d/1SHXGP2B5885z-KBKt6', 'https://docs.google.com/spreadsheets/d/1SHXGP2B5885z', 'lldikti', '1', 'sudah', 0, '-', '-', '2025'),
(41, 'Judul 11  ', 'Title 11  ', 'https://docs.google.com/spreadsheets/d/1OnLp1Gpw7Mrc', 'https://docs.google.com/spreadsheets/d/1CKOK1WdPD0rNA', 'lingkunganhidup', '1', 'tabel tdk tampil', 0, '-', '-', '2025'),
(44, 'Judul 12', 'Title 12', 'https://docs.google.com/spreadsheets/d/1bqFhD3oGtahHRxwM', 'https://docs.google.com/spreadsheets/d/1bqFhD3oGta', 'dinkes', '2', '', 1, '-', '-', '2025'),
(45, 'Judul 13', 'Title 13', 'https://docs.google.com/spreadsheets/d/14cgPQe3GoEjcdo1Y6R5', 'https://docs.google.com/spreadsheets/d/14cgPQe3GoEjcd', 'bkkbn', '2', '', 1, '-', 'PPKBD adalah wadah organisasi di tingkat desa/kelurahan yang diketuai oleh seorang atau beberapa orang \r\n', '2025'),
(49, 'Judul 14', 'Title 14', 'https://docs.google.com/spreadsheets/d/1pdnPVZ8I1LHRcDeCIC625', 'https://docs.google.com/spreadsheets/d/1pdnPVZ8I1LHRcD', 'bpom', '2', '', 1, '', '-', '2025'),
(60, 'Judul 15', 'Title 15', 'https://docs.google.com/spreadsheets/d/1Z6Mr0_y9zVS', 'https://docs.google.com/spreadsheets/d/1Z6Mr0_y9zVSNc5dYq', 'polda', '1', 'sesuai', 0, '-', '-Pengertian tindak pidana dalam Kitab Undang-undang Hukum Pidana (KUHP) ', '2025'),
(67, 'Judul 16', 'Title 16', 'https://docs.google.com/spreadsheets/d/1_sVRpEvlFtMkc2QjWb', 'https://docs.google.com/spreadsheets/d/1_sVRpEvlFtMkc2QjWb', 'dinsos', '2', '', 0, '-', '-', '2025'),
(68, 'Judul 17', 'Title 17', 'https://docs.google.com/spreadsheets/d/193bFxpA9Og4og4', 'https://docs.google.com/spreadsheets/d/193bFxpA9Og4og4WOzypE', 'pengtinggi', '1', '', 0, '-', '- Perkara Masuk : \r\n- Perkara Putus : \r\n- Sisa Perkara : ', '2025'),
(72, 'Judul 18', 'Title 18', 'https://docs.google.com/spreadsheets/d/13cbXRy', 'https://docs.google.com/spreadsheets/d/13cbXRydWmtbH5IT', 'kejaksaan', '2', '', 1, '-', 'Rekapitulasi Laporan Perkara Penting Tindak Pidana ', '2025'),
(73, 'Judul 19', 'Title 19', 'https://docs.google.com/spreadsheets/d/1qOes38liv89rb1ik9ZEls', 'https://docs.google.com/spreadsheets/d/1qOes38liv89rb1', 'ptun', '1', 'Data sudah sesuai', 0, '-', 'Banyak perkara/sengketa tata usaha negara ', '2025'),
(74, 'Judul 20', 'Title 20', 'https://docs.google.com/spreadsheets/d/1Ed2sZlab1cd', 'https://docs.google.com/spreadsheets/d/1Ed2sZlab1cd05atXKk', 'kemenkumham', '1', 'Sudah lengkap', 1, 'Menurut saya sudah OK', '-', '2025'),
(79, 'Judul 21', 'Title 21', 'https://docs.google.com/spreadsheets/d/1Ygf0A5h8nD', '-', 'perumnas', '2', '', 0, '-', '-', '2025'),
(82, 'Judul 22', 'Title 22', 'https://docs.google.com/spreadsheets/d/1_sVRpEvlFtMkc2Qj', 'https://docs.google.com/spreadsheets/d/1_sVRpEvlFtMkc2QjW', 'dinsos', '2', '', 1, '-', '-', '2025'),
(84, 'Judul 23', 'Title 23', 'https://docs.google.com/spreadsheets/d/1fuFgq-FgWDlcZHY', 'https://docs.google.com/spreadsheets/d/1fuFgq', 'bpjs', '2', '', 0, '-', '-', '2025'),
(85, 'Judul 24', 'Title 24', 'https://docs.google.com/spreadsheets/d/1FdJ1dmsOfx5EiXMn57h8', 'https://docs.google.com/spreadsheets/d/1FdJ1dmsOfx5EiXMn57h8Sr', 'pengadilanagama', '2', '', 0, '-', '-', '2025'),
(87, 'Judul 25', 'Title 25', 'https://docs.google.com/spreadsheets/d/193bFxpA9Og4og4WOzypEtI', 'https://docs.google.com/spreadsheets/d/193bFxpA9Og', 'pengtinggi', '1', '', 0, '-', 'Jumlah Data Pengadilan Negeri se Jawa Tengah\r\nHakim : \r\nPanitera/Panitera Pengganti : \r\nJurusita : \r\n', '2025'),
(90, 'Judul 26', 'Title 26', 'https://docs.google.com/spreadsheets/d/13NTn-1Y2VHEa2k', 'https://docs.google.com/spreadsheets/d/13NTn-1Y2VHEa2kX1RG_ry', 'ptpn_ix', '1', '', 1, '-', '-', '2025'),
(92, 'Judul 27', 'Title 27', 'https://docs.google.com/spreadsheets/d/1dIxG1McxvL02LoIC', 'https://docs.google.com/spreadsheets/d/1dIxG1McxvL02LoIC2', 'pertanian', '1', '', 0, '-', '-', '2025'),
(95, 'Judul 28', 'Title 28', 'https://docs.google.com/spreadsheets/d/1fMEe5a-3w0VJB', 'https://docs.google.com/spreadsheets/d/1fMEe5a-3w0VJBj5UfHM', 'ternaksehat', '1', 'ok', 0, '-', '-Kumpulan  atau jumlah ternak  yang hidup pada wilayah dan satu waktu tertentu, ', '2025'),
(99, 'Judul 29', 'Title 29', 'https://docs.google.com/spreadsheets/d/1fy7w58XA_ys07QsGr8YPUQ2m4j', 'https://docs.google.com/spreadsheets/d/1fy7w58XA_ys07QsG', 'dkp', '1', 'ok', 0, '', '- Rumah Tangga Perikanan Tangkap ', '2025'),
(109, 'Judul 30', 'Title 30', 'https://docs.google.com/spreadsheets/d/1em5i2Lgu0ep6n643uD', 'https://docs.google.com/spreadsheets/d/1em5i2Lgu0ep6', 'pertamina', '2', '', 1, '-', '-', '2025'),
(110, 'Judul 31', 'Title 31', 'https://docs.google.com/spreadsheets/d/1vjrT5d7', 'https://docs.google.com/spreadsheets/d/1vjrT5d7yDMw4TLtv5HPk9', 'pln', '1', 'Sudah sesuai', 0, '-', '-', '2025'),
(116, 'Judul 32', 'Title 32', 'https://docs.google.com/spreadsheets/d/1TqdgXJLIlJN', 'https://docs.google.com/spreadsheets/d/1TqdgXJLIlJNVkZRcxO', 'esdm', '1', 'Dibandingkan tahun 2023', 0, '', '-', '2025'),
(117, 'Judul 33', 'Judul 33', 'https://docs.google.com/spreadsheets/d/1rmXXfTsuQ', 'https://docs.google.com/spreadsheets/d/1rmXXfTs', 'koperasi', '1', '', 1, 'konfirmasi', '-', '2025'),
(119, 'Judul 34', 'Title 34', 'https://docs.google.com/spreadsheets/d/1o2bRMvq4n1Db63', 'https://docs.google.com/spreadsheets/d/1o2bRMvq4n1Db63hH', 'bulog', '1', 'tabel 5.1.7 untuk jumlah jawa tengah tahun 2023 dan 2024', 0, '-', '-Penyaluran Raskin ', '2025'),
(122, 'Judul 35', 'Title 35', 'https://docs.google.com/spreadsheets/d/1EkNbkm_o2DHxclcqY', 'https://docs.google.com/spreadsheets/d/1EkNbkm_o2DHxclcq', 'pemudaor', '1', 'OK', 0, '-', '', '2025'),
(1135, 'Judul 36', 'Title 36', '', '', 'adisumarmo', '2', '', 1, '-', '-', '2026'),
(1163, 'Judul 37', 'Title 37', '', '', 'peljuwana', '2', '', 0, '-', '-', '2026'),
(1178, 'Judul 38', 'Title 38', '', '', 'peltegal', '2', '', 1, '-', '-', '2026'),
(1188, 'Judul 39', 'Title 39', '', '', 'kpu', '2', '', 1, '-', 'Memberikan informasi mengenai jumlah suara sah pada Pemilihan Umum Tahun 2019', '2026'),
(1194, 'Judul 40', 'Title 40', '', '', 'sekdprd', '2', '', 0, '-', '- Data yang diisi adalah data per Januari', '2026'),
(1219, 'Judul 41', 'Title 41', '', '', 'ojk', '2', '', 1, '-', '-', '2026');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `forumcomment`
--
ALTER TABLE `forumcomment`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `m_forumtopic`
--
ALTER TABLE `m_forumtopic`
  ADD PRIMARY KEY (`id_topik`);

--
-- Indeks untuk tabel `m_kondef`
--
ALTER TABLE `m_kondef`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `m_master_tabel_usulan`
--
ALTER TABLE `m_master_tabel_usulan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `m_metadata`
--
ALTER TABLE `m_metadata`
  ADD PRIMARY KEY (`id_metadata`);

--
-- Indeks untuk tabel `m_unitkerja`
--
ALTER TABLE `m_unitkerja`
  ADD UNIQUE KEY `id_unitkerja` (`id_unitkerja`);

--
-- Indeks untuk tabel `tr_instansi`
--
ALTER TABLE `tr_instansi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_admin`
--
ALTER TABLE `t_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_list_tabel`
--
ALTER TABLE `t_list_tabel`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `forumcomment`
--
ALTER TABLE `forumcomment`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `m_forumtopic`
--
ALTER TABLE `m_forumtopic`
  MODIFY `id_topik` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `m_kondef`
--
ALTER TABLE `m_kondef`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `m_master_tabel_usulan`
--
ALTER TABLE `m_master_tabel_usulan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `m_metadata`
--
ALTER TABLE `m_metadata`
  MODIFY `id_metadata` int(7) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tr_instansi`
--
ALTER TABLE `tr_instansi`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `t_admin`
--
ALTER TABLE `t_admin`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT untuk tabel `t_list_tabel`
--
ALTER TABLE `t_list_tabel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1306;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
