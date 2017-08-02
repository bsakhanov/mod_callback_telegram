<?php defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$layout = $params->get('layout', 'default');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$moduleclass_sfx = @$moduleclass_sfx ? ' ' . $moduleclass_sfx : '';

$fields = ModTelegramCallbackHelper::remake_array(json_decode($params->get('frontlist'), true));
$showlabels = $params->get('showlabels', true);

require JModuleHelper::getLayoutPath('mod_telegram_callback', $layout);
