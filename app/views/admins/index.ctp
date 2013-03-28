<?
    //debug($admins);
?>

<div class="admins index">
<h2><?php __('Admins');?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Admin', true), array('action'=>'add')); ?></li>
	</ul>
</div>

<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('First Name', 'fname');?></th>
	<th><?php echo $paginator->sort('Last Name', 'lname');?></th>
	<th><?php echo $paginator->sort('Admin Type', 'admin_type_id');?></th>
	<th><?php echo $paginator->sort('email');?></th>
	<!--<th><?php echo $paginator->sort('password');?></th>-->
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($admins as $admin):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $admin['Admin']['fname']; ?>
		</td>
		<td>
			<?php echo $admin['Admin']['lname']; ?>
		</td>
		<td>
			<?php echo $admin['AdminType']['name']; ?>
		</td>
		<td>
			<?php echo $admin['Admin']['email']; ?>
		</td>
		<!--<td>
			<?php echo $admin['Admin']['password']; ?>
		</td>-->
		<td class="actions">
			<!--<?php echo $html->link(__('View', true), array('action'=>'view', $admin['Admin']['id'])); ?>-->
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $admin['Admin']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $admin['Admin']['id']), null, 'Are you sure you want to remove ' . $admin['Admin']['fname'] . ' ' . $admin['Admin']['lname'] . ' as an admin?'); ?>
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
