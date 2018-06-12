<?php defined('_JEXEC') or die;
/*
 * @package     mod_callback_telegram
 * @copyright   Copyright (C) 2018 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

$eol = '%0A'; // \n
$strongMark = '*';
$emMark = '_';

// title
echo $strongMark . $data['title'] . $strongMark;

// preppend text
if ($data['prependText'])
{
	echo $eol . $eol . $data['prependText'];
}

// fileds
foreach ($data['items'] as $item)
{
	// hidden empty values
	if (trim($item->value))
	{
		echo 
			$eol . 
			$eol . 
			$strongMark . $item->name . ':' . $strongMark . $eol . 
			$item->value;
	}
}

// append text
if ($data['appendText'])
{
	echo '%0A%0A' . $data['appendText'];
}
