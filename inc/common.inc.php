<?php
/*
**	a quick hack for the
**
**		1. Goddelauer Badminton Nachturnier
**		2. Goddelauer Badminton Nachturnier
**		3. Goddelauer Badminton Nachturnier
**		4. Goddelauer Badminton Nachturnier
**
**
**	Christian Lorenz
*/
define('COLWIDTH',550);
define('FONTSIZE',4);
define('FONTSIZEPX',40);
define('MAX_PLAYERS',100);
define('TITLE',__TITLE__);
define('IMAGE_BACKGROUND','images/Silvester-bg.png');

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

function print_player($player,$no_grey_out = 0,$pid_highlight = 0)
{
	global $_SERVER;

	print '<span class="player" onClick="clickedPlayer('.$player['Pid'].')">'.str_replace(' ','&nbsp;',$player['Nick']).'</span>';
}

