<?php defined('_JEXEC') or die;
/*
 * @package     mod_callback_telegram
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;

require_once __DIR__ . '/helper.php';

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$moduleclass_sfx = $moduleclass_sfx ? ' ' . $moduleclass_sfx : '';

$fields = ModCallbackTelegramHelper::getFields($params);
$showlabels = $params->get('showlabels', true);

HTMLHelper::script('modules/mod_callback_telegram/assets/mod_callback_telegram.js');

require ModuleHelper::getLayoutPath('mod_callback_telegram', $params->get('layout'));
