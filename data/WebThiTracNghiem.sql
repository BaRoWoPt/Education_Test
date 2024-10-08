-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th9 20, 2024 lúc 05:01 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE
= "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone
= "+00:00";


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

CREATE TABLE `cauhoi`
(
  `macauhoi` int
(11) NOT NULL,
  `noidung` varchar
(500) NOT NULL,
  `dokho` int
(11) NOT NULL,
  `mamonhoc` int
(11) NOT NULL,
  `machuong` int
(11) NOT NULL,
  `nguoitao` varchar
(50) DEFAULT NULL,
  `trangthai` int
(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cautraloi`
--

CREATE TABLE `cautraloi`
(
  `macautl` int
(11) NOT NULL,
  `macauhoi` int
(11) NOT NULL,
  `noidungtl` varchar
(500) NOT NULL,
  `ladapan` tinyint
(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------


--
-- Cấu trúc bảng cho bảng `chitietnhom`
--

CREATE TABLE `chitietnhom`
(
  `manhom` int
(11) NOT NULL,
  `manguoidung` varchar
(50) NOT NULL DEFAULT '0',
  `hienthi` tinyint
(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bẫy `chitietnhom`
--
DELIMITER $$
CREATE TRIGGER `update_group_participants_after_delete` AFTER
DELETE ON `chitietnhom` FOR EACH
ROW
UPDATE nhom
SET siso = (SELECT count(*)
FROM chitietnhom
WHERE manhom = OLD.manhom)
WHERE manhom = OLD.manhom
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_group_participants_after_insert` AFTER
INSERT ON `
chitietnhom`
FOR
EACH
ROW
UPDATE nhom
SET siso = (SELECT count(*)
FROM chitietnhom
WHERE manhom = NEW.manhom)
WHERE manhom = NEW.manhom
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietquyen`
--

CREATE TABLE `chitietquyen`
(
  `manhomquyen` int
(11) NOT NULL,
  `chucnang` varchar
(50) NOT NULL,
  `hanhdong` varchar
(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietthongbao`
--

CREATE TABLE `chitietthongbao`
(
  `matb` int
(11) NOT NULL,
  `manhom` int
(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuong`
--

CREATE TABLE `chuong`
(
  `machuong` int
(11) NOT NULL,
  `tenchuong` varchar
(255) NOT NULL,
  `mamonhoc` int
(11) NOT NULL,
  `trangthai` tinyint
(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmucchucnang`
--

CREATE TABLE `danhmucchucnang`
(
  `chucnang` varchar
(50) NOT NULL,
  `tenchucnang` varchar
(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dethi`
--

CREATE TABLE `dethi`
(
  `made` int
(11) NOT NULL,
  `monthi` int
(11) DEFAULT NULL,
  `nguoitao` varchar
(50) DEFAULT NULL,
  `tende` varchar
(255) DEFAULT NULL,
  `thoigiantao` datetime DEFAULT current_timestamp
(),
  `thoigianthi` int
(11) DEFAULT NULL,
  `thoigianbatdau` datetime DEFAULT NULL,
  `thoigianketthuc` datetime DEFAULT NULL,
  `hienthibailam` tinyint
(1) DEFAULT NULL,
  `xemdiemthi` tinyint
(1) DEFAULT NULL,
  `xemdapan` tinyint
(1) DEFAULT NULL,
  `troncauhoi` tinyint
(1) DEFAULT NULL,
  `trondapan` tinyint
(1) DEFAULT NULL,
  `nopbaichuyentab` tinyint
(1) DEFAULT NULL,
  `loaide` int
(11) DEFAULT NULL,
  `socaude` int
(11) DEFAULT NULL,
  `socautb` int
(11) DEFAULT NULL,
  `socaukho` int
(11) DEFAULT NULL,
  `trangthai` tinyint
(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Cấu trúc bảng cho bảng `chitietdethi`
--

CREATE TABLE `chitietdethi`
(
  `made` int
(11) NOT NULL,
  `macauhoi` int
(11) NOT NULL,
  `thutu` int
(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietketqua`
--

CREATE TABLE `chitietketqua`
(
  `makq` int
(11) NOT NULL,
  `macauhoi` int
(11) NOT NULL,
  `dapanchon` int
(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dethitudong`
--

CREATE TABLE `dethitudong`
(
  `made` int
(11) NOT NULL,
  `machuong` int
(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giaodethi`
--

CREATE TABLE `giaodethi`
(
  `made` int
(11) NOT NULL,
  `manhom` int
(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ketqua`
--

CREATE TABLE `ketqua`
(
  `makq` int
(11) NOT NULL,
  `made` int
(11) NOT NULL,
  `manguoidung` varchar
(50) NOT NULL DEFAULT '',
  `diemthi` double DEFAULT NULL,
  `thoigianvaothi` datetime DEFAULT current_timestamp
(),
  `thoigianlambai` int
(11) DEFAULT NULL,
  `socaudung` int
(11) DEFAULT NULL,
  `solanchuyentab` int
(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `monhoc`
--

CREATE TABLE `monhoc`
(
  `mamonhoc` int
(11) NOT NULL,
  `tenmonhoc` varchar
(255) NOT NULL,
  `sotinchi` int
(11) DEFAULT NULL,
  `sotietlythuyet` int
(11) DEFAULT NULL,
  `sotietthuchanh` int
(11) DEFAULT NULL,
  `trangthai` tinyint
(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung`
(
  `email` varchar
(255) NOT NULL,
  `id` varchar
(50) NOT NULL,
  `googleid` varchar
(150) DEFAULT NULL,
  `hoten` varchar
(255) NOT NULL,
  `gioitinh` tinyint
(1) DEFAULT NULL,
  `ngaysinh` date DEFAULT '1990-01-01',
  `avatar` varchar
(255) DEFAULT NULL,
  `ngaythamgia` date NOT NULL DEFAULT current_timestamp
(),
  `matkhau` varchar
(60) DEFAULT NULL,
  `trangthai` int
(11) NOT NULL,
  `sodienthoai` int
(11) DEFAULT NULL,
  `token` varchar
(255) DEFAULT NULL,
  `otp` varchar
(10) DEFAULT NULL,
  `manhomquyen` int
(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bẫy `nguoidung`
--
DELIMITER $$
CREATE TRIGGER `delete_chitietnhom_by_id` BEFORE
DELETE ON `nguoidung` FOR EACH
ROW
DELETE FROM chitietnhom WHERE chitietnhom.manguoidung = OLD.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhom`
--

CREATE TABLE `nhom`
(
  `manhom` int
(11) NOT NULL,
  `tennhom` varchar
(255) NOT NULL,
  `mamoi` varchar
(50) DEFAULT NULL,
  `siso` int
(11) DEFAULT 0,
  `ghichu` varchar
(255) DEFAULT NULL,
  `namhoc` int
(11) DEFAULT NULL,
  `hocky` int
(11) DEFAULT NULL,
  `trangthai` tinyint
(1) DEFAULT 1,
  `hienthi` tinyint
(1) DEFAULT 1,
  `giangvien` varchar
(50) NOT NULL DEFAULT '',
  `mamonhoc` int
(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhomquyen`
--

CREATE TABLE `nhomquyen`
(
  `manhomquyen` int
(11) NOT NULL,
  `tennhomquyen` varchar
(50) NOT NULL,
  `trangthai` tinyint
(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phancong`
--

CREATE TABLE `phancong`
(
  `mamonhoc` int
(11) NOT NULL,
  `manguoidung` varchar
(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongbao`
--

CREATE TABLE `thongbao`
(
  `matb` int
(11) NOT NULL,
  `noidung` varchar
(255) DEFAULT NULL,
  `thoigiantao` datetime DEFAULT NULL,
  `nguoitao` varchar
(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cauhoi`
--
ALTER TABLE `cauhoi`
ADD PRIMARY KEY
(`macauhoi`),
ADD KEY `FK_CAUHOI_NGUOIDUNG`
(`nguoitao`),
ADD KEY `FK_CAUHOI_CHUONG`
(`machuong`),
ADD KEY `FK_CAUHOI_MONHOC`
(`mamonhoc`);

--
-- Chỉ mục cho bảng `cautraloi`
--
ALTER TABLE `cautraloi`
ADD PRIMARY KEY
(`macautl`),
ADD KEY `FK_CAUTRALOI_CAUHOI`
(`macauhoi`);

--
-- Chỉ mục cho bảng `chitietdethi`
--
ALTER TABLE `chitietdethi`
ADD PRIMARY KEY
(`made`,`macauhoi`),
ADD KEY `FK_CHITIETDETHI_CAUHOI`
(`macauhoi`);

--
-- Chỉ mục cho bảng `chitietketqua`
--
ALTER TABLE `chitietketqua`
ADD PRIMARY KEY
(`makq`,`macauhoi`),
ADD KEY `FK_CHITIETKETQUA_CAUHOI`
(`macauhoi`),
ADD KEY `FK_CHITIETKETQUA_CAUTRALOI`
(`dapanchon`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
