-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.2
-- Время создания: Июн 25 2025 г., 02:18
-- Версия сервера: 8.2.0
-- Версия PHP: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kanban_board`
--

-- --------------------------------------------------------

--
-- Структура таблицы `chats`
--

CREATE TABLE `chats` (
  `id` int NOT NULL,
  `user1_id` int NOT NULL,
  `user2_id` int NOT NULL,
  `user_min_id` int GENERATED ALWAYS AS (least(`user1_id`,`user2_id`)) STORED,
  `user_max_id` int GENERATED ALWAYS AS (greatest(`user1_id`,`user2_id`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `chats`
--

INSERT INTO `chats` (`id`, `user1_id`, `user2_id`) VALUES
(1, 0, 1),
(2, 0, 2),
(3, 4, 2),
(4, 4, 5),
(5, 4, 3),
(6, 4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `chat_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `sender_id`, `message`, `created_at`) VALUES
(1, 6, 4, 'Fdfsfs', '2025-06-10 20:35:46'),
(2, 6, 4, 'sdfsfds', '2025-06-10 20:35:58'),
(3, 6, 1, 'fsdfsdf', '2025-06-10 20:37:23'),
(4, 6, 4, 'Привет?', '2025-06-10 20:40:05'),
(8, 6, 1, 'Привет', '2025-06-10 22:37:39'),
(9, 6, 4, 'Привет!', '2025-06-10 22:40:42'),
(11, 6, 1, 'Привет?', '2025-06-10 22:41:55'),
(13, 6, 1, 'Здравствуй!', '2025-06-10 22:44:35'),
(27, 6, 4, 'Как дела?', '2025-06-13 19:26:38'),
(28, 6, 4, 'АВАЫ', '2025-06-15 21:58:14');

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE `projects` (
  `id_project` int NOT NULL,
  `id_user` int NOT NULL,
  `project_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_description` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `projects`
--

INSERT INTO `projects` (`id_project`, `id_user`, `project_name`, `project_description`, `start_date`, `end_date`) VALUES
(162, 4, 'Воскресняя задача', 'мвфафа', '2025-06-01', '2025-06-27'),
(163, 4, 'Проект', '', '2024-04-30', '2024-06-09');

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE `tasks` (
  `id_task` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_project` int DEFAULT NULL,
  `task_status` int NOT NULL,
  `task_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_description` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `task_color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadline` date NOT NULL,
  `user_name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tasks`
--

INSERT INTO `tasks` (`id_task`, `id_user`, `id_project`, `task_status`, `task_name`, `task_description`, `task_color`, `deadline`, `user_name`) VALUES
(57, 17, 162, 2, 'Новый год', 'новый', '#5cb85c', '2025-06-18', 'eva'),
(63, 4, 162, 1, 'Воскресняя задача', 'Новая задача', '#f0ad4e', '2024-04-21', 'user'),
(65, 4, 162, 3, 'Регистрация', 'Описание задачи', '#f0ad4e', '2025-06-20', 'denis'),
(66, 4, 207, 1, 'Модуль регистрации пользователей', '', '#f0ad4e', '2024-04-20', 'denis'),
(67, 4, 207, 2, 'рнав', 'врп', '#5cb85c', '2025-06-28', 'eva'),
(69, 4, 163, 1, 'Обычная задача', 'Ничем не примечательная', '#5cb85c', '2025-06-26', 'danil'),
(125, 4, 162, 2, 'Задача Лучшая', 'Рабочее', '#f0ad4e', '2025-06-29', 'eva'),
(129, 1, 162, 3, '32кцук', '', '#d9534f', '2025-06-28', 'eva'),
(180, 4, 162, 1, 'Задача', '', '', '2025-06-18', 'eva');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `user`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$w1beYGOz6s7Kd8CPA.m9pualGIOSsugxtvLh84G2srcUfXo./.FTS', 'admin'),
(2, 'user', '$2y$10$m1j/x/4zA02XRtpU1Kia4u6BmosrhJi2eeTVUvcVBVHViRd01ElCy', 'user'),
(3, 'denis', '$2y$10$lSbL6BzPcWP2HM9RCHqDcerOXVHKbIVfxjFbqZDxNxt5xRldWYvnu', 'user'),
(4, 'danil', '$2y$10$cb3kQXFvh0kZC40wJbKKDuOHcRPMyHbbge8YZNjHYmXDbUvH8JnvO', 'admin'),
(5, 'eva', '$2y$10$ZciYiJOmh4D51iQkwkMjMusqmS.G5t0VL.mm9IYELrWge9dMIfsjq', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_chat` (`user_min_id`,`user_max_id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id_project`),
  ADD KEY `id_user` (`id_user`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id_task`),
  ADD KEY `tasks_id_user` (`id_user`),
  ADD KEY `tasks_id_project` (`id_project`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `user` (`user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id_project` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id_task` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
