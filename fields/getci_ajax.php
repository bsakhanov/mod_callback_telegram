<?php define('_JEXEC', 1);

require realpath(__DIR__ . '/../helper.php');

echo ModTelegramCallbackHelper::getCi($_REQUEST['tkn']);

exit;