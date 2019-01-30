<h1><?php echo lang('reset_password_heading');?></h1>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open('utilisateurs/reset_password/' . $code);?>

	<?php echo form_password($new_password['name'], sprintf(lang('reset_password_new_password_label'), $min_password_length));?>

	<?php echo form_password($new_password_confirm['name'], lang('reset_password_new_password_confirm_label', 'new_password_confirm'));?>

	<input type="hidden" name="<?= $user_id['name'] ?>" id="<?= $user_id['id'] ?>" value="<?= $user_id['value'] ?>" /> 
	<?php echo form_hidden($csrf); ?>

	<p><?php echo form_submit('submit', lang('reset_password_submit_btn'));?></p>

<?php echo form_close();?>