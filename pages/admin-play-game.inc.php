<?php
/*
**	Christian Lorenz
*/

/*
**	get the aprameters
*/
$gid = $_GET['gid'];

?>
<script language="JavaScript">
function initPage()
{
}

function reloadPage()
{
	document.location = '?page=<?php print @$_GET['page'].'&gid='.$gid ?>';
}

function clickedSetJokers(gid,pid,jokers)
{
	//alert('setJokers: gid=' + gid + ' pid=' + pid + ' jokers=' + jokers);
	$.get('ajax/points-and-jokers.php?gid=' + gid + '&pid=' + pid + '&jokers=' + jokers, function(data) {
		$( ".result" ).html( data );
			//alert("Joker gespeichert." );
			reloadPage();
		});
}

function clickedSetPoints(gid,pid,points)
{
	//alert('setPoints: gid=' + gid + ' pid=' + pid + ' points=' + points);
	$.get('ajax/points-and-jokers.php?gid=' + gid + '&pid=' + pid + '&points=' + points, function(data) {
		$( ".result" ).html( data );
			//alert("Punkte gespeichert." );
			reloadPage();
		});
}

function clickedClearGame(gid)
{
	if (confirm('Ergebnisse für dieses Spiel wirklich löschen?')) {
		//alert('clickedClearGame: gid=' + gid);
		$.get('ajax/clear-game.php?gid=' + gid, function(data) {
			$( ".result" ).html( data );
				//alert("Ergebnisse für dieses Spiel gelöscht." );
				reloadPage();
			});
	}
}
</script>
<?php

function print_list($players,$game)
{
	if (!$nplayers = count($players))
		return;
	$gid = $game['Gid'];

	?>
	<center>
	<table class="table-player-board">
			<tr class="table-player-board-header">
				<td class="table-player-board-Platz">Platz</td>
				<td class="table-player-board-Player">Spieler</td>
				<td class="table-player-board-SetJoker">Joker</td>
				<td class="table-player-board-SetPunkte">Punkte</td>
			</tr>
		<?php
		for ($row = 0;$row < $nplayers;$row++) {
			$r = db_round_by_gid_and_pid($gid,$players[$row]['Pid']);
			$players_jokers = db_round_jokers_by_pid($players[$row]['Pid']);
			$players_jokers -= $r['Jokers']; // subtracte the jokers for this game to offer the full range
			//dump_array('r',$r);
			?>
			<tr class="table-player-board-row table-player-board-row-<?php print ($row % 2) ? 'odd' : 'even' ?>">
				<td class="table-player-board-Platz"><?php print $row + 1 ?></td>
				<td class="table-player-board-Player"><?php print_player($players[$row]) ?></td>
				<td class="table-player-board-SetJoker"><?php
						/*
						**	how many jokers are allowed/left
						*/
						if ($game['Joker']) {
							$max_jokers = __JOKERS_PER_PLAYER__ - $players_jokers;
							$max_jokers = max($max_jokers,0);
							$max_jokers = min($max_jokers,__JOKERS_PER_PLAYER__);
						}
						else
							$max_jokers = 0;
						for ($jokers = 0;$jokers <= $max_jokers;$jokers++) {
							print '<span class="game-joker'.
								((isset($r['Jokers']) && $r['Jokers'] == $jokers) ? ' game-joker-highlight' : '').
								'" onClick="clickedSetJokers('.$gid.','.$players[$row]['Pid'].','.$jokers.');">'.$jokers.'</span>';
						}
					?></td>
				<td class="table-player-board-SetPunkte"><?php
						for ($points = 0;$points <= __POINTS_PER_GAME__;$points++) {
							print '<span class="game-point'.
								((isset($r['Points']) && $r['Points'] == $points) ? ' game-point-highlight' : '').
								'" onClick="clickedSetPoints('.$gid.','.$players[$row]['Pid'].','.$points.');">'.$points.'</span>';
						}
					?></td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
	html_button('clear','Ergebnisse l&ouml;schen','clickedClearGame('.$gid.');');
	html_button('cancel','zur&uuml;ck','window.history.back();');
	?>
	</center>
	<?php
}

/*
**	get all the data
*/
$game = db_game_lookup_by_gid($gid);
print_list(db_scoreboard(false),$game);
$_title = $game['GNr'].'. '.$game['Title'];

//dump_array('players',$players);
//dump_array('games',db_game_list());
//dump_array('rounds',db_round_list());
