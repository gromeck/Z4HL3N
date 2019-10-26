<?php
/*
**	a quick hack for the
**
**	Christian Lorenz
*/

/*
**	=========================================================
**
**	B A S I C   S T U F F
**
**	=========================================================
*/

/*
**	do a database query
*/
function db_query($query)
{
	global $mysqli;

    if (!($mysqli = new mysqli(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE))) {
		//print('db_query: mysqli() failed');
		return false;
	}
	$mysqli->set_charset('utf8');

//	print "query: $query\n";
	if (!($result = $mysqli->query($query))) {
		print('<br>db_query: mysqli->query('.$query.') failed: '.$mysqli->error);
		return false;
	}
	return $result;
}

/*
**	=========================================================
**
**	S E T T I N G S
**
**	=========================================================
*/

/*
**	load settings
*/
function db_settings_load($settings)
{
	for ($n = 0;$n < count($settings);$n++) {
		if ($result = db_query("SELECT Value FROM Settings WHERE Name='".$settings[$n]['Name']."';")) {
			if (($row = $result->fetch_assoc())) {
				$settings[$n]['Value'] = $row['Value'];
			}
			$result->free();
		}
	}
	return $settings;
}

/*
**	save settings
*/
function db_settings_save($settings)
{
	foreach ($settings as $setting) {
		if (!db_query("INSERT INTO Settings (Name,Value) VALUES ('".$setting['Name']."','".$setting['Value']."')".
			" ON DUPLICATE KEY UPDATE Value='".$setting['Value']."';"))
			return false;
	}
	return true;
}

/*
**	=========================================================
**
**	U S E R
**
**	=========================================================
*/

/*
**	authenticate a user with username and password
*/
function db_user_authenticate($username,$password)
{
	$result = db_query("SELECT * FROM Users WHERE Username='".$username."' AND Password=ENCRYPT('".$password."',Password);");

	if (!$result)
		return false;

	$row = $result->fetch_assoc();
	$result->free();

	return $row;
}

?>
