<?php

if ($is_complite) {
	try {
		$sql = "INSERT INTO `users` (`id`, `name`, `role`, `status`, `password`, `chpassw`, `email`, `phone`, `fname`, `address`, `comment`, `latitude`, `longitude`, `accuracy`, `lastactive`) VALUES (NULL, 'admin', 'admin;', '1', '".sha1('admin')."', '".sha1('admin'.time())."', 'admin@', '0000', 'Real Name', 'Address', 'First Administrator', '0', '0', '0', '".time()."');";
		R::exec($sql);
		$sql = "INSERT INTO `users` (`id`, `name`, `role`, `status`, `password`, `chpassw`, `email`, `phone`, `fname`, `address`, `comment`, `latitude`, `longitude`, `accuracy`, `lastactive`) VALUES (NULL, 'officer', 'officer;', '1', '".sha1('officer')."', '".sha1('officer'.time())."', 'officer@', '0000', 'Real Name', 'Address', 'First Officer', '0', '0', '0', '".time()."');";
		R::exec($sql);
		$sql = "INSERT INTO `users` (`id`, `name`, `role`, `status`, `password`, `chpassw`, `email`, `phone`, `fname`, `address`, `comment`, `latitude`, `longitude`, `accuracy`, `lastactive`) VALUES (NULL, 'demo', 'admin;unit;', '1', '".sha1('demo')."', '".sha1('demo'.time())."', 'demo@', '0000', 'Real Name', 'Address', 'First mixed user', '0', '0', '0', '".time()."');";
		R::exec($sql);

		$sql = "INSERT INTO `docs` (`id`, `name`, `status`, `body`) VALUES (NULL, 'Start document', '1', 'The text of the starting document.');";
		R::exec($sql);

		$sql = "INSERT INTO `messages` (`id`, `type`, `from`, `to`, `status`, `time`, `body`) VALUES (NULL, 'forall', 0, 0, '1', '".time()."', 'First message for all.');";
		R::exec($sql);

		$sql = "INSERT INTO `targets` (`id`, `name`, `status`, `latitude`, `longitude`) VALUES (NULL, 'London', 0, '51.5057', '-0.1235');";
		R::exec($sql);
		$sql = "INSERT INTO `targets` (`id`, `name`, `status`, `latitude`, `longitude`) VALUES (NULL, 'New-York', 0, '40.7121', '-74.0072');";
		R::exec($sql);
		$sql = "INSERT INTO `targets` (`id`, `name`, `status`, `latitude`, `longitude`) VALUES (NULL, 'Berlin', 0, '52.5132', '13.3882');";
		R::exec($sql);
		$sql = "INSERT INTO `targets` (`id`, `name`, `status`, `latitude`, `longitude`) VALUES (NULL, 'Paris', 0, '48.8547', '2.3497');";
		R::exec($sql);
		$sql = "INSERT INTO `targets` (`id`, `name`, `status`, `latitude`, `longitude`) VALUES (NULL, 'Madrid', 0, '40.4150', '-3.6982');";
		R::exec($sql);
		$sql = "INSERT INTO `targets` (`id`, `name`, `status`, `latitude`, `longitude`) VALUES (NULL, 'Moscow', 0, '55.7518', '37.6175');";
		R::exec($sql);
		$sql = "INSERT INTO `targets` (`id`, `name`, `status`, `latitude`, `longitude`) VALUES (NULL, 'Hong-Kong', 0, '22.2760', '114.1675');";
		R::exec($sql);

		$sql = "INSERT INTO `orders` (`id`, `type`, `name`, `status`, `value`, `body`) VALUES (NULL, 'oneorder', 'Go to the goal', 0, 'price=2;discount=1;compensation=1;penalty=1;', 'typeord=one;target=yes;msgstart=Go to the specified goal;msgfinish=You have reached your destination;');";
		R::exec($sql);

		$sql = "INSERT INTO `tasks` (`id`, `userid`, `type`, `orderid`, `status`, `body`) VALUES (NULL, 3, 'oneorder', 1, 0, 'nobody;');";
		R::exec($sql);

		echo '<li><i class="fa-li fa fa-check" style="color: green;"></i>Startup users added.</li>';
	} catch (Exception $e) {
		$is_complite = false;
		echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>Failed to add startup users.</li>';
	}
} else echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>The operation can not be performed because the previous operation was not performed.</li>';

?>
