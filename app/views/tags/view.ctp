<div class="tags view">
    
	<div class="actions">
	    <ul>
		    <li><?php echo $html->link(__('Edit Tag', true), array('action'=>'edit', $tag['Tag']['id'])); ?> </li>
		    <li><?php echo $html->link(__('Delete Tag', true), array('action'=>'delete', $tag['Tag']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $tag['Tag']['id'])); ?> </li>
		    <li><?php echo $html->link(__('List Tags', true), array('action'=>'index')); ?> </li>
		    <li><?php echo $html->link(__('New Tag', true), array('action'=>'add')); ?> </li>
		    <li><?php echo $html->link(__('List Collections', true), array('controller'=> 'collections', 'action'=>'index')); ?> </li>
		    <li><?php echo $html->link(__('New Collection', true), array('controller'=> 'collections', 'action'=>'add')); ?> </li>
	    </ul>
	</div>
	
	<h2><?php  echo 'Tag: ' . $tag['Tag']['name'];?></h2>
</div>

<div class="related">
    <h3><?php __('Related Collections');?></h3>
    <?php if (!empty($tag['Collection'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Name'); ?></th>
		<th><?php __('Access Code'); ?></th>
		<th><?php __('Created'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($tag['Collection'] as $collection):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $collection['name'];?></td>
			<td><?php echo $collection['access_code'];?></td>
			<td><?php echo date("n/j/Y  g:ia" , strtotime($collection['created']));?></td>
			<td class="actions">
				<?php echo $html->link(__('Open Collection', true), array('controller'=> 'collections', 'action'=>'view', $collection['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'collections', 'action'=>'edit', $collection['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'collections', 'action'=>'delete', $collection['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $collection['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
    <?php endif; ?>
</div>
