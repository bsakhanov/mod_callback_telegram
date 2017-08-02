<?php defined('_JEXEC') or die;

class mod_telegram_callbackInstallerScript
{

	public function preflight($type, $parent)
	{
		if ($type != 'uninstall') {
			$app = JFactory::getApplication();
			
			$jversion = new JVersion();
			if (!$jversion->isCompatible('3.2')) {
				$app->enqueueMessage('Please upgrade to at least Joomla! 3.2 before continuing!', 'error');
				return false;
			}
		}
		
		return true;
	}

}