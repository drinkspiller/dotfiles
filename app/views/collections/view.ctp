<?
    App::import('Vendor', 'AWS_SDK', array('file' => 'aws-sdk-1.5.3'.DS.'sdk.class.php'));
   
   
   //NOTE:  $scalable_extensions, $qt_extensions and $direct_dowload_extensions defined in app_controller.php
   //debug($assets);
   
    App::Import('Vendor', 'utils');
    echo $javascript->	codeBlock   (	"var asset_folder = '" . ASSET_UPLOAD_PATH . "';",
                                        array('inline'=>false)
                                    );
    echo $javascript->	link(array  (	'jquery-1.7.1.min.js',
                                        'swfobject.js',
                                        'jquery.filedrop.js',
                                        'jquery.uploadify.v2.1.4.js',
                                        'jquery.cookie.js',
                                        'jquery.color.js',
                                        'thickbox.js',
                                        'jquery.colorize-1.4.0.js',
                                        'jquery.tablednd_0_5.js',
                                        'upload.js',
                                        'main.js'
                                    ),
                                    false
                            );
    echo $html->css('thickbox', null, null, false);
    $paginator->options(array('url' => $this->passedArgs));
?>
<? if($session->read('currentuser')): ?>   
<script type="text/javascript">
   var last_order;
   $(document).ready(function() {
      $("#assets_tbl").tableDnD({
				    onDragClass: 'colorize_selected_row',
				    onDragStart: function(tbl, row){
				       last_order = $.tableDnD.serialize();
				    },
				    onDrop: function(tbl, row){
				       // only remove selection if order has changed
				       if(last_order != $.tableDnD.serialize()){
					  setTimeout("$(row).removeClass('colorize_selected_row')", 150);
					  zebraStripe();
				       }
				       
				       if(console) console.log($.tableDnD.serialize());
				       $.post('/assets/sequence/', $.tableDnD.serialize(), function(data) {
					  if(console) console.log("AJAX RETURNED: " + data);
					});
				    }
				 });
   });
</script>
<?php endif; ?>

<style type="text/css">
   #preparing_files_for_download{
      display: none;
   }
   #TB_ajaxContent img{
      margin: 10px 0;
   }
   
   #TB_ajaxContent{
      text-align: center;
   }
   #dropzone{
        width: 500px ;
        height: 50px;
        border: 1px dashed #555;
        margin: 15px 0;
        padding: 20px;
    }
    
    #dropzone{
        display: none;    
    }
    
    .dropzone_over{
        background-color: #e3fee2;
    }
    
    .upload{
        width: 500px;
        height: 30px;
        padding: 5px;
        border: 1px solid #dadada;
        background: #f5f5f5;
        margin: 5px 0;
        font-size: 11px;
    }
    
    .progress_bar{
        width: 100%;
        height: 3px;
        background: #ff8000;
        border: 1px solid #ec7600;
        margin-top: 5px;
    }
</style>

<div class="collections view">
    <h2><?php echo $collection['Collection']['name']; ?></h2>

    <dl><?php $i = 0; $class = ' class="altrow"';?>
	<input type="hidden" name="collection_id" id="collection_id" value="<?= $collection['Collection']['id']; ?>" />
	
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Access Code'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	    <?php echo $collection['Collection']['access_code']; ?>
	    <input type="hidden" name="access_code" id="access_code" value="<?php echo $collection['Collection']['access_code']; ?>" />
	    &nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	    <?php echo date("n/j/Y  g:ia" , strtotime($collection['Collection']['created'])); ?>
	    &nbsp;
	</dd>
    </dl>
</div>

<? if($session->read('currentuser')): ?>
<div class="actions">
    <ul>
	<li><?php echo $html->link(__('Send Access Code', true), array('action'=>'send_access_code', $collection['Collection']['id'])); ?></li>
	<li><?php echo $html->link(__('Edit Collection', true), array('action'=>'edit', $collection['Collection']['id'])); ?> </li>
	<li><?php echo $html->link(__('Delete Collection', true), array('action'=>'delete', $collection['Collection']['id']), null, sprintf(__('Are you sure you want to PERMANENTLY delete this collection\nAND ALL OF ITS ASSOCIATED MEDIA?', true), $collection['Collection']['id'])); ?> </li>
	<li><?php echo $html->link(__('List Collections', true), array('action'=>'index')); ?> </li>
	<li><?php echo $html->link(__('New Collection', true), array('action'=>'add')); ?> </li>
	<li><?php echo $html->link(__('Delete Selected Assets', true), 'javascript:void(0);', array('class'=>'delete_selected')); ?> </li>
    </ul>
</div>
<? endif; ?>


<h3>Assets</h3>

<p>NOTE: ASSETS AUTOMATICALLY EXPIRE 60 DAYS AFTER THE DATE THEY ARE UPLOADED</p>

