<?php
/*
**	login form
**
**	Christian Lorenz
*/

if ($_POST) {
	/*
	**	store the name in the session and
	**	forward to the playing page
	*/
	$_SESSION['name'] = trim($_POST['name']);
	Header('Location: ?page=play');
	return;
}

?>
<script language="JavaScript">
$( document ).ready(function() {
	$('#name').focus().val('<?php print $_SESSION['name'] ?>');
});

function clickedSubmit()
{
	document.forms['nameform'].submit();
}

</script>
<div class=intro>
	Gebe deinen Namen ein,
	<br>damit dir ein Platz auf der Rangliste sicher ist:
	<p>
	<form name=nameform method=post>
		<input name=name id=name type=text value="" autocomplete=off>
		<?php
			html_separator();
			html_button_href('zurück','?page=start');
			html_button('submit','Ok & weiter','clickedSubmit();');
		?>
	</form>
</div>
<?php
