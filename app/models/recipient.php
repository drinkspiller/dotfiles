<?php
class Recipient extends AppModel {

    var $name = 'Recipient';
    /*/
    var $validate = array   (
			    'email' => array(
					    'rule' => array('email', true), 
					    'required' => true,
					    'message' => 'Please enter a valid email address.'
					    )
			    );
    //*/
   var $hasAndBelongsToMany = array('Collection');
}
?>