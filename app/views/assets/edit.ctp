<style type="text/css">
    .path_prefix{
	float: left;
	font-weight: normal;
	font-size:16px;
    }
</style>

<div class="actions">
    <ul>
	<!--<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Asset.id'), $this->data['Asset']['collection_id']), null, sprintf(__('Are you sure you want to PERMANENTLY delete this asset from the database\nAND ITS ASSOCIATED MEDIA from the server?', true), $form->value('Asset.id'))); ?></li>-->
	<li><?php echo $html->link(__('List Assets', true), array('controller'=>'collections','action'=>'view', $this->data['Asset']['collection_id']));?></li>
	<li><?php echo $html->link(__('List Collections', true), array('controller'=> 'collections', 'action'=>'index')); ?> </li>
	<li><?php echo $html->link(__('New Collection', true), array('controller'=> 'collections', 'action'=>'add')); ?> </li>
	<li><?php echo $html->link(__('List Admins', true), array('controller'=> 'admins', 'action'=>'index')); ?> </li>
	<li><?php echo $html->link(__('New Admin', true), array('controller'=> 'admins', 'action'=>'add')); ?> </li>
    </ul>
</div>

<div class="assets form">
<?php echo $form->create('Asset');?>
    <fieldset>
	    <legend><?php __('Edit Asset');?></legend>
    <?php
	echo $form->input('id');
	$pieces = split("/", $this->data['Asset']['name']);
	$prefix  = $pieces[0] . "/";
	echo $form->hidden('orig_name', array(
					'value'=> $this->data['Asset']['name']
					));
	echo $form->hidden('prefix', array(
					'value'=> $prefix
					));
	echo $form->hidden('collection_id', array(
					'value'=> $this->data['Asset']['collection_id']
					));
	echo $form->input('name', array(
					'value'=> $pieces[1],
					'between'=> '<div class="path_prefix">' . $prefix . "</div>"
					));
	echo $form->input('collection_id', array(
						'disabled'=> 'disabled'
						));
	echo $form->input('admin_id');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>