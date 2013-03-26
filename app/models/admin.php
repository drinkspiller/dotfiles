<?php
class Admin extends AppModel {

	var $name = 'Admin';
	var $validate = array(
		'fname' => array('notempty'),
		'lname' => array('notempty'),
		'email' => array('email'),
		'password' => 	array('notempty'=>	array(
								'rule'=>'notEmpty',
								'message'=>'Please enter a password.',
								'required' => true
							),
				    'passwordsMatch'=>	array(
								'rule'=>'passwordsMatch',
								'message'=>'The password and password confirmation fields do not match. Please, try again.',
								'required' => true
							)
				),
		'confirm_password' => array('notempty')
	);
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
			'Asset' => array('className' => 'Asset',
								'foreignKey' => 'admin_id',
								'dependent' => false,
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
	var $belongsTo = array(
			'AdminType' => array('className' => 'AdminType',
								'foreignKey' => 'admin_type_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);
	//custom password mathing validation function
	function passwordsMatch(){
		//debug($this);
		//die();
		if($this->data['Admin']['password'] == Security::hash($this->data['Admin']['confirm_password'], null, true)){
			return true;
		} else{
			$this->invalidate('password');
			return false;
		}
	}
}
?>