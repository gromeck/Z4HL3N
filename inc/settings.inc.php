<?php
/*
**	a quick hack for Die Perfekte Minute
**
**	Christian Lorenz
*/

define('__TITLE__','Z4HL3N');

/*
**	database access
*/
define('DB_HOSTNAME','localhost');
define('DB_DATABASE','Z4HL3N');
define('DB_USERNAME','Z4HL3N');
define('DB_PASSWORD','Z4HL3N');
include_once 'util.inc.php';
include_once 'database.inc.php';

/*
**	set the defaults
*/
$settings = array(
	array( 'Name' => 'NUMBERS_TO_PLAY',  'Title' => 'Anzahl Zahlen',   'Value' => 10, 'Units' => 'Zahlen' ),
	array( 'Name' => 'TIME_LIMIT',  'Title' => 'Zeitlimit',   'Value' => 300, 'Units' => 'Sekunden' ),
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


