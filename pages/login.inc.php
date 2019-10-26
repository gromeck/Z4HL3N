<?php
/*
**	login form
**
**	Christian Lorenz
*/

$errmsg = '';
if ($_GET) {
	if (@$_GET['type'] == 'logout') {
		unset($_SESSION['User']);
		?>
		<script>
		document.location = '?page=';
		</script>
		<?php
		return;
	}
}
if ($_POST) {
	//print_r($_POST);
	/*
	**	we received a POST request
	*/
	if ($user = db_user_authenticate($_POST['username'],$_POST['password'])) {
		/*
		**	username was valid, so push the user into the session context
		*/
		$_SESSION['User'] = $user;
		?>
		<script>
		document.location = '?page=';
		</script>
		<?php
		return;
	}
	else {
		$errmsg .= "Authentisierungfehler!<br>";
		unset($_SESSION['User']);
	}
}

?>
<script language="JavaScript">
$(document).ready(function() {
	$('#username').focus();
});

function clickedLogin()
{
	document.forms['loginform'].submit();
}

function clickedCancel()
{
	document.location = '?page=';
}
</script>

<center>
<table>
<form name=loginform method=post>
	<tr>
		<td class=bigtext>Benutzername:</td>
		<td><input name=username id=username type=text tabindex=1></td>
	</tr>
	<tr>
		<td class=bigtext>Kennwort:</td>
		<td><input name=password id=password type=password tabindex=2></td>
	</tr>
	<tr>
		<td></td>
		<td>
		<?php
			html_separator();
			html_button('login','Anmelden','clickedLogin();');
			html_button('cancel','Abbruch','clickedCancel()');
		?>
		</td>
	</tr>
</form>
</table>
<?php foreach (explode('<br>',$errmsg) as $line) html_print_fail($line); ?>
</center>

<?php
