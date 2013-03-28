<?php
class CollectionType extends AppModel {

	var $name = 'CollectionType';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasOne = array(
			'Collection' => array('className' => 'Collection',
								'foreignKey' => 'collection_type_id',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

}
?>