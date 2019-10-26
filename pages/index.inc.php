<?php
/*
**	Christian Lorenz
*/
?>
<script language="JavaScript">
function initPage()
{
	setTimeout("reloadPage()",60 * 1000);
}
function reloadPage()
{
	document.location = '?page=<?php print @$_GET['page'] ?>';
}

function clickedPlayer(pid)
{
	<?php if (ADMIN) { ?>
		document.location = '?page=<?php print @$_GET['page'] ?>&toggle=' + pid;
	<?php } else { ?>
		document.location = '?page=playerinfo&pid=' + pid;
	<?php } ?>
}
</script>
<?php

function print_list($players,$show_rounds = false)
{
	if (!$nplayers = count($players))
		return;
	$games = db_game_list();

	?>
	<center>
	<table class="table-player-board">
			<tr class="table-player-board-header">
				<td class="table-player-board-Platz">Platz</td>
				<td class="table-player-board-Player">Spieler</td>
				<td class="table-player-board-Joker">Joker</td>
				<?php
					if ($show_rounds) {
						?><td colspan=<?php print count($games); ?> class="table-player-board-Spiele">Spiele</td><?php
					}
				?>
				<td class="table-player-board-Punkte">Punkte</td>
			</tr>
		<?php
		for ($row = 0;$row < $nplayers;$row++) {
			?>
			<tr class="table-player-board-row table-player-board-row-<?php print ($row % 2) ? 'odd' : 'even' ?>">
				<td class="table-player-board-Platz"><?php print $row + 1 ?></td>
				<td class="table-player-board-Player"><?php print_player($players[$row]) ?></td>
				<td class="table-player-board-Joker"><?php print __JOKERS_PER_PLAYER__ - $players[$row]['Jokers'] ?></td>
				<?php
					if ($show_rounds) {
						foreach ($games as $game) {
							?>
								<td class="table-player-board-Spiele"><?php
									if (isset($players[$row][$game['Gid']]['Points']))
										print $players[$row][$game['Gid']]['Points'];
									else
										print '-'; ?></td>
							<?php
						}
					}
				?>
				<td class="table-player-board-Punkte"><?php print $players[$row]['Points']; ?></td>
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
print_list(db_scoreboard(),true);

//dump_array('scoreboard',db_scoreboard());
//dump_array('games',db_game_list());
//dump_array('rounds',db_round_list());
