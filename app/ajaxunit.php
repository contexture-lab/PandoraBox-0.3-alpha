<?php

if ($unit == "load_msgs") {
	if (!isset($value)) $value = 10;
	$str1 = "(`type` = 'forall' AND `to` = 0) OR (`type` = 'foroper' AND `from` = ?) OR (`type` = 'forunit' AND `to` = ?)";
	$startval = R::count("messages", $str1, array($user->id, $user->id));
	$startval = ($startval > $value) ? $startval - $value : 0;
	$str1 .= " LIMIT ".$startval.", ".$value;
	$msgs = R::find("messages", $str1, array($user->id, $user->id));
	foreach ($msgs as $msg) {
		if ($msg->status != 2) {
			if ($msg->from == $user->id) {
				echo '<div class="unitmsg">';
				echo $msg->body;
				echo '</div>';
			} elseif ($msg->from == 0) {
				echo '<div class="cmdmsg">';
				echo $msg->body;
				echo '</div>';
			} else {
				echo '<div class="opermsg">';
				echo $msg->body;
				echo '</div>';
			}
		}
	}
} elseif ($unit == "send_msg") {
	if (isset($textmsg)) {
		if (!empty($textmsg)) {
			$msg = R::dispense('messages');
			$msg->type = 'foroper';
			$msg->from = $user->id;
			$msg->to = 0;
			$msg->status = 0;
			$msg->time = time();
			$msg->body = $textmsg;
			R::store($msg);
			writeLog("addstr", $user->id, 0, "messages", $msg);
		}
	}
} elseif ($unit == "list_docs") {
	$docs = R::findAll('docs');
	if ($docs) {
		echo '<table class="table"><tbody>';
		foreach ($docs as $doc) {
			if ($doc->status == 1) {
				echo "<tr><td>";
				echo $doc->name;
				echo "</td><td>";
				echo '<button onclick="pbOpenInfo('.$doc->id.')" class="btn btn-default2 pull-right" aria-hidden="true"><i class="fa fa-eye" aria-hidden="true"></i></button>';
				echo "</td></tr>";
			}
		}
		echo "</tbody></table>";
	} else {
		echo $locale['there_is_no_information_material'];
	}
} elseif ($unit == "load_doc_name") {
	if (isset($value)) {
		if (!empty($value)) {
			$doc = R::load('docs', $value);
			if ($doc) {
				echo '<button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>';
            echo '<h4 class="modal-title"> &nbsp; <i class="fa fa-file-text-o" aria-hidden="true"></i>';
				echo " ".$doc->name;
				echo "</h4>";
			}
		}
	}
} elseif ($unit == "load_doc_body") {
	if (isset($value)) {
		if (!empty($value)) {
			$doc = R::load('docs', $value);
			if ($doc) {
				echo $doc->body;
			}
		}
	}
} elseif ($unit == "load_tasks_json") {
	$allResultArr = [];
	$value = 10;
	$str1 = "`userid` = ? OR `userid` = 0";
	$startval = R::count("tasks", $str1, array($user->id));
	$startval = ($startval > $value) ? $startval - $value : 0;
	$str1 .= " LIMIT ".$startval.", ".$value;
	$tasks = R::find('tasks', $str1, array($user->id));
	if ($tasks) {
		$allResultArr['result'] = true;
		$allResultArr['ulat'] = $user->latitude;
		$allResultArr['ulng'] = $user->longitude;
		$allResultArr['uaccu'] = $user->accuracy;
		$i1 = 0;
		foreach ($tasks as $task) {
			$taskResultArr = [];
			$i1++;
			$order = R::load('orders', $task->orderid);
			$nosep = explode(";", $order->body);
			foreach ($nosep as $arg) {
				$ras = explode("=", $arg);
				if ($ras[0] == "typeord") {
					$typeord = $ras[1];
				} elseif ($ras[0] == "target") {
					$target = $ras[1];
				} elseif ($ras[0] == "msgstart") {
					$msgstart = $ras[1];
				} elseif ($ras[0] == "msgfinish") {
					$msgfinish = $ras[1];
				} elseif ($ras[0] == "typechain") {
					$typechain = $ras[1];
				} elseif ($ras[0] == "chain") {
					$chain = $ras[1];
				}
				unset($ras);
			}

			if ($order->type == "oneorder") {
				$taskResultArr['taskid'] = $task->id;
				$taskResultArr['taskstatus'] = $task->status;
				$taskResultArr['msgstart'] = $msgstart;
				$taskResultArr['msgfinish'] = ' ';
				$taskResultArr['target'] = false;
				$taskResultArr['targetname'] = ' ';
				$taskResultArr['targetlat'] = ' ';
				$taskResultArr['targetlng'] = ' ';
				if ($task->body != 'nobody;') {
					$tbres1 = explode(";", $task->body);
					$tbres2 = explode("=", $tbres1[0]);
					if ($tbres2[0] == 'target') {
						$targetbean = R::load("targets", $tbres2[1]);
						$taskResultArr['target'] = true;
						$taskResultArr['targetname'] = $targetbean->name;
						$taskResultArr['targetlat'] = $targetbean->latitude;
						$taskResultArr['targetlng'] = $targetbean->longitude;
					}
				}
				if ($task->status > 1) {
					$taskResultArr['msgfinish'] = $msgfinish;
				}
			} elseif ($order->type == "chainorders") {
				$taskResultArr['taskid'] = $task->id;
				$taskResultArr['taskstatus'] = $task->status;
				$taskResultArr['msgstart'] = ' ';
				$taskResultArr['msgfinish'] = ' ';
				$taskResultArr['target'] = false;
				$taskResultArr['targetname'] = ' ';
				$taskResultArr['targetlat'] = ' ';
				$taskResultArr['targetlng'] = ' ';
				$chain_block = explode("+", $chain);
				$numbody = str_replace(';', "", $task->body);
				$flnum = explode("=", $numbody);
				$num = ($flnum[0] == 'num') ? $flnum[1] : 0;
				$chain_ids = explode("&", $chain_block[$num]);
				$suborder = R::load("orders", $chain_ids[0]);
				$subbody = explode(";", $suborder->body);
				foreach ($subbody as $subdata) {
					$subfl = explode("=", $subdata);
					if ($subfl[0] == 'msgstart') $taskResultArr['msgstart'] = $subfl[1];
					if ($subfl[0] == 'msgfinish') $taskResultArr['msgfinish'] = $subfl[1];
				}
				if (isset($chain_ids[1])) {
					$targetbean = R::load("targets", $chain_ids[1]);
					$taskResultArr['target'] = true;
					$taskResultArr['targetname'] = $targetbean->name;
					$taskResultArr['targetlat'] = $targetbean->latitude;
					$taskResultArr['targetlng'] = $targetbean->longitude;
				}
			}
			$allResultArr['tasks'][] = $taskResultArr;
			unset($taskResultArr);
		}
	} else {
		$allResultArr['result'] = false;
	}
	echo json_encode($allResultArr);
} elseif ($unit == "service_query_json") {
	$response = [];
	$response['host'] = $_SERVER['HTTP_HOST'];
	$response['securecode'] = $securecode;
	$response['timestamp'] = time();
	$response['label'] = $pblabel;
	$response['fldata'] = false;
	if ($query == 'label') {
		$response['label'] = $pblabel;
	} elseif ($query == 'all') {
		$respData = R::findAll($table);
		if ($respData) {
			$response['fldata'] = true;
			$response['data'] = $respData;
		}
	} else {
		$respData = R::find($table, $query);
		if ($respData) {
			$response['fldata'] = true;
			$response['data'] = $respData;
		}
	}
	echo json_encode($response);
} elseif ($unit == "save_report") {
	$isOneOrd = false;
	if (isset($value)) {
		if (!empty($value)) {
			if (isset($msgreport)) {
				if (!empty($msgreport)) {
					$msg = R::dispense('messages');
					$msg->type = 'foroper';
					$msg->from = $user->id;
					$msg->to = 0;
					$msg->status = 0;
					$msg->time = time();
					$msg->body = '<b>'.$locale['report'].':</b> '.$msgreport;
					R::store($msg);
					writeLog("addstr", $user->id, 0, "messages", $msg);
					echo $locale['the_report_has_been_sent'].'<br>';
					unset($msg);
				}
			}
			$reptask = R::load("tasks", $value);
			if ($reptask->type == 'oneorder') {
				$order = R::load("orders", $reptask->orderid);
				$ordres1 = explode(";", $order->body);
				foreach ($ordres1 as $vordres1) {
					$ordres2 = explode("=", $vordres1);
					if ($ordres2[0] == 'typeord') {
						if ($ordres2[1] == 'one') {
							$isOneOrd = true;
							$reptask->status = 2;
							R::store($reptask);
							writeLog("updstr", $user->id, 0, "tasks", $reptask);
						} else {
							$msg = R::dispense('messages');
							$msg->type = 'forunit';
							$msg->from = 0;
							$msg->to = $user->id;
							$msg->status = 0;
							$msg->time = time();
							$msg->body = $locale['continue_with_the_task'];
							R::store($msg);
							writeLog("addstr", $user->id, 0, "messages", $msg);
							echo $locale['continue_with_the_task'];
							unset($msg);
						}
					}
					if (($ordres2[0] == 'msgfinish') and ($isOneOrd == true)) {
						$msg = R::dispense('messages');
						$msg->type = 'forunit';
						$msg->from = 0;
						$msg->to = $user->id;
						$msg->status = 0;
						$msg->time = time();
						$msg->body = $ordres2[1];
						R::store($msg);
						writeLog("addstr", $user->id, 0, "messages", $msg);
						echo $ordres2[1];
						unset($msg);
					}
				}
			} else {
				$res1 = str_replace(";", "", $reptask->body);
				$res2 = explode("=", $res1);
				if ($res2[0] == 'num') {
					$tasknum = $res2[1];
				}
				unset($res1);
				unset($res2);
				$order = R::load("orders", $reptask->orderid);
				$ordres1 = explode(";", $order->body);
				foreach ($ordres1 as $vordres1) {
					$ordres2 = explode("=", $vordres1);
					if ($ordres2[0] == 'typechain') {
						$typechain = $ordres2[1];
					}
					if ($ordres2[0] == 'chain') {
						$chainord = $ordres2[1];
					}
				}
				$res3 = explode("+", $chainord);
				$maxord = count($res3) - 1;
				$workblock = $res3[$tasknum];
				$res4 = explode("&", $workblock);
				$suborder = R::load("orders", $res4[0]);
				if (isset($res4[1])) {
					$target = R::load("targets", $res4[1]);
				}
				if ($typechain == 'one') {
					if ($tasknum < $maxord) {
						$reptask->body = 'num='.($tasknum+1).';';
						R::store($reptask);
						writeLog("updstr", $user->id, 0, "tasks", $reptask);
						$subordres1 = explode(";", $suborder->body);
						foreach ($subordres1 as $vsubordres1) {
							$subordres2 = explode("=", $vsubordres1);
							if ($subordres2[0] == 'msgfinish') {
								$msg = R::dispense('messages');
								$msg->type = 'forunit';
								$msg->from = 0;
								$msg->to = $user->id;
								$msg->status = 0;
								$msg->time = time();
								$msg->body = $subordres2[1];
								R::store($msg);
								writeLog("addstr", $user->id, 0, "messages", $msg);
								echo $subordres2[1].'<br>';
								unset($msg);
							}
						}
						$workblock2 = $res3[$tasknum+1];
						$res4 = explode("&", $workblock2);
						$suborder2 = R::load("orders", $res4[0]);
						$subordres3 = explode(";", $suborder2->body);
						foreach ($subordres3 as $vsubordres3) {
							$subordres4 = explode("=", $vsubordres3);
							if ($subordres4[0] == 'msgstart') {
								$msg = R::dispense('messages');
								$msg->type = 'forunit';
								$msg->from = 0;
								$msg->to = $user->id;
								$msg->status = 0;
								$msg->time = time();
								$msg->body = $subordres4[1];
								R::store($msg);
								writeLog("addstr", $user->id, 0, "messages", $msg);
								echo $subordres4[1].'<br>';
								unset($msg);
							}
						}
					} else {
						$reptask->status = 2;
						R::store($reptask);
						writeLog("updstr", $user->id, 0, "tasks", $reptask);
						$subordres1 = explode(";", $suborder->body);
						foreach ($subordres1 as $vsubordres1) {
							$subordres2 = explode("=", $vsubordres1);
							if ($subordres2[0] == 'msgfinish') {
								$msg = R::dispense('messages');
								$msg->type = 'forunit';
								$msg->from = 0;
								$msg->to = $user->id;
								$msg->status = 0;
								$msg->time = time();
								$msg->body = $subordres2[1];
								R::store($msg);
								writeLog("addstr", $user->id, 0, "messages", $msg);
								echo $subordres2[1].'<br>';
								unset($msg);
							}
						}
						$msg = R::dispense('messages');
						$msg->type = 'forunit';
						$msg->from = 0;
						$msg->to = $user->id;
						$msg->status = 0;
						$msg->time = time();
						$msg->body = $locale['wait_for_the_operators_instructions'];
						R::store($msg);
						writeLog("addstr", $user->id, 0, "messages", $msg);
						unset($msg);
						echo $locale['wait_for_the_operators_instructions'];
					}
				} else {
					if ($tasknum < $maxord) {
						$reptask->body = 'num='.($tasknum+1).';';
						R::store($reptask);
						writeLog("updstr", $user->id, 0, "tasks", $reptask);
						$subordres1 = explode(";", $suborder->body);
						foreach ($subordres1 as $vsubordres1) {
							$subordres2 = explode("=", $vsubordres1);
							if ($subordres2[0] == 'msgfinish') {
								$msg = R::dispense('messages');
								$msg->type = 'forunit';
								$msg->from = 0;
								$msg->to = $user->id;
								$msg->status = 0;
								$msg->time = time();
								$msg->body = $subordres2[1];
								R::store($msg);
								writeLog("addstr", $user->id, 0, "messages", $msg);
								echo $subordres2[1].'<br>';
								unset($msg);
							}
						}
						$workblock2 = $res3[$tasknum+1];
						$res4 = explode("&", $workblock2);
						$suborder2 = R::load("orders", $res4[0]);
						$subordres3 = explode(";", $suborder2->body);
						foreach ($subordres3 as $vsubordres3) {
							$subordres4 = explode("=", $vsubordres3);
							if ($subordres4[0] == 'msgstart') {
								$msg = R::dispense('messages');
								$msg->type = 'forunit';
								$msg->from = 0;
								$msg->to = $user->id;
								$msg->status = 0;
								$msg->time = time();
								$msg->body = $subordres4[1];
								R::store($msg);
								writeLog("addstr", $user->id, 0, "messages", $msg);
								echo $subordres4[1].'<br>';
								unset($msg);
							}
						}
					} else {
						$newind = 0;
						$reptask->body = 'num='.$newind.';';
						R::store($reptask);
						writeLog("updstr", $user->id, 0, "tasks", $reptask);
						$subordres1 = explode(";", $suborder->body);
						foreach ($subordres1 as $vsubordres1) {
							$subordres2 = explode("=", $vsubordres1);
							if ($subordres2[0] == 'msgfinish') {
								$msg = R::dispense('messages');
								$msg->type = 'forunit';
								$msg->from = 0;
								$msg->to = $user->id;
								$msg->status = 0;
								$msg->time = time();
								$msg->body = $subordres2[1];
								R::store($msg);
								writeLog("addstr", $user->id, 0, "messages", $msg);
								echo $subordres2[1].'<br>';
								unset($msg);
							}
						}
						$workblock2 = $res3[$newind];
						$res4 = explode("&", $workblock2);
						$suborder2 = R::load("orders", $res4[0]);
						$subordres3 = explode(";", $suborder2->body);
						foreach ($subordres3 as $vsubordres3) {
							$subordres4 = explode("=", $vsubordres3);
							if ($subordres4[0] == 'msgstart') {
								$msg = R::dispense('messages');
								$msg->type = 'forunit';
								$msg->from = 0;
								$msg->to = $user->id;
								$msg->status = 0;
								$msg->time = time();
								$msg->body = $subordres4[1];
								R::store($msg);
								writeLog("addstr", $user->id, 0, "messages", $msg);
								echo $subordres4[1].'<br>';
								unset($msg);
							}
						}
					}
				}
			}
		}
	}
} elseif ($unit == "load_sets") {
	$outstr = '<div class="panel panel-default">
                        <div class="panel-body">
                            <i class="fa fa-user fa-2x" title="'.$locale['login'].'"></i>
                            &nbsp;';
    $outstr .= $user->name;
    $outstr .= '		</div>
                </div>';

    $outstr .= '<div class="panel panel-default">
                        <div class="panel-body">
                            <i class="fa fa-universal-access fa-2x" title="'.$locale['role'].'"></i>
                            &nbsp;';
	$nosep = explode(";", $user->role);
	foreach ($nosep as $arg) {
		if ($arg == 'admin') $outstr .= $locale['role_administrator'].'; ';
		elseif ($arg == 'officer') $outstr .= $locale['role_officer'].'; ';
		elseif ($arg == 'accaunter') $outstr .= $locale['role_accountant'].'; ';
		elseif ($arg == 'unit') $outstr .= $locale['role_executor'].'; ';
		elseif ($arg == 'ghost') $outstr .= $locale['role_ghost'].'; ';
	}
    $outstr .= '        </div>
                </div>';

    $outstr .= '<div class="form-group input-group">
                           <span class="input-group-addon">
                                <i class="fa fa-key fa-2x" title="'.$locale['password'].'" style="padding-left: 1px; padding-right: 1px;"></i>
                            </span>
                            <input name="password" id="idpassword" type="password" class="form-control" placeholder="'.$locale['password'].'" required pattern="^[a-zA-Z0-9]+$" disabled>
                            <span class="input-group-btn">
                                <button id="idPassbutton" onclick="togglePassEdit()" class="btn btn-default3"><i class="fa fa-pencil fa-2x" aria-hidden="true"></i></a>
                            </span>
                </div>';

    $outstr .= '<br>';

    $outstr .= '<div class="form-group input-group">
                           <span class="input-group-addon">
                                <i class="fa fa-at fa-2x" title="'.$locale['email'].'" style="padding-left: 3px; padding-right: 3px;"></i>
                            </span>';
	$outstr .= '	<input name="email" id="idemail" type="email" class="form-control" value="'.$user->email.'" placeholder="'.$locale['email'].'">';
	$outstr .= '</div>';

	$outstr .= '<div class="form-group input-group">
                           <span class="input-group-addon">
                                <i class="fa fa-phone fa-2x" title="'.$locale['phone'].'" style="padding-left: 4px; padding-right: 4px;"></i>
                            </span>';
	$outstr .= '	<input name="phone" id="idphone" type="text" class="form-control" value="'.$user->phone.'">';
    $outstr .= '</div>';
    $outstr .= '<div class="form-group input-group">
                           <span class="input-group-addon">
                                <i class="fa fa-address-book fa-2x" title="'.$locale['full_name'].'" style="padding-left: 2px; padding-right: 2px;"></i>
                            </span>';
	$outstr .= '	<input name="fname" id="idfname" type="text" class="form-control" value="'.$user->fname.'">';
	$outstr .= '</div>';

	$outstr .= '<div class="form-group input-group">
                           <span class="input-group-addon">
                                <i class="fa fa-road fa-2x" title="'.$locale['address'].'"></i>
                            </span>';
	$outstr .= '	<input name="address" id="idaddress" type="text" class="form-control" value="'.$user->address.'">';
	$outstr .= '</div>';

	$outstr .= '<div class="form-group input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-commenting fa-2x" title="'.$locale['сomments'].'" style="padding-left: 1px; padding-right: 1px;"></i>
                            </span>';
	$outstr .= '	<textarea id="idcomment" class="form-control" rows="5">'.$user->comment.'</textarea>';
	$outstr .= '</div>';

    echo $outstr;
} elseif ($unit == "save_sets") {
	$outstr = $locale['the_data_is_saved']." <br> ";
	$upduser = R::load('users', $user->id);
	if (isset($fidpassword)) {
		if (!empty($fidpassword)) {
			$upduser->password = sha1($fidpassword);
			$outstr .= $locale['the_password_has_changed'];
		} else
			$outstr .= $locale['the_password_remains_the_same'];
	} else
		$outstr .= $locale['the_password_remains_the_same'];
	if (isset($fidemail)) {
		if (!empty($fidemail)) {
			$upduser->email = $fidemail;
		}
	}
	if (isset($fidphone)) {
		$upduser->phone = $fidphone;
	}
	if (isset($fidfname)) {
		$upduser->fname = $fidfname;
	}
	if (isset($fidaddress)) {
		$upduser->address = $fidaddress;
	}
	if (isset($fidcomment)) {
		$upduser->comment = $fidcomment;
	}
	$upduser->lastactive = time();
	R::store($upduser);
	writeLog("updstr", $user->id, $user->id, "users", $upduser);
	$user = $upduser;
	unset($_SESSION['logged_user']);
	$_SESSION['logged_user'] = $user;
	echo $outstr;
} elseif ($unit == "my_flags") {
	echo 'iunit.removeFrom(map);';
	echo 'itarget.removeFrom(map);';
	echo 'ipathline.removeFrom(map);';
	echo 'iunit = addUnit({lat: '.$user->latitude.', lng: '.$user->longitude.'}, map, "'.$user->name.'", '.$user->id.');';
	$task = R::findOne("tasks", "(`userid` = ?) AND (`status` < 2)", array($user->id));
	if ($task) {
		if ($task->type == 'oneorder') {
			$res = explode(";", $task->body);
			foreach ($res as $vres) {
				$res2 = explode("=", $vres);
				if ($res2[0] == 'target') {
					$target = R::load("targets", $res2[1]);
					if ($target) {
						echo 'itarget = addTargetU({lat: '.$target->latitude.', lng: '.$target->longitude.'}, map, "'.$target->name.'", '.$target->id.');';
						echo 'ipathline = addLineUT({lat: '.$user->latitude.', lng: '.$user->longitude.'}, {lat: '.$target->latitude.', lng: '.$target->longitude.'}, map);';
					}
				}
			}
		} else {
			$res1 = str_replace(";", "", $task->body);
			$res2 = explode("=", $res1);
			if ($res2[0] == 'num') {
				$tasknum = $res2[1];
			}
			unset($res1);
			unset($res2);
			$order = R::findOne("orders", "`id` = ?", array($task->orderid));
			if ($order) {
				$res1 = explode(";", $order->body);
				foreach ($res1 as $vres1) {
					$res2 = explode("=", $vres1);
					if ($res2[0] == 'chain') {
						$chainord = $res2[1];
					}
				}
				$res3 = explode("+", $chainord);
				$workblock = $res3[$tasknum];
				$res4 = explode("&", $workblock);
				if (isset($res4[1])) {
					$target = R::load("targets", $res4[1]);
					if ($target) {
						echo 'itarget.removeFrom(map);';
						echo 'itarget = addTargetU({lat: '.$target->latitude.', lng: '.$target->longitude.'}, map, "'.$target->name.'", '.$target->id.');';
						echo 'ipathline.removeFrom(map);';
						echo 'ipathline = addLineUT({lat: '.$user->latitude.', lng: '.$user->longitude.'}, {lat: '.$target->latitude.', lng: '.$target->longitude.'}, map);';
					}
				}
			}
		}
	}
	echo 'map.panTo({lat: '.$user->latitude.', lng: '.$user->longitude.'});';
} else {
	echo $locale['unknown_command'];
}
