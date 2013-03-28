<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Admins', true), array('action'=>'index'));?></li>
	</ul>
</div>
<div class="admins form">
<?php echo $form->create('Admin');?>
	<fieldset>
		<legend><?php __('Add Admin');?></legend>
	<?php
	     echo $form->input('fname', array('label'=>'First Name'));
	     echo $form->input('lname', array('label'=>'Last Name'));
	     echo $form->input('email');
	     echo $form->input('password');
	     echo $form->input('confirm_password', array('type'=>'password'));
	     echo $form->input('admin_type_id');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
