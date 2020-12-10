-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-12-10 04:36:27
-- 服务器版本： 5.7.24
-- PHP 版本： 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `homepod`
--

-- --------------------------------------------------------

--
-- 表的结构 `wiki`
--

CREATE TABLE `wiki` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `contents` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `cate` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mt` date NOT NULL,
  `likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `wiki`
--

INSERT INTO `wiki` (`id`, `title`, `author`, `contents`, `date`, `cate`, `mt`, `likes`) VALUES
(1, '测试Wiki', 'youranreus', 'yo', '2020-10-18', 'default', '2020-10-18', 0),
(2, '总之就是十分离谱', 'youranreus', 'yo', '2020-10-18', 'default', '2020-10-18', 0),
(5, '总之就是十分离谱', 'youranreus', '', '0000-00-00', 'default', '0000-00-00', 0),
(6, '开发日志之字段名写错', 'youranreus', '还是我太笨了', '0000-00-00', 'default', '0000-00-00', 0),
(7, '开发日志之时间格式写错', 'youranreus', '还是我太笨了', '2020-10-19', 'default', '0000-00-00', 0);

--
-- 转储表的索引
--

--
-- 表的索引 `wiki`
--
ALTER TABLE `wiki`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `wiki`
--
ALTER TABLE `wiki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
