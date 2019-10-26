<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
/*
**	a quick hack for the
**
**	The Perfekte Minute
**
**	Christian Lorenz
*/
include_once 'inc/settings.inc.php';
include_once 'inc/common.inc.php';
include_once 'inc/html.inc.php';
include_once 'inc/util.inc.php';
include_once 'inc/database.inc.php';
include_once 'inc/numbers.inc.php';

/*
**	the available pages
*/
$pages = array(
	'index' =>		   		array( 'title' => 'Zahlen schreiben',	'show' => 1, 'admin' => 1, 'separator' => 0 ),
	'login' =>		   		array( 'title' => 'Login',				'show' => 0, 'admin' => 0, 'separator' => 0 ),
	'admin-settings' =>	   	array( 'title' => 'Einstellungen',		'show' => 1, 'admin' => 1, 'separator' => 0 ),
	'play' =>				array( 'title' => 'Zahlen schreiben',	'show' => 1, 'admin' => 0, 'separator' => 0, 'button' => 'Los geht\'s!', ),
);

/*
**	call the page -- with output buffering
*/
if (!($page = @$pages[$pageid = @$_GET['page']]))
	$page = $pages[$pageid = 'index'];
$_title = $page['title'];
ob_start();
include 'pages/'.$pageid.'.inc.php';
$content = ob_get_clean();

/*
**	output the content and use the title
*/
html_header($pageid,$_title);
print $content;

if (ADMIN) {
	/*
	**	admin will have some more functions
	*/
	?></div><div class=adminfooter><?php
	foreach ($pages as $pageid => $page) {
		if ($page['title'] && $page['show'] && $page['admin']) {
			html_button_href($page['title'],'?page='.$pageid,-1);
		}
	}
	?></div><div><?php
}

/*
**	close the page
*/
html_footer();
