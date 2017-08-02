<?php defined('JPATH_PLATFORM') or die;

class JFormFieldGetci extends JFormField
{
	protected $type = 'getci';

	protected function getInput()
	{
		$p = str_replace(JPATH_SITE, '', dirname(__FILE__));
		$p = str_replace('\\', '/', $p);
		
		$label = (string)$this->element['btnlabel'];
		$label = (!empty($label) ? $label : 'Compile LESS');
		return '<button id="getci" type="button" class="btn btn-success" onclick="getChatId()">' . JText::_($label) . '</button>
<script type="text/javascript">
	function getChatId() {
		var token = jQuery("#jform_params_token").val();
		if (token.length == 0) {
			return;
		}
		jQuery.ajax({
			type: "POST",
			cache: false,
			dataType: "json",
			url: "' . $p . '/getci_ajax.php",
			data: {
				tkn: token
			},
			beforeSend: function () {
				jQuery("#getci").attr("disabled", "disabled");
			},
			success: function (data) {
				if (data !== null)
					jQuery("#jform_params_ci").val(data);
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
	}
}