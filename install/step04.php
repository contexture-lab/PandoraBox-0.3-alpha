<?php

if ($is_complite) {
	try {
		$sql = "CREATE TABLE `users` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR(255) NOT NULL, `role` VARCHAR(100) NOT NULL, `status` SMALLINT UNSIGNED NOT NULL, `password` VARCHAR(100) NOT NULL, `chpassw` VARCHAR(100) NOT NULL, `email` VARCHAR(255) NOT NULL, `phone` VARCHAR(20) NOT NULL, `fname` VARCHAR(255), `address` VARCHAR(255), `comment` TEXT, `latitude` VARCHAR(50) NOT NULL, `longitude` VARCHAR(50) NOT NULL, `accuracy` VARCHAR(50) NOT NULL, `lastactive` VARCHAR(25) NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;";
		R::exec($sql);

		$sql = "CREATE TABLE `docs` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR(255) NOT NULL, `status` SMALLINT UNSIGNED NOT NULL, `body` TEXT NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;";
		R::exec($sql);

		$sql = "CREATE TABLE `messages` (`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, `type` VARCHAR(10) NOT NULL, `from` INT UNSIGNED NOT NULL, `to` INT UNSIGNED NOT NULL, `status` SMALLINT UNSIGNED NOT NULL, `time` VARCHAR(25) NOT NULL, `body` TEXT NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;";
		R::exec($sql);

		$sql = "CREATE TABLE `targets` (`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR(255) NOT NULL, `status` SMALLINT UNSIGNED NOT NULL, `latitude` VARCHAR(50) NOT NULL, `longitude` VARCHAR(50) NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;";
		R::exec($sql);

		$sql = "CREATE TABLE `orders` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `type` VARCHAR(25) NOT NULL, `name` VARCHAR(255) NOT NULL, `status` SMALLINT UNSIGNED NOT NULL, `value` VARCHAR(255) NOT NULL, `body` TEXT NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;";
		R::exec($sql);

		$sql = "CREATE TABLE `tasks` (`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, `userid` INT UNSIGNED NOT NULL, `type` VARCHAR(25) NOT NULL, `orderid` INT UNSIGNED NOT NULL, `status` SMALLINT UNSIGNED NOT NULL, `body` TEXT NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;";
		R::exec($sql);

		$sql = "CREATE TABLE `history` (`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, `type` VARCHAR(25) NOT NULL, `operid` INT UNSIGNED NOT NULL, `userid` INT UNSIGNED NOT NULL, `table` VARCHAR(50) NOT NULL, `time` VARCHAR(25) NOT NULL, `body` TEXT NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;";
		R::exec($sql);

		echo '<li><i class="fa-li fa fa-check" style="color: green;"></i>Tables are created.</li>';
	} catch (Exception $e) {
		$is_complite = false;
		echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>Can not create database tables.</li>';
	}
} else echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>The operation can not be performed because the previous operation was not performed.</li>';

?>
