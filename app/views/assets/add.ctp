<?php
App::Import('Vendor', 'utils');
App::import('Vendor', 'AWS_SDK', array('file' => 'aws-sdk-1.5.3'.DS.'sdk.class.php'));
?>
<tr>
    <td class="filename">
	<?
	    //print_r($asset);
        $pathInfo = pathinfo($asset['Asset']['name']);
	    $basename = $pathInfo['basename'];
	?>
	<?php
	    echo $html->image('asset_icons/32/' . getFileExtension($asset['Asset']['name']) . '.png', array('class'=>'document_icon')) .
								    $html->link     (__($basename, true), 	array   ('controller'=> 'assets',
																'action'=>'view',
																$asset['Asset']['id'],
																$asset['Collection']['access_code']
														), 
											array   (   'class'=>'filename_list',
												    'title'=> $collection['Collection']['name'] . ": " . $basename
												)
										    );
	?>
    </td>
    <td>
	<?php if($uploader_id) : ?>
	    <?php echo $html->link($asset['Admin']['fname'] . " " . $asset['Admin']['lname'], array('controller'=> 'admins', 'action'=>'view', $asset['Admin']['id'])); ?>
	<?php else: ?>
	    Guest
	<?php endif; ?>
    </td>
    <td>
	<?php echo date("n/j/Y  g:ia" , strtotime($asset['Asset']['created'])); ?>
    </td>
    <td class="actions">
    
	<?php
        
        $s3 = new AmazonS3();
        $asset_name =  $asset['Asset']['name'];
        $download_link = $s3->get_object_url(BUCKET, $asset_name, '60 days',
                                                                            array(
                                                                                'response' => array(
                                                                                                'content-disposition' => 'attachment; filename=' . pathinfo($asset_name, PATHINFO_BASENAME)
                                                                                                )
                                                                            ));
    ?>
    <a href="<?= $download_link ?>" class="download_btn" title="Download">Download</a>
	<?php
	    echo $html->link(__('View', true), array(
						    'controller'=> 'assets',
						    'action'=>'view', $asset['Asset']['id'],
						    $asset['Collection']['access_code']
						     ),
						    array('class'=>''));
	    
	?>
     
	<?php if($uploader_id) : ?>
	    <?php echo $html->link(__('Edit', true), array('controller'=> 'assets','action'=>'edit', $asset['Asset']['id'])); ?>
	    <?php echo $html->link(__('Delete', true), array('controller'=> 'assets','action'=>'delete', $asset['Asset']['id']), null, sprintf(__('Are you sure you want to PERMANENTLY delete this asset from the database\nAND ITS ASSOCIATED MEDIA from the server?', true), $asset['Asset']['id'])); ?>
	<?php endif; ?>
    </td>
</tr>