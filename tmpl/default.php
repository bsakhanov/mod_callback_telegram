<?php defined('_JEXEC') or die; ?>

<div class="mod_telegram_callback<?php echo $moduleclass_sfx; ?>">
	<form class="form-vertical" action="" method="post" enctype="multipart/form-data">
		<?php foreach( $fields as $i => $fielditem ) { ?>
		<div class="control-group">
			<?php
			switch ($fielditem['ftype'])
			{
				case 'text':
					echo
						($showlabels ? '<label class="control-label" for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label><div class="controls">' : ''),
						'<input type="text" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ($fielditem['fplaceholder'] ? ' placeholder="' . $fielditem['fplaceholder'] . '"' : '') . ' />',
						($showlabels ? '</div>' : '');
					break;
				
				case 'email':
					echo
						($showlabels ? '<label class="control-label" for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label><div class="controls">' : ''),
						'<input type="email" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ($fielditem['fplaceholder'] ? ' placeholder="' . $fielditem['fplaceholder'] . '"' : '') . ' />',
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
						($showlabels ? '<label class="control-label" for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label><div class="controls">' : ''),
						'<select name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ' >',
						$options,
						'</select>',
						($showlabels ? '</div>' : '');
					break;
				
				case 'textarea':
					echo
						($showlabels ? '<label class="control-label" for="' . $fielditem['fname'] . '">' . $fielditem['ftitle'] . '</label><div class="controls">' : ''),
						'<textarea name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ($fielditem['fplaceholder'] ? ' placeholder="' . $fielditem['fplaceholder'] . '"' : '') . '></textarea>',
						($showlabels ? '</div>' : '');
					break;
				
				case 'checkbox':
					echo
						($showlabels ? '<div class="controls">' : ''),
						'<label class="checkbox" for="' . $fielditem['fname'] . '">',
						'<input type="checkbox" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ' />',
						' ' . $fielditem['ftitle'] . '</label>',
						($showlabels ? '</div>' : '');
					break;
				
				case 'radio':
					echo ($showlabels ? '<label class="control-label" for="' . $fielditem['fname'] . '0">' . $fielditem['ftitle'] . '</label><div class="controls">' : '');
					$opts = explode(';', $fielditem['flist']);
					foreach ($opts as $j => $opt)
					{
						echo
							'<label for="' . $fielditem['fname'] . $j . '">',
							'<input type="radio" name="' . $fielditem['fname'] . '" id="' . $fielditem['fname'] . $j . '" value="' . $opt . '"' . ($fielditem['frequired'] ? ' required="required"' : '') . ' />',
							' ' . $opt . '</label>';
					}
					echo
						($showlabels ? '</div>' : '');
					break;
				
				default: break;
			}
			?>
		</div>
		<?php } ?>
		<?php echo ($showlabels ? '<div class="controls">' : ''); ?>
			<button type="submit" class="btn"><?php echo JText::_('MOD_TELEGRAM_CALLBACK_SUBMIT_LABEL'); ?></button>
		<?php echo ($showlabels ? '</div>' : ''); ?>
		<div>
			<input type="hidden" name="option" value="com_ajax" />
			<input type="hidden" name="module" value="telegram_callback" />
			<input type="hidden" name="format" value="raw" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
