<?php defined('_JEXEC') or die;
/*
 * @package     mod_callback_telegram
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Log\Log;

class ModCallbackTelegramHelper
{

    static protected $urlUpd = 'https://api.telegram.org/bot%s/getUpdates';
    static protected $urlSend = 'https://api.telegram.org/bot%s/sendMessage?chat_id=%s&parse_mode=Markdown&text=%s';

    static protected function file_get_contents_curl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    static public function getCI($token)
    {
        $data = self::file_get_contents_curl(sprintf(self::$urlUpd, $token));
        $data = json_decode($data, true);
        return $data['result']['0']['message']['chat']['id'];
    }

    static public function getFields($params)
    {
        $items = (array)$params->get('items');
        foreach ($items as $key => $item) {
            $items[$key]->fname = OutputFilter::stringURLSafe($item->fname);
        }
        return $items;
    }

    static protected function checkForm()
    {
        $rsl = explode(':', filter_input(INPUT_POST, 'rsl', FILTER_SANITIZE_STRING));
        $base = Uri::base();
        $current = Uri::current();
        return
            !empty($_POST) && (
				($_SERVER['HTTP_REFERER'] == $base || $_SERVER['HTTP_REFERER'] == $base . 'index.php' || $_SERVER['HTTP_REFERER'] == $current) && 
				(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] == XMLHttpRequest : true) && 
				(count($rsl) === 2 && (int)$rsl[0] > 0 && (int)$rsl[1] > 0)
			);
    }

    static protected function getLayoutPath($extension, $layout = 'default')
    {
        $template = Factory::getApplication()->getTemplate();
        $defaultLayout = $layout;

        if (strpos($layout, ':') !== false) {
            $temp = explode(':', $layout);
            $template = $temp[0] === '_' ? $template : $temp[0];
            $layout = $temp[1];
            $defaultLayout = $temp[1] ?: 'default';
        }

        $tPath = JPATH_THEMES . '/' . $template . '/html/layouts/' . $extension . '/' . $layout . '.php';
        $bPath = JPATH_BASE . '/modules/' . $extension . '/layouts/' . $defaultLayout . '.php';
        $dPath = JPATH_BASE . '/modules/' . $extension . '/layouts/default.php';

        if (file_exists($tPath)) {
            return $tPath;
        }

        if (file_exists($bPath)) {
            return $bPath;
        }

        return $dPath;
    }


    static private function printJson($message, $result = false, $custom = [])
    {
        if (empty($message)) {
            $message = '< empty message >';
        }

        $jsonData = ['result' => $result, 'message' => $message];

        foreach ($custom as $key => $value) {
            $jsonData[$key] = $value;
        }

        echo json_encode($jsonData);

        exit;
    }

    static public function getAjax()
    {
        $extension = 'mod_callback_telegram';

        Log::addLogger(['text_file' => $extension . '.php', 'text_entry_format' => '{DATETIME}	{PRIORITY}	{MESSAGE}'], Log::ALL);

        if (!Session::checkToken()) {
            $data = [
                'referer' => $_SERVER['HTTP_REFERER'],
                'result' => 'Invalid token'
            ];
            Log::add(json_encode($data), Log::ERROR);
            self::printJson(Text::_('JINVALID_TOKEN'));
        }

        if (!self::checkForm()) {
            $data = [
                'referer' => $_SERVER['HTTP_REFERER'],
                'result' => 'Invalid checkform'
            ];
            Log::add(json_encode($data), Log::ERROR);
            self::printJson(Text::_('JINVALID_TOKEN'));
        }

        $language = Factory::getLanguage();
        $language->load($extension, JPATH_BASE, null, true);

        $module = ModuleHelper::getModule($extension);
        $params = new Registry();
        $params->loadString($module->params);
        $fields = self::getFields($params);
        $token = $params->get('token', '');
        $chatId = $params->get('getci', '');

        $data = [
            'title' => trim($params->get('tgmtitle', Text::sprintf('MOD_CALLBACK_TELEGRAM_PRM_TGMTITLE_DEFAULT', Uri::base()))),
            'prependText' => trim($params->get('premsg', '')),
            'appendText' => trim($params->get('postmsg', '')),
            'items' => [],
        ];

        $c = count($fields);
        for ($i = 0; $i < $c; $i++) {
            $val = trim(filter_input(INPUT_POST, $fields['items' . $i]->fname, FILTER_SANITIZE_STRING));
            if ($fields['items' . $i]->ftype == 'checkbox') {
                $val = $val ? Text::_('JYES') : Text::_('JNO');
            }
            $item = new StdClass();
            $item->name = $fields['items' . $i]->ftitle;
            $item->value = $val;
            $data['items'][] = $item;
        }
        unset($item);

        $layout = str_replace('\\', '/', self::getLayoutPath($extension, $params->get('msgtemplate')));
        ob_start();
        include $layout;
        $out = ob_get_clean();

        $result = json_decode(self::file_get_contents_curl(sprintf(self::$urlSend, $token, $chatId, $out)), true);

        $data['referer'] = $_SERVER['HTTP_REFERER'];
        $data['out'] = $out;
        $data['result'] = $result;
        unset($data['prependText'], $data['appendText']);

        Log::add(json_encode($data), $result['ok'] ? Log::INFO : Log::ERROR);

        if ($result['ok']) {
            self::printJson(Text::_('MOD_CALLBACK_TELEGRAM_SUBMIT_SUCCESSFULLY_MSG'), true);
        } else {
            self::printJson(Text::_('MOD_CALLBACK_TELEGRAM_SUBMIT_FAILED_MSG'));
        }
    }
}
