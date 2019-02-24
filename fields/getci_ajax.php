<?php define('_JEXEC', 1);
/*
 * @package     mod_callback_telegram
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

require realpath(__DIR__ . '/../helper.php');

echo ModCallbackTelegramHelper::getCi(filter_input(INPUT_POST, ['tkn']));

exit;
