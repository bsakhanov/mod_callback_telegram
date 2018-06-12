<?php defined('_JEXEC') or die;
/*
 * @package     mod_callback_telegram
 * @copyright   Copyright (C) 2018 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

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
		foreach ($items as $key => $item)
		{
			$items[$key]->fname = JFilterOutput::stringURLSafe($item->fname);
		}
		return $items;
	}

	static protected function checkForm()
	{
		$rsl = explode(':', filter_input(INPUT_POST, 'rsl', FILTER_SANITIZE_STRING));
		$base = JUri::base();
		$current = JUri::current();
		return 
			!empty($_POST) && 
			( 
				( $_SERVER['HTTP_REFERER'] == $base || $_SERVER['HTTP_REFERER'] == $base . 'index.php' || $_SERVER['HTTP_REFERER'] == $current ) && 
				( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ? $_SERVER['HTTP_X_REQUESTED_WITH'] == XMLHttpRequest : true ) &&
				( count($rsl) === 2 && (int)$rsl[0] > 0 && (int)$rsl[1] > 0 )
			);
	}
	
	static protected function getLayoutPath($extension, $layout = 'default')
	{
		$template = \JFactory::getApplication()->getTemplate();
		$defaultLayout = $layout;

		if (strpos($layout, ':') !== false)
		{
			$temp = explode(':', $layout);
			$template = $temp[0] === '_' ? $template : $temp[0];
			$layout = $temp[1];
			$defaultLayout = $temp[1] ?: 'default';
		}

		$tPath = JPATH_THEMES . '/' . $template . '/html/layouts/' . $extension . '/' . $layout . '.php';
		$bPath = JPATH_BASE . '/modules/' . $extension . '/layouts/' . $defaultLayout . '.php';
		$dPath = JPATH_BASE . '/modules/' . $extension . '/layouts/default.php';

		if (file_exists($tPath))
		{
			return $tPath;
		}

		if (file_exists($bPath))
		{
			return $bPath;
		}

		return $dPath;
	}

	static public function getAjax()
	{
		$uri = JFactory::getURI();
		$extension = 'mod_callback_telegram';
		
		JLog::addLogger( ['text_file' => $extension . '.php', 'text_entry_format' => '{DATETIME}	{PRIORITY}	{MESSAGE}'], JLog::ALL );

		if (!JSession::checkToken()) 
		{
			$data = [
				'referer' => $_SERVER['HTTP_REFERER'],
				'result' => 'Invalid token'
			];
			JLog::add(json_encode($data), JLog::ERROR);
			jexit(JText::_('JINVALID_TOKEN'));
		}

		if (!self::checkForm())
		{
			$data = [
				'referer' => $_SERVER['HTTP_REFERER'],
				'result' => 'Invalid checkform'
			];
			JLog::add(json_encode($data), JLog::ERROR);
			jexit(JText::_('JINVALID_TOKEN'));
		}
		
		$app = JFactory::getApplication();
		$language = JFactory::getLanguage();
		$language->load($extension, JPATH_BASE, null, true);
		
		$module = JModuleHelper::getModule($extension);
		$params = new JRegistry();
		$params->loadString($module->params);
		$fields = self::getFields($params);
		$token = $params->get('token', '');
		$chatId = $params->get('getci', '');
		
		$data = [
			'title' => trim($params->get('tgmtitle', JText::sprintf('MOD_CALLBACK_TELEGRAM_PRM_TGMTITLE_DEFAULT', JUri::base()))),
			'prependText' => trim($params->get('premsg', '')),
			'appendText' => trim($params->get('postmsg', '')),
			'items' => [],
		];
		
		$c = count($fields);
		for($i = 0; $i < $c; $i++)
		{
			$val = trim(filter_input(INPUT_POST, $fields['items' . $i]->fname, FILTER_SANITIZE_STRING));
			if ($fields['items' . $i]->ftype == 'checkbox')
			{
				$val = $val ? JText::_('JYES') : JText::_('JNO');
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
		
		if ($result['ok'])
		{
			$app->enqueueMessage(JText::_('MOD_CALLBACK_TELEGRAM_SUBMIT_SUCCESSFULLY_MSG'));
		}
		else
		{
			$app->enqueueMessage(JText::_('MOD_CALLBACK_TELEGRAM_SUBMIT_FAILED_MSG'), 'error');
		}
		
		$data['referer'] = $_SERVER['HTTP_REFERER'];
		$data['out'] = $out;
		$data['result'] = $result;
		unset($data['prependText'], $data['appendText']);
		
		JLog::add(json_encode($data), $result['ok'] ? JLog::INFO : JLog::ERROR);

		$app->redirect($uri);
	}
}
