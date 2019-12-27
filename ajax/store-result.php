<?php
/*
**	expected parameters
*/
session_start();
$name = trim($_SESSION['name']);
$numbers = $_GET['numbers'];
$time = $_GET['time'];

include_once '../inc/settings.inc.php';
include_once '../inc/common.inc.php';
include_once '../inc/util.inc.php';
include_once '../inc/database.inc.php';

/*
**	update the database
*/
if ($name && $numbers >= 0 && $time > 0)
	db_score_store($name,$numbers,$time);
?>
name=<?php print $name ?>
numbers=<?php print $numbers ?>
time=<?php print $time ?>
