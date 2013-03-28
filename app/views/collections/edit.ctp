<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Collection.id')), null, sprintf(__('Are you sure you want to PERMANENTLY delete this collection\nAND ALL OF ITS ASSOCIATED MEDIA?', true), $form->value('Collection.id'))); ?></li>
        <li><?php echo $html->link(__('List Collections', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('New Collection', true), array('action'=>'add'));?></li>
        <li><?php echo $html->link(__('Edit Tags', true), array('controller'=> 'tags',
								'action'=>'index'));?></li>
    </ul>
</div>

<div class="collections form">
<?php echo $form->create('Collection');?>
    <fieldset>
        <legend><?php __('Edit Collection');?></legend>
    <?php
        echo $form->input('id');
        echo $form->input('name');
        echo $form->input('access_code', array('label'=> 'Access Code (read only)', 'disabled'=> 'disabled'));
	echo $form->input('collection_type_id');
        echo $form->input('Tag.newtags', array	(
					'label'=>'New Tags (seperate multiple with commas)',
					'type'=>'text'
					)
			 );
	echo $form->input('Tag', array	(
					'label'=>'Existing Tags',
					'multiple'=>'checkbox'
					)
			 );
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>