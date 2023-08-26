-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Agu 2023 pada 04.32
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
('OWNER_CONTACT', '{\"site_name\":\"My UMKM\",\"address\":\"Jl. Raya 1 No. 12 Rt. 012\\/Rw. 04, Kelurahan, Kecamatan, Kota\\/Kabupaten, Provinsi 12345\",\"email\":\"ralfian096@gmail.com\",\"phone_number\":\"628123456789\",\"office_number\":\"\",\"social_media\":{\"instagram\":{\"url\":\"https:\\/\\/instagram.com\\/\",\"icon\":\"ri-instagram-line\",\"img\":\"\",\"username\":\"\",\"placeholder\":\"username\"},\"whatsapp\":{\"url\":\"https:\\/\\/wa.me\\/\",\"icon\":\"ri-whatsapp-line\",\"img\":\"\",\"username\":\"\",\"placeholder\":\"6281234567890\"},\"facebook\":{\"url\":\"https:\\/\\/facebook.com\\/\",\"icon\":\"ri-facebook-circle-line\",\"img\":\"\",\"username\":\"\",\"placeholder\":\"username\"},\"youtube\":{\"url\":\"https:\\/\\/youtu.be\\/\",\"icon\":\"ri-youtube-line\",\"img\":\"\",\"username\":\"\",\"placeholder\":\"username\"},\"linkedin\":{\"url\":\"https:\\/\\/linkedin.com\\/\",\"icon\":\"ri-linkedin-box-fill\",\"img\":\"\",\"username\":\"\",\"placeholder\":\"username\"},\"twitter\":{\"url\":\"https:\\/\\/twitter.com\\/\",\"icon\":\"ri-twitter-x-line\",\"img\":\"\",\"username\":\"\",\"placeholder\":\"username\"},\"tiktok\":{\"url\":\"https:\\/\\/tiktok.com\\/\",\"icon\":\"ri-tiktok-fill\",\"img\":\"\",\"username\":\"\",\"placeholder\":\"username\"},\"quora\":{\"url\":\"https:\\/\\/quora.com\\/\",\"icon\":\"\",\"img\":\"logo\\/third_party\\/logo-quora.svg\",\"username\":\"\",\"placeholder\":\"username\"}},\"marketplace\":{\"tokopedia\":{\"url\":\"https:\\/\\/tokopedia.com\\/\",\"icon\":\"\",\"img\":\"logo\\/third_party\\/logo-tokopedia.svg\",\"username\":\"\",\"placeholder\":\"username\"},\"shopee\":{\"url\":\"https:\\/\\/shopee.com\\/\",\"icon\":\"\",\"img\":\"logo\\/third_party\\/logo-shopee.svg\",\"username\":\"\",\"placeholder\":\"username\"},\"bukalapak\":{\"url\":\"https:\\/\\/bukalapak.com\\/\",\"icon\":\"\",\"img\":\"logo\\/third_party\\/logo-bukalapak.svg\",\"username\":\"\",\"placeholder\":\"username\"},\"blibli\":{\"url\":\"https:\\/\\/blibli.com\\/\",\"icon\":\"\",\"img\":\"logo\\/third_party\\/logo-blibli.svg\",\"username\":\"\",\"placeholder\":\"username\"},\"zalora\":{\"url\":\"https:\\/\\/zalora.com\\/\",\"icon\":\"\",\"img\":\"logo\\/third_party\\/logo-zalora.svg\",\"username\":\"\",\"placeholder\":\"username\"},\"lazada\":{\"url\":\"https:\\/\\/lazada.com\\/\",\"icon\":\"\",\"img\":\"logo\\/third_party\\/logo-lazada.svg\",\"username\":\"\",\"placeholder\":\"username\"}}}'),
('PAYMENT_METHOD', '{\"BCA\":{\"name\":\"Bank Central Asia\",\"icon\":\"\",\"img\":\"logo/third_party/logo-bca.svg\"},\"BRI\":{\"name\":\"Bank Rakyat Indonesia\",\"icon\":\"\",\"img\":\"logo/third_party/logo-bri.svg\"},\"BNI\":{\"name\":\"Bank Negara Indonesia\",\"icon\":\"\",\"img\":\"logo/third_party/logo-bni.svg\"},\"CIMB\":{\"name\":\"CIMB Niaga\",\"icon\":\"\",\"img\":\"logo/third_party/logo-cimb.svg\"},\"MANDIRI\":{\"name\":\"Bank Mandiri\",\"icon\":\"\",\"img\":\"logo/third_party/logo-mandiri.svg\"},\"PERMATA\":{\"name\":\"Bank Permata\",\"icon\":\"\",\"img\":\"logo/third_party/logo-permata.svg\"},\"BSI\":{\"name\":\"Bank Syariah Indonesia\",\"icon\":\"\",\"img\":\"logo/third_party/logo-bsi.svg\"},\"MEGA\":{\"name\":\"Bank Mega Indonesia\",\"icon\":\"\",\"img\":\"logo/third_party/logo-mega.svg\"},\"DANAMON\":{\"name\":\"Bank Danamon Indonesia\",\"icon\":\"\",\"img\":\"logo/third_party/logo-danamon.svg\"},\"MAYBANK\":{\"name\":\"Bank Maybank Indonesia\",\"icon\":\"\",\"img\":\"logo/third_party/logo-maybank.svg\"},\"SAMPOERNA\":{\"name\":\"Bank Sahabat Sampoerna\",\"icon\":\"\",\"img\":\"logo/third_party/logo-sampoerna.svg\"},\"GOPAY\":{\"name\":\"Gopay\",\"icon\":\"\",\"img\":\"logo/third_party/logo-gopay.svg\"},\"DANA\":{\"name\":\"DANA\",\"icon\":\"\",\"img\":\"logo/third_party/logo-dana.svg\"},\"SHOPEEPAY\":{\"name\":\"Shopeepay\",\"icon\":\"\",\"img\":\"logo/third_party/logo-shopeepay.svg\"},\"OVO\":{\"name\":\"OVO\",\"icon\":\"\",\"img\":\"logo/third_party/logo-ovo.svg\"}}'),
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `myu_payment_method`
--

CREATE TABLE `myu_payment_method` (
  `id` int(10) NOT NULL,
  `method` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` varchar(30) NOT NULL
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
-- Indeks untuk tabel `myu_payment_method`
--
ALTER TABLE `myu_payment_method`
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

--
-- AUTO_INCREMENT untuk tabel `myu_payment_method`
--
ALTER TABLE `myu_payment_method`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
