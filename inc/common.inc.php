<?php
/*
**	common function
**
**	Christian Lorenz
*/

/*
**	start a session if not in CLI mode
*/
if (!defined('CLI') || (defined('CLI') && !CLI)) {
	session_start();
	define('ADMIN',(@$_SESSION['User'] && @$_SESSION['User']['Uid'] && $_SESSION['User']['Admin']) ? 1 : 0);
}
if (!defined('ADMIN'))
	define('ADMIN',0);

/*
**	some setting
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
