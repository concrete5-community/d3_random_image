<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Application;

$form  = Application::getFacadeApplication()->make('helper/form');

/** @var array $fileSetOptions*/
?>

<div class="form-group">
	<?php
    if (count($fileSetOptions) > 0) {
		echo $form->label('fsID', t('Choose a file set'));
		?>
	
		<div class="input">
			<?php 
			echo $form->select('fsID', $fileSetOptions, $fsID);
			?>
		</div>
		<?php 
    }
    ?>
</div>

<div class="form-group">
	<?php 
	echo $form->label('max_width', t('Max width'));
	?>

	<div class="input">
		<?php 
		echo $form->number('max_width', $max_width);
		?>
	</div>
</div>

<div class="form-group">
	<?php 
	echo $form->label('max_height', t('Max height'));
	?>

	<div class="input">
		<?php 
		echo $form->number('max_height', $max_height);
		?>
	</div>
</div>

<div class="form-group">
	<label>
		<?php 
		echo $form->checkbox('do_crop', 1, $do_crop);
		?>
		
		<?php 
		echo t('Force image dimensions (crop)');
		?>
	</label>
</div>
