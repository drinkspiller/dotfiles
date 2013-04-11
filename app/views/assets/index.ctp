<div class="assets index">
<h2><?php __('Assets');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('collection_id');?></th>
	<th><?php echo $paginator->sort('admin_id');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($assets as $asset):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $asset['Asset']['id']; ?>
		</td>
		<td>
			<?php echo $asset['Asset']['name']; ?>
		</td>
		<td>
			<?php echo $html->link($asset['Collection']['name'], array('controller'=> 'collections', 'action'=>'view', $asset['Collection']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($asset['Admin']['id'], array('controller'=> 'admins', 'action'=>'view', $asset['Admin']['id'])); ?>
		</td>
		<td>
			<?php echo $asset['Asset']['created']; ?>
		</td>
		<td>
			<?php echo $asset['Asset']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $asset['Asset']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $asset['Asset']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $asset['Asset']['id']), null, sprintf(__('Are you sure you want to PERMANENTLY delete this asset from the database\nAND ITS ASSOCIATED MEDIA from the server?', true), $asset['Asset']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Asset', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Collections', true), array('controller'=> 'collections', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Collection', true), array('controller'=> 'collections', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Admins', true), array('controller'=> 'admins', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Admin', true), array('controller'=> 'admins', 'action'=>'add')); ?> </li>
	</ul>
</div>
