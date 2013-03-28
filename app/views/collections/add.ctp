<div class="actions">
    <ul>
	<li><?php echo $html->link(__('List Collections', true), array('action'=>'index'));?></li>
	<li><?php echo $html->link(__('Edit Tags', true), array('controller'=> 'tags',
								'action'=>'index'));?></li>
    </ul>
</div>

<div class="collections form">
<?php echo $form->create('Collection');?>
    <fieldset>
	<legend><?php __('Add Collection');?></legend>
    <?php
	echo $form->input('name');
	echo $form->input('access_code', array('type'=> 'hidden', 'value'=>$access_code));
	echo $form->input('collection_type_id');
	echo $form->input('Tag.newtags', array	(
					'label'=>'New Tags (seperate multiple with commas)',
					'type'=>'text'
					)
			 );
	echo $form->input('Tag', array	(
					'label'=>'Existing Tags',
					'multiple'=>'checkbox',
					'div'=>'tag_wrapper'
					)
			 );
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>

<style type="text/css">
    .tag_wrapper{
	height: 300px;
	overflow: auto;
    }
</style>