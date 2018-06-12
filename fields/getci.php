<?php defined('JPATH_PLATFORM') or die;
/*
 * @package     mod_callback_telegram
 * @copyright   Copyright (C) 2018 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

class JFormFieldGetci extends JFormFieldText
{
	protected $type = 'getci';

	protected function getInput()
	{
		$path = str_replace(JPATH_SITE, '', dirname(__FILE__));
		$path = str_replace('\\', '/', $path);
		
		$label = (string)$this->element['btnlabel'];
		$label = (!empty($label) ? $label : 'Get ID');
		
		$layout = '<div class="input-append">' .
			$this->getRenderer('joomla.form.field.text')->render($this->getLayoutData()) . 
			'<button id="' . $this->name . '-getci" type="button" class="btn btn-success" onclick="getChatId()">' . JText::_($label) . '</button></div>
			<script type="text/javascript">
				function getChatId() {
					var token = jQuery("#jform_params_token").val();
					if (token.length == 0) {
						//return;
					}
					jQuery.ajax({
						type: "POST",
						cache: false,
						dataType: "json",
						url: "' . $path . '/getci_ajax.php",
						data: {
							tkn: token
						},
						beforeSend: function () {
							jQuery("#getci").attr("disabled", "disabled");
						},
						success: function (data) {
							if (data !== null)
								jQuery("#jform_params_' . $this->name . '").val(data);
							jQuery("#getci").removeAttr("disabled");
						},
						complete: function (data) {
							jQuery("#jform_params_' . $this->name . '").val("success");
							jQuery("#getci").removeAttr("disabled");
						},
						error: function (data) {
							alert("Failed: view error in console");
							console.log(data);
							jQuery("#getci").removeAttr("disabled");
						}
					});
				}
			</script>';
		
		return $layout;
	}
}