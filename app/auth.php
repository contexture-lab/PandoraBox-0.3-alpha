<?php
$regok = false;

if (isset($_SESSION['logged_user'])) {
 	$user = $_SESSION['logged_user'];
 	$userconf = R::findOne('users', 'id = ?', array($user->id));
 	if ($user->password == $userconf->password) {
 		if (isset($efoper)) {
			$_SESSION['pb_intrface'] = 1;
			require 'officer.php';
		} elseif (isset($efunit)) {
			$_SESSION['pb_intrface'] = 2;
			require 'unit.php';
		} else {
 			require 'front.php';
 		}
 	} else {
 		unset($_SESSION['logged_user']);
 		unset($_SESSION['pb_intrface']);
 		unset($user);
 		require 'signin.php';
 	}
} else {
	if (isset($signin)) {
 		$user = R::findOne('users', 'name = ?', array($login));
 		if ($user) {
 			if ($user->status == '0') {
 				$errors[] = $locale['the_user_is_not_activated_check_your_EMail_and_confirm_the_registration'];
 				require 'signin.php';
 			} else {
 				if ( $user->password == sha1($password)) {
	 				$_SESSION['logged_user'] = $user;
	 				writeLog("signin", $user->id, 0, "", "");
	 				require 'front.php';
	 			} else {
	 				$errors[] = $locale['the_password_is_incorrect'];
	 				require 'signin.php';
	 			}
 			}
 		} else {
 			$errors[] = $locale['user_is_not_found'];
 			require 'signin.php';
 		}
	} elseif (isset($forgot)) {
		if (!empty($login)) {
			$res = R::findOne('users', 'name = ?', array($login));
			if ($res) {
				$emailto = $res->email;
				$chpassw = sha1($login.time());
				$res->chpassw = $chpassw;
				R::store($res);
				writeLog("updstr", 0, 0, "users", $res);
				$mailsubject = 'Change password';
				$mailmessage = $mailsubject.' '.$protocol.$_SERVER['HTTP_HOST'].'/?change='.$chpassw;
				$mailheaders = 'From: info@'.$_SERVER['HTTP_HOST']."\r\n".'Reply-To: info@'.$_SERVER['HTTP_HOST']."\r\n".'X-Mailer: PHP/'.phpversion();
				mail($emailto, $mailsubject, $mailmessage, $mailheaders);

				$errors[] = $locale['a_message_has_been_sent_to_you_to_restore_access'];
				require 'signin.php';
			} else {
				$errors[] = $locale['invalid_user_name'];
				require 'signin.php';
			}
		} elseif (!empty($email)) {
			$res = R::findOne('users', 'email = ?', array($email));
			if ($res) {
				$emailto = $res->email;
				$chpassw = sha1($email.time());
				$res->chpassw = $chpassw;
				R::store($res);
				writeLog("updstr", 0, 0, "users", $res);
				$mailsubject = 'Change password';
				$mailmessage = $mailsubject.' '.$protocol.$_SERVER['HTTP_HOST'].'/?change='.$chpassw;
				$mailheaders = 'From: info@'.$_SERVER['HTTP_HOST']."\r\n".'Reply-To: info@'.$_SERVER['HTTP_HOST']."\r\n".'X-Mailer: PHP/'.phpversion();
				mail($emailto, $mailsubject, $mailmessage, $mailheaders);

				$errors[] = $locale['a_message_has_been_sent_to_you_to_restore_access'];
				require 'signin.php';
			} else {
				$errors[] = $locale['invalid_email'];
				require 'signin.php';
			}
		} else {
			$errors[] = $locale['invalid_data_to_restore_access'];
			require 'signin.php';
		}
	} elseif (isset($signup)) {
		if ($password != $password_retry) {
			$errors[] = $locale['incorrect_password'];
			require 'signin.php';
		} else {
			$res1 = R::findOne('users', 'email = ?', array($email));
			$res2 = R::findOne('users', 'name = ?', array($login));
			if ($res1) {
				$errors[] = $locale['invalid_email'];
				require 'signin.php';
			} elseif ($res2) {
				$errors[] = $locale['invalid_user_name'];
				require 'signin.php';
			} else {
				$user = R::dispense('users');
				$user->name = $login;
				$user->role = 'unit;';
				$user->status = '0';
				$user->password = sha1($password);
				$chpassw = sha1($login.time());
				$user->chpassw = $chpassw;
				$user->email = $email;
				$user->phone = (empty($phone)) ? '-' : $phone;
				$user->fname = (empty($fname)) ? '-' : $fname;
				$user->address = (empty($address)) ? '-' : $address;
				$user->comment = '0';
				$user->latitude = '0';
				$user->longitude = '0';
				$user->accuracy = '0';
				$user->lastactive = time();
				R::store($user);
				writeLog("addstr", 0, 0, "users", $user);
				$mailsubject = 'Confirm signup';
				$mailmessage = $mailsubject.' '.$protocol.$_SERVER['HTTP_HOST'].'/?confirm='.$chpassw;
				$mailheaders = 'From: info@'.$_SERVER['HTTP_HOST']."\r\n".'Reply-To: info@'.$_SERVER['HTTP_HOST']."\r\n".'X-Mailer: PHP/'.phpversion();
				mail($email, $mailsubject, $mailmessage, $mailheaders);
				$regok = true;
				$errors[] = $locale['the_user_is_registered_check_your_email_and_confirm_the_registration'];
				require 'signin.php';
			}
		}
	} elseif (isset($changebut)) {
		if ($password == $password_retry) {
			$user = R::findOne('users', 'name = ?', array($login));
			$user->password = sha1($password);
			R::store($user);
			writeLog("updstr", 0, 0, "users", $user);
			$regok = true;
			$errors[] = $locale['password_changed'].' <a href="'.$protocol.$_SERVER['HTTP_HOST'].'/"> '.$locale['sign_in'].' </a> ';
			require 'signin.php';
		} else {
			$regok = true;
			$errors[] = $locale['passwords_are_unequal_try_to_change_the_password_again'];
			require 'signin.php';
		}
	} else {
		require 'signin.php';
	}
}
