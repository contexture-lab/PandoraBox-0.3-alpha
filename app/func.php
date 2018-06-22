<?php
$protocol = "https://";

function cancelAllTasksForUser($idunit, $idoper) {
	$str1 = "(`userid` = ?) AND (`status` < 2)";
	$tasks = R::find("tasks", $str1, array($idunit));
	if ($tasks) {
		foreach ($tasks as $task) {
			$task->status = 3;
		}
		R::storeAll($tasks);
		writeLog("updstr", $idoper, 0, "tasks", $tasks);
	}
}

function writeTask($idcmd, $idunit, $targetsel, $idoper) {
	$order = R::load("orders", $idcmd);
	$task = R::dispense("tasks");
	$task->userid = $idunit;
	$task->type = $order->type;
	$task->orderid = $idcmd;
	$task->status = 1;
	if ($order->type == 'oneorder') {
		if ($targetsel == 0)
			$task->body = 'nobody;';
		else
			$task->body = 'target='.$targetsel.';';
	}
	else {
		$task->body = 'num=0;';
	}
	$ids = R::store($task);
	writeLog("addstr", $idoper, 0, "tasks", $task);
	return $order;
}

function writeMsgtoTask($order, $idunit) {
	if ($order->type == 'oneorder') {
		$ras = explode(";", $order->body);
		foreach ($ras as $vras) {
			$ras2 = explode("=", $vras);
			if ($ras2[0] == 'msgstart') {
				$msg = R::dispense('messages');
				$msg->type = ($idunit == 0) ? 'forall' : 'forunit';
				$msg->from = 0;
				$msg->to = $idunit;
				$msg->status = 0;
				$msg->time = time();
				$msg->body = $ras2[1];
				R::store($msg);
				writeLog("addstr", 0, 0, "messages", $msg);
			}
		}
	} else {
		$ras = explode(";", $order->body);
		foreach ($ras as $vras) {
			$ras2 = explode("=", $vras);
			if ($ras2[0] == 'chain') {
				$nums = str_replace(';', "", $ras2[1]);
				$ras3 = explode("+", $nums);
				$ras4 = explode("&", $ras3[0]);
				$suborder = R::load("orders", $ras4[0]);
				$ras5 = explode(";", $suborder->body);
				foreach ($ras5 as $vras2) {
					$ras6 = explode("=", $vras2);
					if ($ras6[0] == 'msgstart') {
						$msg = R::dispense('messages');
						$msg->type = ($idunit == 0) ? 'forall' : 'forunit';
						$msg->from = 0;
						$msg->to = $idunit;
						$msg->status = 0;
						$msg->time = time();
						$msg->body = $ras6[1];
						R::store($msg);
						writeLog("addstr", 0, 0, "messages", $msg);
					}
				}
			}
		}
	}
}

function Subord($unitrole, $myrole) {
	$fresult = false;
	$ind_ur = 1000;
	$ind_mr = 1000;
	$res1 = explode(";", $unitrole);
	foreach ($res1 as $vres1) {
		if (($vres1 == 'admin') and ($ind_ur > 1))
			$ind_ur = 1;
		elseif (($vres1 == 'officer') and ($ind_ur > 2))
			$ind_ur = 2;
		elseif (($vres1 == 'accaunter') and ($ind_ur > 3))
			$ind_ur = 3;
		elseif (($vres1 == 'unit') and ($ind_ur > 4))
			$ind_ur = 4;
		elseif (($vres1 == 'ghost') and ($ind_ur > 5))
			$ind_ur = 5;
	}
	$res2 = explode(";", $myrole);
	foreach ($res2 as $vres2) {
		if (($vres2 == 'admin') and ($ind_mr > 1))
			$ind_mr = 1;
		elseif (($vres2 == 'officer') and ($ind_mr > 2))
			$ind_mr = 2;
		elseif (($vres2 == 'accaunter') and ($ind_mr > 3))
			$ind_mr = 3;
		elseif (($vres2 == 'unit') and ($ind_mr > 4))
			$ind_mr = 4;
		elseif (($vres2 == 'ghost') and ($ind_mr > 5))
			$ind_mr = 5;
	}
	if ($ind_mr < $ind_ur) {
		$fresult = true;
	}
	elseif (($ind_mr == $ind_ur) and ($ind_mr == 1)) {
		$fresult = true;
	}
	return $fresult;
}

