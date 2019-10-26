<?php
/*
**	html functions
**
*/

function html_print_big($text,$class = 'bigtext')
{
	if (strlen($text))
		print '<span class="'.$class.'">'.$text.'</span>';
}

function html_print_fail($text)
{
	if (strlen($text))
		print '<p><span class="bigtext message message_fail">'.$text.'</span><p>';
}

function html_print_info($text)
{
	if (strlen($text))
		print '<p><span class="bigtext message message_ok">'.$text.'</span><p>';
}

function html_separator()
{
	print '<p class="separator"></p>';
}

function html_header($page,$title)
{
	global $_SESSION;
	global $_round;
	?>
<html>
<head>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no" />
<link rel="SHORTCUT ICON" href="favicon.ico" title="external:favicon.ico">
<title><?php print TITLE ?></title>
<link href="css/common.css" rel="stylesheet" type="text/css">
<link href="css/button.css" rel="stylesheet" type="text/css">
<link href="css/input.css" rel="stylesheet" type="text/css">
<link href="css/numbers.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="3rdparty/jquery/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript">
<!--

(function () { 
	var clock = function () {
		clearTimeout(timer);
		date = new Date();    
		var timer = setTimeout(clock, 1000);
		$('#clock').html(
			('0' + Math.floor(date.getHours())).slice(-2)+':'+
			('0' + Math.floor(date.getMinutes())).slice(-2)+':'+
			('0' + Math.floor(date.getSeconds())).slice(-2));
	};
	clock();
	})();

-->
</script>
</head>
<body onload="initPage()">
<div id="background"></div>
<div class="header">
	<div class="header-left">
		<img class="logo" src="images/Z4HL3N.svg" onclick="document.location='?page=index';" border=0>
	</div>
	<div class="header-right"><?php
		if (@$_SESSION['User'] && @$_SESSION['User']['Uid']) {
				print htmlentities($_SESSION['User']['Username']);
				if (ADMIN) {
					print ' (Admin)';
				}
			?> <a href="index.php?page=login&type=logout" tabindex=-1>Abmelden</a><?php
		}
		else {
			?><a href="index.php?page=login" tabindex=-1>Anmelden</a><?php
		}
		?>
		<div class="clock" id="clock"></div>
	</div>
	<div class="header-center"><?php
			print $title;
	?></div>
</div>
<div class="page">
	<?php
}

function html_footer()
{
	?>
</div>
<div class="dimm" id="dimm">
</div>
</body>
</html>
	<?php
}

$button_select_redered = 0;

function html_button($id,$title,$onclick = 'return false;',$tabindex = -1,$class = 'button')
{
	global $button_select_redered;

	if (!$button_select_redered) {
		$button_select_redered = 1;
?>
<script language="JavaScript" type="text/javascript">
<!--

function button_select(id,onoff)
{
	var button = document.getElementById('button_' + id);

	button_warn(id,0);
	if (button) {
		if (onoff) {
			button.style.border = '1px solid #a0a0a0';
			button.style.background = '#d0ffd0';
		}
		else {
			button.style.border = '1px solid #c0c0c0';
			button.style.background = '#d0d0d0';
		}
	}
}

function button_disable(id,onoff)
{
	var button = document.getElementById('button_' + id);

	button_warn(id,0);
	if (button) {
		if (onoff) {
			button.style.color = '#c0c0c0';
			button.style.cursor = 'auto';
			button.style.cursor = 'auto';
			button.onclick = null;
		}
		else {
			button.style.color = '#808080';
			button.style.cursor = 'pointer';
			button.onclick = null; // better restore the right event handler
		}
	}
}

function button_warn(id,onoff)
{
	var button = document.getElementById('button_' + id);

	if (button) {
		if (onoff) {
			button.style.border = '1px solid #f00000';
		}
		else {
			button.style.border = '1px solid #c0c0c0';
		}
	}
}

function button_keypress(ev,id)
{
	var button = document.getElementById('button_' + id);

	if (ev.keyCode != 9) {
		button.onclick();
	}
}

function input_warn(id,onoff)
{
	var input = document.getElementById(id);

	if (input) {
		if (onoff) {
			input.style.border = '1px solid #f00000';
		}
		else {
			input.style.border = '1px solid #c0c0c0';
		}
	}
}

function input_set(id,value)
{
	var input = document.getElementById(id);

	if (input) {
		input.value = value;
		input_warn(id,0);
	}
}

-->
</script>
<?php
	}
	?>
	<span class="<?php print $class ?>" id='button_<?php print $id ?>'
		onclick="<?php print $onclick ?>"
		onkeypress="button_keypress(event,'<?php print $id ?>')"
		tabindex="<?php print $tabindex ?>"
		><?php print $title ?></span>
	<?php
}

function html_button_href($title,$url,$width = 0,$class = '')
{
	?>
	<a href="<?php print $url ?>"
		class="button <?php print $class ?>"
		<?php print ($width != 0) ? 'style="width:'.$width.'px;"' : '' ?>
		><?php print $title ?></a>
	<?php
}
