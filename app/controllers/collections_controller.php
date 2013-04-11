<?php
App::Import('Vendor', 'utils');
App::import('Vendor', 'AWS_SDK', array('file' => 'aws-sdk-1.5.3'.DS.'sdk.class.php'));

class CollectionsController extends AppController {

	var $name = 'Collections';
	var $helpers = array('Html', 'Form');
	var $components = array('Email');
	var $paginate = array(
						  'Asset' => array('order'=>array	(
										   'Asset.created' => 'desc')
						  ),
						  'Collection' => array('order'=>array	(
												'Collection.created' => 'desc')
						  )
						  );
	var $uses = array("Collection");
	var $scaffold;

	function beforeFilter(){
		parent::beforeFilter();
		$this->Session->write("currentuser", $this->Auth->user());
		$this->set('is_admin', $this->Auth->user());
	}
	function index() {
	  $this->Collection->recursive = 1;
	  $this->set('collections', $this->paginate());
  }

  function view($id = null) {
		//Configure::write('debug', '0');	//disable debug writing to keep ajax response clean from SQL output
	  if (!$id) {
		 $this->Session->setFlash(__('Invalid Collection.', true));
		 $this->redirect(array('action'=>'index'));
	 }

	 $collection = $this->Collection->read(null, $id);
	 $this->set('collection', $collection);

	 $bidirectional = false;

	 if(intval($collection['Collection']['collection_type_id']) == intval(BIDIRECTIONAL)){
	   $bidirectional = true;
   }
   $this->set('bidirectional', $bidirectional);


   $this->Collection->Asset->recursive = 0;

   $this->paginate = array(
						   'Asset' => array(
											'limit' => 25,
											'conditions'=> array('Asset.collection_id =' => $id),
											'order' => array('Asset.sequence' => 'asc')
											)
						   );


   $this->set('assets', $this->paginate("Asset"));
}

function getS3(){
	Configure::write('debug', '0');  //disable debug writing to keep ajax response clean
	$s3configfile = ($_SERVER["HTTP_HOST"]== "media.cannonballagency.com") ? "/home/deploy/.s3cfg" : "/Users/sgiordano/.s3cfg";
  $s3usage= shell_exec ("/usr/local/bin/s3cmd --config=" . $s3configfile .  " du s3://cbdam 2>&1");
	$s3usage = explode(" ", $s3usage);
	echo $s3usage[0];
  $this->autoRender = false;
  $this->layout = 'nolayout';
}

function test2 (){
	phpinfo();
}


function add() {
  if (!empty($this->data)) {
	 $this->data = $this->Collection->mergeNewTags($this->data, explode(",", $this->data['Tag']['newtags']));
	 $this->Collection->create();

	 if ($this->Collection->save($this->data)) {
		$this->Session->setFlash(__('The Collection has been saved', true));
		$this->redirect(array('action'=>'index'));
	} else {
		$this->Session->setFlash(__('The Collection could not be saved. Please, try again.', true));
	}
}

$this->set('tags', $this->Collection->Tag->find('list', array('order' => 'name ASC')));
$this->set('access_code', $this->Collection->makeAccessCode());
$collectionTypes = $this->Collection->CollectionType->find('list');
$this->set(compact('collectionTypes'));
}

function edit($id = null) {
  if (!$id && empty($this->data)) {
	 $this->Session->setFlash(__('Invalid Collection', true));
	 $this->redirect(array('action'=>'index'));
 }
 if (!empty($this->data)) {
	 $this->data = $this->Collection->mergeNewTags($this->data, explode(",", $this->data['Tag']['newtags']));
	 if ($this->Collection->save($this->data)) {
		 $this->Session->setFlash(__('The Collection has been saved', true));
		 $this->redirect(array('action'=>'index'));
	 } else {
		 $this->Session->setFlash(__('The Collection could not be saved. Please, try again.', true));
	 }
 }
 if (empty($this->data)) {
	 $this->data = $this->Collection->read(null, $id);
 }
 $this->set('tags', $this->Collection->Tag->find('list'));
 $collectionTypes = $this->Collection->CollectionType->find('list');
 $this->set(compact('collectionTypes'));
}

function delete($id = null) {
	if (!$id) {
		$this->Session->setFlash(__('Invalid id for Collection', true));
		$this->redirect(array('action'=>'index'));
	}

	$collection_asset = $this->Collection->Asset->find  ('first', array (
														 'recursive' 	=> -1,
														 'conditions'	=> array('Asset.collection_id'=>$id),
														 'fields'		=> 'Asset.name'
														 )
	);

	if ($this->Collection->del($id, true)) {

		$dir = explode("/", $collection_asset['Asset']['name']);

		$s3 = new AmazonS3();
		$to_delete = $s3->get_object_list(BUCKET, array('prefix' => $dir[0]));

		if($collection_asset != ''){
			foreach($to_delete as $del_obj){
				$s3->delete_object(BUCKET, $del_obj);
			}
			$this->Session->setFlash(__('Collection and assets deleted', true));
		} else {
		   $this->Session->setFlash(__('Collection deleted', true));
	   }


	   $this->redirect(array('action'=>'index'));
   }
}

function send_access_code($collection_id = null, $asset_id = null){
	$this->set("asset_id", $asset_id);
	$this->set("collection_id", $collection_id);

	if (!empty($this->data)) {
		$new_recipients = $this->Collection->validateRecipients($this->data['Recipient']['new_recipients']);


		if(empty($this->data['Recipient']['email'])){
		  $email_recipients = array();
	  } else {
		$email_recipients = $this->data['Recipient']['email'];
	}

			//If there were validation errors from new recipients entered, prep errors for output, otherwise send the access code
	if(count($new_recipients['invalid']) && !empty($this->data['Recipient']['new_recipients'])){
			$this->set('errors', $new_recipients['invalid']); // contains validationErrors array
		}
		else {
			if(!empty($this->data['Recipient']['new_recipients'])){

				//save new recipients
				foreach($new_recipients['recipients'] as $email){
				// check to see if email adress is already in Recipients
					$recipient_exists = $this->Collection->Recipient->find('first', array('conditions' => array('Recipient.email' => $email)));
					$this->data['Recipient']['email'] = $email;

					if(!empty($recipient_exists)){
					// if the recipient already exists, use their existing id
						$this->data['Recipient']['id'] = $recipient_exists['Recipient']['id'];
					}

					$added_recipient =  $this->Collection->Recipient->save($this->data);
				// combine new recipients with checked recipients to prep for emailing
					array_push($email_recipients, $email);
				}
			}

			foreach($email_recipients as $recipient){
				$this->Email->to = $recipient;
				$cu = $this->Session->read('currentuser');
				if($cu){
					$this->Email->from = $cu['Admin']['email'];
				} else {
					$this->Email->from = REPLY_EMAIL;
				}

				$this->Email->subject = $this->data['Collection']['subject'];
				$body = $this->data['Collection']['body'];

				if ($this->Email->send($body)) {
					$this->Session->setFlash(__('The Access Code has been sent', true));
				} else {
					$this->Session->setFlash(__('The Access Code could not be sent. Please, try again.', true));
				}
			}

			$this->redirect($this->data['Collection']['dest']);
		}
	}

	$recipients = $this->Collection->getCollectionRecipients($collection_id);

	if(!empty($recipients['Recipient'])){
		$this->set('recipients', Set::combine($recipients['Recipient'], "{n}.email", "{n}.email"));
	} else {
	   $this->set('recipients', array());
   }

   $this->Collection->recursive = 0;
   $this->set('collection', $this->Collection->read(null, $collection_id));
   if($asset_id) $this->set('asset_id', $asset_id);
}

function access_code_entry(){
	if (!empty($this->data)) {
		if($this->data['Collections']['access_code'] == '') {
			$this->Session->setFlash(__('The access code is invalid.', true));
		} else {
			$collection = $this->Collection->find	('first', array	(
												   'conditions'=> array('access_code'=>$this->data['Collections']['access_code']),
												   'recursive'=>-1
												   )
			);

			if(count($collection['Collection'])){
				$this->redirect('/collections/view/' . $collection['Collection']['id'] . '/' . $collection['Collection']['access_code']);
			} else {
				$this->Session->setFlash(__('The access code is invalid.', true));
			}
		}

	}
}

function test(){
	$this->layout = 'antidote';
}
}
?>
