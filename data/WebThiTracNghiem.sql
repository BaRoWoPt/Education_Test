-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th10 14, 2024 lúc 08:53 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `WebThiTracNghiem`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cauhoi`
--

CREATE TABLE `cauhoi` (
  `macauhoi` int(11) NOT NULL,
  `noidung` varchar(500) NOT NULL,
  `dokho` int(11) NOT NULL,
  `mamonhoc` int(11) NOT NULL,
  `machuong` int(11) NOT NULL,
  `nguoitao` varchar(50) DEFAULT NULL,
  `trangthai` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cauhoi`
--

INSERT INTO `cauhoi` (`macauhoi`, `noidung`, `dokho`, `mamonhoc`, `machuong`, `nguoitao`, `trangthai`) VALUES
(23, 'gbao nè', 2, 2905, 1, 'mhanhgv', 1),
(24, '123123', 1, 2905, 145, 'mhanhgv', 1),
(25, '222', 3, 2905, 5555, '', 1),
(26, '123', 3, 2905, 234234, 'mhanhgv', 1),
(27, '123123', 3, 2905, 123, 'Chưa có ID', 1),
(28, '2', 2, 2905, 1, 'Chưa có ID', 1),
(29, '123', 2, 2905, 1231, 'mhanhgv', 1),
(30, 'asdasd', 3, 2905, 1, 'mhanhgv', 1),
(31, '123123', 3, 2905, 123, 'mhanhgv', 1),
(32, 'ai là thần đồng toán học ?', 2, 2905, 1, 'mhanhgv', 1),
(33, '56', 2, 1190082, 1, 'mhanhgv', 1),
(34, 'áadasdasd', 2, 1190082, 1, 'mhanhgv', 1),
(35, '123123123123123123123123', 2, 1190082, 1, 'mhanhgv', 1),
(36, '23123123', 3, 1190082, 1231231, 'mhanhgv', 1),
(37, '1231231', 3, 1190082, 123123, 'mhanhgv', 1),
(38, '1', 2, 2905, 1, 'mhanhgv', 1),
(39, '123', 3, 2905, 123123, 'mhanhgv', 1),
(40, '123123123', 3, 1190082, 1, 'mhanhgv', 1),
(41, '1234412', 3, 2905, 54, 'mhanhgv', 1),
(42, '123123123', 2, 1190082, 123, 'mhanhgv', 1),
(43, '123123123', 3, 1190082, 123123, 'mhanhgv', 1),
(44, '123123', 3, 1190082, 2, 'mhanhgv', 1),
(45, '123', 3, 1190082, 1, 'mhanhgv', 1),
(46, '12', 2, 1190082, 123123, 'mhanhgv', 1),
(47, '3', 2, 1190082, 1, 'mhanhgv', 1),
(48, '241234', 2, 2905, 3, 'mhanhgv', 1),
(49, 'Nội dung câu hỏi dễ', 0, 2905, 123, 'yui', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cautraloi`
--

CREATE TABLE `cautraloi` (
  `macautl` int(11) NOT NULL,
  `macauhoi` int(11) NOT NULL,
  `noidungtl` varchar(500) NOT NULL,
  `ladapan` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cautraloi`
--

INSERT INTO `cautraloi` (`macautl`, `macauhoi`, `noidungtl`, `ladapan`) VALUES
(113, 25, '123', 0),
(114, 25, '123123', 1),
(115, 25, '123', 0),
(116, 25, '2', 0),
(121, 27, '31231232', 0),
(122, 27, '123123', 0),
(123, 27, '12312312', 0),
(124, 27, '3123123', 1),
(125, 28, '123', 0),
(126, 28, '123', 0),
(127, 28, '123123', 0),
(128, 28, '123', 1),
(129, 29, '123', 0),
(130, 29, '123123123', 0),
(131, 29, '123', 0),
(132, 29, '123123', 1),
(137, 31, '123', 1),
(138, 31, '123', 0),
(139, 31, '123', 0),
(140, 31, '111', 0),
(141, 30, '123', 0),
(142, 30, '123', 0),
(143, 30, '123', 1),
(144, 30, '12344', 0),
(145, 32, 'tôi qưe', 0),
(146, 32, ' tôi ', 1),
(147, 32, 'tooiiii', 0),
(148, 32, 'tôiiiiiqweqwe', 0),
(149, 33, 'f', 1),
(150, 33, 'gfd', 0),
(151, 33, 'gdf', 0),
(152, 33, 'sfdsdf', 0),
(153, 34, '123', 0),
(154, 34, 'Bảo', 1),
(155, 34, 'Bảo', 1),
(156, 34, 'tôi qưe', 0),
(157, 0, '123', 1),
(158, 0, '123123123', 1),
(159, 0, '123123', 0),
(160, 0, '1231231111', 0),
(161, 0, '124124', 1),
(162, 0, '1123', 1),
(163, 0, '123111', 0),
(164, 0, '123123123', 0),
(169, 36, '123', 1),
(170, 36, '123', 1),
(171, 36, '113', 0),
(172, 36, '123', 1),
(177, 38, '23', 0),
(178, 38, '231', 1),
(179, 38, '123', 0),
(180, 38, '123', 0),
(181, 39, '123123123123', 1),
(182, 39, '123123123', 1),
(183, 39, '1', 1),
(184, 39, '5', 0),
(189, 41, '124124', 0),
(190, 41, '12412413123123', 1),
(191, 41, '123', 1),
(192, 41, '15424124124', 0),
(197, 37, '123123', 1),
(198, 37, '1231231111111111111', 1),
(199, 37, '123123', 0),
(200, 37, '123123', 0),
(201, 43, '12312312312312', 0),
(202, 43, '312312312312', 0),
(203, 43, '312312312312312312312', 1),
(204, 43, '1231231231231233', 1),
(213, 44, '123123', 1),
(214, 44, '123123123', 1),
(215, 44, '123123123', 0),
(216, 44, '123', 1),
(217, 40, '123123', 0),
(218, 40, '123123', 1),
(219, 40, '123123123123123123123123123123123123123', 1),
(220, 40, '123123123123123123123', 0),
(225, 35, '123123123123123', 0),
(226, 35, '123123123', 0),
(227, 35, '1231237', 0),
(228, 35, '123123', 0),
(229, 45, '23', 0),
(230, 45, '123', 1),
(231, 45, '321', 1),
(232, 45, '1', 0),
(233, 46, 'hi', 1),
(234, 46, 'ha', 1),
(235, 46, 'hád', 0),
(236, 46, 'hádasd', 0),
(245, 42, '123123', 0),
(246, 42, '123123', 1),
(247, 42, '3123123', 1),
(248, 42, '123123123', 0),
(249, 24, '123', 1),
(250, 24, '123123123123', 0),
(251, 24, '123123123', 0),
(252, 24, '123123123', 1),
(257, 47, '1', 0),
(258, 47, '2', 0),
(259, 47, '3', 1),
(260, 47, '4', 1),
(261, 48, '123', 0),
(262, 48, '32', 0),
(263, 48, '1', 1),
(264, 48, '3', 1),
(265, 23, '123123', 0),
(266, 23, '123123', 1),
(267, 23, '123123', 0),
(268, 23, '123123123123123', 0),
(269, 26, '123', 0),
(270, 26, '12312312', 1),
(271, 26, '123123', 0),
(272, 26, '123123', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdethi`
--

CREATE TABLE `chitietdethi` (
  `made` int(11) NOT NULL,
  `macauhoi` int(11) NOT NULL,
  `thutu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdethi`
--

INSERT INTO `chitietdethi` (`made`, `macauhoi`, `thutu`) VALUES
(14, 42, 1),
(15, 23, 1),
(15, 25, 3),
(15, 26, 2),
(15, 27, 2),
(15, 28, 2),
(15, 29, 3),
(15, 30, 2),
(15, 32, 2),
(15, 38, 1),
(15, 39, 1),
(15, 41, 3),
(15, 48, 3),
(16, 23, 3),
(16, 28, 3),
(16, 29, 1),
(16, 32, 2),
(16, 38, 2),
(16, 48, 1),
(17, 33, 1),
(17, 34, 1),
(17, 35, 1),
(17, 42, 1),
(17, 47, 1),
(18, 23, 4),
(18, 24, 1),
(18, 25, 5),
(18, 26, 6),
(18, 27, 8),
(18, 28, 4),
(18, 29, 2),
(18, 32, 3),
(18, 38, 5),
(18, 41, 7),
(18, 48, 4),
(19, 24, 1),
(19, 29, 2),
(19, 32, 3),
(20, 23, 2),
(20, 24, 1),
(20, 28, 4),
(20, 29, 5),
(20, 32, 3),
(20, 38, 3),
(20, 48, 6),
(21, 23, 3),
(21, 24, 1),
(21, 28, 5),
(21, 29, 2),
(21, 32, 4),
(21, 38, 3),
(21, 48, 6),
(23, 23, 4),
(23, 24, 1),
(23, 25, 12),
(23, 26, 10),
(23, 27, 10),
(23, 28, 3),
(23, 29, 5),
(23, 30, 11),
(23, 31, 11),
(23, 32, 2),
(23, 38, 6),
(23, 39, 8),
(23, 41, 9),
(23, 48, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietketqua`
--

CREATE TABLE `chitietketqua` (
  `makq` varchar(255) NOT NULL,
  `macauhoi` int(11) NOT NULL,
  `dapanchon` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietnhom`
--

CREATE TABLE `chitietnhom` (
  `manhom` int(11) NOT NULL,
  `manguoidung` varchar(50) NOT NULL DEFAULT '0',
  `hienthi` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietnhom`
--

INSERT INTO `chitietnhom` (`manhom`, `manguoidung`, `hienthi`) VALUES
(29050424, 'nnguoidung123', 1),
(0, '22dh1177864', 1),
(252551, 'nnguoidung123', 1),
(252551, '22dh1177864', 1),
(35, '22dh1177864', 1),
(29050424, '22dh1177864', 1),
(29050424, '22dh1177864', 1),
(123123123, '123123123', 1),
(124123, 'nnguoidung123', 1),
(29050406, 'nnguoidung123', 1),
(29050406, 'mhanhgv', 1),
(35, 'mhanhgv', 1),
(252551, 'mhanhgv', 1),
(35, 'mhanhgv', 1),
(29050424, 'mhanhgv', 1),
(124123, 'mhanhgv', 1),
(29050406, '123123123', 1);

--
-- Bẫy `chitietnhom`
--
DELIMITER $$
CREATE TRIGGER `update_group_participants_after_delete` AFTER DELETE ON `chitietnhom` FOR EACH ROW UPDATE nhom 
SET siso = (SELECT count(*) FROM chitietnhom WHERE manhom = OLD.manhom) 
WHERE manhom = OLD.manhom
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_group_participants_after_insert` AFTER INSERT ON `chitietnhom` FOR EACH ROW UPDATE nhom 
SET siso = (SELECT count(*) FROM chitietnhom WHERE manhom = NEW.manhom) 
WHERE manhom = NEW.manhom
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietquyen`
--

CREATE TABLE `chitietquyen` (
  `manhomquyen` int(11) NOT NULL,
  `chucnang` varchar(50) NOT NULL,
  `hanhdong` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietthongbao`
--

CREATE TABLE `chitietthongbao` (
  `matb` int(11) NOT NULL,
  `manhom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuong`
--

CREATE TABLE `chuong` (
  `machuong` int(11) NOT NULL,
  `tenchuong` varchar(255) NOT NULL,
  `mamonhoc` int(11) NOT NULL,
  `trangthai` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chuong`
--

INSERT INTO `chuong` (`machuong`, `tenchuong`, `mamonhoc`, `trangthai`) VALUES
(121, '121', 1190082, 1),
(123, '123', 1190082, 1),
(1, '1', 1190082, NULL),
(123123, '123123', 1190082, NULL),
(12, '12', 1190082, NULL),
(123123, '123123', 1190082, 1),
(123, '123', 1190082, 1),
(145, '145', 2905, 1),
(1, '1', 1190082, 1),
(1, '1', 1190082, 1),
(3, '3', 2905, NULL),
(234234, '234234', 2905, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmucchucnang`
--

CREATE TABLE `danhmucchucnang` (
  `chucnang` varchar(50) NOT NULL,
  `tenchucnang` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dethi`
--

CREATE TABLE `dethi` (
  `made` int(11) NOT NULL,
  `monthi` int(11) DEFAULT NULL,
  `nguoitao` varchar(50) DEFAULT NULL,
  `tende` varchar(255) DEFAULT NULL,
  `thoigiantao` datetime DEFAULT current_timestamp(),
  `thoigianthi` int(11) DEFAULT NULL,
  `thoigianbatdau` datetime DEFAULT NULL,
  `thoigianketthuc` datetime DEFAULT NULL,
  `hienthibailam` tinyint(1) DEFAULT NULL,
  `xemdiemthi` tinyint(1) DEFAULT NULL,
  `xemdapan` tinyint(1) DEFAULT NULL,
  `troncauhoi` tinyint(1) DEFAULT NULL,
  `trondapan` tinyint(1) DEFAULT NULL,
  `nopbaichuyentab` tinyint(1) DEFAULT NULL,
  `loaide` int(11) DEFAULT NULL,
  `socaude` int(11) DEFAULT NULL,
  `socautb` int(11) DEFAULT NULL,
  `socaukho` int(11) DEFAULT NULL,
  `trangthai` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dethi`
--

INSERT INTO `dethi` (`made`, `monthi`, `nguoitao`, `tende`, `thoigiantao`, `thoigianthi`, `thoigianbatdau`, `thoigianketthuc`, `hienthibailam`, `xemdiemthi`, `xemdapan`, `troncauhoi`, `trondapan`, `nopbaichuyentab`, `loaide`, `socaude`, `socautb`, `socaukho`, `trangthai`) VALUES
(14, 1190082, 'mhanhgv', 'Gia Bảo', '2024-10-10 02:06:49', NULL, '2024-10-13 17:42:00', '2024-10-26 04:17:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4, 1, 0),
(15, 2905, 'mhanhgv', 'Gia Bảo', '2024-10-10 02:08:36', 56, '2024-10-13 18:30:00', '2024-10-19 02:47:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 3, 1),
(16, 2905, 'mhanhgv', 'Gia Bảo', '2024-10-10 02:08:42', 56, '2024-10-19 01:51:00', '2024-10-19 02:47:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 3, 1),
(17, 1190082, 'mhanhgv', 'Gia Bảo', '2024-10-10 02:08:53', 56, '2024-10-19 01:51:00', '2024-10-19 02:47:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4, 1, 1),
(18, 2905, 'mhanhgv', 'Gia Bảo', '2024-10-10 12:36:03', 45, '2024-10-20 12:35:00', '2024-10-20 13:20:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4, 4, 1),
(19, 2905, 'mhanhgv', 'Bài kiểm tra 1', '2024-10-11 09:50:44', 45, '2024-10-27 09:50:00', '2024-10-27 10:35:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, 2, 1),
(20, 2905, 'mhanhgv', 'Bài kiểm tra 2', '2024-10-11 09:54:49', 45, '2024-10-27 09:50:00', '2024-10-27 10:35:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, 5, 1),
(21, 2905, 'mhanhgv', 'Bài kiểm tra 1', '2024-10-12 13:09:03', 45, '2024-11-14 16:08:00', '2024-11-14 16:53:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, 5, 1),
(22, 2905, 'mhanhgv', 'Bài kiểm tra 3', '2024-10-12 13:17:13', 45, '2024-10-20 15:16:00', '2024-10-20 16:01:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, 5, 0),
(23, 2905, 'mhanhgv', 'Bài kiểm tra 5', '2024-10-13 18:44:30', 45, '2024-10-27 18:44:00', '2024-10-27 19:29:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, 5, 1),
(24, 2905, 'mhanhgv', 'Bài kiểm tra 21', '2024-10-15 00:24:12', 2700, '2024-10-27 03:27:00', '2024-10-27 04:12:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 12, 11, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dethitudong`
--

CREATE TABLE `dethitudong` (
  `made` int(11) NOT NULL,
  `machuong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giaodethi`
--

CREATE TABLE `giaodethi` (
  `made` int(11) NOT NULL,
  `manhom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ketqua`
--

CREATE TABLE `ketqua` (
  `makq` varchar(255) NOT NULL,
  `made` int(11) NOT NULL,
  `manguoidung` varchar(50) NOT NULL DEFAULT '',
  `diemthi` double DEFAULT NULL,
  `thoigianvaothi` datetime DEFAULT current_timestamp(),
  `thoigianlambai` int(11) DEFAULT NULL,
  `socaudung` int(11) DEFAULT NULL,
  `solanchuyentab` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `monhoc`
--

CREATE TABLE `monhoc` (
  `mamonhoc` int(11) NOT NULL,
  `tenmonhoc` varchar(255) NOT NULL,
  `sotinchi` int(11) DEFAULT NULL,
  `sotietlythuyet` int(11) DEFAULT NULL,
  `sotietthuchanh` int(11) DEFAULT NULL,
  `trangthai` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `monhoc`
--

INSERT INTO `monhoc` (`mamonhoc`, `tenmonhoc`, `sotinchi`, `sotietlythuyet`, `sotietthuchanh`, `trangthai`) VALUES
(2905, 'toán ứng dụng', 3, 45, 25, 1),
(1190082, 'toán ứng dụng 2', 3, 45, 15, 1);

--
-- Bẫy `monhoc`
--
DELIMITER $$
CREATE TRIGGER `after_monhoc_delete` AFTER DELETE ON `monhoc` FOR EACH ROW BEGIN
    DELETE FROM nhom WHERE mamonhoc = OLD.mamonhoc;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `email` varchar(255) NOT NULL,
  `id` varchar(50) NOT NULL,
  `googleid` varchar(150) DEFAULT NULL,
  `hoten` varchar(255) NOT NULL,
  `gioitinh` tinyint(1) DEFAULT NULL,
  `ngaysinh` date DEFAULT '1990-01-01',
  `avatar` varchar(255) DEFAULT NULL,
  `ngaythamgia` date NOT NULL DEFAULT current_timestamp(),
  `matkhau` varchar(60) DEFAULT NULL,
  `trangthai` int(11) NOT NULL,
  `sodienthoai` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `manhomquyen` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`email`, `id`, `googleid`, `hoten`, `gioitinh`, `ngaysinh`, `avatar`, `ngaythamgia`, `matkhau`, `trangthai`, `sodienthoai`, `token`, `otp`, `manhomquyen`) VALUES
('test1@gmail.com', '22dh1109234', NULL, 'test 1', 0, '1990-01-01', NULL, '2024-10-03', '$2y$10$xyBZNVIzsdZtiTRyh1J3TeDlY4GpZuHvpqoJDc3iB/sSpuWWVX0.C', 1, NULL, NULL, NULL, 10),
('newuser123@example.com', 'nnguoidung123', NULL, 'Nguyễn Văn B', 1, '2000-01-01', NULL, '2024-10-04', '$2y$10$967g20cRuYGW05p8P4AcpOB6go29VusQY7NTL4WCJyzts0Bo/t.Qa', 1, NULL, NULL, NULL, 11),
('newuser123@example.com', 'nnguoidung123', NULL, 'Nguyễn Văn B', 1, '2000-01-01', NULL, '2024-10-04', '$2y$10$NBgBL0u2VsjTKjMTbXjjPuHxtjg.Ke/YL0YdceVp54ARV7szJv4l2', 1, NULL, NULL, NULL, 11),
('newuser123@example.com', 'nnguoidung123', NULL, 'Nguyễn Văn B', 1, '2000-01-01', NULL, '2024-10-04', '$2y$10$yYhV5oYMf3gZe5IvghkwnuWT79FA2BVcl3mv9P20hto6EJVxHGo1G', 1, NULL, NULL, NULL, 1),
('newuser12@example.com', 'gbao290504', NULL, 'Nguyễn Văn B', 1, '2000-01-01', NULL, '2024-10-04', '$2y$10$r9fsNa.q.vzvkMNjCivOqOgxijKSnl5im2TAYBc9DcPFG6jq2Hzz.', 1, NULL, NULL, NULL, 1),
('hhoacute@example.com', 'gbao290504', NULL, 'Hồng Hoa', 0, '2000-01-01', NULL, '2024-10-04', '$2y$10$tP2wuBQakl3wri5W1pUOjeP.0zZxMZdUqtCF4N4kpPbTCHDMg3zA6', 1, NULL, NULL, NULL, 10),
('hhoacute@example.com', 'hhoacute', NULL, 'Hồng Hoa', 0, '2000-01-01', NULL, '2024-10-04', '$2y$10$zVlZ4fc0QReJ.62BV/rdjuYymA6WWcUxveTJMPD0jzdUssdYT9lLK', 1, NULL, NULL, NULL, 10),
('hhoacute@example.com', 'hhoacute', NULL, 'Hồng Hoa', 0, '2000-01-01', NULL, '2024-10-04', '$2y$10$t7IbfJKktyaRkmEDi18LW.sWv7Wz2YMTKr6wdAuVHGkrYlBx/1kNO', 1, NULL, NULL, NULL, 10),
('ttnnam@gmail.com', '22dh1177864', NULL, 'Tran Nguyen Nhat Nam', NULL, '1990-01-01', NULL, '2024-10-04', '$2y$10$u1F9zAEtvar/I7raebE7GO6qssfKeG5zR6iOta7TNG4yL4dzyLS.K', 1, NULL, NULL, NULL, 11),
('ntmhanh@gmail.com', 'mhanhgv', NULL, 'Nguyễn Thị Mỹ Hạnh', 0, '1990-01-01', NULL, '2024-10-04', '$2y$10$2zud3oPDEJ0QAf2gVubHSeHSVWh65fxwVjxPXulvM6cM2kycyKhKG', 1, NULL, NULL, NULL, 10),
('123124@example.com', '123123123', NULL, 'Hồng Hoa', 0, '2000-01-01', NULL, '2024-10-12', '$2y$10$4.kAjhwpc9prbt5nR6Rag.OkEkV8tybHFowYv1nH7iA1/HEzKWC.O', 1, NULL, NULL, NULL, 11);

--
-- Bẫy `nguoidung`
--
DELIMITER $$
CREATE TRIGGER `delete_chitietnhom_by_id` BEFORE DELETE ON `nguoidung` FOR EACH ROW DELETE FROM chitietnhom WHERE chitietnhom.manguoidung = OLD.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhom`
--

CREATE TABLE `nhom` (
  `manhom` int(11) NOT NULL,
  `tennhom` varchar(255) NOT NULL,
  `mamoi` varchar(50) DEFAULT NULL,
  `siso` int(11) DEFAULT 0,
  `ghichu` varchar(255) DEFAULT NULL,
  `namhoc` int(11) DEFAULT NULL,
  `hocky` int(11) DEFAULT NULL,
  `trangthai` tinyint(1) DEFAULT 1,
  `hienthi` tinyint(1) DEFAULT 1,
  `giangvien` varchar(50) NOT NULL DEFAULT '',
  `mamonhoc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhom`
--

INSERT INTO `nhom` (`manhom`, `tennhom`, `mamoi`, `siso`, `ghichu`, `namhoc`, `hocky`, `trangthai`, `hienthi`, `giangvien`, `mamonhoc`) VALUES
(29050424, 'Ca chiều thứ 2', NULL, 4, NULL, NULL, NULL, 1, 1, 'hhoacute', 444),
(252551, 'Toán Bổ Cấp ', NULL, 3, NULL, NULL, NULL, 1, 1, 'mhanhgv', 2905),
(35, 'TOÁN', NULL, 3, NULL, NULL, NULL, 1, 1, 'hhoacute', 1190082),
(29050424, 'Toán Áp Dụng 3', NULL, 4, NULL, NULL, NULL, 1, 1, 'hhoacute', 1190082),
(29050424, 'Toán Áp Dụng 6', NULL, 4, NULL, NULL, NULL, 1, 1, 'gbao290504', 1190082),
(123123123, 'Toán Cao Cấp 6', NULL, 1, NULL, NULL, NULL, 1, 1, 'hhoacute', 2905),
(124123, 'Toán Cao Cấp 123', NULL, 2, NULL, NULL, NULL, 1, 1, 'gbao290504', 2905),
(29050406, 'Toán Cao Cấp 123', NULL, 3, NULL, NULL, NULL, 1, 1, 'hhoacute', 1190082);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhomquyen`
--

CREATE TABLE `nhomquyen` (
  `manhomquyen` int(11) NOT NULL,
  `tennhomquyen` varchar(50) NOT NULL,
  `trangthai` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phancong`
--

CREATE TABLE `phancong` (
  `mamonhoc` int(11) NOT NULL,
  `manguoidung` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongbao`
--

CREATE TABLE `thongbao` (
  `matb` int(11) NOT NULL,
  `noidung` varchar(255) DEFAULT NULL,
  `thoigiantao` datetime DEFAULT NULL,
  `nguoitao` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cauhoi`
--
ALTER TABLE `cauhoi`
  ADD PRIMARY KEY (`macauhoi`),
  ADD KEY `FK_CAUHOI_NGUOIDUNG` (`nguoitao`),
  ADD KEY `FK_CAUHOI_CHUONG` (`machuong`),
  ADD KEY `FK_CAUHOI_MONHOC` (`mamonhoc`);

--
-- Chỉ mục cho bảng `cautraloi`
--
ALTER TABLE `cautraloi`
  ADD PRIMARY KEY (`macautl`),
  ADD KEY `FK_CAUTRALOI_CAUHOI` (`macauhoi`);

--
-- Chỉ mục cho bảng `chitietdethi`
--
ALTER TABLE `chitietdethi`
  ADD PRIMARY KEY (`made`,`macauhoi`),
  ADD KEY `FK_CHITIETDETHI_CAUHOI` (`macauhoi`);

--
-- Chỉ mục cho bảng `chitietketqua`
--
ALTER TABLE `chitietketqua`
  ADD PRIMARY KEY (`makq`,`macauhoi`),
  ADD KEY `FK_CHITIETKETQUA_CAUHOI` (`macauhoi`),
  ADD KEY `FK_CHITIETKETQUA_CAUTRALOI` (`dapanchon`);

--
-- Chỉ mục cho bảng `dethi`
--
ALTER TABLE `dethi`
  ADD PRIMARY KEY (`made`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cauhoi`
--
ALTER TABLE `cauhoi`
  MODIFY `macauhoi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT cho bảng `cautraloi`
--
ALTER TABLE `cautraloi`
  MODIFY `macautl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=273;

--
-- AUTO_INCREMENT cho bảng `dethi`
--
ALTER TABLE `dethi`
  MODIFY `made` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
