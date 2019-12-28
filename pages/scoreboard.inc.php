<?php
/*
**	Christian Lorenz
*/

if (ADMIN && $_GET['clear']) {
	/*
	**	clear the scoreboard
	*/
	db_score_clear();
}

$list = db_score_list();

//print_r($list);

?>
<div class=scoreboard>
	<center>
	<table class=score>
		<thead>
			<tr>
				<td class=score-rank>Platz</td>
				<td class=score-name>Spieler</td>
				<td class=score-numbers>Zahlen</td>
				<td class=score-time>Zeit</td>
				<td class=score-timestamp>Datum/Uhrzeit</td>
			</tr>
		</thead>
		<?php
			for ($n = 0;$n < @count(@$list);$n++) {
				?>
					<tr class=tr-<?php print ($n % 2) ? 'odd' : 'even' ?>>
						<td class=score-rank><?php print $n + 1 ?></td>
						<td class=score-name><?php print $list[$n]['Name'] ?></td>
						<td class=score-numbers><?php print $list[$n]['Numbers'] ?></td>
						<td class=score-time><?php printf("%.1f",$list[$n]['Time'] / 1000) ?>s</td>
						<td class=score-timestamp><?php print $list[$n]['Timestamp'] ?></td>
					</tr>
				<?php
			}
		?>
	</table>
	</center>
</div>
<?php
html_button_href('zurück','?page=start',-1);
if (ADMIN) {
	html_button_href('Rangliste löschen','?page='.$pageid.'&clear=1',-1);
}
?>
