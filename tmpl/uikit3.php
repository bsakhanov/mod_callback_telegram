<?php defined('_JEXEC') or die;
/*
 * @package     mod_callback_telegram
 * @copyright   Copyright (C) 2018 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

?>

<div class="mod_telegram_callback<?php echo $moduleclass_sfx; ?>">
	<form id="mod_telegram_callback_<?php echo $module->id; ?>" class="uk-form uk-form-stacked" action="" method="post" enctype="multipart/form-data">
		<?php foreach( $fields as $i => $fielditem ) { ?>
		<div class="uk-margin">
			<?php
			
			$class = trim($fielditem->fclass);
			$class = $class ? ' ' . $class : '';
			
			$placeholder = trim($fielditem->fplaceholder);
			$placeholder = $placeholder ? '  placeholder="' . $placeholder . '"' : '';

			$required = $fielditem->frequired ? ' required="required"' : '';

			switch ($fielditem->ftype)
			{
				case 'text':
				case 'email':
				case 'url':
				case 'tel':
				case 'password':
					echo
						($showlabels ? '<label class="uk-form-label" for="' . $fielditem->fname . '">' . $fielditem->ftitle . '</label>' : ''),
						'<div class="uk-form-controls">',
							'<input type="' . $fielditem->ftype . '" name="' . $fielditem->fname . '" id="' . $fielditem->fname . '" class="uk-input' . $class . '"' . $required . $placeholder . ' />',
						'</div>';
					break;
				
				case 'textarea':
					echo
						($showlabels ? '<label class="uk-form-label" for="' . $fielditem->fname . '">' . $fielditem->ftitle . '</label>' : ''),
						'<div class="uk-form-controls">',
							'<textarea name="' . $fielditem->fname . '" id="' . $fielditem->fname . '" class="uk-textarea' . $class . '"' . $required . $placeholder . '></textarea>',
						'</div>';
					break;
				
				case 'select':
					$options = '';
					$opts = explode("\n", $fielditem->flist);
					foreach ($opts as $opt)
					{
						$options .= '<option value="' . $opt . '">' . $opt . '</option>';
					}
					echo
						($showlabels ? '<label class="uk-form-label" for="' . $fielditem->fname . '">' . $fielditem->ftitle . '</label>' : ''),
						'<div class="uk-form-controls">',
							'<select name="' . $fielditem->fname . '" id="' . $fielditem->fname . '" class="uk-select' . $class . '"' . $required . ' >',
								$options,
							'</select>',
						'</div>';
					break;
				
				case 'checkbox':
					echo
						'<div class="uk-form-controls">',
							'<input type="checkbox" name="' . $fielditem->fname . '" id="' . $fielditem->fname . '" class="uk-checkbox' . $class . '"' . $required . ' />',
							'<label class="checkbox" for="' . $fielditem->fname . '">' . $fielditem->ftitle . '</label>',
						'</div>';
					break;
				
				case 'radio':
					echo ($showlabels ? '<label class="uk-form-label" for="' . $fielditem->fname . '0">' . $fielditem->ftitle . '</label>' : '');
					echo '<div class="uk-form-controls">';
					$opts = explode("\n", $fielditem->flist);
					foreach ($opts as $j => $opt)
					{
						echo
							'<input type="radio" name="' . $fielditem->fname . '" id="' . $fielditem->fname . $j . '" value="' . $opt . '" class="uk-radio' . $class . '"' . $required . ' />',
							'<label for="' . $fielditem->fname . $j . '">' . $opt . '</label>';
					}
					echo '</div>';
					break;
				
				default: break;
			}
			?>
		</div>
		<?php } ?>
		
		<div class="uk-margin">
			<button type="submit" class="uk-button uk-button-default"><?php echo JText::_('MOD_CALLBACK_TELEGRAM_SUBMIT_LABEL'); ?></button>
		</div>
		
		<input type="hidden" name="option" value="com_ajax" />
		<input type="hidden" name="module" value="callback_telegram" />
		<input type="hidden" name="format" value="raw" />
		<?php echo JHtml::_('form.token'); ?>

	</form>
</div>
