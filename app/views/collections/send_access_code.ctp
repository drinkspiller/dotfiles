<div class="actions">
    <ul>
	<li><?php echo $html->link(__('Back to ' . $collection['Collection']['name'], true), array('action'=>'view', $collection['Collection']['id']));?></li>
	<li><?php echo $html->link(__('List Collections', true), array('action'=>'index'));?></li>
    </ul>
</div>

<?
    //debug($recipients);
?>

<div class="collections form">
<?php echo $form->create(array('url' => $this->here));
    if(isset($errors)){
	    echo $html->div("error-message", "Did not send. Please correct the following address(es): ");
	    foreach ($errors as $error) {
		echo $html->div("", $error);
	    }	
	}
?>
    
    <fieldset>
	<legend><?php __($collection['Collection']['name'] . ': Send Access Code');?></legend>
	<?php
	echo $form->input('id', array("value"=>$collection['Collection']['id']));
	echo $form->input('access_code', array("value"=> $collection['Collection']['access_code'], 'type'=>'hidden'));
	if(isset($asset_id)){
	    echo $form->input('asset_id', array("value"=> $asset_id, 'type'=>'hidden'));
	}
	echo $html->para(null, 'Send Access Code: ' . $collection['Collection']['access_code']);
	
	echo $form->input('subject', array('label'=>'Subject',
					   'type'=>'text',
					   'value'=>'Access Code to media assets from ' . COMPANY_NAME
					   )
			  );
	
	echo $form->input('Recipient.new_recipients', array('label'=>'New Recipients (separate with comma)', 'type'=>'text'));
	
	echo $form->input('Recipient.email', array(
					    'options'=>$recipients,
					    'label'=>'Previous Recipients',
					    'multiple'=>'checkbox',
					    'div'=>'recipient'
					    )
	 );
	 
	
	
	$body = "Access your media files from " . COMPANY_NAME . " here:\n" ;
	
	$url = 'http://' . $_SERVER['SERVER_NAME'] . "/";
	if(isset($asset_id)) {
	    $url .= "assets/view/" . $asset_id . "/" . $collection['Collection']['access_code'];
	    $dest = "/assets/view/" . $asset_id;
	} else{
	    $url .= "collections/view/" . $collection_id . "/" . $collection['Collection']['access_code'];
	    $dest = "/collections/view/" . $collection_id;
	}
	
	//$bitly = "http://api.bitly.com/v3/shorten?login=drinkspiller&apiKey=R_050a4b1e5532bcd0fb7fb78061132d40&longUrl=" . urlencode($url) . "&format=txt";
	//$handle = fopen($bitly, "r");
	//$short_url = stream_get_contents($handle);
	//fclose($handle);
	//$body .= $short_url;
	$body .= $url;
	
	echo $form->input('dest', array("value"=> $dest, 'type'=>'hidden'));
	echo $form->input('body', array('label'=>'Message',
					'type'=>'text',
					'rows' => '10',
					'cols' => '5',
					'value' => $body
					)
			 );
	
	?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>