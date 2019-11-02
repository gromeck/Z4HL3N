<?php
/*
**	Christian Lorenz
*/

/*
**	get a bunch of numbers --  one number per two seconds should be ok ;-)
*/
$numbers = Numbers::getNumbers(__TIME_TO_PLAY__ / 2);

/*
**	get the aprameters
*/

?>
<script language="JavaScript">
const COLOR_FOREGROUND = "#cceaf5";
const COLOR_BACKGROUND = "#0a2129";
const COLOR_BACKGROUND2 = "#f5d1cc";
const COLOR_OK = 'green';
const COLOR_KO = 'red';
var _state = "init",_score = 0,_numidx = 0;
var _color_secs;
var _timer;
var _timer_stopped;
var _time_left;

const TIME_TO_PLAY = <?php print __TIME_TO_PLAY__ ?>;
const TIME_LAST_SECS = 10;
const COLOR_SECONDS = COLOR_FOREGROUND;
const COLOR_LAST_SECONDS = 'red';
const MILLISECONDS_PER_TICK = 100;
const MILLISECONDS_PER_SECOND = 1000;

var _numbers = [
<?php
	$idx = 0;
	foreach ($numbers as $number)
		print '{ number: '.$number['number'].', readable: "'.$number['readable'].'", text: "'.$number['text'].'", html: "'.$number['html'].'" },'."\n";
?>
{ } ];

$( document ).ready(function() {
	clicked();
});

/*
**	init the timer
*/
function timer_init(seconds)
{
	_time_left = seconds * MILLISECONDS_PER_SECOND;
	_timer_stopped = 1;
	timer_display();
}

function timer_tick()
{
	if ((_time_left -= MILLISECONDS_PER_TICK) <= 0) {
		timer_stop();
		timer_timeout();
	}
	timer_display();
}

function timer_blink()
{
	$('#state-time-seconds').css('visibility',($('#state-time-seconds').css('visibility') == 'visible') ? 'hidden' : 'visible');
}

/*
**	start/continue the timer
*/
function timer_start()
{
	$('#state-time-seconds').css('visibility','visible');
	_timer_stopped = 0;
	clearInterval(_timer);
	_timer = setInterval(timer_tick,MILLISECONDS_PER_TICK);
}

/*
**	stop/pause the timer
*/
function timer_stop()
{
	_timer_stopped = 1;
	clearInterval(_timer);
	_timer = setInterval(timer_blink,MILLISECONDS_PER_SECOND / 2);
	timer_display();
}

/*
**	time is up -- timeout
*/
function timer_timeout()
{
	clearInterval(_timer);
	$('#state-time-seconds').css('visibility','visible');
	_time_left = 0;
	timer_display();
	_state = 'timeout';
	clicked();
}

/*
**	display the current time
*/
function timer_display()
{
	var seconds = _time_left / MILLISECONDS_PER_SECOND * MILLISECONDS_PER_TICK / MILLISECONDS_PER_TICK;
	$('#state-time-seconds').css('color',(_time_left / MILLISECONDS_PER_SECOND <= TIME_LAST_SECS) ? COLOR_LAST_SECONDS : COLOR_SECONDS);
	$('#state-time-seconds').text(seconds.toFixed(1) + "s");
}

