--
-- Индексы таблицы `users` делаем email уникальным
--
ALTER TABLE `users`
 ADD UNIQUE KEY `uniq_email` (`email`);
