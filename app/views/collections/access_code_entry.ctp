<?php echo $form->create('Collections', array('action' => 'access_code_entry')); ?>
    <fieldset>
	<legend>Please Enter Your Access Code</legend>
    <?php
    echo $form->input('access_code');
     echo $form->submit('Fetch Collection');
    ?>
    </fieldset>
<?php echo $form->end(); ?>