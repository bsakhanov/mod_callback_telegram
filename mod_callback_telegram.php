<?php defined('_JEXEC') or die;
/*
 * @package     mod_callback_telegram
 * @copyright   Copyright (C) 2018 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

require_once __DIR__ . '/helper.php';

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$moduleclass_sfx = $moduleclass_sfx ? ' ' . $moduleclass_sfx : '';

$fields = ModCallbackTelegramHelper::getFields($params);
$showlabels = $params->get('showlabels', true);

JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
$doc->addScript('/modules/mod_callback_telegram/assets/mod_callback_telegram.js');

require JModuleHelper::getLayoutPath('mod_callback_telegram', $params->get('layout'));
