<div class="actions">
    <ul>
	<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Tag.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Tag.id'))); ?></li>
	<li><?php echo $html->link(__('List Tags', true), array('action'=>'index'));?></li>
	<li><?php echo $html->link(__('List Collections', true), array('controller'=> 'collections', 'action'=>'index')); ?> </li>
	<li><?php echo $html->link(__('New Collection', true), array('controller'=> 'collections', 'action'=>'add')); ?> </li>
    </ul>
</div>

<div class="tags form">
<?php echo $form->create('Tag');?>
	<fieldset>
 	    <legend><?php __('Edit Tag');?></legend>
	<?php
	    echo $form->input('id');
	    echo $form->input('name');
	    echo "<h4>Tag Used in These Collections</h4>";
	    echo '<ul>';
	    foreach($this->data['Collection'] as $coll){
		echo '<li>' . $html->link($coll['name'], array('controller'=>'collections', 'action'=>'view', $coll['id'])) . '</li>';
	    }
	    echo '</ul>';
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>