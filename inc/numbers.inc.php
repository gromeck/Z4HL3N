<?php
/*
**	long numbers
**
*/
include '3rdparty/zahl-in-zahlwort-umwandeln.inc.php';

class Numbers {
	/*
	**	class to generate numbers like numbers from
	**	Schlag den Star: Zahlen schreiben (09.02.2019)
	**
	**						// length, ratio zeros : non-zeros, number of different non-zeros
	**		60020,			// 5,	3 : 2,	2
	**		8001032,		// 7,	3 : 4,	4
	**		4000020054,		// 10,	6 : 4,	3
	**		76006067,		// 8,	3 : 5,	2
	**		10011010011,	// 11,	5 : 6,	1
	**		12303009,		// 8,	3 : 5,	4
	**		4400440,		// 7,	3 : 4,	1
	**		917405,			// 6,	1 : 5,	5
	**		3000416001,		// 10,	5 : 5,	4
	**		459000,			// 6,	3 : 3,	3
	**		2100056,		// 7,	3 : 4,	4
	**		1000045,		// 7,	4 : 3,	3
	**						// length: 5 - 11
	**						// zeros:  1 - 6
	**						// non-zeros: 2 - 6
	**						// different non-zeros: 1 - 5
	*/

	/*
	**	generate a big number with some contraints
	**
	**	a. pick rand(1,5) different non-zero digits
	**	b. duplicate from this set until rand(2,6) digits are found
	**	c. get rand(1,6) zeros
	**	d. put all numbers together and shuffle them
	**	e. repeat shuffling until a non-zero is the first digit
	**	f. convert to (int)
	*/
	public static function getNumber()
	{
		$a = substr(str_shuffle('123456789'),0,rand(1,5));
		$len = rand(2,6);
		$b = $a;
		while (strlen($b) < $len)
			$b = str_shuffle($b.substr($b,0,1));
		$c = str_repeat('0',rand(1,6));
		$d = $b.$c;
		do
			$d = str_shuffle($d);
		while (substr($d,0,1) == '0');

		return (int) $d;
	}

	/*
	**	convert a number into readable text
	*/
	public static function getNumberReadable($number)
	{
		return preg_replace('/-/','',strtolower(num2text($number)));
	}

	/*
	**	convert a number into lowercase text without any blanks
	*/
	public static function getNumberText($number)
	{
		return preg_replace('/[ -]/','',strtolower(num2text($number)));
	}

	/*
	**	convert a number into lowercase text without blanks but wbr
	*/
	public static function getNumberHtml($number)
	{
		return preg_replace('/[ -]/','<wbr>',strtolower(num2text($number)));
	}

	/*
	**	generate an array of numbers and their texts
	*/
	public static function getNumbers($n)
	{
		$numbers = array();
		while ($n-- > 0) {
			$number = self::getNumber();
			$numbers[] = array(
				'number' => $number,
				'readable' => self::getNumberReadable($number),
				'text' => self::getNumberText($number),
				'html' => self::getNumberHtml($number),
			);
		}
		return $numbers;
	}
}
