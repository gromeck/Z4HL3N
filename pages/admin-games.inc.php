<?php
/*
**	a quick hack for the
**
**		1. Goddelauer Badminton Nachturnier
**
**
**	Christian Lorenz
*/
?>
<script language="JavaScript">
function initPage()
{
}
</script>
<?php

function print_list($games,$players)
{
	if (!$ngames = count($games))
		return;
	if (!$nplayers = count($players))
		return;

	?>
	<center>
	<table class="table-game-board">
			<tr class="table-game-board-header">
				<td class="table-game-board-Nummer">Nr.</td>
				<td class="table-game-board-Titel">Titel</td>
				<td class="table-game-board-Play">&nbsp;</td>
			</tr>
		<?php
		for ($row = 0;$row < $ngames;$row++) {
			?>
			<tr class="table-game-board-row table-game-board-row-<?php print ($row % 2) ? 'odd' : 'even' ?>">
				<td class="table-game-board-Nummer"><?php print $games[$row]['GNr']; ?></td>
				<td class="table-game-board-Titel"><?php print $games[$row]['Title']; ?></td>
				<td class="table-game-board-Play"><?php
					html_button_href('...','?page=admin-play-game&gid='.$games[$row]['Gid'],0,'button-small');
					list ($players_with_points,$players_with_jokers) = db_round_played($games[$row]['Gid']);
					if ($players_with_points == $nplayers)
						print '<img src="images/checkmark-green.png" align=top>';
					else if ($players_with_points > 0 || $players_with_jokers > 0)
						print '<img src="images/warning.png" align=top>';
					?></td>
			</tr>
			<?php
		}
		?>
	</table>
	</center>
	<?php
}

/*
**	get all the data
*/
$games = db_game_list();
$players = db_player_list();

print_list($games,$players);

//dump_array('games',db_game_list());
