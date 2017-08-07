<?php defined('_JEXEC') or die; ?>

<div class="mod_telegram_callback<?php echo $moduleclass_sfx; ?>">
	<form class="uk-form uk-form-stacked" action="" method="post" enctype="multipart/form-data">
		<?php foreach( $fields as $i => $fielditem ) { ?>
		<div class="uk-margin">
			<?php
			switch ($fielditem['ftype'])
			{
				case 'text':
					echo
						($showlabels ? '<label class="uk-form-label" for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label><div class="uk-form-controls">' : ''),
						'<input class="uk-input" type="text" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ($fielditem['fplaceholder'] ? ' placeholder="' . $fielditem['fplaceholder'] . '"' : '') . ' />',
						($showlabels ? '</div>' : '');
					break;
				
				case 'email':
					echo
						($showlabels ? '<label class="uk-form-label" for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label><div class="uk-form-controls">' : ''),
						'<input class="uk-input" type="email" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ($fielditem['fplaceholder'] ? ' placeholder="' . $fielditem['fplaceholder'] . '"' : '') . ' />',
						($showlabels ? '</div>' : '');
					break;
				
				case 'select':
					$options = '';
					$opts = explode(';', $fielditem['flist']);
					foreach ($opts as $opt)
					{
						$options .= '<option value="' . $opt . '">' . $opt . '</option>';
					}
					echo
						($showlabels ? '<label class="uk-form-label" for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label><div class="uk-form-controls">' : ''),
						'<select class="uk-select" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ' >',
						$options,
						'</select>',
						($showlabels ? '</div>' : '');
					break;
				
				case 'textarea':
					echo
						($showlabels ? '<label class="uk-form-label" for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label><div class="uk-form-controls">' : ''),
						'<textarea class="uk-textarea" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ($fielditem['fplaceholder'] ? ' placeholder="' . $fielditem['fplaceholder'] . '"' : '') . '></textarea>',
						($showlabels ? '</div>' : '');
					break;
				
				case 'checkbox':
					echo
						($showlabels ? '<label class="uk-form-label" for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label><div class="uk-form-controls">' : ''),
						'<input class="uk-checkbox" type="checkbox" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ' />',
						(!$showlabels ? '<label for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label>' : ''),
						($showlabels ? '</div>' : '');
					break;
				
				case 'radio':
					echo ($showlabels ? '<label class="uk-form-label" for="' . $fielditem['fname'] . '0">' . $fielditem['ftitle'] . '</label><div class="uk-form-controls">' : '');
					$opts = explode(';', $fielditem['flist']);
					foreach ($opts as $j => $opt)
					{
						echo
							'<p>',
							'<input class="uk-radio" type="radio" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . $j . '" value="' . $opt . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ' />',
							'<label for="' . $fielditem['fname'] . $j . '">' . $opt . '</label>',
							'</p>';
					}
					echo
						($showlabels ? '</div>' : '');
					break;
				
				default: break;
			}
			?>
		</div>
		<?php } ?>
		<div class="uk-form-row">
			<button type="submit" class="uk-button"><?php echo JText::_('MOD_TELEGRAM_CALLBACK_SUBMIT_LABEL'); ?></button>
		</div>
		<div>
			<input type="hidden" name="option" value="com_ajax" />
			<input type="hidden" name="module" value="telegram_callback" />
			<input type="hidden" name="format" value="raw" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
