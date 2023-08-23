-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Agu 2023 pada 23.07
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `submyu_seed`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `myu_account`
--

CREATE TABLE `myu_account` (
  `id` int(10) NOT NULL,
  `uuid` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `authority` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`authority`)),
  `registration_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`registration_data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `myu_account`
--

INSERT INTO `myu_account` (`id`, `uuid`, `username`, `password`, `email`, `authority`, `registration_data`) VALUES
(1, '39a14955-e7fa-44f6-9b9e-7786368cd674', 'admin_master', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'admin.master@mail.com', '{}', '{\"regis_date\": \"2023-05-10 22:14:51\", \"deletable\": false}');

-- --------------------------------------------------------

--
-- Struktur dari tabel `myu_app_manifest`
--

CREATE TABLE `myu_app_manifest` (
  `manifest_code` varchar(100) NOT NULL,
  `manifest_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manifest_value`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `myu_app_manifest`
--

INSERT INTO `myu_app_manifest` (`manifest_code`, `manifest_value`) VALUES
('AUTHENTICATION', '{\"basic\": {\"general\": {\"username\": \"dpfir\", \"password\": \"123123\"}, \"email\": {\"username\": \"dpfir_email\", \"password\": \"123123\"}, \"page_template\": {\"username\": \"dpfir_pagetemplate\", \"password\": \"123123\"}}, \"api_key\": \"aGVsbG93b3JsZF8xMjMxMjM=\", \"google_api_2_oauth\": {\"client_id\": \"\", \"client_secret\": \"\"}, \"google_api_key\": \"\"}'),
('CONTACT_API_TEXT_TEMPLATE', '{\"whatsapp\":\"Halo Helloworld!\\n\\nSaya telah membuat pesanan  melalui website dengan rincian,\\nNama :\\nNo. Telephone :\\nPackage :\\nPackage Detail :\\nAdds on :\\nTanggal & Jam Acara :\\nLokasi :\\n\\nIngin langsung melakukan pembayaran,\"}'),
('OWNER_CONTACT', '{\"address\":\"Jl. Melati No. 1, Rw. Laut, Kec. Tanjungkarang Timur, Kota Bandar Lampung, Lampung 35213\",\"instagram\":{\"name\":\"hello.world\",\"url\":\"https:\\/\\/instagram.com\\/hello.world\"},\"line\":{\"name\":\"hello.world\",\"url\":\"\\/hello.world\"},\"whatsapp\":{\"name\":\"081212311321\",\"url\":\"https:\\/\\/wa.me\\/081212311321\"},\"facebook\":{\"name\":\"\\\"\",\"url\":\"https:\\/\\/facebook.com\\/\"},\"youtube\":{\"name\":\"hello.world\",\"url\":\"https:\\/\\/youtu.be\\/hello.world\"},\"linkedin\":{\"name\":\"\",\"url\":\"\"},\"twitter\":{\"name\":\"\",\"url\":\"\"},\"email\":\"\",\"phone_number\":\"081212311321\",\"office_number\":\"\",\"google_maps\":{\"name\":\"\",\"url\":\"\"},\"0\":{\"url\":\"\"}}'),
('UPLOAD_SETTING', '{\"image\":{\"compress\":\"0.5\",\"max_size\":{\"size\":\"1\",\"unit\":\"mb\"}}}');

-- --------------------------------------------------------

--
-- Struktur dari tabel `myu_blog`
--

CREATE TABLE `myu_blog` (
  `id` int(10) NOT NULL,
  `author_id` varchar(10) NOT NULL,
  `thumbnail_path` varchar(50) NOT NULL,
  `tags` varchar(100) NOT NULL,
  `status` enum('SHOW','ARCHIVE','PENDING') NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `date` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`date`)),
  `reputation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`reputation`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `myu_catalog`
--

CREATE TABLE `myu_catalog` (
  `id` int(10) NOT NULL,
  `category_id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `price` int(10) NOT NULL,
  `image_path` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`image_path`)),
  `status` enum('ARCHIVE','SHOW','PENDING','DELETED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `myu_catalog_category`
--

CREATE TABLE `myu_catalog_category` (
  `id` int(10) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `image_path` varchar(50) NOT NULL,
  `type` enum('SERVICE','PRODUCT') NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `myu_account`
--
ALTER TABLE `myu_account`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `myu_app_manifest`
--
ALTER TABLE `myu_app_manifest`
  ADD PRIMARY KEY (`manifest_code`);

--
-- Indeks untuk tabel `myu_blog`
--
ALTER TABLE `myu_blog`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `myu_catalog`
--
ALTER TABLE `myu_catalog`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `myu_catalog_category`
--
ALTER TABLE `myu_catalog_category`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `myu_account`
--
ALTER TABLE `myu_account`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `myu_blog`
--
ALTER TABLE `myu_blog`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `myu_catalog`
--
ALTER TABLE `myu_catalog`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `myu_catalog_category`
--
ALTER TABLE `myu_catalog_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
