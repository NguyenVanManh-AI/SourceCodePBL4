-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 25, 2022 lúc 10:29 AM
-- Phiên bản máy phục vụ: 10.4.22-MariaDB
-- Phiên bản PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `pbl4`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(3, 'Điện thoại', '2022-10-17 02:35:43', '2022-10-17 02:35:43'),
(5, 'Quần2', '2022-10-17 02:36:06', '2022-10-22 08:00:21'),
(6, 'BKAV', '2022-10-17 02:36:13', '2022-10-19 23:02:01'),
(7, 'Đồng hồ', '2022-10-17 05:28:35', '2022-10-17 05:28:35'),
(8, 'GiàyGiày', '2022-10-17 05:28:45', '2022-10-17 05:28:45'),
(9, 'DépDép', '2022-10-17 05:28:54', '2022-10-17 05:28:54'),
(10, 'Đồ dùng học tập', '2022-10-17 05:29:07', '2022-10-17 05:29:07'),
(12, 'Phụ kiện', '2022-10-17 05:29:46', '2022-10-17 05:29:46'),
(13, 'Sách', '2022-10-17 05:29:58', '2022-10-17 05:29:58'),
(14, 'Máy ảnh', '2022-10-17 05:30:06', '2022-10-17 05:30:06'),
(15, 'Áo quần', '2022-10-17 05:32:12', '2022-10-17 05:32:12'),
(16, 'Xe máy', NULL, NULL),
(17, 'Ô tô', NULL, NULL),
(18, 'Xe đạp', NULL, NULL),
(19, 'Bàn là', NULL, NULL),
(20, 'Tủ lạnh', NULL, NULL),
(21, 'Điều hòa', NULL, NULL),
(22, 'Quạt', NULL, NULL),
(25, 'Chén', NULL, NULL),
(26, 'Đũa', NULL, NULL),
(27, 'Xoong', NULL, NULL),
(28, 'Bếp ga', NULL, NULL),
(29, 'áo mưa', NULL, NULL),
(30, 'bàn ghế tủ', NULL, NULL),
(31, 'tủ', NULL, NULL),
(32, 'dép', NULL, NULL),
(33, 'giày', NULL, NULL),
(34, 'Laptop', '2022-10-19 00:57:01', '2022-10-24 20:14:29'),
(35, 'PC', '2022-10-19 00:58:12', '2022-10-24 20:14:40'),
(36, 'Máy tính bảng', '2022-10-19 00:59:47', '2022-10-19 00:59:47'),
(37, 'Máy tính để bàn', '2022-10-19 00:59:59', '2022-10-19 00:59:59'),
(38, 'Âm thanh', '2022-10-19 01:00:13', '2022-10-19 01:00:13'),
(39, 'Camera Giám sát', '2022-10-19 01:00:23', '2022-10-19 01:00:23'),
(40, 'Máy in', '2022-10-19 01:01:02', '2022-10-19 01:01:02'),
(45, 'Apple Watch', '2022-10-24 21:16:26', '2022-10-24 21:16:26'),
(46, 'Loa', '2022-10-24 22:40:52', '2022-10-24 22:40:52'),
(47, 'Tai nghe', '2022-10-24 22:45:44', '2022-10-24 22:45:44'),
(48, 'Bàn phím', '2022-10-25 00:22:20', '2022-10-25 00:22:20'),
(49, 'Ốp', '2022-10-25 00:57:36', '2022-10-25 00:57:36'),
(50, 'Bộ nhớ lưu trữ', '2022-10-25 01:03:59', '2022-10-25 01:03:59'),
(51, 'Sạc dự phòng', '2022-10-25 01:07:32', '2022-10-25 01:07:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`id`, `fullname`, `email`, `username`, `email_verified_at`, `password`, `address`, `date_of_birth`, `gender`, `status`, `phone`, `url_img`, `google_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Văn MạnhMạnh', 'buongtaynhenhang99@gmail.com', 'vanmanh1pro', NULL, '$2y$10$0jn7fYhRXDLZxYpcihWx9uiReZAIfKUxkTSWHi.WgeQ7.jyHtz6ri', 'Hà Nội - Việt NamNam', '2001-09-29', 1, 1, '0971404372', 'storage/images/SWw5qAdRDrziCfEcMHaqROFnRBxkoLuYWYL3M6HF.jpg', '110656888206613286806', NULL, '2022-09-27 23:37:59', '2022-10-15 02:01:54'),
(2, 'Nguyễn Công CườngCường', 'congcuong999@gmail.com', 'congcuong999', NULL, '$2y$10$bd1l2ZAR9MiJD1XgFzq/5OqV4m2/IHHbQi0kkYNnVeoR1AFPVICbu', 'Hà Nội - Việt NamNam', '2001-09-29', 1, 1, '0971404372', NULL, NULL, NULL, '2022-09-28 03:39:51', '2022-10-03 05:31:13'),
(4, 'Nguyễn Công CườngCường', 'congcuong999666@gmail.com', 'congcuong999666', NULL, '$2y$10$ZheqF7VaZ/2FRneHyKymmuijNZZf7CAbDtDf6QXPWB/O40Dp0f0bu', 'Hà Nội - Việt NamNam', '2001-09-29', 1, 0, '0971404372', NULL, NULL, NULL, '2022-09-28 09:47:49', '2022-10-21 06:00:00'),
(9, 'Mạnh Nguyễn Văn', 'thanhxuantuoitre.sky2@gmail.com', 'gg_106115875168590721730', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '106115875168590721730', NULL, '2022-09-29 07:44:12', '2022-10-03 04:20:10'),
(10, 'Nguyen Van Hoang Phuc', 'hoangphuc999@gmail.com', 'hoangphuc', NULL, '$2y$10$iYYPaTlKBkEEHaoyxG.RiuskTHxVnmgBJnNWNsTRGJJWMD.YzLsPO', 'Hà Nội - Việt NamNam', '2001-09-29', 1, 1, '0971404372', NULL, NULL, NULL, '2022-10-03 04:17:51', '2022-10-03 04:17:51'),
(11, 'Bùi Văn Nguyện', 'buivannguyen999@gmail.com', 'buivannguyen999', NULL, '$2y$10$05is4KoFU95PyArvvjgt9O.QCKiat0rc2PnymxghBsXxEvjT631r2', 'Hà Nội - Việt NamNam', '2001-09-29', 1, 1, '0971404372', NULL, NULL, NULL, '2022-10-03 04:18:54', '2022-10-03 04:18:54'),
(12, 'Mai Thị Kim Khánh', 'kimkhanh999@gmail.com', 'kimkhanh999', NULL, '$2y$10$eGgeOKbxOiOOwsVyDiFkweCdozwEsN20IZuC87uJixdkD5NO1FWSe', 'Hồ Chí Minh - Việt Nam', '2002-09-28', 0, 1, '01284727372', NULL, NULL, NULL, '2022-10-03 04:20:00', '2022-10-15 02:01:24'),
(13, 'Mai Thị Kim Khánh2', 'kimkhanh9992@gmail.com', 'kimkhanh9992', NULL, '$2y$10$lj2ZAj1ZuvsNlSxA2of.iea/L.EDtcwy7vwx5Vk9.oe2nFpSIcS6e', 'Hồ Chí Minh - Việt Nam', '2002-09-28', 0, 1, '01284727372', NULL, NULL, NULL, '2022-10-03 04:33:53', '2022-10-03 04:33:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `images`
--

CREATE TABLE `images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `images`
--

INSERT INTO `images` (`id`, `product_id`, `image_path`, `created_at`, `updated_at`) VALUES
(23, 1, 'storage/products/eq6sb4qIWKnf34JLGTTKpTz44BgMWplJ5hPBHVoh.jpg', NULL, NULL),
(24, 1, 'storage/products/WfPNlJgvK9cKrT1PcWw4ys8jXHUS5AbnZkXYrKut.jpg', NULL, NULL),
(25, 1, 'storage/products/Sibc7jqSztK4yXhjocd2DqLtrdXFzscPqq2v9CUX.jpg', NULL, NULL),
(26, 1, 'storage/products/Uy8JjP5uAsnf0tDWEjMMKrlH1B1ru84uraOuScHm.jpg', NULL, NULL),
(27, 1, 'storage/products/ZsoLLG16FGABYa2brAtIeuhlrRoOBjgnJyEE8Qil.jpg', NULL, NULL),
(28, 1, 'storage/products/YC4TA3m11zK0Sl5hmUgRUMwNlgAmeLGic2hFQeDV.jpg', NULL, NULL),
(29, 2, 'storage/products/ReH0xq94M3VMXE88aukrshuxHqKYTsbfjDvNrPYN.jpg', NULL, NULL),
(30, 2, 'storage/products/rIrljL5OVxVoseN8BUpA55kgSqtexEsXRlvIt96c.jpg', NULL, NULL),
(31, 2, 'storage/products/lMYq1Ru1qZQEoveeDEAepwS3Iyd3BTDXkQFhBwmz.jpg', NULL, NULL),
(32, 2, 'storage/products/BM2tGnLF74h97zwv6iGCRpEJaBkZtIr8naMOQmJp.jpg', NULL, NULL),
(33, 2, 'storage/products/Ks2WzbfldnyzFc2KBZ9o1YbC9VmbOsWxSO00gsOb.jpg', NULL, NULL),
(35, 13, 'storage/products/LO8NiQFMvsK6EKY4SE6H0LUEINhkmqkZnP1vK1Je.png', NULL, NULL),
(36, 13, 'storage/products/MGsfDYgRUgr4amLEhU06kTmFKuCsWbVhWRewX1ok.png', NULL, NULL),
(38, 14, 'storage/products/qjd0MVDrRr0VfZ45GZgQGxkDlr2FtMTnG2BhTesU.png', NULL, NULL),
(39, 14, 'storage/products/BjJiAJEaC90nnH48DOPXjW8w0mgNcZ6LJ8TysufD.png', NULL, NULL),
(40, 14, 'storage/products/mf7I28mUszXoUduKQarjg7vx0tM1GX6gU9efUwC4.png', NULL, NULL),
(47, 54, 'storage/products/5wjQKTWS3WuOot7tlzvciCVzoihf0Ctngqsy1gfX.jpg', NULL, NULL),
(48, 54, 'storage/products/tU0mBtmJy2iNprEqvLhM7WyvZKnR1y0OeHIbGNiZ.jpg', NULL, NULL),
(49, 54, 'storage/products/UGKnDE6tgvYjh6onE1FJXiaUp4BqCr0bn2tfZTNV.png', NULL, NULL),
(50, 55, 'storage/products/KzoiP7jFyThJyjTOs482va1rusmHM6W3Swh17WIW.jpg', NULL, NULL),
(51, 55, 'storage/products/QxyMPWghESMgsO6fvMNZRv3XFs0LpGBat2N7vAJz.jpg', NULL, NULL),
(52, 55, 'storage/products/EERNI2BtFv238FORsz7WXpAfwQLLIKOCjRZnfNsQ.jpg', NULL, NULL),
(53, 55, 'storage/products/PPJAcCytcVRqIZN5TRd6QuG9P7Rg8iUKNhX4XeOi.jpg', NULL, NULL),
(54, 56, 'storage/products/QiOHKjAneTy3A2REf1y0BUkK3ERtopreBGKiqO2V.jpg', NULL, NULL),
(55, 56, 'storage/products/sDFHr2qcO5TjS0CaZrkwQfECbMWSM0CndethYvME.jpg', NULL, NULL),
(56, 56, 'storage/products/XVlp03P6QLYDVTZfmSGCTmuJtGhDRcZDHEPFLdcc.jpg', NULL, NULL),
(57, 56, 'storage/products/0BKzFYTYdXvXZ14NSCXVHTYgU5lt2aTcbY7P3147.jpg', NULL, NULL),
(58, 57, 'storage/products/x7IC4Nnc5NM2cqur84cRj27HSqQzeQvCPkYTwIyP.jpg', NULL, NULL),
(59, 57, 'storage/products/5X3lDJOepkzPFWOek5TenhgGnpK67jim7KPcBR1k.jpg', NULL, NULL),
(60, 57, 'storage/products/gTXLAuIV3zfE405NbJaEaibnvrSFDVNE1kloplmr.jpg', NULL, NULL),
(61, 58, 'storage/products/hAgQy6GOLRXdZ8K6iSHgEbC7VZe9rhgsa3cDn0lY.jpg', NULL, NULL),
(62, 58, 'storage/products/obnTxO4N3ik55hiryzBG6bZWVIrHych7UMk7CKz2.jpg', NULL, NULL),
(63, 58, 'storage/products/agsgUXQm0KZ4GKdTsVlV7etMvMAKlPTDkhyWiguW.jpg', NULL, NULL),
(64, 59, 'storage/products/w2BoQax2lspdx1fafvv1JrN2CUNRx7UG5eBJ8wk0.jpg', NULL, NULL),
(65, 59, 'storage/products/n1MZ3CEGGlF9ZQ7j3Li5rFYFAPvQ3IGSrCQB1v0k.jpg', NULL, NULL),
(66, 59, 'storage/products/CaqU3xR0vh40B8qgZnoOnbAzHRYnO58SIVG6YCgb.jpg', NULL, NULL),
(67, 60, 'storage/products/SSYf6DmTeiSiME4TZSNVTNFeAk2IJOt2ctBqunAk.jpg', NULL, NULL),
(68, 60, 'storage/products/3hGgoXrLsDgjCDQrk3gO3b8s0C21KCmFYi7JHI1g.jpg', NULL, NULL),
(69, 60, 'storage/products/izTe1sXyiGX0dWpQx0JpzmdLCrcNhI0ZJjEzo2LU.jpg', NULL, NULL),
(70, 61, 'storage/products/dnJTj8Jp6sdcP9Aqz01kyutGMeV6zNOgpRDy44W3.png', NULL, NULL),
(71, 61, 'storage/products/wuBx046UGTZfZPhp18215teeCBYViWj1p6MmGQpf.jpg', NULL, NULL),
(72, 61, 'storage/products/ICitN7o4HxU5hb6U8VQ2YaaeT8yxDzLKFAj2OjGx.jpg', NULL, NULL),
(73, 62, 'storage/products/GYcZucArY2ZtbmvYnDVVKAwCTKHePAgkubzeSJrq.jpg', NULL, NULL),
(74, 62, 'storage/products/IzktndGtUdq5AQ9V4TpebpCEaVj49BeeZaWp7QzS.jpg', NULL, NULL),
(75, 62, 'storage/products/e2ew5xO8XySWjBJqMzMGuTg7jTWFb3TBeZgwrazK.png', NULL, NULL),
(76, 63, 'storage/products/xnmFqkSXvSkKZHsk9WVzLhGyIGH5ePSFEz9sCjqX.png', NULL, NULL),
(77, 63, 'storage/products/byeX7WfdchrOsMABAHzwEB6y7BmkTi5krHLW5mWL.png', NULL, NULL),
(78, 64, 'storage/products/ZyWN62JCHuom46RTebWR8hRztssZ6j3uKeGMscfd.jpg', NULL, NULL),
(79, 64, 'storage/products/W5IIlJwIk4dnZjofaQ6CdCV0N3K3LS8W5bTLMZic.jpg', NULL, NULL),
(80, 64, 'storage/products/ViM8D4DTHaazgo40ubtfwVOJOz1NxhC7fxfzQZTS.jpg', NULL, NULL),
(81, 65, 'storage/products/HRVcwRPCATgqeZMTAZfoE1Roz9gch0dqlJY8LA2n.jpg', NULL, NULL),
(82, 65, 'storage/products/jJGD6SmK8IrFRhFpa4beQp1ryLzDBQxBgNRrf05y.jpg', NULL, NULL),
(83, 65, 'storage/products/ilTYg11TrdstQTFo0svbBXTyCmUAgqTnhLW0d5zf.jpg', NULL, NULL),
(84, 66, 'storage/products/ZjsvsotWj2m8dpAmvX4tTgyujnmNA5R4pN6qkDpt.jpg', NULL, NULL),
(85, 66, 'storage/products/VWuffeIL5o9kzLr7XFqXpzJLmCHOvUTmj3j7X6ss.jpg', NULL, NULL),
(86, 67, 'storage/products/djLZHwH61hClVsrnlCBqkK7DUxXrNGJ4I2TN4rHa.png', NULL, NULL),
(87, 67, 'storage/products/5TGULMf0nKduntSQl7Xe5dAcG7jVcB7Y2K5eJWpw.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `imports`
--

CREATE TABLE `imports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `importer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` bigint(20) UNSIGNED DEFAULT NULL,
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_tax_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `import_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `imports`
--

INSERT INTO `imports` (`id`, `importer_name`, `provider_id`, `provider_name`, `provider_tax_id`, `import_date`, `created_at`, `updated_at`) VALUES
(1, 'Nguyen Van Manh', NULL, 'Tran Thanh Nguyen ', '30030300030392', '2022-10-13', NULL, '2022-10-19 23:16:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `import_details`
--

CREATE TABLE `import_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `provider_tax_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_09_27_161838_create_products_table', 1),
(6, '2022_09_27_161855_create_customers_table', 1),
(7, '2022_09_27_192234_create_categories_table', 1),
(11, '2022_09_27_192306_create_shipping_addresses_table', 1),
(12, '2022_09_27_192315_create_user_orders_table', 1),
(13, '2022_09_27_192329_create_order_details_table', 1),
(14, '2022_09_27_201327_create_images_table', 1),
(15, '2022_09_27_192242_create_providers_table', 2),
(16, '2022_09_27_192250_create_imports_table', 3),
(17, '2022_09_27_192258_create_import_details_table', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `warranty_period` date NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` float(14,2) NOT NULL,
  `material` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dimension` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `warranty_period`, `description`, `category_id`, `price`, `material`, `dimension`, `uri`, `created_at`, `updated_at`) VALUES
(1, 'Asus Gaming', 0, '2024-10-20', 'Laptop Asus cấu hình mạnh', 34, 25000000.00, 'thép', '14.1 x 9.1 x 0.8 inch', 'asdfghjkl1234567890', '2022-09-27 05:59:43', '2022-10-24 20:13:37'),
(2, 'MacBook Air M1', 0, '2024-10-02', 'Laptop Apple MacBook Air M1 2020 8GB/256GB/7-core GPU (MGN93SA/A) \nLaptop Apple MacBook Air M1 2020 thuộc dòng laptop cao cấp sang trọng có cấu hình mạnh mẽ, chinh phục được các tính năng văn phòng lẫn đồ hoạ mà bạn mong muốn, thời lượng pin dài, thiết kế mỏng nhẹ sẽ đáp ứng tốt các nhu cầu làm việc của bạn.', 34, 30000000.00, 'Nhôm', '16 x 10 x 0.8 inch229', 'zxcvbnm1234567890', '2022-10-24 02:18:49', '2022-10-24 20:22:24'),
(13, 'Iphone 14', 0, '2024-12-27', '- Thiết kế và chất liệu: Khung viền nhôm Aluminum, mặt lưng kính.\n\n- Màn hình: OLED 6.1 inch, Super Retina XDR, độ phân giải 2532 - x 1170 pixel, tần số quét 60 Hz.\n\n- Camera: Trước 12 MP. Sau 12 MP, 12 MP.\n\n- Hệ điều hành: IOS 16.\n\n- Chip CPU: Apple A15 Bionic\n\n- Bộ nhớ: 128 GB, 256 GB, 512 GB.\n\n- Kết nối: 5G, Wi-Fi, eSIM.\n\n- Pin, sạc: Sử dụng lên tới 20 giờ, sạc không dây MagSafe 15W, sạc không dây Qi lên đến 7,5W, sạc nhanh 20W.\n\n- Tiện ích: Camera TrueDepth nhận dạng khuôn mặt, hỗ trợ quay video với HDR Dolby Vision, chống nước và bụi chuẩn IP68,…', 3, 32000000.00, 'Khung viền nhôm Aluminum, mặt lưng kính.', '6.1 inch', 'c239c94e9d02a705b8ac', '2022-10-23 14:17:57', '2022-10-24 20:39:27'),
(14, 'Samsung Galaxy Z Flip', 0, '2024-10-26', 'Samsung Galaxy Z Flip là điện thoại thông minh có thể gập lại chạy trên nền tảng Android được phát triển, sản xuất và thương mại hóa bởi Samsung Electronics. Đây là mẫu máy điện thoại gập thứ hai của Samsung sau Galaxy Fold.', 3, 9999999.00, 'Phủ thủy tinh', '6,7 inch', 'c89e98596274b5268138', '2022-10-23 14:22:14', '2022-10-24 20:33:21'),
(54, 'Apple Watch', 0, '2022-10-27', 'Apple Watch Series 0 trang bị màn hình Retina OLED kích thước 1.65″, được bảo vệ bởi lớp kính cường lực Sapphire crystal glass chống trầy xước và chịu được những tác động mạnh. Mặt lưng của Series 0 có tích hợp cụm cảm biến để đo nhịp tim.', 45, 999222.00, 'Bản nhôm: Màu trắng, màu xám', '1.65″ Retina OLED', 'f780ba967d1d010a8a4a', '2022-10-24 20:55:53', '2022-10-24 22:30:39'),
(55, 'Xiaomi MI 10T Pro', 0, '2025-10-07', 'Mi 10T Pro 5G mẫu smartphone cao cấp của Xiaomi trong năm 2020 cuối cùng cũng được trình làng với loạt những thông số gây “choáng ngợp”: Màn hình tần số quét 144 Hz, vi xử lý Snapdragon 865 và cụm camera khủng 108 MP kèm theo đó là một mức giá dễ chịu cho người dùng.', 3, 27000000.00, 'Thép', 'IPS LCD6.67\"Full HD+', 'c8d1f7e65401decaca03', '2022-10-24 21:01:23', '2022-10-24 21:01:23'),
(56, 'Gaming GL66', 0, '2022-11-05', 'Thông số kỹ thuật\nLoại CPU. Intel Core i7 11800H.\nLoại card đồ họa. Geforce RTX 3050Ti 4GB.\nDung lượng RAM. 16GB.\nLoại RAM. DDR4 16GB (2 x 8GB) 3200MHz; 2 khe, hỗ trợ tối đa 64GB.\nỔ cứng. 512GB SSD + 1 HDD 2.5\'\nKích thước màn hình. 15.6 inches.\nĐộ phân giải màn hình. 1080 x 1920 pixels (FullHD)\nCổng giao tiếp. 1x (4K @ 60Hz) HDMI.', 34, 23500000.00, 'Thép', '1080 x 1920 pixels (FullHD)', '58630fd8809728d8aa21', '2022-10-24 22:36:09', '2022-10-24 22:36:09'),
(57, 'Loa JPL', 0, '2022-10-20', 'JBL charge 3 là một sản phẩm độc quyền của thương hiệu JBL, có xuất xứ từ Mỹ. Đây là một thiết bị loa bluetooth từ khi ra mắt đã được rất nhiều người săn đón. Bởi lẽ, người ta yêu thích và lựa chọn dòng loa này là do sự dung hòa tuyệt đỉnh giữa chất lượng âm thanh, pin trâu và khả năng chống nước bền bỉ.', 46, 300000.00, 'Thép', '213 x 87 x 88,5 mm', '8406e6175cd5d31d8c49', '2022-10-24 22:40:16', '2022-10-24 22:41:08'),
(58, 'Apple Airpods Pro 2', 0, '2022-10-30', 'Airpods Pro 2 chính thức ra mắt người dùng vào  rạng sáng 8/9/2022 theo giờ Việt Nam tại sự kiện “Far Out”. Mẫu tai nghe Airpods Pro 2 với những cải tiến vô cùng mạnh mẽ từ vi xử lý mới, thời lượng pin lên đến 30 giờ và công nghệ Find My tích hợp cho khả năng tìm kiếm tai nghe dễ dàng hơn bao giờ hết.', 47, 11000110.00, 'Nhựa', 'Dài 3.09 cm – Rộng 2.18 cm – Cao 2.17 cm', 'beb6a5c2315d57b15cd6', '2022-10-24 22:45:17', '2022-10-24 22:46:00'),
(59, 'Bàn phím cơ', 0, '2022-12-09', 'Bàn phím cơ giá tốt', 48, 450000.00, 'Nhựa', '20cmx10cm', 'c80aa2615376a523f289', '2022-10-25 00:21:59', '2022-10-25 00:22:38'),
(60, 'Sách DevUp', 0, '2022-12-10', 'Sách hay nhất của Người trong muôn nghề', 13, 90000.00, 'Bìa cứng', '20cm -  6cm', 'b6b938e5052d9b6d6c06', '2022-10-25 00:27:58', '2022-10-25 00:27:58'),
(61, 'Nhà Giả Kim', 0, '2022-12-30', 'Sách hay của tác giả Paulo Coelho', 13, 30000.00, 'Bìa cứng', '20cm - 6cm', 'eef751f4d5d65beb9f83', '2022-10-25 00:47:56', '2022-10-25 00:47:56'),
(62, 'Đắc Nhân Tâm', 0, '2023-02-10', 'Sách hay - Đắc nhân tâm', 13, 345000.00, 'Bìa cứng', '20cm - 6cm', 'a6167fbae577e26eeb60', '2022-10-25 00:51:43', '2022-10-25 00:51:43'),
(63, 'Ốp lưng IP12', 0, '2023-01-28', 'Ốp lưng màu đỏ dành cho điện thoại IPhone 12', 49, 30000.00, 'Ốp da', '12cm - 6cm', '9724895233d7d168774a', '2022-10-25 00:57:20', '2022-10-25 00:58:27'),
(64, 'USB 2T', 0, '2022-12-25', 'Bộ nhớ , tốc độ đọc ghi siêu nhanh .', 50, 500300.00, 'Nhôm', '6cm - 4cm', 'bda1d26c51203943f113', '2022-10-25 01:05:09', '2022-10-25 01:05:09'),
(65, 'Sạc dự phòng Energizer 20.000mAh UE20012PQ', 0, '2024-11-22', 'Sạc dự phòng với 2 Đầu vào: Micro-USB, Đầu vào: Type C', 51, 900233.00, 'Nhôm', '10cm - 6cm', '2bbda9b780b75c226dd4', '2022-10-25 01:09:37', '2022-10-25 01:09:37'),
(66, 'Bàn văn phòng', 0, '2022-10-28', 'Bàn văn phòng giá tốt . Để máy tính siêu đẹp .', 30, 1000999.00, 'Gỗ lim', '2m - 0.5m . Khối lượng 10kg', '1514021ea4a864141a5e', '2022-10-25 01:17:49', '2022-10-25 01:17:49'),
(67, 'Ghế văn phòng', 0, '2023-02-04', 'Ghế văn phòng cao cấp . Chân sắt .', 30, 560399.00, 'Bọc vải - Khung sắt', '0.5m - 0.3m - 0.2m', 'e6c346ab60e7ab052bce', '2022-10-25 01:28:08', '2022-10-25 01:28:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `providers`
--

CREATE TABLE `providers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `providers`
--

INSERT INTO `providers` (`id`, `name`, `email`, `address`, `phone`, `tax_id_number`, `created_at`, `updated_at`) VALUES
(1, 'VinFast', 'vinfast@gmail.com', 'Ho Chi Minh City', '0123456789', '0307030428931', '2022-10-19 22:53:41', '2022-10-23 02:04:40'),
(2, 'SunHouse', 'sunhouse@gmail.com', 'Ha Noi - Viet Nam', '022030123292', '0233303232122', '2022-10-19 22:54:52', '2022-10-19 22:54:52'),
(3, 'VinaMilk', 'vinamilk@gmail.com', 'Ba Vi - Viet Nam', '02102032933', '0330402022304', '2022-10-19 22:55:36', '2022-10-19 22:55:36'),
(4, 'Apple', 'applestore@gmail.com', 'NewYork - USA', '01102394832', '03432201594843', '2022-10-19 22:56:28', '2022-10-19 22:56:28'),
(5, 'SamSung', 'groupsamsung@gmail.com', 'Korea', '0940394223', '04445323392322', '2022-10-19 22:57:11', '2022-10-19 22:57:11'),
(6, 'BKAV', 'bkav@gmail.com', 'Hà Nội - Việt Nam', '09876543213', '0593958382333', '2022-10-19 23:01:02', '2022-10-19 23:02:39'),
(8, 'Ten nha cung cap 1', 'nhacungcap2222221@gmail.com', 'Da Nang - Viet Nam', '022222222222', '030303032222203030', '2022-10-19 23:28:12', '2022-10-20 02:08:40'),
(13, 'Nha cung cap 1', 'nhacungcap2@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:29:17', '2022-10-19 23:29:17'),
(14, 'Nha cung cap 1', 'nhacungcap3@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:29:20', '2022-10-19 23:29:20'),
(21, 'Nha cung cap 1', 'nhac3ung3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:29:44', '2022-10-19 23:29:44'),
(22, 'Nha cung cap 1', 'nhac3u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:29:46', '2022-10-19 23:29:46'),
(23, 'Nha cung cap 1', 'nha3c3u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:29:48', '2022-10-19 23:29:48'),
(24, 'Nha cung cap 1', 'nha33c3u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:29:50', '2022-10-19 23:29:50'),
(25, 'Nha cung cap 1', 'nha333c3u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:29:52', '2022-10-19 23:29:52'),
(26, 'Nha cung cap 1', 'nha3333c3u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:29:54', '2022-10-19 23:29:54'),
(27, 'Nha cung cap 1', 'nha33333c3u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:29:57', '2022-10-19 23:29:57'),
(28, 'Nha cung cap 1', 'nh3a33333c3u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:30:01', '2022-10-19 23:30:01'),
(29, 'Nha cung cap 1', 'nh3a33333c33u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:30:03', '2022-10-19 23:30:03'),
(30, 'Nha cung cap 1', 'nh33a33333c33u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:30:05', '2022-10-19 23:30:05'),
(31, 'Nha cung cap 1', 'nh33a333333c33u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:30:07', '2022-10-19 23:30:07'),
(34, 'Nha cung cap 1', 'nh333a33333333c33u3ng3a37@gmail.com', 'Da Nang', '011111111111', '0303030303030', '2022-10-19 23:30:12', '2022-10-19 23:30:12'),
(35, 'NextTech', 'nexttech@gmail.com', 'Sài Gòn - Việt Nam', '01236432322', '0223203320022', '2022-10-20 01:12:05', '2022-10-20 01:12:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shipping_addresses`
--

CREATE TABLE `shipping_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `username`, `email_verified_at`, `password`, `address`, `date_of_birth`, `gender`, `phone`, `url_img`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Nguyễn Công Cường', 'congcuong222@gmail.com', 'congcuong222', NULL, '$2y$10$AoJH9QoeH4XRSIFt5QXgUehVxAcCJ5xhovx8QlwICkdYQ7sxUIg9K', 'Tam Ky - Quang Nam - Viet Nam', '2022-09-06', 1, '0123456789', 'storage/images/T2fPnOwVPWVyWmPuQ8X2OuYoKEN47nq0gHnXo5yH.jpg', 'super admin', NULL, '2022-09-27 22:28:01', '2022-09-30 11:00:26'),
(6, 'Nguyễn Thị Mỹ An', 'myandethuong@gmail.com', NULL, NULL, '$2y$10$TmMqGXAydua9nxGM84IW/u3Vsx4JtHiifOQF.uTV3hnT8u2uA6v02', NULL, NULL, NULL, NULL, NULL, 'super admin', NULL, '2022-09-30 12:01:15', '2022-09-30 12:09:19'),
(7, 'Trần Thị Thùy Dương', 'thuyduongcute@gmail.com', 'hithuyduong', NULL, '$2y$10$RJC.p3ZIpNJfTtGtpyfa8uNk4.NR5BoFC.57or6cvWWCvrvGSEIia', 'Hue City - Viet Nam', '2022-10-19', 0, '0123544212', 'storage/images/50dj0eXQFbU2Havc4KPkF4S2W0lk8o68hNvD74XG.jpg', 'super admin', NULL, '2022-09-30 12:08:57', '2022-10-01 04:24:23'),
(8, 'ascasacas', 'vanmanascsacasch111@gmail.com', NULL, NULL, '$2y$10$pY6FFhgAzduaIjqaHWK1YuE.ipNQNs.EZLfz6pgt6ybWh.uupS09e', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-09-30 23:04:10', '2022-10-01 02:01:25'),
(11, 'ascsacascasc', 'vanmsacsacascanh111@gmail.com', NULL, NULL, '$2y$10$mDGrrF0vbSfOXR3leC6fLuh1TgQ5Sf78Fjnx22/uG5RRKJzJSt1nm', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-09-30 23:04:52', '2022-09-30 23:04:52'),
(12, 'acsacasc', 'vanascsacmanh111@gmail.com', NULL, NULL, '$2y$10$W40Rt5Y22NCyTLw3RlPLeePNKjoJ/S0/VOF9Kn8EZhUBsMSE1OWpi', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-09-30 23:44:39', '2022-10-02 22:51:06'),
(19, 'Nguyễn Văn Hoàng Phúc', 'hoangphuc@gmail.com', NULL, NULL, '$2y$10$KJG5cltAQuoDWgRUu/q3b.oKZvPEqwWhKFYOTmsWDdN5pb9pPzvhK', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-10-01 05:34:27', '2022-10-01 05:34:27'),
(20, 'Hương Giang', 'huonggiang@gmail.com', NULL, NULL, '$2y$10$8MbbGu7wu205CUexQiwFsOoTcW23QPaXa7.0OhEaJWYWOzmTexb2a', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-10-01 05:36:56', '2022-10-01 05:36:56'),
(21, 'Trần Thanh Nguyên', 'thanhnguyen@gmail.com', NULL, NULL, '$2y$10$PF3GH9m/vAULyvAAVok5FuarT6FL.bGUmLTuVXwImk5wmfQCdJ1FW', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-10-01 05:40:11', '2022-10-01 05:40:11'),
(30, 'FULLSTACK 55', 'khoahocfullstack5@gmail.com', NULL, NULL, '$2y$10$UrAxDjvqu5y0.a4kXvhZpuPmoqQ2Sc.vi5YYgBjKeb3ItSQxgUneO', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-10-01 06:49:48', '2022-10-02 22:51:09'),
(31, 'FULLSTACK 55', 'khoahocfullstack5555@gmail.com', NULL, NULL, '$2y$10$CHnYTqzxAf5z8qiecU2.uuk7zBCYgRxt0CdAp4HceXvHmVMCMZwu2', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-10-01 06:50:00', '2022-10-02 22:51:34'),
(32, 'FULLSTACK 55', 'khoahocfullstack55556666@gmail.com', NULL, NULL, '$2y$10$9QJsymUwvUBQu1/98QkbOetTBX75U2LOubuKl114R4FNOnth5sQbC', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-10-01 06:50:18', '2022-10-02 22:51:37'),
(33, 'Nguyen Van Manh PRO', 'nguyenvanmanh2001it1@gmail.com', NULL, NULL, '$2y$10$2kgy90FL/3IlwazxbufSLuqYQjSCimzN6BdhVP4cZf/GXzl6GwHxe', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-10-01 06:51:37', '2022-10-15 02:15:25'),
(34, 'JOMA Tech', 'itmaster2908@gmail.com', 'jomatech99', NULL, '$2y$10$K2mH1tHzSONAaAXlfBWjku/3e78Cn9zyNYHt8rY.0ZGiPMFj50riq', 'USA', '2022-10-12', 1, '0928472632', 'storage/images/88ba41zgejIkVtF0uytAMdRPBpns8R40o4aYw9OC.jpg', 'super admin', NULL, '2022-10-01 07:04:29', '2022-10-02 22:51:46'),
(35, 'sdvsdbdfbdfbdfb', 'dfbfdbfdbfdb@gmail.com', NULL, NULL, '$2y$10$9qpYKaO8/r4TZnXn9UwSG.ZC61l9mcpxKk6/BWaJqUytcSBbzkCfG', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2022-10-01 07:04:54', '2022-10-02 22:51:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_orders`
--

CREATE TABLE `user_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`),
  ADD UNIQUE KEY `customers_username_unique` (`username`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `imports`
--
ALTER TABLE `imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Chỉ mục cho bảng `import_details`
--
ALTER TABLE `import_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `import_id` (`import_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_uri_unique` (`uri`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `providers_email_unique` (`email`);

--
-- Chỉ mục cho bảng `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Chỉ mục cho bảng `user_orders`
--
ALTER TABLE `user_orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT cho bảng `imports`
--
ALTER TABLE `imports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `import_details`
--
ALTER TABLE `import_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT cho bảng `providers`
--
ALTER TABLE `providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `user_orders`
--
ALTER TABLE `user_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `imports`
--
ALTER TABLE `imports`
  ADD CONSTRAINT `imports_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`);

--
-- Các ràng buộc cho bảng `import_details`
--
ALTER TABLE `import_details`
  ADD CONSTRAINT `import_details_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `import_details_ibfk_2` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
