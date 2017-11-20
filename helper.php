<?php defined('_JEXEC') or die;

class ModTelegramCallbackHelper
{
	
	static protected $urlUpd = 'https://api.telegram.org/bot%s/getUpdates';
	static protected $urlSend = 'https://api.telegram.org/bot%s/sendMessage?chat_id=%s&parse_mode=Markdown&text=%s';
	
	protected function file_get_contents_curl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	static public function getCI($token)
	{
		$ci = self::file_get_contents_curl(sprintf(self::$urlUpd, $token));
		$ci = json_decode($ci, true);
		$ci = $ci['result']['0']['message']['chat']['id'];
		return $ci;
	}
	
	static public function remake_array($in)
	{
		$out = array();
		$keys = array_keys($in);
		$c = count($in[$keys[0]]);
		for ($i = 0; $i < $c; $i++)
		{
			$a = array();
			foreach ($keys as $key)
			{
				$a[$key] = $in[$key][$i];
			}
			$out[] = $a;
			unset($a);
		}
		unset($keys);
		return $out;
	}

	protected function check_form()
	{
		return 
			!empty($_POST) && 
			( 
				( $_SERVER['HTTP_REFERER'] == JUri::base() || $_SERVER['HTTP_REFERER'] == JUri::base() . 'index.php' ) && 
				( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ? $_SERVER['HTTP_X_REQUESTED_WITH'] == XMLHttpRequest : true )
			);
	}
	
	public function getAjax()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		self::check_form() or jexit(JText::_('JINVALID_TOKEN'));
		$uri = JFactory::getURI();
		
		$app = JFactory::getApplication();
		$language = JFactory::getLanguage();
		$language->load('mod_telegram_callback', JPATH_BASE, null, true);
		
		$module = JModuleHelper::getModule('mod_telegram_callback');
		$params = new JRegistry();
		$params->loadString($module->params);
		$fields = self::remake_array(json_decode($params->get('frontlist'), true));
		$tgmtitle = $params->get('tgmtitle', '');
		$token = $params->get('token', '');
		$chat_id = $params->get('ci', '');
		
		$msg = $tgmtitle ? '*' . $tgmtitle . '*%0A%0A' : '*' . JText::sprintf('MOD_TELEGRAM_CALLBACK_PRM_TGMTITLE_DEFAULT', JUri::base()) . '*%0A%0A';
		$c = count($fields);
		
		for($i = 0; $i < $c; $i++)
		{
			$val = trim(filter_input(INPUT_POST, $fields[$i]['fname'], FILTER_SANITIZE_STRING));
			if ($fields[$i]['ftype'] == 'checkbox')
					$val = $val ? JText::_('JYES') : JText::_('JNO');
			$msg .= '*' . $fields[$i]['ftitle'] . '*:' . (($i+1)<$c ? ' ' . $val . '%0A' : '%0A' . urlencode($val));
		}
		
		$url = sprintf(self::$urlSend, $token, $chat_id, $msg);
		$result = json_decode(self::file_get_contents_curl(sprintf(self::$urlSend, $token, $chat_id, $msg)), true);
		
		if ($result['ok'])
		{
			$app->enqueueMessage(JText::_('MOD_TELEGRAM_CALLBACK_SUBMIT_SUCCESSFULLY_MSG'));
		} else {
			$app->enqueueMessage(JText::_('MOD_TELEGRAM_CALLBACK_SUBMIT_FAILED_MSG'), 'error');
		}
		$app->redirect($uri);
	}
}