function bestRole($myrole) {
	$ind_mr = 1000;
	$res2 = explode(";", $myrole);
	foreach ($res2 as $vres2) {
		if (($vres2 == 'admin') and ($ind_mr > 1))
			$ind_mr = 1;
		elseif (($vres2 == 'officer') and ($ind_mr > 2))
			$ind_mr = 2;
		elseif (($vres2 == 'accaunter') and ($ind_mr > 3))
			$ind_mr = 3;
		elseif (($vres2 == 'unit') and ($ind_mr > 4))
			$ind_mr = 4;
		elseif (($vres2 == 'ghost') and ($ind_mr > 5))
			$ind_mr = 5;
	}
	return $ind_mr;
}

function safeSaveRole($numBestRole, $saveRole) {
	if ($numBestRole == 1) {
		return $saveRole;
	} elseif ($numBestRole == 2) {
		$saveRole = str_replace("admin;", "", $saveRole);
		return $saveRole;
	} elseif ($numBestRole == 3) {
		$saveRole = str_replace("admin;", "", $saveRole);
		$saveRole = str_replace("officer;", "", $saveRole);
		return $saveRole;
	} else {
		$saveRole = str_replace("admin;", "", $saveRole);
		$saveRole = str_replace("officer;", "", $saveRole);
		$saveRole = str_replace("accaunter;", "", $saveRole);
		return $saveRole;
	}
}

function getColorUnitCircle($baseAc, $unitAc) {
	$colorRet = '#00FF00';
	if ($baseAc <= $unitAc) {
		$colorRet = '#FFFF00';
		if (($unitAc / $baseAc) >= 10)
			$colorRet = '#FF8000';
		if (($unitAc / $baseAc) >= 50)
			$colorRet = '#FF4000';
		if (($unitAc / $baseAc) >= 250)
			$colorRet = '#FF0000';
	}
	return $colorRet;
}

function writeLog($typeS, $operidS, $useridS, $tableS, $bodyBeen) {
	$newbeen = R::dispense("history");
	$newbeen->type = $typeS;
	$newbeen->operid = $operidS;
	$newbeen->userid = $useridS;
	$newbeen->table = $tableS;
	$newbeen->time = time();
	$newbeen->body = json_encode($bodyBeen);
	R::store($newbeen);
}

function clearLog() {
	$countUser = R::count("users");
	$k1 = 500;
	$k2 = 1000;
	$k3 = 10;
	$k4 = 5000;
	$k5 = 200;
	$k6 = 200;
	$strQ1 = "`type` = 'signin'";
	$countQ1 = R::count("history", $strQ1);
	if ($countQ1 >= ($countUser*$k6)) {
		$strQ1 .= " LIMIT 0, ".(($countUser*$k6)/2);
		$bean1 = R::find("history", $strQ1);
		R::trashAll($bean1);
		unset($bean1);
	}
	$strQ2 = "`type` = 'signout'";
	$countQ2 = R::count("history", $strQ2);
	if ($countQ2 >= ($countUser*$k5)) {
		$strQ2 .= " LIMIT 0, ".(($countUser*$k5)/2);
		$bean2 = R::find("history", $strQ2);
		R::trashAll($bean2);
		unset($bean2);
	}
	$strQ3 = "`type` = 'updlla'";
	$countQ3 = R::count("history", $strQ3);
	if ($countQ3 >= ($countUser*$k4)) {
		$strQ3 .= " LIMIT 0, ".(($countUser*$k4)/2);
		$bean3 = R::find("history", $strQ3);
		R::trashAll($bean3);
		unset($bean3);
	}
	$strQ4 = "`type` = 'delstr'";
	$countQ4 = R::count("history", $strQ4);
	if ($countQ4 >= ($countUser*$k3)) {
		$strQ4 .= " LIMIT 0, ".(($countUser*$k3)/2);
		$bean4 = R::find("history", $strQ4);
		R::trashAll($bean4);
		unset($bean4);
	}
	$strQ5 = "`type` = 'updstr'";
	$countQ5 = R::count("history", $strQ5);
	if ($countQ5 >= ($countUser*$k2)) {
		$strQ5 .= " LIMIT 0, ".(($countUser*$k2)/2);
		$bean5 = R::find("history", $strQ5);
		R::trashAll($bean5);
		unset($bean5);
	}
	$strQ6 = "`type` = 'addstr'";
	$countQ6 = R::count("history", $strQ6);
	if ($countQ6 >= ($countUser*$k1)) {
		$strQ6 .= " LIMIT 0, ".(($countUser*$k1)/2);
		$bean6 = R::find("history", $strQ6);
		R::trashAll($bean6);
		unset($bean6);
	}
}