<?php echo $html->link(__('Download Selected Assets', true), 'javascript:void(0);', array('class'=>'download_selected')); ?>
<? if($session->read('currentuser') || $bidirectional): ?>
    <input name="asset_upload" id="asset_upload" type="file" />
    <?php /*if($bidirectional): ?>
    <div id="upload_time_remaining">
	<strong>Guest Upload Ends In: </strong>
	<?php
	    $hours_remaining = ((SECONDS_IN_A_DAY - $bidirectional)/60)/60;
	    echo floor($hours_remaining) . " hours " ;
	    $minutes_remaining = fmod($hours_remaining, floor($hours_remaining)) * 60;
	    echo round($minutes_remaining) . " minutes";
	?>
    </div>
    <?php endif; */?>
    
    <div id="dropzone">
        Drag Files Here and Drop to Upload
    </div>
<? endif; ?>

<table cellpadding="0" cellspacing="0" id="assets_tbl" class="alt_color_table">
<tr class="nodrag nodrop">
    <th><?php echo 'Path';?></th>
    <th><?php echo $paginator->sort('Uploaded By','admin_id');?></th>
    <th><?php echo $paginator->sort('created');?></th>
   <!-- <th><?php echo $paginator->sort('modified');?></th> -->
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
    <tr<?php echo $class;?> <?php echo 'id="' . $asset['Asset']['id'] . '"' ?>>
	<td class="filename">
	    <?
		$pathInfo = pathinfo($asset['Asset']['name']);
		$basename = $pathInfo['basename'];
	    ?>
	    <?php echo $html->image('asset_icons/32/' . getFileExtension($asset['Asset']['name']) . '.png', array('class'=>'document_icon')) .
								    $html->link     (__($basename, true), 	array   ('controller'=> 'assets',
																'action'=>'view',
																$asset['Asset']['id'],
																$collection['Collection']['access_code']
														), 
											array   (   'class'=>'filename_list',
												    'title'=> $collection['Collection']['name'] . ": " . $basename
												)
										    );
	    ?>
	</td>
	<td>
	    <?php if($asset['Admin']['id']):?>
		<?php echo $html->link($asset['Admin']['fname'] . " " . $asset['Admin']['lname'], 'mailto:' . $asset['Admin']['email'] . "?subject=Collection: " . $collection['Collection']['name']); ?>
	    <?php else: ?>
		Guest
	    <?php endif; ?>
	</td>
	<td>
	    <?php echo date("n/j/Y  g:ia" , strtotime($asset['Asset']['created'])); ?>
	</td>
	<!--<td>
	    <?php echo $asset['Asset']['modified']; ?>
	</td>-->
	<td class="actions">
        <?php
            $s3 = new AmazonS3();
            $asset_name =  $collection['Collection']['id'] . $collection['Collection']['access_code'] . "/" . $basename;
            $download_link = $s3->get_object_url(BUCKET, $asset_name, '60 days',
                                                                                array(
                                                                                    'response' => array(
                                                                                                    'content-disposition' => 'attachment; filename=' . pathinfo($asset_name, PATHINFO_BASENAME)
                                                                                                    )
                                                                                ));
        ?>
	    <a href="<?= $download_link ?>" class="download_btn" title="Download">Download</a>
	    <?php
		
		echo $html->link  (__('View', true), 	array   (   'controller'=> 'assets',
									'action'=>'view',
									$asset['Asset']['id'],
									$collection['Collection']['access_code']
								    ), 
							    array   (   'class'=>'',
									'title'=> $collection['Collection']['name'] . ": " . $basename
								    )
				    );
		
		/*/
		$extension = getFileExtension($asset['Asset']['name']);
		if(in_array($extension, $direct_dowload_extensions)){
		    echo $html->link(   'Download',
					htmlentities(ASSET_REL_PATH . $asset['Asset']['name']),
					array('class'=>'')
				);	
		} else {
		    echo $html->link(   'Download',
					array('action'=>'download', 'controller'=> 'assets', htmlentities(ASSET_REL_PATH . $asset['Asset']['name'])),
					array('class'=>'')
				    );	
		}
		//*/
	

	    ?>
	    <? if($session->read('currentuser')): ?>
		<?php echo $html->link(__('Edit', true), array('controller'=> 'assets','action'=>'edit', $asset['Asset']['id'])); ?>
		<?php echo $html->link(__('Delete', true), array('controller'=> 'assets','action'=>'delete', $asset['Asset']['id'], $asset['Asset']['collection_id']), null, sprintf(__('Are you sure you want to PERMANENTLY delete this asset from the database\nAND ITS ASSOCIATED MEDIA from the server?', true), $asset['Asset']['id'])); ?>
	    <?php endif; ?>
	</td>
    </tr>
<?php endforeach; ?>
</table>

<div class="paging">
    <p>
<!-- ASSET PAGING -->
    <?php
    echo $paginator->counter(array(
    'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
    ));
    ?></p>
    <?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
    | 	<?php echo $paginator->numbers();?>
    <?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<!-- END ASSET PAGING -->

<div id="preparing_files_for_download">
   <div>
      <h2>Preparing Files</h2>
      Please wait while we prepare your files for download. This may take several minutes depending on the size of the files.<br />
      <img src="/img/ajax-loader.gif" width="32" height="22" alt="Please wait..." title="Please wait..." />
      
   </div>
</div>
<a href="#TB_inline?height=155&width=300&inlineId=preparing_files_for_download&modal=false" id="file_prep_trigger" class="thickbox"></a>

