<?php
class AdminType extends AppModel {

	var $name = 'AdminType';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasOne = array(
			'Admin' => array('className' => 'Admin',
								'foreignKey' => 'admin_type_id',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);
}
?>