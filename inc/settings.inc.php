<?php
/*
**	a quick hack for Die Perfekte Minute
**
**	Christian Lorenz
*/

/*
**	database access
*/
define('DB_HOSTNAME','localhost');
define('DB_DATABASE','Silvester');
define('DB_USERNAME','Silvester');
define('DB_PASSWORD','Silvester');
include_once 'util.inc.php';
include_once 'database.inc.php';

/*
**	set the defaults
*/
$settings = array(
	array( 'Name' => 'TITLE',              'Title' => 'Titel',              'Value' => 'Die Perfekte Minute', 'Units' => '' ),
	array( 'Name' => 'YEAR',               'Title' => 'Jahr',               'Value' => 2017, 'Units' => '' ),
	array( 'Name' => 'JOKERS_PER_PLAYER',  'Title' => 'Joker pro Spieler',	'Value' => '3',  'Units' => '' ),
	array( 'Name' => 'POINTS_PER_GAME',	   'Title' => 'Punkte pro Spiel',	'Value' => '3',  'Units' => '' ),
);

/*
**	load the settings from the database
*/
$settings = db_settings_load($settings);

/*
**	define all settings as defines
*/
foreach ($settings as $setting) {
	define('__'.$setting['Name'].'__',$setting['Value']);
}

//print_r($settings);


