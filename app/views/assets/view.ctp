<?
  
    // NOTE: $scalable_extensions, $qt_extensions and $direct_dowload_extensions defined in app_controller.php
    // NOTE: this page's functionality is closely tied with the contents of webroot/js/render-asset.js
    
    App::import('Vendor', 'AWS_SDK', array('file' => 'aws-sdk-1.5.3'.DS.'sdk.class.php'));
    App::Import('Vendor', 'utils');
    
    //debug($filename);
    //debug($extension);
    
    echo $javascript->	link(	array	(   'jquery.easing.1.3.js',
					    'jquery.metadata.js',
					    'jquery.media.js',
					    'jquery.maximage.js',
					    'render-asset.js'
					),
                    false
			    );
    
    $s3 = new AmazonS3();
    $info = $s3->get_object(BUCKET, $asset['Asset']['name'], array('range' => '0-10'));
    $download_link = $s3->get_object_url(BUCKET, $asset['Asset']['name'], '60 days', array(
                                                                                'response' => array(
                                                                                                    'content-disposition' => 'attachment; filename=' . $filename . "." . $extension
                                                                                                    )
                                                                                ));
    //echo '<pre>',var_dump($download_link),'</pre>';
    //echo '<pre>',print_r($info,1),'</pre>';
?>

<style type="text/css">
    #container{
	width: 97%;
    }
    
    #dl_wrapper{
	margin: 10px 0 20px 0;
    }
    
    .download_btn{
        display: block;
        background: url(/img/download_btn.gif) no-repeat;
        text-indent: -5000px;
        width: 120px;
        height: 30px;
        margin: 10px 0;
    }
</style>
<!--[if lt IE 7]>
    <script type="text/javascript" src="/js/unitpngfix.js"></script>
<![endif]--> 
<? if($session->read('currentuser')): ?>
<div class="actions">
    <ul>
	<li><?php echo $html->link("Back to Collection " . $asset['Collection']['name'], array('controller'=> 'collections', 'action'=>'view', $asset['Collection']['id'], $asset['Collection']['access_code']), array('target'=>'_top')); ?></li>
	<? if($session->read('currentuser')): ?>
	<li><?php echo $html->link(__('Edit Asset', true), array('action'=>'edit', $asset['Asset']['id']), array('target'=>'_top')); ?> </li>
	<li><?php echo $html->link(__('Delete Asset', true), array('action'=>'delete', $asset['Asset']['id']), array('target'=>'_top'), sprintf(__('Are you sure you want to PERMANENTLY delete this asset from the database\nAND ITS ASSOCIATED MEDIA from the server?', true), $asset['Asset']['id'])); ?> </li>
	<? endif; ?>
    </ul>