function clicked()
{
	//alert('clicked: _state=' + _state);
	switch (_state) {
		case '':
		case 'init':
			/*
			**	start the game
			*/
			_numidx = 0;
			_score = 0;
			timer_init(TIME_TO_PLAY);
			$('#number-text-field').html('In diesem Feld erscheinen die ausgeschriebenen Zahlen.');
			$('#number-input-field').val('');
			$('#number-solution-field').css('visibility','hidden');
			$('#solve-text').html('Sobald du bereit bist, drücke <b>Start</b>.');
			$('#solve-button').val('Start');
			$('#solve-button').focus();
			$('#number-input-field').css('visibility','hidden');
			$("#number-input-field").inputFilter(function(value) { return /^\d*$/.test(value); });
			$("#number-input-field").prop("readonly",true);
			_state = 'start';
			break;
		case 'start':
		case 'waiting to continue':
			/*
			**	start the game
			*/
			$('#number-solution-field').css('visibility','hidden');
			$('#number-text-field').html(_numbers[_numidx]['html']);
			$('#number-solution-field').val(_numbers[_numidx]['number']);
			$('#number-input-field').css('visibility','visible');
			$('#number-input-field').css('background-color',COLOR_BACKGROUND2);
			$('#number-input-field').val('');
			$("#number-input-field").prop("readonly",false);
			$('#number-input-field').focus();
			$('#solve-text').html('Gebe die richtige Zahl bestehend aus Ziffern ein und klicke <b>Lösen</b>.');
			$('#solve-button').val('Lösen');
			timer_start();
			_state = 'waiting for solution';
			break;
		case 'waiting for solution':
			/*
			**	player clicked Lösen
			*/
			timer_stop();
			$('#number-solution-field').css('visibility','visible');
			$("#number-input-field").prop("readonly",true);
			if ($('#number-solution-field').val() == $('#number-input-field').val().trim()) {
				++_score;
				$('#number-input-field').css('background-color',COLOR_OK);
				msg = 'Sehr gut, richtig. ';
			}
			else {
				$('#number-input-field').css('background-color',COLOR_KO);
				msg = 'Nein, leider falsch. ';
			}
			++_numidx;
			$('#solve-text').html(msg + 'Klicke auf <b>Weiter</b> für die nächste Zahl.');
			$('#solve-button').val('Weiter');
			_state = 'waiting to continue';
			break;
		case 'timeout':
			/*
			**	no time left
			*/
			$('#solve-button').val('Neustart');
			$('#solve-text').html('<font color=red>Du hast <b>' + _score + '</b> Zahlen korrekt geschrieben.</font>');
			$('#number-solution-field').css('visibility','hidden');
			$('#number-input-field').css('visibility','hidden');
			_state = 'reload';
			break;
		case 'reload':
			/*
			**	reload the page to get new numbers
			*/
			location.reload();
			break;
		default:
			$('#solve-button').val('Neustart');
			$('#number-text-field').html('<font color=red>Fehler in State Maschine: _state=' + _state + '.</font>');
			$('#number-solution-field').css('visibility','hidden');
			$('#solve-button').focus();
			_state = 'init';
			break;
	}
	$('#state-score-points').text(_score + '/' + _numidx);
}

/*
**	handle key events
*/
$(document).focus();
$(document).keypress(function(event) {
		var keycode = (event.keyCode ? event.keyCode : event.which);
		//alert('keycode=' + keycode);
		if (keycode == 13 && (_state == 'waiting for solution' || _state == 'waiting to continue'))
			clicked();
	});

/*
**	implement input filter
*/
(function($) {
	$.fn.inputFilter = function(inputFilter) {
		return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
			if (inputFilter(this.value)) {
				this.oldValue = this.value;
				this.oldSelectionStart = this.selectionStart;
				this.oldSelectionEnd = this.selectionEnd;
			} else if (this.hasOwnProperty("oldValue")) {
				this.value = this.oldValue;
				this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
			}
		});
	};
}(jQuery));

</script>

<div>
	<div id=state>
		<div id=state-time>
			<div id=state-time-label>Zeit:</div>
			<div id=state-time-seconds>0</div>
		</div>
		<div id=state-score>
			<div id=state-score-label>Richtige Lösungen:</div>
			<div id=state-score-points>0/0</div>
		</div>
	</div>
	<div id=number-text-field>
	</div>
	<div id=number-solution>
		<input id=number-solution-field type=text value="" readonly>
	</div>
	<div id=number-input>
		<input id=number-input-field type=text>
	</div>
	<div id=solve>
		<div id=solve-text></div>
		<input id=solve-button type=button value="Button" onclick="clicked()">
	</div>
</div>
<?php

// dump_array('numbers',$numbers);
