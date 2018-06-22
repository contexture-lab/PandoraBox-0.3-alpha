<?php

if ($oper == "list_docs") {
	$docs = R::findAll('docs');
	if ($docs) {
		foreach ($docs as $doc) {
			if ($doc->status == 1) {
				echo '<div class="panel panel-default">
	                        <div class="panel-body">
	                            &nbsp;
	                            <i class="fa fa-file-text-o" aria-hidden="true"></i> ';
				echo $doc->name;
				echo ' &nbsp; ';
				echo '			<button onclick="pbEditInfoOper('.$doc->id.')" class="btn btn-default2"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
				echo '			<button onclick="pbOpenInfoOper('.$doc->id.')" class="btn btn-default2"><i class="fa fa-search" aria-hidden="true"></i></button>';
				echo '			<button onclick="pbTrashInfo('.$doc->id.')" class="btn btn-default2 pull-right"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				echo '		</div>
	                  </div>';
            }
		}
	} else {
		echo $locale['there_is_no_information_material'];
	}
} elseif ($oper == "load_doc_name") {
	if (isset($value)) {
		if (!empty($value)) {
			$doc = R::load('docs', $value);
			if ($doc) {
				echo '<button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>';
            	echo '<h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-search" aria-hidden="true"></i>';
				echo " ".$doc->name;
				echo '&nbsp; <button onclick="pbEditInfoOper('.$doc->id.')" class="btn btn-default2"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
				echo "</h4>";
			}
		}
	}
} elseif ($oper == "load_doc_body") {
	if (isset($value)) {
		if (!empty($value)) {
			$doc = R::load('docs', $value);
			if ($doc) {
				echo $doc->body;
			}
		}
	}
} elseif ($oper == "load_doc_name_edit") {
	if (isset($value)) {
		if (!empty($value)) {
			$doc = R::load('docs', $value);
			if ($doc) {
				echo '<button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>';
            	echo '<h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-pencil" aria-hidden="true"></i> &nbsp;';
                echo '<input type="text" id="idDocTextHeader" class="form-control" style="display: inline; width:90%;" value="'.$doc->name.'" placeholder="'.$locale['file_name'].'">';
				echo "</h4>";
			}
		}
	}
} elseif ($oper == "load_doc_body_edit") {
	if (isset($value)) {
		if (!empty($value)) {
			$doc = R::load('docs', $value);
			if ($doc) {
				echo '<div class="form-group input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-file-text-o fa-2x"></i>
                            </span>
                            <textarea id="idDocTextBody" class="form-control" rows="23">';
				echo $doc->body;
				echo '</textarea>
					  </div>';
			}
		}
	}
} elseif ($oper == "trash_doc") {
	if (isset($value)) {
		if (!empty($value)) {
			$doct = R::load('docs', $value);
			$doct->status = 2;
			R::store($doct);
			writeLog("updstr", $user->id, 0, "docs", $doct);
			$docs = R::findAll('docs');
			if ($docs) {
				foreach ($docs as $doc) {
					if ($doc->status == 1) {
						echo '<div class="panel panel-default">
			                        <div class="panel-body">
			                            &nbsp;
			                            <i class="fa fa-file-text-o" aria-hidden="true"></i> ';
						echo $doc->name;
						echo ' &nbsp; ';
						echo '			<button onclick="pbEditInfoOper('.$doc->id.')" class="btn btn-default2"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
						echo '			<button onclick="pbOpenInfoOper('.$doc->id.')" class="btn btn-default2"><i class="fa fa-search" aria-hidden="true"></i></button>';
						echo '			<button onclick="pbTrashInfo('.$doc->id.')" class="btn btn-default2 pull-right"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
						echo '		</div>
			                  </div>';
		            }
				}
			} else {
				echo $locale['there_is_no_information_material'];
			}
		}
	}
} elseif ($oper == "save_doc") {
	if (isset($value)) {
		if (!empty($value)) {
			if (!empty($fidtextheader)) {
				$doc = R::load('docs', $value);
				$doc->name = $fidtextheader;
				$doc->body = $fidtextbody;
				R::store($doc);
				writeLog("updstr", $user->id, 0, "docs", $doc);
				echo $locale['the_document_has_been_saved'];
			} else {
				echo $locale['invalid_document_name_the_document_was_not_saved'];
			}
		} else {
			if (!empty($fidtextheader)) {
				$doc = R::dispense('docs');
				$doc->name = $fidtextheader;
				$doc->body = $fidtextbody;
				$doc->status = 1;
				R::store($doc);
				writeLog("addstr", $user->id, 0, "docs", $doc);
				echo $locale['a_new_document_has_been_created'];
			} else {
				echo $locale['invalid_document_name_the_document_was_not_saved'];
			}
		}
	}
} elseif ($oper == "load_all_msgs") {
	if (!isset($value)) $value = 10;
	$startval = R::count("messages");
	$startval = ($startval > $value) ? $startval - $value : 0;
	$str1 = " LIMIT ".$startval.", ".$value;
	$msgs = R::findAll("messages", $str1);
	foreach ($msgs as $msg) {
		if ($msg->status != 2) {
			if ($msg->from == $user->id) {
				if ($msg->to == 0) {
	        		echo '<div class="unitallmsgself">';
	                echo " ".$msg->body;
	                echo '</div>';
				} else {
					$unitto = R::load("users", $msg->to);
	        		echo '<div class="unitallmsgself">';
	                echo " ".$msg->body;
	                echo '	<button onclick="countMsgList = 10; panToUnit('.$msg->to.')" class="btn btn-default2 pull-right" title="'.$locale['go_to_unit'].' '.$unitto->name.'"><i class="fa fa-eye" aria-hidden="true"></i></button>
	                	  </div>';
				}
			} else {
				if (!empty($msg->from)) {
					if ($msg->type == 'forall') {
						$unit = R::load("users", $msg->from);
						echo '<div class="unitallmsg">
		                                <i class="fa fa-user" aria-hidden="true" title="'.$unit->name.'"></i>';
		                echo " ".$msg->body;
		                echo '</div>';
					} elseif ($msg->type == 'foroper') {
						$unit = R::load("users", $msg->from);
						echo '<div class="unitallmsg">
		                                <i class="fa fa-user" aria-hidden="true" title="'.$unit->name.'"></i>';
		                echo " ".$msg->body;
		                echo '			<button onclick="countMsgList = 10; panToUnit('.$unit->id.')" class="btn btn-default2 pull-right" title="'.$locale['go_to_unit'].' '.$unit->name.'"><i class="fa fa-eye" aria-hidden="true"></i></button>
		                      </div>';
					} elseif ($msg->type == 'forunit') {
						$unit = R::load("users", $msg->from);
						$unitto = R::load("users", $msg->to);
						echo '<div class="unitallmsg">
		                                <i class="fa fa-user" aria-hidden="true" title="'.$unit->name.'"></i>';
		                echo " ".$msg->body;
		                echo '			<button onclick="countMsgList = 10; panToUnit('.$unitto->id.')" class="btn btn-default2 pull-right" title="'.$locale['go_to_unit'].' '.$unitto->name.'"><i class="fa fa-eye" aria-hidden="true"></i></button>
		                      </div>';
					}
				} else {
					if (!empty($msg->to)) {
						$unitto = R::load("users", $msg->to);
						echo '<div class="unitallmsgcmd">
		                                <i class="fa fa-user" aria-hidden="true" title="'.$locale['system_message_for'].' '.$unitto->name.'"></i>';
		                echo " ".$msg->body;
		                echo '		<button onclick="countMsgList = 10; panToUnit('.$unitto->id.')" class="btn btn-default2 pull-right" title="'.$locale['go_to_unit'].' '.$unitto->name.'"><i class="fa fa-eye" aria-hidden="true"></i></button>
		                  </div>';
					} else {
						echo '<div class="unitallmsgcmd">
		                                <i class="fa fa-user" aria-hidden="true" title="'.$locale['system_message'].'"></i>';
		                echo " ".$msg->body;
		                echo '</div>';
	            	}
				}
			}
		}
	}
} elseif ($oper == "send_msg_all") {
	if (isset($textmsg)) {
		if (!empty($textmsg)) {
			$msg = R::dispense('messages');
			$msg->type = 'forall';
			$msg->from = $user->id;
			$msg->to = 0;
			$msg->status = 0;
			$msg->time = time();
			$msg->body = $textmsg;
			R::store($msg);
			writeLog("addstr", $user->id, 0, "messages", $msg);
		}
	}
} elseif ($oper == "load_footer_fam") {
	echo '<div class="form-group input-group dropup">
	    <input type="text" class="form-control" placeholder="'.$locale['message_to_all'].'" id="inputOperMsgAll">
	    <span class="input-group-btn">
	        <button onclick="sendOperMsgAll()" class="btn btn-default3" type="button" title="'.$locale['send_to_all'].'"><i class="fa fa-commenting-o fa-2x"></i></button>
	        <button onclick="soundClick()" class="btn btn-default3 dropdown-toggle" data-toggle="dropdown" title="'.$locale['orders_to_all'].'"><i class="fa fa-tasks fa-2x"></i></button>
	        <ul class="dropdown-menu dropdown-menu-right">';
	$orders = R::findAll('orders');
	foreach ($orders as $order) {
		if ($order->status < 2) {
			if ($order->type == 'oneorder') {
				echo '      <li onclick="startOrder(0, '.$order->id.')" class="dropdown-menu-li">
				                    <i class="fa fa-tasks" aria-hidden="true" title="'.$locale['order'].'"></i> '.$order->name.' ';
				$ras1 = explode(";", $order->body);
				foreach ($ras1 as $vras) {
					$ras2 = explode("=", $vras);
					if ($ras2[0] == 'typeord') {
						if ($ras2[1] == 'one')
							echo '<i class="fa fa-sticky-note-o" aria-hidden="true"></i> ';
						else
							echo '<i class="fa fa-clone" aria-hidden="true"></i> ';
					} elseif ($ras2[0] == 'target') {
						if ($ras2[1] == 'yes')
							echo '<i class="fa fa-flag-o" aria-hidden="true"></i> ';
						else
							echo '<i class="fa fa-circle-o" aria-hidden="true"></i> ';
					}
				}
				echo '      </li>';
			} else {
				echo '      <li onclick="startOrder(0, '.$order->id.')" class="dropdown-menu-li">
				                    <i class="fa fa-link" aria-hidden="true" title="'.$locale['chain_of_orders'].'"></i> '.$order->name.' ';
				$ras1 = explode(";", $order->body);
				foreach ($ras1 as $vras) {
					$ras2 = explode("=", $vras);
					if ($ras2[0] == 'typechain') {
						if ($ras2[1] == 'one')
							echo '<i class="fa fa-chain-broken" aria-hidden="true"></i> ';
						else
							echo '<i class="fa fa-exchange" aria-hidden="true"></i> ';
					}
				}
				echo '      </li>';
			}
		}
	}
	echo '  </ul>
	    </span>
	</div>';
} elseif ($oper == "unit_coord") {
	if (isset($value)) {
		if (!empty($value)) {
			$unit = R::load("users", $value);
			if ($unit) {
				echo "map.panTo({ lat: ".$unit->latitude.", lng: ".$unit->longitude." });";
				echo "unitCircle = L.circle({ lat: ".$unit->latitude.", lng: ".$unit->longitude." }, {
						stroke: false,
						fillColor: '".getColorUnitCircle($pbaccuracy, $unit->accuracy)."',
						fillOpacity: 0.37,
						geodesic: true,
						radius: ".$unit->accuracy."
					}).addTo(map);";
				$task = R::findOne("tasks", "(`userid` = ?) AND (`status` < 2)", array($unit->id));
				if ($task) {
					if ($task->type == 'oneorder') {
						$res = explode(";", $task->body);
						foreach ($res as $vres) {
							$res2 = explode("=", $vres);
							if ($res2[0] == 'target') {
								$target = R::load("targets", $res2[1]);
								if ($target) {
									echo 'ipathline = addLineUT({lat: '.$unit->latitude.', lng: '.$unit->longitude.'}, {lat: '.$target->latitude.', lng: '.$target->longitude.'}, map);';
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
									echo 'ipathline = addLineUT({lat: '.$unit->latitude.', lng: '.$unit->longitude.'}, {lat: '.$target->latitude.', lng: '.$target->longitude.'}, map);';
								}
							}
						}
					}
				}
				if ( $unit->lastactive < (time()-($systimeout+20)) ) {
					echo "var newmarker = L.marker({lat: ".$unit->latitude.", lng: ".$unit->longitude."}, {
				                icon: histFlMarker,
				                title: '".$unit->name."',
				            }).addTo(map);";
		            echo "units.push(newmarker);";
	            }
			} else {
				echo "map.panTo({ lat: 0, lng: 0 });";
			}
		} else {
			echo "map.panTo({ lat: 0, lng: 0 });";
		}
	}
} elseif ($oper == "load_unit_msgs_header") {
	if (isset($unitid)) {
		$unit = R::load("users", $unitid);
		echo '<strong>
			<i class="fa fa-user-o" aria-hidden="true"></i> '.$unit->name.'
		</strong>
		&nbsp;
		<button onclick="soundClick()" class="btn btn-default2 disabled" title="'.$locale['make_a_pandorabox_call'].'"><i class="fa fa-microphone" aria-hidden="true"></i></button>
		<button onclick="soundClick()" class="btn btn-default2 disabled" title="'.$locale['make_a_phone_call'].'"><i class="fa fa-phone" aria-hidden="true"></i></button>
		<button onclick="panToUnit('.$unitid.', false)" class="btn btn-default2" title="'.$locale['go_to_unit'].'"><i class="fa fa-eye" aria-hidden="true"></i></button>
		<button onclick="showWayUnit('.$unitid.')" class="btn btn-default2" title="'.$locale['history_of_displacement'].'"><i class="fa fa-share-alt" aria-hidden="true"></i></button>
		<button onclick="moreOneUnitMsgOper('.$unitid.')" class="btn btn-default2 pull-right" title="'.$locale['more'].'"><i class="fa fa-paperclip" aria-hidden="true"></i></button>';
	}
} elseif ($oper == "load_unit_msgs_body") {
	if (!isset($value)) $value = 10;
	$str1 = "(`type` = 'forall' AND `to` = 0) OR (`type` = 'foroper' AND `from` = ?) OR (`type` = 'forunit' AND `to` = ?)";
	$startval = R::count("messages", $str1, array($unitid, $unitid));
	$startval = ($startval > $value) ? $startval - $value : 0;
	$str1 .= " LIMIT ".$startval.", ".$value;
	$msgs = R::find("messages", $str1, array($unitid, $unitid));
	foreach ($msgs as $msg) {
		if ($msg->status != 2) {
			if ($msg->from == $unitid) {
				echo '<div class="unitmsg">';
				echo ' '.$msg->body;
				echo '</div>';
			} elseif ($msg->from == 0) {
				echo '<div class="cmdmsg"><strong>';
				echo ' '.$msg->body;
				echo '</strong></div>';
			} elseif ($msg->to == 0) {
				echo '<div class="opermsg">';
				echo ' '.$msg->body;
				echo '</div>';
			} else {
				echo '<div class="opermsg">';
				echo ' '.$msg->body;
				echo '</div>';
			}
		}
	}
} elseif ($oper == "load_unit_msgs_footer") {
	if (isset($unitid)) {
		echo '<div class="form-group input-group dropup">
	            <input type="text" class="form-control" placeholder="'.$locale['message'].'" id="inputOperMsgOne">
	            <span class="input-group-btn">
	                <button onclick="sendOperMsgOne('.$unitid.')" class="btn btn-default3" title="'.$locale['send'].'"><i class="fa fa-comment-o fa-2x"></i></button>
	                <button onclick="soundClick()" class="btn btn-default3 dropdown-toggle" data-toggle="dropdown" title="'.$locale['orders'].'"><i class="fa fa-tasks fa-2x"></i></button>
	                <ul class="dropdown-menu dropdown-menu-right">';
		$orders = R::findAll('orders');
		foreach ($orders as $order) {
			if ($order->status < 2) {
				if ($order->type == 'oneorder') {
					echo '      <li onclick="startOrder('.$unitid.', '.$order->id.')" class="dropdown-menu-li">
					                    <i class="fa fa-tasks" aria-hidden="true" title="'.$locale['order'].'"></i> '.$order->name.' ';
					$ras1 = explode(";", $order->body);
					foreach ($ras1 as $vras) {
						$ras2 = explode("=", $vras);
						if ($ras2[0] == 'typeord') {
							if ($ras2[1] == 'one')
								echo '<i class="fa fa-sticky-note-o" aria-hidden="true"></i> ';
							else
								echo '<i class="fa fa-clone" aria-hidden="true"></i> ';
						} elseif ($ras2[0] == 'target') {
							if ($ras2[1] == 'yes')
								echo '<i class="fa fa-flag-o" aria-hidden="true"></i> ';
							else
								echo '<i class="fa fa-circle-o" aria-hidden="true"></i> ';
						}
					}
					echo '      </li>';
				} else {
					echo '      <li onclick="startOrder('.$unitid.', '.$order->id.')" class="dropdown-menu-li">
					                    <i class="fa fa-link" aria-hidden="true" title="'.$locale['chain_of_orders'].'"></i> '.$order->name.' ';
					$ras1 = explode(";", $order->body);
					foreach ($ras1 as $vras) {
						$ras2 = explode("=", $vras);
						if ($ras2[0] == 'typechain') {
							if ($ras2[1] == 'one')
								echo '<i class="fa fa-chain-broken" aria-hidden="true"></i> ';
							else
								echo '<i class="fa fa-exchange" aria-hidden="true"></i> ';
						}
					}
					echo '      </li>';
				}
			}
		}
	    echo '		</ul>
	            </span>
	        </div>';
	}
} elseif ($oper == "send_msg_one") {
	if (isset($textmsg) and isset($unitid)) {
		if (!empty($textmsg)) {
			$msg = R::dispense('messages');
			$msg->type = 'forunit';
			$msg->from = $user->id;
			$msg->to = $unitid;
			$msg->status = 0;
			$msg->time = time();
			$msg->body = $textmsg;
			R::store($msg);
			writeLog("addstr", $user->id, 0, "messages", $msg);
		}
	}
} elseif ($oper == "load_one_cmd") {
	if (isset($orderid) and isset($unitid)) {
		$order = R::load('orders', $orderid);
		if ($order->type == 'oneorder') {
			echo '  <h4><i class="fa fa-tasks" aria-hidden="true" title="'.$locale['order'].'"></i> '.$order->name.'</h4>';
			$ras1 = explode(";", $order->body);
			foreach ($ras1 as $vras) {
				$ras2 = explode("=", $vras);
				if ($ras2[0] == 'typeord') {
					if ($ras2[1] == 'one') {
						echo '<div class="form-group">
		                        <label>'.$locale['type_of_order'].': </label>
		                        <label class="radio-inline">
		                            <i class="fa fa-sticky-note-o" aria-hidden="true"></i> '.$locale['one_time_order'].'
		                        </label>
		                    </div>';
					} else {
						echo '<div class="form-group">
		                        <label>'.$locale['type_of_order'].': </label>
		                        <label class="radio-inline">
		                            <i class="fa fa-clone" aria-hidden="true"></i> '.$locale['standing_order'].'
		                        </label>
		                    </div>';
					}
				}
				if ($ras2[0] == 'target') {
					if ($ras2[1] == 'yes') {
						$targets = R::findAll("targets");
						if ($targets) {
							echo '<div class="form-group">
			                        <label>'.$locale['targeting'].': </label>
			                        <label class="radio-inline">
			                            <i class="fa fa-flag-o" aria-hidden="true"></i> '.$locale['required'].'
			                        </label>
			                    </div>';
							echo '  <div class="form-group input-group">
						                    <span class="input-group-addon">
						                        <i class="fa fa-flag-checkered fa-2x" title="'.$locale['target'].'"></i>
						                    </span>
						                    <select type="text" class="form-control" id="idSelectTarget">';
							foreach ($targets as $target) {
								if ($target->status != 2)
									echo '		<option value="'.$target->id.'">'.$target->name.'</option>';
						    }
						    echo '          </select>
						            </div>';
					    }
					} else {
						echo '<div class="form-group">
		                        <label>'.$locale['targeting'].': </label>
		                        <label class="radio-inline">
		                            <i class="fa fa-circle-o" aria-hidden="true"></i> '.$locale['not_required'].'
		                        </label>
		                    </div>';
						echo '<input type="hidden" id="idSelectTarget" value="0">';
					}
				}
				if ($ras2[0] == 'msgstart') {
					echo '<div class="form-group">
	                        <label>'.$locale['starting_message'].': </label>
	                        <label class="radio-inline">
	                            '.$ras2[1].'
	                        </label>
	                    </div>';
				}
				if ($ras2[0] == 'msgfinish') {
					echo '<div class="form-group">
	                        <label>'.$locale['finishing_message'].': </label>
	                        <label class="radio-inline">
	                            '.$ras2[1].'
	                        </label>
	                    </div>';
				}
			}
		} else {
			echo '  <h4><i class="fa fa-link" aria-hidden="true" title="'.$locale['chain_of_orders'].'"></i> '.$order->name.'</h4>';
			$ras1 = explode(";", $order->body);
			foreach ($ras1 as $vras) {
				$ras2 = explode("=", $vras);
				if ($ras2[0] == 'typechain') {
					if ($ras2[1] == 'one') {
						echo '<div class="form-group">
	                        <label>'.$locale['type_of_orders_chain'].': </label>
	                        <label class="radio-inline">
	                            <i class="fa fa-chain-broken" aria-hidden="true"></i> '.$locale['one_time'].'
	                        </label>
	                    </div>';
					}
					else {
						echo '<div class="form-group">
	                        <label>'.$locale['type_of_orders_chain'].': </label>
	                        <label class="radio-inline">
	                            <i class="fa fa-exchange" aria-hidden="true"></i> '.$locale['cyclic'].'
	                        </label>
	                    </div>';
					}
					echo '<input type="hidden" id="idSelectTarget" value="0">';
				} elseif ($ras2[0] == 'chain') {
					echo '<div id="arrChainView">';
					$ras3 = explode("+", $ras2[1]);
					foreach ($ras3 as $vras2) {
						$ras4 = explode("&", $vras2);
						$order2 = R::load("orders", $ras4[0]);
						if ($order2) {
							if (($order2->type == 'oneorder') and ($order2->status < 2)) {
								echo '<div class="panel panel-default">
		                            <div class="panel-body">
		                                <i class="fa fa-tasks" aria-hidden="true"></i> '.$order2->name.'
		                                &nbsp;';
		                        $o2ras1 = explode(";", $order2->body);
		                        foreach ($o2ras1 as $o2vras) {
		                        	$o2ras2 = explode("=", $o2vras);
		                        	if ($o2ras2[0] == 'typeord') {
		                        		if ($o2ras2[1] == 'one') {
		                        			echo '<i class="fa fa-sticky-note-o" aria-hidden="true" title="'.$locale['one_time_order'].'"></i> ';
		                        		} else {
		                        			echo '<i class="fa fa-clone" aria-hidden="true" title="'.$locale['standing_order'].'"></i> ';
		                        		}
		                        	}
		                        	if ($o2ras2[0] == 'target') {
		                        		if ($o2ras2[1] == 'yes') {
		                        			echo '<i class="fa fa-flag-o" aria-hidden="true" title="'.$locale['performed_with_target_designation'].'"></i> ';
		                        			if (count($ras4) > 1) {
		                        				$target = R::load("targets", $ras4[1]);
		                        				if ($target) {
		                        					echo ' &nbsp; <i class="fa fa-flag-checkered" aria-hidden="true"></i> '.$target->name;
		                        				}
		                        			}
		                        		} else {
		                        			echo '<i class="fa fa-circle-o" aria-hidden="true" title="'.$locale['will_be_performed_without_target_designation'].'"></i> ';
		                        		}
		                        	}
		                        }
		                        echo '<button onclick="viewOrderMsgs('.$order2->id.')" class="btn btn-default2 pull-right"><i class="fa fa-info" aria-hidden="true"></i></button>';
		                        echo '</div>
		                        </div>';
	                    	}
                    	}
					}
					echo '</div>';
				}
			}
		}
	}
} elseif ($oper == "view_order_msgs") {
	if (isset($value)) {
		if (!empty($value)) {
			$order = R::load("orders", $value);
			echo '<div class="form-group">
                    <label>'.$locale['order'].': </label>
                    <label class="radio-inline">';
            echo $order->name;
            echo '  </label>
                </div>';
			$ras = explode(";", $order->body);
			foreach ($ras as $vras) {
				$ras2 = explode("=", $vras);
				if ($ras2[0] == 'msgstart') {
					echo '<div class="form-group">
	                        <label>'.$locale['starting_message'].': </label>
	                        <label class="radio-inline">';
	                echo $ras2[1];
	                echo '  </label>
	                    </div>';
				}
				if ($ras2[0] == 'msgfinish') {
					echo '<div class="form-group">
	                        <label>'.$locale['finishing_message'].': </label>
	                        <label class="radio-inline">';
	                echo $ras2[1];
	                echo '  </label>
	                    </div>';
				}
			}
		}
	}
} elseif ($oper == "save_task") {
	if (!empty($unitid)) {
		cancelAllTasksForUser($unitid, $user->id);
		$orderBin = writeTask($cmdid, $unitid, $fseltarget, $user->id);
		writeMsgtoTask($orderBin, $unitid);
	} else {
		$qstr1 = "`role` LIKE '%unit;%'";
		$units = R::find("users", $qstr1);
		if ($units) {
			foreach ($units as $unit) {
				cancelAllTasksForUser($unit->id, $user->id);
				$orderBin = writeTask($cmdid, $unit->id, $fseltarget, $user->id);
				writeMsgtoTask($orderBin, $unit->id);
			}
		}
	}
} elseif ($oper == "load_all_target_body") {
	if (!isset($value)) $value = 10;
	$str1 = "`status` <> 2";
	$startval = R::count("targets", $str1);
	$startval = ($startval > $value) ? $startval - $value : 0;
	$str1 .= " LIMIT ".$startval.", ".$value;
	$targets = R::find("targets", $str1);
	foreach ($targets as $target) {
		if ($target->status < 2) {
			echo '	<div class="unitallmsg">
	                	<div id="idEditTarget'.$target->id.'">
	                		<button onclick="editTarget('.$target->id.', \''.$target->name.'\')" class="btn btn-default4" title="'.$locale['edit_goal_name'].'"><i class="fa fa-pencil"></i></button><i class="fa fa-flag" aria-hidden="true"></i> '.$target->name.'
	                		<div class="pull-right">
	                    		<button onclick="deleteOneTargetConfirm('.$target->id.', \''.$target->name.'\')" class="btn btn-default2 pull-right" title="'.$locale['remove'].'"><i class="fa fa-minus"></i></button>
	                    		<button onclick="panToTarget('.$target->id.')" class="btn btn-default2 pull-right" title="'.$locale['show'].'"><i class="fa fa-eye" aria-hidden="true"></i></button>
	                    	</div>
	                	</div>
	                    <sub><i class="fa fa-compass" aria-hidden="true"> Lat: </i> '.$target->latitude.' </sub><br>
	                    <sub><i class="fa fa-compass" aria-hidden="true"> Lng: </i> '.$target->longitude.' </sub>
	                </div>';
        }
	}
} elseif ($oper == "target_coord") {
	if (isset($value)) {
		if (!empty($value)) {
			$target = R::load("targets", $value);
			if ($target) {
				echo 'map.panTo({ lat: '.$target->latitude.', lng: '.$target->longitude.' });';
			} else {
				echo "map.panTo({ lat: 0, lng: 0 });";
			}
		} else {
			echo "map.panTo({ lat: 0, lng: 0 });";
		}
	}
} elseif ($oper == "del_one_target") {
	if (isset($value)) {
		if (!empty($value)) {
			$target = R::load("targets", $value);
			$target->status = 2;
			R::store($target);
			writeLog("updstr", $user->id, 0, "targets", $target);
		}
	}
} elseif ($oper == "save_name_target") {
	if (isset($value)) {
		if (!empty($value)) {
			$target = R::load("targets", $value);
			$target->name = $namenew;
			R::store($target);
			writeLog("updstr", $user->id, 0, "targets", $target);
		}
	}
} elseif ($oper == "add_target") {
	$target = R::dispense('targets');
	$target->name = 'Target';
	$target->status = 0;
	$target->latitude = $lat;
	$target->longitude = $lng;
	$idnewtarget = R::store($target);
	writeLog("addstr", $user->id, 0, "targets", $target);
	$newtarget = R::load("targets", $idnewtarget);
	$str1 = $newtarget->name.$newtarget->id;
	$newtarget->name = $str1;
	R::store($newtarget);
	writeLog("updstr", $user->id, 0, "targets", $newtarget);
	echo 'isAddTarget = true; var idNewTarget = '.$idnewtarget.'; var nameTarget = "'.$str1.'";';
} elseif ($oper == "count_targets") {
	$counttargets = R::count("targets", "status < 2");
	echo 'var counttargets = '.$counttargets.';';
} elseif ($oper == "load_target") {
	$wstr = "status < 2 LIMIT ".$value.", 1";
	$targets = R::find("targets", $wstr);
	foreach ($targets as $target) {
		$str1 = $target->name;
		echo 'var nameTarget = "'.$str1.'"; var idNewTarget = '.$target->id.'; var newLatLng = {lat: '.$target->latitude.', lng: '.$target->longitude.'}; ';
	}
} elseif ($oper == "update_target_coord") {
	$target = R::load("targets", $targid);
	$target->latitude = $lat;
	$target->longitude = $lng;
	R::store($target);
	writeLog("updstr", $user->id, 0, "targets", $target);
	echo 'isUpdTarget = true; ';
} elseif ($oper == "load_orders_set") {
	$orders = R::findAll("orders");
	foreach ($orders as $order) {
		if ($order->status < 2) {
			if ($order->type == 'oneorder') {
				echo '<div class="panel panel-default">
	                    <div class="panel-body">
	                        &nbsp;
	                        <i class="fa fa-tasks" aria-hidden="true" title="'.$locale['order'].'"></i> '.$order->name.'
	                        &nbsp;';
				$res = explode(";", $order->body);
				foreach ($res as $vres) {
					$res2 = explode("=", $vres);
					if ($res2[0] == 'typeord') {
						if ($res2[1] == 'one') {
							echo '<i class="fa fa-sticky-note-o" aria-hidden="true" title="'.$locale['one_time_order'].'"></i> ';
						} else {
							echo '<i class="fa fa-clone" aria-hidden="true" title="'.$locale['standing_order'].'"></i> ';
						}
					}
					if ($res2[0] == 'target') {
						if ($res2[1] == 'yes') {
							echo '<i class="fa fa-flag-o" aria-hidden="true" title="'.$locale['performed_with_target_designation'].'"></i> ';
						} else {
							echo '<i class="fa fa-circle-o" aria-hidden="true" title="'.$locale['will_be_performed_without_target_designation'].'"></i> ';
						}
					}
				}
	            echo '      &nbsp;
	            			<button onclick="viewOrderMsgs('.$order->id.')" class="btn btn-default2" title="'.$locale['information'].'"><i class="fa fa-info" aria-hidden="true"></i></button>
	                        <button onclick="pbEditOrderDialog('.$order->id.')" class="btn btn-default2" title="'.$locale['edit'].'"><i class="fa fa-pencil" aria-hidden="true"></i></button>
	                        <button onclick="stopAllTasksOrderConfirm('.$order->id.', \''.$order->name.'\')" class="btn btn-default2" title="'.$locale['stop_execution'].'"><i class="fa fa-stop-circle" aria-hidden="true"></i></button>

	                        <button onclick="deleteOneOrderConfirm('.$order->id.', \''.$order->name.'\')" class="btn btn-default2 pull-right" title="'.$locale['remove'].'"><i class="fa fa-minus" aria-hidden="true"></i></button>
	                    </div>
	                </div>';
			} else {
				echo '<div class="panel panel-default">
	                    <div class="panel-body">
	                        &nbsp;
	                        <i class="fa fa-link" aria-hidden="true" title="'.$locale['chain_of_orders'].'"></i> '.$order->name.'
	                        &nbsp;';
				$res = explode(";", $order->body);
				foreach ($res as $vres) {
					$res2 = explode("=", $vres);
					if ($res2[0] == 'typechain') {
						if ($res2[1] == 'one') {
							echo '<i class="fa fa-chain-broken" aria-hidden="true" title="'.$locale['one_time'].'"></i> ';
						} else {
							echo '<i class="fa fa-exchange" aria-hidden="true" title="'.$locale['cyclic'].'"></i> ';
						}
					}
				}
	            echo '      &nbsp;
	                        <button onclick="pbEditChainOrderDialog('.$order->id.')" class="btn btn-default2" title="'.$locale['edit'].'"><i class="fa fa-pencil" aria-hidden="true"></i></button>
	                        <button onclick="stopAllTasksOrderConfirm('.$order->id.', \''.$order->name.'\')" class="btn btn-default2" title="'.$locale['stop_execution'].'"><i class="fa fa-stop-circle" aria-hidden="true"></i></button>

	                        <button onclick="deleteOneOrderConfirm('.$order->id.', \''.$order->name.'\')" class="btn btn-default2 pull-right" title="'.$locale['remove'].'"><i class="fa fa-minus" aria-hidden="true"></i></button>
	                    </div>
	                </div>';
			}
		}
	}
} elseif ($oper == "del_one_order") {
	if (isset($value)) {
		if (!empty($value)) {
			$order = R::load("orders", $value);
			$order->status = 2;
			R::store($order);
			writeLog("updstr", $user->id, 0, "orders", $order);
		}
	}
} elseif ($oper == "stop_tasks") {
	if (isset($value)) {
		if (!empty($value)) {
			$str1 = "(`orderid` = ".$value.") AND (`status` < 2)";
			$tasks = R::find("tasks", $str1);
			foreach ($tasks as $task) {
				$task->status = 3;
			}
			R::storeAll($tasks);
			writeLog("updstr", $user->id, 0, "tasks", $tasks);
			echo $locale['tasks_canceled'];
		}
	}
} elseif ($oper == "load_order_one") {
	if (isset($value)) {
		if (!empty($value)) {
			$order = R::load("orders", $value);
			echo '<div class="form-group input-group">
                    <span class="input-group-addon" style="padding-left: 7px; padding-right: 7px;">
                        <i class="fa fa-tasks fa-2x" title="'.$locale['the_name_of_the_order'].'"></i>
                    </span>
                    <input id="idNameOrder" type="text" class="form-control" maxlength="40" value="'.$order->name.'" placeholder="'.$locale['the_name_of_the_order'].'">
                </div>
                <br>';
            $res = explode(";", $order->body);
            foreach ($res as $vres) {
            	$res2 = explode("=", $vres);
            	if ($res2[0] == 'typeord') {
            		if ($res2[1] == 'one') {
            			echo '<div class="form-group">
			                    <label>'.$locale['type_of_order'].': </label>
			                    <label class="radio-inline">
			                        <input type="radio" name="optionsTypeCmd" value="standing"> <i class="fa fa-clone" aria-hidden="true"></i> '.$locale['standing_order'].'
			                    </label>
			                    <label class="radio-inline">
			                        <input type="radio" name="optionsTypeCmd" value="one" checked> <i class="fa fa-sticky-note-o" aria-hidden="true"></i> '.$locale['one_time_order'].'
			                    </label>
			                </div>
			                <br>';
            		} else {
            			echo '<div class="form-group">
			                    <label>'.$locale['type_of_order'].': </label>
			                    <label class="radio-inline">
			                        <input type="radio" name="optionsTypeCmd" value="standing" checked> <i class="fa fa-clone" aria-hidden="true"></i> '.$locale['standing_order'].'
			                    </label>
			                    <label class="radio-inline">
			                        <input type="radio" name="optionsTypeCmd" value="one"> <i class="fa fa-sticky-note-o" aria-hidden="true"></i> '.$locale['one_time_order'].'
			                    </label>
			                </div>
			                <br>';
            		}
            	}
	            if ($res2[0] == 'target') {
	            		if ($res2[1] == 'yes') {
	            			echo '<div class="form-group">
				                    <label>'.$locale['targeting'].': </label>
				                    <label class="radio-inline">
				                        <input type="radio" name="optionsRadiosTarget" value="yes" checked> <i class="fa fa-flag-o" aria-hidden="true"></i> '.$locale['required'].'
				                    </label>
				                    <label class="radio-inline">
				                        <input type="radio" name="optionsRadiosTarget" value="no"> <i class="fa fa-circle-o" aria-hidden="true"></i> '.$locale['not_required'].'
				                    </label>
				                </div>
				                <br>';
	            		} else {
	            			echo '<div class="form-group">
				                    <label>'.$locale['targeting'].': </label>
				                    <label class="radio-inline">
				                        <input type="radio" name="optionsRadiosTarget" value="yes"> <i class="fa fa-flag-o" aria-hidden="true"></i> '.$locale['required'].'
				                    </label>
				                    <label class="radio-inline">
				                        <input type="radio" name="optionsRadiosTarget" value="no" checked> <i class="fa fa-circle-o" aria-hidden="true"></i> '.$locale['not_required'].'
				                    </label>
				                </div>
				                <br>';
	            		}
	            }
	        	if ($res2[0] == 'msgstart') {
	        		echo '<div class="form-group">
		                    <label>'.$locale['starting_message'].': </label>
		                    <textarea id="idOrderMsgStart" class="form-control" rows="3">'.$res2[1].'</textarea>
		                </div>
		                <br>';
	        	}
	        	if ($res2[0] == 'msgfinish') {
	        		echo '<div class="form-group">
		                    <label>'.$locale['finishing_message'].': </label>
		                    <textarea id="idOrderMsgFinish" class="form-control" rows="3">'.$res2[1].'</textarea>
		                </div>
		                <br>';
	        	}
	        }
		} else {
			echo '<div class="form-group input-group">
                    <span class="input-group-addon" style="padding-left: 7px; padding-right: 7px;">
                        <i class="fa fa-tasks fa-2x" title="'.$locale['the_name_of_the_order'].'"></i>
                    </span>
                    <input id="idNameOrder" type="text" class="form-control" maxlength="40" value="" placeholder="'.$locale['the_name_of_the_order'].'">
                </div>
                <br>';
            echo '<div class="form-group">
                    <label>'.$locale['type_of_order'].': </label>
                    <label class="radio-inline">
                        <input type="radio" name="optionsTypeCmd" value="standing" checked> <i class="fa fa-clone" aria-hidden="true"></i> '.$locale['standing_order'].'
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="optionsTypeCmd" value="one"> <i class="fa fa-sticky-note-o" aria-hidden="true"></i> '.$locale['one_time_order'].'
                    </label>
                </div>
                <br>';
            echo '<div class="form-group">
                    <label>'.$locale['targeting'].': </label>
                    <label class="radio-inline">
                        <input type="radio" name="optionsRadiosTarget" value="yes" checked> <i class="fa fa-flag-o" aria-hidden="true"></i> '.$locale['required'].'
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="optionsRadiosTarget" value="no"> <i class="fa fa-circle-o" aria-hidden="true"></i> '.$locale['not_required'].'
                    </label>
                </div>
                <br>';
            echo '<div class="form-group">
                    <label>'.$locale['starting_message'].': </label>
                    <textarea id="idOrderMsgStart" class="form-control" rows="3"></textarea>
                </div>
                <br>';
            echo '<div class="form-group">
                    <label>'.$locale['finishing_message'].': </label>
                    <textarea id="idOrderMsgFinish" class="form-control" rows="3"></textarea>
                </div>
                <br>';
		}
	}
} elseif ($oper == "save_order_one") {
	if (isset($value)) {
		if (!empty($value)) {
			$order = R::load("orders", $value);
			$order->type = 'oneorder';
			$order->name = $fidnameorder;
			$order->status = 1;
			$order->body = 'typeord='.$foptionstypecmd.';target='.$foptionsradiostarget.';msgstart='.$fidordermsgstart.';msgfinish='.$fidordermsgfinish.';';
			R::store($order);
			writeLog("updstr", $user->id, 0, "orders", $order);
		} else {
			$order = R::dispense("orders");
			$order->type = 'oneorder';
			$order->name = $fidnameorder;
			$order->status = 1;
			$order->value = 'price=2;discount=1;compensation=1;penalty=1;';
			$order->body = 'typeord='.$foptionstypecmd.';target='.$foptionsradiostarget.';msgstart='.$fidordermsgstart.';msgfinish='.$fidordermsgfinish.';';
			R::store($order);
			writeLog("addstr", $user->id, 0, "orders", $order);
		}
	}
} elseif ($oper == "service_query_json") {
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
} elseif ($oper == "load_orderchain_one_json") {
	if (isset($value)) {
		if (!empty($value)) {
			$arrEditChain = [];
			$order = R::load("orders", $value);
            $arrEditChain["namechain"] = $order->name;

			$ras1 = explode(";", $order->body);
			foreach ($ras1 as $vras) {
				$ras2 = explode("=", $vras);
				if ($ras2[0] == 'typechain') {
					if ($ras2[1] == 'one') {
		                $arrEditChain["typechain"] = 'one';
					}
					else {
						$arrEditChain["typechain"] = 'cyclic';
					}
				} elseif ($ras2[0] == 'chain') {
					$i1 = 0;
					$arrEditChain["chain"] = [];
					$ras3 = explode("+", $ras2[1]);
					foreach ($ras3 as $vras2) {
						$i1++;
						$ras4 = explode("&", $vras2);
						$order2 = R::load("orders", $ras4[0]);
						if ($order2) {

							if (($order2->type == 'oneorder') and ($order2->status < 2)) {
								$arrEditChain["chain"][$i1] = [];
								$arrEditChain["chain"][$i1]["id"] = $ras4[0];
								$arrEditChain["chain"][$i1]["name"] = $order2->name;
		                        $o2ras1 = explode(";", $order2->body);
		                        foreach ($o2ras1 as $o2vras) {
		                        	$o2ras2 = explode("=", $o2vras);
		                        	if ($o2ras2[0] == 'typeord') {
		                        		if ($o2ras2[1] == 'one') {
		                        			$arrEditChain["chain"][$i1]["typeord"] = 'one';
		                        		} else {
		                        			$arrEditChain["chain"][$i1]["typeord"] = 'standing';
		                        		}
		                        	}
		                        	if ($o2ras2[0] == 'target') {
		                        		if ($o2ras2[1] == 'yes') {
		                        			$arrEditChain["chain"][$i1]["target"] = 'yes';
		                        			if (count($ras4) > 1) {
		                        				$arrEditChain["chain"][$i1]["targetid"] = $ras4[1];
		                        			}
		                        		} else {
		                        			$arrEditChain["chain"][$i1]["target"] = 'no';
		                        			$arrEditChain["chain"][$i1]["targetid"] = 0;
		                        		}
		                        	}
		                        }
	                    	}
                    	}
					}
				}
			}
			echo json_encode($arrEditChain);
		} else {
			$arrEditChain = [];
            $arrEditChain["namechain"] = "";
			$arrEditChain["typechain"] = 'one';
            $arrEditChain["chain"] = [];
            echo json_encode($arrEditChain);
		}
	}
} elseif ($oper == "load_orders_all_json") {
	$arrOrders = [];
	$str1 = "(`type` = 'oneorder') AND (`status` < 2)";
    $orders = R::find("orders", $str1);
    if ($orders) {
		foreach ($orders as $vorder) {
			$arrOrders[$vorder->id] = [];
			$arrOrders[$vorder->id]["id"] = $vorder->id;
			$arrOrders[$vorder->id]["name"] = $vorder->name;
			$res1 = explode(";", $vorder->body);
			foreach ($res1 as $vres1) {
				$res2 = explode("=", $vres1);
				if ($res2[0] == 'typeord') {
					$arrOrders[$vorder->id]["typeord"] = $res2[1];
				}
				if ($res2[0] == 'target') {
					$arrOrders[$vorder->id]["target"] = $res2[1];
				}
			}
		}
    }
    echo json_encode($arrOrders);
} elseif ($oper == "load_targets_all_json") {
	$arrTargets = [];
	$targets = R::find("targets", "`status` < 2");
	if ($targets) {
		$i1 = 0;
		foreach ($targets as $target) {
			$i1++;
			$arrTargets[$i1] = [];
			$arrTargets[$i1]["id"] = $target->id;
			$arrTargets[$i1]["name"] = $target->name;
		}
	}
    echo json_encode($arrTargets);
} elseif ($oper == "save_chain_one_json") {
	if (isset($value)) {
		if (!empty($value)) {
			$arrEditChain = json_decode($chainjson);
			$order = R::load("orders", $value);
			$order->name = $arrEditChain->namechain;
			$bodystr = 'typechain='.$arrEditChain->typechain.";";
			$bodystr .= 'chain=';
			foreach ($arrEditChain->chain as $vchain) {
				$bodystr .= $vchain->id;
				if ($vchain->target == 'yes') {
					$bodystr .= '&'.$vchain->targetid;
				}
				$bodystr .= '+';
			}
			if ($bodystr{strlen($bodystr)-1} == '+') {
				$bodystr = substr($bodystr, 0, -1);
			}
			if ($bodystr{strlen($bodystr)-1} == '=') {
				$bodystr = 'typechain='.$arrEditChain->typechain;
			}
			if ($bodystr{strlen($bodystr)-1} == ';') {
				$bodystr = 'typechain='.$arrEditChain->typechain;
			}
			$bodystr .= ';';
			$order->body = $bodystr;
			R::store($order);
			writeLog("updstr", $user->id, 0, "orders", $order);
		} else {
			$arrEditChain = json_decode($chainjson);
			$order = R::dispense("orders");
			$order->type = "chainorders";
			$order->name = $arrEditChain->namechain;
			$order->status = 1;
			$order->value = "novalue;";
			$bodystr = 'typechain='.$arrEditChain->typechain.";";
			$bodystr .= 'chain=';
			foreach ($arrEditChain->chain as $vchain) {
				$bodystr .= $vchain->id;
				if ($vchain->target == 'yes') {
					$bodystr .= '&'.$vchain->targetid;
				}
				$bodystr .= '+';
			}
			if ($bodystr{strlen($bodystr)-1} == '+') {
				$bodystr = substr($bodystr, 0, -1);
			}
			if ($bodystr{strlen($bodystr)-1} == '=') {
				$bodystr = 'typechain='.$arrEditChain->typechain;
			}
			if ($bodystr{strlen($bodystr)-1} == ';') {
				$bodystr = 'typechain='.$arrEditChain->typechain;
			}
			$bodystr .= ';';
			$order->body = $bodystr;
			R::store($order);
			writeLog("addstr", $user->id, 0, "orders", $order);
		}
	}
} elseif ($oper == "load_active_unit") {
	if (isset($value)) {
		if (!empty($value)) {
			if (!isset($value)) $value = 10;
			$str1 = "(`status` < 4) AND (`lastactive` > ".(time()-($systimeout+20)).")";
			$startval = R::count("users", $str1);
			$startval = ($startval > $value) ? $startval - $value : 0;
			$str1 .= " LIMIT ".$startval.", ".$value;
			$actunits = R::find("users", $str1);
			if ($actunits) {
				foreach ($actunits as $actunit) {
					$qstr1 = "`userid` = ? AND `status` = ?";
					$counttask = R::count("tasks", $qstr1, array($actunit->id, 1));
					if ($counttask == 0)
						echo '<div class="unitallmsg" style="background-color: hotpink;">';
					else
						echo '<div class="unitallmsg">';
					$nosep = explode(";", $actunit->role);
					foreach ($nosep as $arg) {
						if ($arg == 'admin') echo ' <i class="fa fa-user" aria-hidden="true" title="'.$locale['role_administrator'].'"></i> ';
						elseif ($arg == 'officer') echo ' <i class="fa fa-user" aria-hidden="true" title="'.$locale['role_officer'].'"></i> ';
						elseif ($arg == 'accaunter') echo ' <i class="fa fa-user" aria-hidden="true" title="'.$locale['role_accountant'].'"></i> ';
						elseif ($arg == 'unit') echo ' <i class="fa fa-user-o" aria-hidden="true" title="'.$locale['role_executor'].'"></i> ';
						elseif ($arg == 'ghost') echo ' <i class="fa fa-user-o" aria-hidden="true" title="'.$locale['role_ghost'].'"></i> ';
					}
					echo ' '.$actunit->name.'
	                        <div class="form-group input-group dropup pull-right">
	                            <button onclick="countMsgList = 10; panToUnit('.$actunit->id.')" class="btn btn-default2" title="'.$locale['go_to_unit'].'"><i class="fa fa-eye" aria-hidden="true"></i></button>
	                            <button onclick="soundClick()" class="btn btn-default2 dropdown-toggle" data-toggle="dropdown" title="'.$locale['orders'].'"><i class="fa fa-tasks"></i></button>
	                            <ul class="dropdown-menu dropdown-menu-right">';
					$orders = R::findAll('orders');
					foreach ($orders as $order) {
						if ($order->status < 2) {
							if ($order->type == 'oneorder') {
								echo '      <li onclick="startOrder('.$actunit->id.', '.$order->id.')" class="dropdown-menu-li">
								                    <i class="fa fa-tasks" aria-hidden="true" title="'.$locale['order'].'"></i> '.$order->name.' ';
								$ras1 = explode(";", $order->body);
								foreach ($ras1 as $vras) {
									$ras2 = explode("=", $vras);
									if ($ras2[0] == 'typeord') {
										if ($ras2[1] == 'one')
											echo '<i class="fa fa-sticky-note-o" aria-hidden="true"></i> ';
										else
											echo '<i class="fa fa-clone" aria-hidden="true"></i> ';
									} elseif ($ras2[0] == 'target') {
										if ($ras2[1] == 'yes')
											echo '<i class="fa fa-flag-o" aria-hidden="true"></i> ';
										else
											echo '<i class="fa fa-circle-o" aria-hidden="true"></i> ';
									}
								}
								echo '      </li>';
							} else {
								echo '      <li onclick="startOrder('.$actunit->id.', '.$order->id.')" class="dropdown-menu-li">
								                    <i class="fa fa-link" aria-hidden="true" title="'.$locale['chain_of_orders'].'"></i> '.$order->name.' ';
								$ras1 = explode(";", $order->body);
								foreach ($ras1 as $vras) {
									$ras2 = explode("=", $vras);
									if ($ras2[0] == 'typechain') {
										if ($ras2[1] == 'one')
											echo '<i class="fa fa-chain-broken" aria-hidden="true"></i> ';
										else
											echo '<i class="fa fa-exchange" aria-hidden="true"></i> ';
									}
								}
								echo '      </li>';
							}
						}
					}
	                echo '      </ul>
	                        </div>
	                    </div>';
				}
			}
		}
	}
} elseif ($oper == "load_sets") {
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
} elseif ($oper == "save_sets") {
	$outstr = $locale['the_data_is_saved'].' <br> ';
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
} elseif ($oper == "load_tasks_oper") {
	if (isset($value)) {
		if (!empty($value)) {
			$startval = R::count("tasks");
			$startval = ($startval > $value) ? $startval - $value : 0;
			$str1 = " LIMIT ".$startval.", ".$value;
			$tasks = R::find("tasks", $str1);
			if ($tasks) {
				foreach ($tasks as $task) {
					$outstr = ' ';
					if ($task->status < 2) {
						echo '<div class="eventmsg"> ';
						echo '<table style="width: 100%"><tr><td>';
					} else {
						echo '<div class="eventmsgok"> ';
						echo '<table style="width: 100%"><tr><td>';
					}

					$order = R::load("orders", $task->orderid);

					if ($task->type == 'oneorder') {
                    	echo '<i class="fa fa-tasks" aria-hidden="true" title="'.$order->name.'"></i> ';
                    	$outstr .= $order->name;
                    	$res1 = explode(";", $order->body);
                    	foreach ($res1 as $vres1) {
                    		$res2 = explode("=", $vres1);
                    		if ($res2[0] == 'typeord') {
                    			if ($res2[1] == 'one')
                    				$outstr .= ' <i class="fa fa-sticky-note-o" aria-hidden="true" title="'.$locale['one_time_order'].'"></i> ';
                    			else
                    				$outstr .= ' <i class="fa fa-clone" aria-hidden="true" title="'.$locale['standing_order'].'"></i> ';
                    		}
                    		if ($res2[0] == 'target') {
                    			if ($res2[1] == 'yes') {
                    				$tres1 = explode(";", $task->body);
                    				foreach ($tres1 as $tvres1) {
                    					$tres2 = explode("=", $tvres1);
                    					if ($tres2[0] == 'target') {
                    						$target = R::load("targets", $tres2[1]);
                    						$outstr .= ' <i class="fa fa-flag-checkered" aria-hidden="true" title="'.$target->name.'"></i> ';
                    					}
                    				}
                    			} else {
                    				$outstr .= ' <i class="fa fa-circle-o" aria-hidden="true" title="'.$locale['will_be_performed_without_target_designation'].'"></i> ';
                    			}
                    		}
                    		if ($res2[0] == 'msgstart') {
                    			$outstr .= ' <br> &nbsp; &nbsp; <i> '.$res2[1].'</i> ';
                    		}
                    	}
					} else {
                    	echo '<i class="fa fa-link" aria-hidden="true" title="'.$order->name.'"></i> ';
                    	$cres1 = explode(";", $task->body);
                    	$cres2 = explode("=", $cres1[0]);
                    	if ($cres2[0] == 'num') {
                    		$co_res1 = explode(";", $order->body);
                    		foreach ($co_res1 as $co_vres1) {
                    			$co_res2 = explode("=", $co_vres1);
                    			if ($co_res2[0] == 'chain') {
                    				$co_res3 = explode("+", $co_res2[1]);
                    				$i1 = $cres2[1];
                    				$vGroup = $co_res3[$i1];
                    				$aGroup = explode("&", $vGroup);
                    				$order2 = R::load("orders", $aGroup[0]);
		                    		$outstr .= $order2->name;
		                    		$res1 = explode(";", $order2->body);
		                    		foreach ($res1 as $vres1) {
			                    		$res2 = explode("=", $vres1);
			                    		if ($res2[0] == 'typeord') {
			                    			if ($res2[1] == 'one')
			                    				$outstr .= ' <i class="fa fa-sticky-note-o" aria-hidden="true" title="'.$locale['one_time_order'].'"></i> ';
			                    			else
			                    				$outstr .= ' <i class="fa fa-clone" aria-hidden="true" title="'.$locale['standing_order'].'"></i> ';
			                    		}
			                    		if ($res2[0] == 'target') {
			                    			if (($res2[1] == 'yes') and isset($aGroup[1])) {
	                    						$target = R::load("targets", $aGroup[1]);
	                    						$outstr .= ' <i class="fa fa-flag-checkered" aria-hidden="true" title="'.$target->name.'"></i> ';
			                    			} else {
			                    				$outstr .= ' <i class="fa fa-circle-o" aria-hidden="true" title="'.$locale['will_be_performed_without_target_designation'].'"></i> ';
			                    			}
			                    		}
			                    		if ($res2[0] == 'msgstart') {
			                    			$outstr .= ' <br> &nbsp; &nbsp; <i> '.$res2[1].'</i> ';
			                    		}
			                    	}
                    			}
                    		}
                    	}
                    }

                    if ($task->status < 2)
                		echo '<i class="fa fa-bell" aria-hidden="true" title="'.$locale['task_active'].'"></i> ';
                	elseif ($task->status == 2)
                		echo '<i class="fa fa-thumbs-up" aria-hidden="true" title="'.$locale['task_complete'].'"></i> ';
                	elseif ($task->status == 3)
                		echo '<i class="fa fa-hand-paper-o" aria-hidden="true" title="'.$locale['task_canceled'].'"></i> ';
                	elseif ($task->status == 4)
                		echo '<i class="fa fa-thumbs-down" aria-hidden="true" title="'.$locale['task_failed'].'"></i> ';

                    if ($task->userid != 0) {
                    	$unit = R::load("users", $task->userid);
                    	echo '	<i class="fa fa-user-o" aria-hidden="true" title="'.$unit->name.'"></i> ';
						echo $outstr;
						if ($task->status < 2) {
							echo '	<div class="dropup pull-right">';
	                        echo '		<button onclick="soundClick()" class="btn btn-default2 dropdown-toggle pull-right" data-toggle="dropdown"><i class="fa fa-angle-double-up" aria-hidden="true"></i></button>
	                        				<ul class="dropdown-menu dropdown-menu-right">
									        	<li onclick="stopOneTaskConfirm('.$task->id.', 3)" class="dropdown-menu-li">
									        		<i class="fa fa-hand-paper-o" aria-hidden="true"></i> '.$locale['cancel_the_task'].'
									        	</li>
									        	<li onclick="stopOneTaskConfirm('.$task->id.', 4)" class="dropdown-menu-li">
									        		<i class="fa fa-thumbs-down" aria-hidden="true"></i> '.$locale['mark_the_task_as_failed'].'
									        	</li>
									        	<li onclick="stopOneTaskConfirm('.$task->id.', 2)" class="dropdown-menu-li">
									        		<i class="fa fa-thumbs-up" aria-hidden="true"></i> '.$locale['confirm_the_task'].'
									        	</li>
	                        				</ul>';
	                        echo '		<button onclick="panToUnit('.$unit->id.')" class="btn btn-default2 pull-right" title="'.$locale['go_to_unit'].' '.$unit->name.'"><i class="fa fa-eye" aria-hidden="true"></i></button>';
	                        echo '	</div>';
						} else {
							echo '	<div class="dropup pull-right">';
	                        echo '  	<button onclick="startOrder('.$unit->id.', '.$task->orderid.', true)" class="btn btn-default2 pull-right" title="'.$locale['repeat'].'"><i class="fa fa-refresh" aria-hidden="true"></i></button>';
	                        echo '		<button onclick="panToUnit('.$unit->id.')" class="btn btn-default2 pull-right" title="'.$locale['go_to_unit'].' '.$unit->name.'"><i class="fa fa-eye" aria-hidden="true"></i></button>';
	                        echo '	</div>';
						}
                    } else {
                    	echo '	<i class="fa fa-users" aria-hidden="true" title="'.$locale['general_order'].'"></i> ';
						echo $outstr;
                    }
                    echo '</td></tr></table>';
                    echo '</div>';
				}
			}
		}
	}
} elseif ($oper == "stop_onetask") {
	$task = R::load("tasks", $idtask);
	$task->status = $idselector;
	R::store($task);
	writeLog("updstr", $user->id, 0, "tasks", $task);
	$msg = R::dispense('messages');
	$msg->type = 'forunit';
	$msg->from = 0;
	$msg->to = $task->userid;
	$msg->status = 0;
	$msg->time = time();
	$msg->body = $locale['note_tasks_are_changed_by_the_operator'];
	R::store($msg);
	writeLog("addstr", $user->id, 0, "messages", $msg);
	echo $locale['task_status_changed'];
} elseif ($oper == "load_users_manage") {
	$musers = R::findAll("users");
	foreach ($musers as $muser) {
		if ($muser->status < 6) {
			echo '<div class="panel panel-default">
	                <div class="panel-body">
	                    &nbsp;
	                    <i class="fa fa-user" aria-hidden="true"></i> '.$muser->name.'
	                    &nbsp;
	                    <i class="fa fa-universal-access" aria-hidden="true" title="';
			$res1 = explode(";", $muser->role);
			foreach ($res1 as $vres1) {
				if ($vres1 == 'admin')
					echo $locale['role_administrator']."; ";
				elseif ($vres1 == 'officer')
					echo $locale['role_officer']."; ";
				elseif ($vres1 == 'accaunter')
					echo $locale['role_accountant']."; ";
				elseif ($vres1 == 'unit')
					echo $locale['role_executor']."; ";
				elseif ($vres1 == 'ghost')
					echo $locale['role_ghost']."; ";
			}
			echo '"></i>';
			if (Subord($muser->role, $user->role)) {
		        echo '	<div class="pull-right">
		        			<table>
		        				<tr>
		        					<td>
					        			<button onclick="pbEditUser('.$muser->id.')" class="btn btn-default2"><i class="fa fa-pencil" aria-hidden="true"></i></button>
					                    <button onclick="panToUnit('.$muser->id.')" class="btn btn-default2"><i class="fa fa-eye" aria-hidden="true"></i></button>
									</td>
									<td>
										&nbsp; &nbsp;
									</td>
									<td>
		                    			<button onclick="pbDelUser('.$muser->id.')" class="btn btn-default2"><i class="fa fa-ban" aria-hidden="true"></i></button>
		                    		</td>
		                    	</tr>
		                    </table>
	                    </div>';
	        }
	        echo '	</div>
	            </div>';
		}
	}
} elseif ($oper == "delete_user") {
	if (isset($value)) {
		if (!empty($value)) {
			$unit = R::load("users", $value);
			$unit->status = 6;
			R::store($unit);
			writeLog("updstr", $user->id, $unit->id, "users", $unit);
			echo $locale['the_user_has_been_deleted'];
		}
	}
} elseif ($oper == "load_user_data") {
	if (isset($value)) {
		if (!empty($value)) {
			$arrRole = [
				'admin' => false,
				'officer' => false,
				'accaunter' => false,
				'unit' => false,
				'ghost' => false
			];
			$unit = R::load("users", $value);
			$ras = explode(";", $unit->role);
			foreach ($ras as $vras) {
				if ($vras == 'admin')
					$arrRole['admin'] = true;
				elseif ($vras == 'officer')
					$arrRole['officer'] = true;
				elseif ($vras == 'accaunter')
					$arrRole['accaunter'] = true;
				elseif ($vras == 'unit')
					$arrRole['unit'] = true;
				elseif ($vras == 'ghost')
					$arrRole['ghost'] = true;
			}
			echo '<input id="iduserid" name="unitid" type="hidden" value="'.$unit->id.'">';
			echo '<div class="form-group input-group">
                       <span class="input-group-addon" style="padding-left: 7px; padding-right: 7px;">
                            <i class="fa fa-user fa-2x" title="'.$locale['login'].'"></i>
                        </span>
                        <input id="iduserlogin" name="login" type="text" class="form-control" value="'.$unit->name.'" placeholder="'.$locale['login'].'" required pattern="^[a-zA-Z0-9_-]+$">
                </div>';
			echo '<div class="form-group input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-universal-access fa-2x" title="'.$locale['role'].'" style="padding-left: 1px; padding-right: 1px;"></i>
                        </span>
						<div class="input-group-btn" style="width: 100%">
							<button type="button" style="width: 100%" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$locale['choose'].' <span class="caret"></span></button>
							<ul class="dropdown-menu" style="width: 100%">
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleAdmin" '.((bestRole($user->role) > 1) ? "disabled" : " ").' '.(($arrRole['admin']) ? "checked" : " ").'> '.$locale['role_administrator'].'</label></li>
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleOfficer" '.((bestRole($user->role) > 2) ? "disabled" : " ").' '.(($arrRole['officer']) ? "checked" : " ").'> '.$locale['role_officer'].'</label></li>
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleAccaunter" '.((bestRole($user->role) > 3) ? "disabled" : " ").' '.(($arrRole['accaunter']) ? "checked" : " ").'> '.$locale['role_accountant'].'</label></li>
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleUnit" '.((bestRole($user->role) > 3) ? "disabled" : " ").' '.(($arrRole['unit']) ? "checked" : " ").'> '.$locale['role_executor'].'</label></li>
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleGhost" '.((bestRole($user->role) > 3) ? "disabled" : " ").' '.(($arrRole['ghost']) ? "checked" : " ").'> '.$locale['role_ghost'].'</li>
							</ul>
						</div>
                </div>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-key fa-2x" title="'.$locale['password'].'" style="padding-left: 1px; padding-right: 1px;"></i>
                        </span>
                        <input id="iduserpassword" name="password" type="password" class="form-control" placeholder="'.$locale['password'].'" required pattern="^[a-zA-Z0-9]+$">
                </div>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-at fa-2x" title="'.$locale['email'].'" style="padding-left: 3px; padding-right: 3px;"></i>
                        </span>
                        <input id="iduseremail" name="email" type="email" class="form-control" value="'.$unit->email.'" placeholder="'.$locale['email'].'">
                </div>
                <br>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-phone fa-2x" title="'.$locale['phone'].'" style="padding-left: 4px; padding-right: 4px;"></i>
                        </span>
                        <input id="iduserphone" name="phone" type="text" class="form-control" value="'.$unit->phone.'" placeholder="'.$locale['phone_number'].'">
                </div>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-address-book fa-2x" title="'.$locale['full_name'].'" style="padding-left: 2px; padding-right: 2px;"></i>
                        </span>
                        <input id="iduserfname" type="text" class="form-control" value="'.$unit->fname.'" placeholder="'.$locale['full_name'].'">
                </div>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-road fa-2x" title="'.$locale['address'].'"></i>
                        </span>
                        <input id="iduseraddress" type="text" class="form-control" value="'.$unit->address.'" placeholder="'.$locale['address'].'">
                </div>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-commenting fa-2x" title="'.$locale['сomments'].'" style="padding-left: 1px; padding-right: 1px;"></i>
                        </span>
                        <textarea id="idusercomment" class="form-control" rows="5">'.$unit->comment.'</textarea>
                </div>';
		} else {
			echo '<input id="iduserid" name="unitid" type="hidden" value="0">';
			echo '<div class="form-group input-group">
                       <span class="input-group-addon" style="padding-left: 7px; padding-right: 7px;">
                            <i class="fa fa-user fa-2x" title="'.$locale['login'].'"></i>
                        </span>
                        <input id="iduserlogin" name="login" type="text" class="form-control" value="" placeholder="'.$locale['login'].'" required pattern="^[a-zA-Z0-9_-]+$">
                </div>';
			echo '<div class="form-group input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-universal-access fa-2x" title="'.$locale['role'].'" style="padding-left: 1px; padding-right: 1px;"></i>
                        </span>
						<div class="input-group-btn" style="width: 100%">
							<button type="button" style="width: 100%" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$locale['choose'].' <span class="caret"></span></button>
							<ul class="dropdown-menu" style="width: 100%">
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleAdmin" '.((bestRole($user->role) > 1) ? "disabled" : " ").'> '.$locale['role_administrator'].'</label></li>
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleOfficer" '.((bestRole($user->role) > 2) ? "disabled" : " ").'> '.$locale['role_officer'].'</label></li>
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleAccaunter" '.((bestRole($user->role) > 3) ? "disabled" : " ").'> '.$locale['role_accountant'].'</label></li>
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleUnit" '.((bestRole($user->role) > 3) ? "disabled" : " ").' checked> '.$locale['role_executor'].'</label></li>
								<li class="dropdown-menu-li"><label style="width: 100%"><input type="checkbox" id="setUserRoleGhost" '.((bestRole($user->role) > 3) ? "disabled" : " ").'> '.$locale['role_ghost'].'</li>
							</ul>
						</div>
                </div>';
			echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-key fa-2x" title="'.$locale['password'].'" style="padding-left: 1px; padding-right: 1px;"></i>
                        </span>
                        <input id="iduserpassword" name="password" type="password" class="form-control" placeholder="'.$locale['password'].'" required pattern="^[a-zA-Z0-9]+$">
                </div>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-at fa-2x" title="'.$locale['email'].'" style="padding-left: 3px; padding-right: 3px;"></i>
                        </span>
                        <input id="iduseremail" name="email" type="email" class="form-control" value="" placeholder="'.$locale['email'].'">
                </div>
                <br>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-phone fa-2x" title="'.$locale['phone'].'" style="padding-left: 4px; padding-right: 4px;"></i>
                        </span>
                        <input id="iduserphone" name="phone" type="text" class="form-control" value="" placeholder="'.$locale['phone_number'].'">
                </div>';
            echo '<div class="form-group input-group">
	                   <span class="input-group-addon">
	                        <i class="fa fa-address-book fa-2x" title="'.$locale['full_name'].'" style="padding-left: 2px; padding-right: 2px;"></i>
	                    </span>
	                    <input id="iduserfname" type="text" class="form-control" value="" placeholder="'.$locale['full_name'].'">
	            </div>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-road fa-2x" title="'.$locale['address'].'"></i>
                        </span>
                        <input id="iduseraddress" type="text" class="form-control" value="" placeholder="'.$locale['address'].'">
                </div>';
            echo '<div class="form-group input-group">
                       <span class="input-group-addon">
                            <i class="fa fa-commenting fa-2x" title="'.$locale['сomments'].'" style="padding-left: 1px; padding-right: 1px;"></i>
                        </span>
                        <textarea id="idusercomment" class="form-control" rows="5"></textarea>
                </div>';
		}
	}
} elseif ($oper == "save_user") {
	if (!empty($fiduserid)) {
		if (!empty($fiduserlogin) and !empty($fiduseremail)) {
			$isUser = R::find("users", "(`name` = ?) AND (`id` <> ?)", array($fiduserlogin, $fiduserid));
			if ($isUser) {
				echo $locale['invalid_user_name'];
			} else {
				$euser = R::load("users", $fiduserid);
				$euser->name = $fiduserlogin;
				if (!empty($fuserroles)) {
					$euser->role = safeSaveRole(bestRole($user->role), $fuserroles);
				} else {
					$euser->role = 'unit;';
				}
				if (!empty($fiduserpassword)) {
					$euser->password = sha1($fiduserpassword);
					$euser->chpassw = sha1($fiduserpassword.time());
				}
				$euser->email = $fiduseremail;
				$euser->phone = $fiduserphone;
				$euser->fname = $fiduserfname;
				$euser->address = $fiduseraddress;
				$euser->comment = $fidusercomment;
				R::store($euser);
				writeLog("updstr", $user->id, $euser->id, "users", $euser);
				echo $locale['user_saved'];
			}
		} else {
			echo $locale['important_fields_login_or_email_are_not_filled'];
		}
	} else {
		if (!empty($fiduserlogin) and !empty($fiduserpassword) and !empty($fiduseremail)) {
			$isUser = R::find("users", "`name` = ?", array($fiduserlogin));
			if ($isUser) {
				echo $locale['invalid_user_name'];
			} else {
				$euser = R::dispense("users");
				$euser->name = $fiduserlogin;
				if (!empty($fuserroles)) {
					$euser->role = safeSaveRole(bestRole($user->role), $fuserroles);
				} else {
					$euser->role = 'unit;';
				}
				$euser->status = '1';
				$euser->password = sha1($fiduserpassword);
				$euser->chpassw = sha1($fiduserpassword.time());
				$euser->email = $fiduseremail;
				$euser->phone = $fiduserphone;
				$euser->fname = $fiduserfname;
				$euser->address = $fiduseraddress;
				$euser->comment = $fidusercomment;
				$euser->latitude = 0;
				$euser->longitude = 0;
				$euser->accuracy = 0;
				$euser->lastactive = 0;
				R::store($euser);
				writeLog("addstr", $user->id, $euser->id, "users", $euser);
				echo $locale['a_new_user_has_been_added'];
			}
		} else {
			echo $locale['important_fields_login_or_email_are_not_filled'];
		}
	}
} elseif ($oper == "count_units") {
	$timecheck = time()-($systimeout+20);
	$wstr = "(`status` < 5) AND (`lastactive` > ".$timecheck.") AND ((`role` LIKE '%unit;%') OR (`role` LIKE '%ghost;%'))";
	$countunits = R::count("users", $wstr);
	echo 'var countunits = '.$countunits.'; var timecheck = '.$timecheck.';';
} elseif ($oper == "load_unit") {
	$wstr = "(`status` < 5) AND (`lastactive` > ".$timecheck.") AND ((`role` LIKE '%unit;%') OR (`role` LIKE '%ghost;%')) LIMIT ".$value.", 1";
	$units = R::find("users", $wstr);
	foreach ($units as $unit) {
		$str1 = $unit->name;
		echo 'var nameUnit = "'.$str1.'"; var idNewUnit = '.$unit->id.'; var newLatLng = {lat: '.$unit->latitude.', lng: '.$unit->longitude.'}; ';
	}
} elseif ($oper == "load_sysset") {
	echo '<div class="form-group input-group">';
		echo '<span class="input-group-addon">
                '.$locale['title_browser'].': <input onclick="toggleSysSet(1)" type="checkbox" id="chpbtitle" '.(($setflags['pbtitle']) ? "checked" : " ").'>
            </span>';
		echo '<input type="text" name="syspbtitle" id="idsyspbtitle" class="form-control" value="'.$pbtitle.'" placeholder="'.$locale['title_browser'].'" '.(($setflags['pbtitle']) ? " " : "disabled").'>';
	echo '</div>';
	echo '<div class="form-group input-group">';
		echo '<span class="input-group-addon">
                '.$locale['login_header'].': <input onclick="toggleSysSet(2)" type="checkbox" id="chauhead" '.(($setflags['auhead']) ? "checked" : " ").'>
            </span>';
		echo '<input type="text" name="sysauhead" id="idsysauhead" class="form-control" value="'.$auhead.'" placeholder="'.$locale['login_header'].'" '.(($setflags['auhead']) ? " " : "disabled").'>';
	echo '</div>';
	echo '<div class="form-group input-group">';
		echo '<span class="input-group-addon">
                '.$locale['login_infoblock'].': <input onclick="toggleSysSet(3)" type="checkbox" id="chauinfo" '.(($setflags['auinfo']) ? "checked" : " ").'>
            </span>';
		echo '<input type="text" name="sysauinfo" id="idsysauinfo" class="form-control" value="'.$auinfo.'" placeholder="'.$locale['login_infoblock'].'" '.(($setflags['auinfo']) ? " " : "disabled").'>';
	echo '</div>';
	echo '<div class="form-group input-group" style="height: 34px;">';
		echo '<span class="input-group-addon">
                <label><input onclick="soundClick()" type="checkbox" id="chpbsignup" '.(($setflags['pbsignup']) ? "checked" : " ").'> '.$locale['free_registration'].' </label>
            </span>';
		echo '<span class="input-group-addon">
                <label><input onclick="soundClick()" type="checkbox" id="chpbforgot" '.(($setflags['pbforgot']) ? "checked" : " ").'> '.$locale['password_recovery'].' </label>
            </span>';
	echo '</div>';
	echo '<div class="form-group input-group">';
		echo '<span class="input-group-addon">
                '.$locale['accuracy'].':
            </span>
            <input type="text" id="idsysaccuracy" class="form-control" value="'.$pbaccuracy.'" placeholder="'.$locale['accuracy'].'">
            <span class="input-group-addon">
                '.$locale['meters'].'
            </span>';

		echo '<span class="input-group-addon"></span>';
		echo '<span class="input-group-addon">
                '.$locale['timeout'].':
            </span>
            <input type="text" id="idsystimeout" class="form-control" value="'.$systimeout.'" placeholder="'.$locale['timeout'].'">
            <span class="input-group-addon">
                '.$locale['sec'].'
            </span>';

        echo '<span class="input-group-addon"></span>';
		echo '<span class="input-group-addon">
                '.$locale['language'].':
            </span>';
        echo '<select id="idsyslanguage" class="form-control" style="width: 120px;">';
        	foreach ($arrlangs as $langkey => $langvalue) {
        		echo '<option value="'.$langkey.'" '.(($pblanguage == $langkey) ? "selected" : " ").'>'.$langvalue.'</option>';
        	}
		echo '</select>';

	echo '</div>';
	echo '<div class="form-group input-group">';
		echo '<span class="input-group-addon"> <b>'.$locale['base_coordinates'].'</b> </span>';
		echo '<span class="input-group-addon"></span>';
		echo '<span class="input-group-addon">
                '.$locale['latitude'].':
            </span>
            <input type="text" id="idsyslat" class="form-control" value="'.$pblat.'" placeholder="'.$locale['latitude'].'">';
        echo '<span class="input-group-addon"></span>';
		echo '<span class="input-group-addon">
                '.$locale['longitude'].':
            </span>
            <input type="text" id="idsyslng" class="form-control" value="'.$pblng.'" placeholder="'.$locale['longitude'].'">';
	echo '</div>';
} elseif ($oper == "save_sysset") {
	try {
		$securecode = sha1($_SERVER['HTTP_HOST'].time());
		$pbconfig = '<?php ';
		$pbconfig .= '$dbhost = '.'"'.$dbhost.'"; ';
		$pbconfig .= '$dbname = '.'"'.$dbname.'"; ';
		$pbconfig .= '$dblogin = '.'"'.$dblogin.'"; ';
		$pbconfig .= '$dbpassword = '.'"'.$dbpassword.'"; ';
		$pbconfig .= '$pblabel = '.'"'.'PandoraBox v.: 0.3 (alpha)'.'"; ';
		$pbconfig .= '$securecode = '.'"'.$securecode.'"; ';
		$pbconfig .= '$pbtitle = '.'"'.$fpbtitle.'"; ';
		$pbconfig .= '$auhead = '.'"'.$fauhead.'"; ';
		$pbconfig .= '$auinfo = '.'"'.$fauinfo.'"; ';
		if (is_numeric($fpbaccuracy)) {
			$fpbaccuracy = (int) $fpbaccuracy;
			if ($fpbaccuracy < 10) $fpbaccuracy = 10;
			$pbconfig .= '$pbaccuracy = '.$fpbaccuracy.'; ';
		} else {
			$pbconfig .= '$pbaccuracy = '.$pbaccuracy.'; ';
		}
		if (is_numeric($fsystimeout)) {
			$fsystimeout = (int) $fsystimeout;
			if ($fsystimeout < 20) $fsystimeout = 20;
			if ($fsystimeout > 300) $fsystimeout = 300;
			$pbconfig .= '$systimeout = '.$fsystimeout.'; ';
		} else {
			$pbconfig .= '$systimeout = '.$systimeout.'; ';
		}
		$pbconfig .= '$pblanguage = "'.$fpblanguage.'"; ';
		if (is_numeric($fpblat)) {
			$pbconfig .= '$pblat = '.$fpblat.'; ';
		} else {
			$pbconfig .= '$pblat = '.$pblat.'; ';
		}
		if (is_numeric($fpblng)) {
			$pbconfig .= '$pblng = '.$fpblng.'; ';
		} else {
			$pbconfig .= '$pblng = '.$pblng.'; ';
		}
		$pbconfig .= '$setflags = [ "pbtitle" => '.$cpbtitle.', "auhead" => '.$cauhead.', "auinfo" => '.$cauinfo.', "pbsignup" => '.$cpbsignup.', "pbforgot" => '.$cpbforgot.' ];';

		file_put_contents('../config.php', $pbconfig);
		echo 'setTimeout(funcRefresh, 3000);';
	} catch (Exception $e) {
		echo $locale['can_not_write_configuration'].'<br> '.$e.' ';
	}
} elseif ($oper == "show_way") {
	if (isset($value)) {
		if (!empty($value)) {
			$str = "(`type` = 'updlla') AND (`userid` = ?)";
			$ways = R::find("history", $str, array($value));
			$waysres = [];
			foreach ($ways as $way) {
				$res1 = json_decode($way->body);
				$res2 = [];
				$res2['name'] = $res1->name;
				$res2['lat']  = $res1->latitude;
				$res2['lng']  = $res1->longitude;
				$res2['acc']  = $res1->accuracy;
				$res2['time'] = date("d M Y - H:i:s", $res1->lastactive);
				$waysres[] = $res2;
				unset($res1);
				unset($res2);
			}

			$way = R::load("users", $value);
			$res2 = [];
			$res2['name'] = $way->name;
			$res2['lat']  = $way->latitude;
			$res2['lng']  = $way->longitude;
			$res2['acc']  = $way->accuracy;
			$res2['time'] = date("d M Y - H:i:s", $way->lastactive);
			$waysres[] = $res2;
			unset($res2);

			echo 'var resway = '.json_encode($waysres).';';
		}
	}
} else {
	echo $locale['unknown_command'];
}
