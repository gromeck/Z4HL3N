<?php
/*
**	Christian Lorenz
*/
?>
<div class=intro>
	<?php print __TITLE__ ?> ist ein einfaches Spiel, bei dem ausgeschriebene Zahlen
	vorgegeben werden, die der Spieler ziffernweise eingeben muss.
	<p>
	Jeder korrekte Versuch wird gezählt.
	<p>
	Wie lange brauchst Du für <?php print __NUMBERS_TO_PLAY__ ?> Zahlen?
	<p>
	Viel Spaß!
</div>
<?php
	/*
	**	offer the options as buttons
	*/
	foreach ($pages as $pageid => $page) {
		if (($page['title'] || $page['button']) && $page['show'] && !$page['admin']) {
			html_button_href($page['button'] ? $page['button'] : $page['title'],'?page='.$pageid,-1);
		}
	}
?>	
