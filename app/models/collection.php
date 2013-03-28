<?php
App::Import('Vendor', 'passgen');

class Collection extends AppModel {

    var $name = 'Collection';
    var $validate = array(
	    'name' => array('notempty'),
	    'access_code' => array('notempty')
    );
    
    var $hasAndBelongsToMany = array('Tag', 'Recipient');

    var $hasMany = array(
		    'Asset' => array('className' => 'Asset',
							    'foreignKey' => 'collection_id',
							    'dependent' => true,
							    'conditions' => '',
							    'fields' => '',
							    'order' => '',
							    'limit' => '',
							    'offset' => '',
							    'exclusive' => '',
							    'finderQuery' => '',
							    'counterQuery' => ''
		    )
    );
    
    var $belongsTo = array  (
			    'CollectionType' => array('className' =>    'CollectionType',
									'foreignKey' => 'collection_type_id',
									'conditions' => '',
									'fields' => '',
									'order' => ''
			    )
    );
    
    function checkAccessCode($access_code){
	
	$collection = $this->find('count', array    (
						    'conditions'=> array('access_code'=>$access_code),
						    'recursive'=>-1
						    )
	);
	
	return $collection;
    }
    
    function getCollectionRecipients($collection_id){
	$sql = 'SELECT Recipient.email, Recipient.id from recipients as Recipient LEFT JOIN collections_recipients ON collections_recipients.recipient_id = Recipient.id ';
	$sql .= 'WHERE collections_recipients.collection_id = ' . $collection_id . ' ORDER BY Recipient.email ASC';
	$tmp = $this->query($sql);
	$recipients = array();
	
	foreach($tmp as $i => $recip){
	  $recipients['Recipient'][$i]['id'] = $recip['Recipient']['id'];
	  $recipients['Recipient'][$i]['email'] = $recip['Recipient']['email'];
	}
	
	return $recipients;
    }
    
    function makeAccessCode(){
	    $access_code = random_readable_pwd(14);
	    while($this->find('count', array('conditions'=>array('access_code'=>$access_code))) == 1) 
	    {
		$access_code = random_readable_pwd(14);
	    }
	    return $access_code;	
    }
    
    function mergeNewTags($data, $newTags){
	foreach($newTags as $newtag){
	    if  (!$this->Tag->find('count',
					    array(
						  'conditions'=>array('name'=>$newtag)
						 )
					    )
		&& $newtag != ""
	    )
	    {
		$this->Tag->create();
		$this->Tag->save(
				array('Tag'=>array('name'=>$newtag))
				);
		array_push($data['Tag']['Tag'], $this->Tag->id);
	    }
	}
	
	return $data;
    }
    
    function validateRecipients($recipient_list){
	$recipients = explode(",", str_replace(" ", "", $recipient_list));
	$bad = array();
	for($i = 0; $i < count($recipients); $i++){
	    if (strstr($recipients[$i], "@")==false || strstr($recipients[$i], ".")==false){
		array_push($bad, $recipients[$i]);
		array_shift($recipients);
	    }
	}
	return array('recipients'=>$recipients,
		     'invalid'=>$bad);
    }
    
    function prepRecipientData($data, $newRecipients, $session){
	foreach($newRecipients as $newRecipient){
	    if  (!$this->Recipient->find('count',
						array(
						      'conditions'=>array('email'=>$newRecipient)
						     )
						)
		&& $newRecipient != ""
	    )
	    {		
		$this->Recipient->create();
		if	(!$this->Recipient->save	(
						array('Recipient'=>array('email'=>$newRecipient))
						)
			)
		{
		    $session->setFlash(__('The Access Code was not sent. Please correct the errors below.', true));
		    return array('ValidationErrors' => $this->Recipient->validationErrors);
		} else {
		   array_push($data['Recipient']['Recipient'], $this->Recipient->id);
		}
	    } else{
		$recipient = $this->Recipient->find('first',
						array(
						      'recursive'=>-1,
						      'fields'=>'Recipient.id',
						      'conditions'=>array('email'=>$newRecipient)
						     )
						);
		if($data['Recipient']['Recipient']){
		    array_push($data['Recipient']['Recipient'], $recipient['Recipient']['id']);
		} else {
		    $data['Recipient']['Recipient'] = $recipient['Recipient']['id'];
		}
		
	    }
	}
	
	return $data;
   }
   
   function isBiDirectional($access_code){
	$collection = $this->find('first', array    (
						    'conditions'=> array('access_code'=>$access_code),
						    'recursive'=>-1
						    )
	);
	return ($collection['Collection']['collection_type_id'] == BIDIRECTIONAL);
   }
   
   function checkUploadTimeRemaining($id, $created){
	//if 24 hours have passed since the collection was created,
	// mark as unidirectional
	$diff = time()-strtotime($created);

	if( ($diff/SECONDS_IN_A_DAY) >=1){
	   $this->id = $id;
	   $this->saveField('collection_type_id', UNIDIRECTIONAL);
	   return 0;
	} else {
	    return $diff;
	}
   }

}
?>
