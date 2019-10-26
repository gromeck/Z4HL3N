<?php
/*
**	a quick hack for the
**
**		1. Goddelauer Badminton Nachturnier
**
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

/*
**	=========================================================
**
**	P L A Y E R
**
**	=========================================================
*/

/*
**	lookup a player by its pid
*/
function db_player_lookup_by_pid($pid)
{
	$result = db_query("SELECT * FROM Players WHERE Pid='".$pid."';");

	if (!$result)
		return false;

	$row = $result->fetch_assoc($result);
	$result->free();

	return $row;
}

/*
**	create a new player
*/
function db_player_create($nick)
{
	global $mysqli;

	$nick = trim($nick);
	if (!db_query("INSERT INTO Players (Nick) VALUES('".$nick."');"))
		return array(false,$mysqli->error);
	return array(true,true);
}

/*
**	delete a player
*/
function db_player_delete($pid)
{
	global $mysqli;

	if (!db_query("DELETE FROM Players WHERE Pid=".$pid))
		return array(false,$mysqli->error);
	return array(true,true);
}

/*
**	lookup the complete list of players using filters
*/
function db_player_list($order = "Nick ASC")
{
	if (!($result = db_query("SELECT * FROM Players ORDER BY $order;")))
		return false;

	$players = array();
	while ($row = $result->fetch_assoc()) {
		$players[] = $row;
	}

	$result->free();
	return $players;
}

/*
**	=========================================================
**
**	G A M E S
**
**	=========================================================
*/

/*
**	lookup a game by its gid
*/
function db_game_lookup_by_gid($gid)
{
	$result = db_query("SELECT * FROM Games WHERE Gid='".$gid."';");

	if (!$result)
		return false;

	$row = $result->fetch_assoc();
	$result->free();

	return $row;
}

/*
**	lookup the complete list of games
*/
function db_game_list()
{
	if (!($result = db_query("SELECT * FROM Games ORDER BY GNr ASC;")))
		return false;

	$games = array();
	while ($row = $result->fetch_assoc()) {
		$games[] = $row;
	}

	$result->free();
	return $games;
}


/*
**	=========================================================
**
**	R O U N D S
**
**	=========================================================
*/

/*
**	retrieve the complete list of rounds
*/
function db_round_list()
{
	if (!($result = db_query("SELECT * FROM Rounds;")))
		return false;

	$rounds = array();
	while ($row = $result->fetch_assoc()) {
		$rounds[] = $row;
	}

	$result->free();
	return $rounds;
}

/*
**	get a single result for a player/game
*/
function db_round_by_gid_and_pid($gid,$pid)
{
	if (!($result = db_query("SELECT * FROM Rounds WHERE Gid=$gid AND Pid=$pid;")))
		return false;

	$r = false;
	while ($row = $result->fetch_assoc()) {
		$r = $row;
	}

	$result->free();
	return $r;
}

/*
**	get the already used jokers for the given player
*/
function db_round_jokers_by_pid($pid)
{
	/*
	**	count the entries for that game
	*/
	if (!($result = db_query("SELECT SUM(Jokers) FROM Rounds WHERE Pid=$pid")))
		return false;

	$row = $result->fetch_assoc();
	$result->free();
	return $row['SUM(Jokers)'];
}

/*
**	check if a game was played by all players
*/
function db_round_played($gid)
{
	/*
	**	count the entries for that game
	*/
	if ($result = db_query("SELECT COUNT(*) FROM Rounds WHERE Gid=$gid AND Points IS NOT NULL")) {
		$row = $result->fetch_assoc();
		$result->free();
		$players_with_points = $row['COUNT(*)'];
	}
	else
		$players_with_points = 0;

	/*
	**	count the entries for that game
	*/
	if ($result = db_query("SELECT COUNT(*) FROM Rounds WHERE Gid=$gid AND Jokers IS NOT NULL")) {
		$row = $result->fetch_assoc();
		$result->free();
		$players_with_jokers = $row['COUNT(*)'];
	}
	else
		$players_with_jokers = 0;

	return array($players_with_points,$players_with_jokers);
}

/*
**	write the points and jokers for one player to the database
*/
function db_round_update($gid,$pid,$points,$jokers)
{
	db_round_update_points($gid,$pid,$points);
	return db_round_update_jokers($gid,$pid,$jokers);
}

function db_round_update_points($gid,$pid,$points)
{
	return db_query("INSERT INTO Rounds (Gid,Pid,Points) VALUES($gid,$pid,$points) ON DUPLICATE KEY UPDATE Points=$points");
}

function db_round_update_jokers($gid,$pid,$jokers)
{
	return db_query("INSERT INTO Rounds (Gid,Pid,Jokers) VALUES($gid,$pid,$jokers) ON DUPLICATE KEY UPDATE Jokers=$jokers");
}

/*
**	delete all entries for a game
*/
function db_round_clear($gid)
{
	return db_query("DELETE FROM Rounds WHERE Gid=$gid");
}
/*
**	=========================================================
**
**	S C O R E B O A R D
**
**	=========================================================
*/

/*
**	get the complete scoreboard
*/
function db_scoreboard($sort = true)
{
	$players = db_player_list();
	$games = db_game_list();

	/*
	**	add the results to the player array
	*/
	for ($pnr = 0;$pnr < count($players);$pnr++) {
		$players[$pnr]['Points'] = 0;
		$players[$pnr]['Jokers'] = 0;
		foreach ($games as $game) {
			$result = db_round_by_gid_and_pid($game['Gid'],$players[$pnr]['Pid']);
			//dump_array('result',$result);

			$players[$pnr][$game['Gid']]['Points'] = $result['Points'];
			$players[$pnr]['Points'] += $result['Points'];
			$players[$pnr][$game['Gid']]['Jokers'] = $result['Jokers'];
			$players[$pnr]['Jokers'] += $result['Jokers'];
		}
	}

	if ($sort) {
		/*
		**	sort the table
		*/
		usort($players,function($a,$b) {
				if ($a['Points'] == $b['Points'])
					return strcasecmp($a['Nick'],$b['Nick']);
				return ($a['Points'] > $b['Points']) ? -1 : 1;
			});
	}

	return $players;
}

?>
