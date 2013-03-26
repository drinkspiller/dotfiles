<?php echo $form->create('Admin', array('action' => 'login')); ?>
<fieldset>
    <legend><?php __('Login');?></legend>
<?php
    if  ($session->check('Message.auth')) $session->flash('auth');
    
    echo $form->input('email');
    echo $form->input('password');
    echo $form->submit('login');
?>
</fieldset>
<?php  echo $form->end(); ?>