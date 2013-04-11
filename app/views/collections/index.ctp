<?
    //debug($collections);
    echo $javascript->link(array  (	'jquery-1.3.2.min.js',
                                        'jquery.colorize-1.4.0.js',
                                        'main.js'
                                        ),
                                        false
                                    );
?>

<div class="collections index">
<h2><?php __('Collections');?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Collection', true), array('action'=>'add')); ?></li>
	</ul>
</div>

<p>
<?php
    echo $paginator->counter(array(
    'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
    ));
?></p>

<?php
    //$s3usage= shell_exec ("s3cmd --config=/home/deploy/.s3cfg du s3://cbdam 2>&1");
    //$s3usage = explode(" ", $s3usage);
    //echo "";
?>
<script>
  var SizePrefixes = ' KMGTPEZYXWVU';
  function GetHumanSize(size) {
    if(size <= 0) return '0';
    var t2 = Math.min(Math.round(Math.log(size)/Math.log(1024)), 12);
    return (Math.round(size * 100 / Math.pow(1024, t2)) / 100) +
      SizePrefixes.charAt(t2).replace(' ', '') + 'B';
  }

  $(function() {
    $.get('/collections/getS3', function(data, textStatus, xhr) {
      $(".s3_total").html(GetHumanSize(parseInt(data)));
    });
  });


</script>
<h3>Total S3 Usage: <span class="s3_total"><?php echo $html->image("ajax-loader.gif")?></span></h3>
<table cellpadding="0" cellspacing="0"  id="collections_tbl" class="alt_color_table">
<tr>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('access_code');?></th>
    <th><?php echo 'Assets' ?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('collection_type_id');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($collections as $collection):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
            <?php echo $html->link(__($collection['Collection']['name'], true), array('action'=>'view', $collection['Collection']['id'], $collection['Collection']['access_code'])); ?>
		</td>
		<td>
			<?php echo $collection['Collection']['access_code']; ?>
		</td>
        <td>
			<?php echo count($collection['Asset']); ?>
		</td>
		<td>
			<?php echo date("n/j/Y  g:ia" , strtotime($collection['Collection']['created'])); ?>
		</td>
		<td>
			<?php echo $collection['CollectionType']['name']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Open Collection', true), array('action'=>'view', $collection['Collection']['id'], $collection['Collection']['access_code'])); ?>
            <?php echo $html->link(__('Send Access Code', true), array('action'=>'send_access_code', $collection['Collection']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $collection['Collection']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $collection['Collection']['id']), null, sprintf(__('Are you sure you want to PERMANENTLY delete this collection\nAND ALL OF ITS ASSOCIATED MEDIA?', true), $collection['Collection']['id'])); ?>
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