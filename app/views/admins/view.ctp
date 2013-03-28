<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Admin', true), array('action'=>'edit', $admin['Admin']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Admin', true), array('action'=>'delete', $admin['Admin']['id']), null, 'Are you sure you want to remove ' . $admin['Admin']['fname'] . ' ' . $admin['Admin']['lname'] . ' as an admin?'); ?> </li>
		<li><?php echo $html->link(__('List Admins', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Admin', true), array('action'=>'add')); ?> </li>
	</ul>
</div>

<div class="admins view">
<h2><?php  __('Admin');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		    <?php echo $admin['Admin']['id']; ?>
		    &nbsp;
	    </dd>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fname'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		    <?php echo $admin['Admin']['fname']; ?>
		    &nbsp;
	    </dd>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Lname'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		    <?php echo $admin['Admin']['lname']; ?>
		    &nbsp;
	    </dd>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		    <?php echo $admin['Admin']['email']; ?>
		    &nbsp;
	    </dd>
    </dl>
</div>