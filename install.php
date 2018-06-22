<?php
extract($_POST, EXTR_SKIP);
require "lang/en.php";

if (isset($setup)) {
	require 'install/progress.php';
} else {
	require 'install/start.php';
}
