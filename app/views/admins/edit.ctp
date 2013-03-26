<div class="actions">
	   <ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Admin.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Admin.id'))); ?></li>
		<li><?php echo $html->link(__('List Admins', true), array('action'=>'index'));?></li>
	   </ul>
</div>

<div class="admins form">
<?php echo $form->create('Admin');?>
	<fieldset>
 	   <legend><?php __('Edit Admin');?></legend>
	<?php
	    echo $form->input('id');
	    echo $form->input('fname', array('label'=>'First Name'));
	    echo $form->input('lname', array('label'=>'Last Name'));
	    echo $form->input('email');
	    if(isset($this->validationErrors) && isset($this->validationErrors['Admin']['password'])){
		$pw_arr = array('value'=>'', 'type'=>'password');
	    } else {
		$pw_arr = array('value'=>$this->data['Admin']['password'], 'type'=>'password');
	    }
	    echo $form->input('orig_password', array(
						     'type'=>'hidden',
						     'value'=>$this->data['Admin']['password']
						     ));
	    echo $form->input('password', $pw_arr);
	    echo $form->input('confirm_password', $pw_arr);
	    echo $form->input('admin_type_id');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