</div>
<? endif; ?>
<div class="assets view">
	<?
	echo '<h2>' . $html->image('asset_icons/64/' . $extension . '.png', array('class'=>'document_icon')) . $html->tag('span', $filename . "."  . $extension, array('class'=>'filename_view')) . '</h2>';
	
	$metadata = '';
	$controllerHeightOffset = 0;
	$controllerOnlyHeight = ($extension == 'wma')? 45 : 20;
	
	if(in_array($extension, $qt_extensions)){
	    $controllerHeightOffset = 15;
	}
    
	if(!$info->isOK()){
		if(Configure::read('debug')) echo $html->para('error', $response->status);
	}
	else{
	    if(isset($info->header['x-amz-meta-height'])){
            $w = $info->header['x-amz-meta-width'];
            $h = ($info->header['x-amz-meta-height'] + $controllerHeightOffset);

            $metadata = ' {width: ' . $w . ', height: ' .  $h . ', autoplay: false';

	    if(in_array($extension, $qt_extensions)){
                $metadata .= ', scale: \'TOFIT\', ';
                $metadata .= 'params: { scale: \'TOFIT\', controller: true, showlogo: false, cache: true, wmode: \'window\'}';
            }
            $metadata .= '}';
	    } else {
		$metadata = ' {height: ' .  $controllerOnlyHeight . ', autoplay: false}';
	    }
	    
	}
	
	
	//*
    // CHECK TO SEE IF THIS IS A 'DOWNLOAD ONLY' FILE OR IF IT SHOULD BE DISPLAYED INLINE
	if(in_array($extension, $direct_dowload_extensions)){
	    // DOWNLOAD ONLY
        $link_text = 'Download ' . $filename . "."  . $extension;
	    $link_class = "download";
	} else {
	    // DISPLAY INLINE
        $link_text = $filename . "."  . $extension;
	    
	    if(!$info->isOK()){
            $link_class = '';
	    } else{
            $link_class = 'media ' . $metadata;
	    }
        //echo 'link class:<pre>',print_r($link_class, 1),'</pre>';
	}
	//*/
	

    ?>
    
    <?php
        // MEDIA EMBED
        if(!in_array($extension, $direct_dowload_extensions)){
            print "<a href=\"{$info->header['x-aws-request-url']}\" class=\"$link_class\" title=\"Download  $link_text \">Download $link_text</a>";
        }
    ?>
    
	<a href="<?= $download_link ?>" class="download_btn" title="Download <?= $link_text ?>">Download <?= $link_text ?></a><br />
	<p>NOTE: ASSETS AUTOMATICALLY EXPIRE 60 DAYS AFTER THE DATE THEY ARE UPLOADED</p>

	<?
	if(in_array($extension, $scalable_extensions)){
	    echo 'View Scale: ';
	    echo $html->link(	'1x',
				'javascript: void(0);', 
				array('class'=>'1x')
			    );
	    echo ' | ';
	    echo $html->link(	'2x',
				'javascript: void(0);', 
				array('class'=>'2x')
			    );
	}
    ?>
    
    <dl><?php $i = 0; $class = ' class="altrow"';?>
	    
	    <?php
        $bytes = $info->header['content-range'];
        $bytes = explode("/", $bytes);
        $bytes = $bytes[count($bytes)-1];
        
        
        if($bytes > 0): ?>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('File Size'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo byteConvert($bytes); ?>
		&nbsp;
	    </dd>
	    <? endif ?>
	    
	    <?php if(isset($w) && isset($h)): ?>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Width/Height'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $w . " x " . $h ; ?>
		&nbsp;
	    </dd>
	    <? endif ?>
	    
	    <? if(isset($info->header['x-amz-meta-frame_rate'])): ?>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Framerate'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $info->header['x-amz-meta-frame_rate']; ?>
		&nbsp;
	    </dd>
	    <? endif ?>
	    <? if(isset($info->header['x-amz-meta-swf_version'])): ?>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('SWF Version'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $info->header['x-amz-meta-swf_version']; ?>
		&nbsp;
	    </dd>
	    <? endif ?>
	    
	    <? if(isset($info->header['x-amz-meta-bitrate']) && $extension != 'swf'): ?>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Bitrate'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo round($info->header['x-amz-meta-bitrate'], 2) . ' bytes'; ?> (<?php echo byteConvert($info->header['x-amz-meta-bitrate']); ?>)
		&nbsp;
	    </dd>
	    <? endif ?>
	    
	 
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo date("n/j/Y  g:ia" , strtotime($asset['Asset']['created'])); ?>
		&nbsp;
	    </dd>
	    
	    <!--<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $asset['Asset']['modified']; ?>
		&nbsp;
	    </dd>-->
	    
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Collection'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $html->link($asset['Collection']['name'], array('controller'=> 'collections', 'action'=>'view', $asset['Collection']['id'], $asset['Collection']['access_code']), array('target'=>'_top')); ?>
		&nbsp;
	    </dd>
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Admin'); ?></dt>
	    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $html->link($asset['Admin']['fname'] . " " . $asset['Admin']['lname'], 'mailto:' . $asset['Admin']['email'] . '?body=[ regarding http://' . env('HTTP_HOST') . urlencode(env('REQUEST_URI')) . ' ]'); ?>
		&nbsp;
	    </dd>
	    
	    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Access Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		    <?php
		    echo $asset['Collection']['access_code'];
		     if($session->read('currentuser')) echo " " .$html->link(__('send access to asset', true), array('controller'=>'collections','action'=>'send_access_code', $asset['Collection']['id'], $asset['Asset']['id']), array('target'=>'_top'));
		    ?>
		    &nbsp;
		</dd>
	    </dl>

</div>
