-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Фев 28 2019 г., 21:13
-- Версия сервера: 10.3.11-MariaDB
-- Версия PHP: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yamap`
--

-- --------------------------------------------------------

--
-- Структура таблицы `savedpoints`
--

CREATE TABLE `savedpoints` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `savedpoints`
--

INSERT INTO `savedpoints` (`id`, `name`, `latitude`, `longitude`, `timestamp`) VALUES
(10, 'ÐÐ¸Ð¶Ð½Ð¸Ðµ ÐŸÑ‹ÑˆÐ¼Ñ‹', '28.028730', '48.073254', '2019-02-27 20:15:47'),
(14, 'Ð—Ð°ÑÑ€Ð°Ð½ÑÐº', '45.183938', '54.187433', '2019-02-27 20:28:48'),
(15, 'ÐŸÐ°Ñ€Ñ‹Ð¶', '2.351556', '48.856663', '2019-02-27 20:41:59'),
(29, 'Ð¢Ð¾Ð»ÑŒÑÑ‚Ñ‚Ð¸', '49.419207', '53.508816', '2019-02-28 21:10:39'),
(30, 'Ð¡Ð°Ð¼Ð°Ñ€Ð°', '50.101783', '53.195538', '2019-02-28 21:10:44'),
(31, 'ÐœÐ¾ÑÐºÐ²Ð°', '37.622504', '55.753215', '2019-02-28 21:10:50'),
(32, 'Ð¡Ð°Ð½ÐºÑ‚-ÐŸÐµÑ‚ÐµÑ€Ð±ÑƒÑ€Ð³', '30.315868', '59.939095', '2019-02-28 21:10:57'),
(33, 'ÐšÐ¾ÑÑ‚Ñ€Ð¾Ð¼Ð°', '40.926858', '57.767961', '2019-02-28 21:11:23'),
(34, 'ÐÑƒÐ»', '141.329545', '65.624155', '2019-02-28 21:11:40'),
(35, 'Ð¢Ð¾ÐºÐ¸Ð¾', '139.753137', '35.682272', '2019-02-28 21:11:56');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `savedpoints`
--
ALTER TABLE `savedpoints`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `savedpoints`
--
ALTER TABLE `savedpoints`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
