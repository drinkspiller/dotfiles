<div class="actions">
    <ul>
	<li><?php echo $html->link(__('List Tags', true), array('action'=>'index'));?></li>
	<li><?php echo $html->link(__('List Collections', true), array('controller'=> 'collections', 'action'=>'index')); ?> </li>
	<li><?php echo $html->link(__('New Collection', true), array('controller'=> 'collections', 'action'=>'add')); ?> </li>
    </ul>
</div>
<div class="tags form">
<?php echo $form->create('Tag');?>
    <fieldset>
	<legend><?php __('Add Tag');?></legend>
    <?php
	echo $form->input('name');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>