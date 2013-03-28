<?php
class AdminsController extends AppController {

    var $name = 'Admins';
    var $helpers = array('Html', 'Form');
    
    function beforeFilter() {
	parent::beforeFilter();	
	//store unhashed password for edit action to see if it has changed.
	//if it has not, we'll set back to this unhashed value to avoid double-hashing
	if($this->action == 'edit' && !empty($this->data)){
	    $this->set('unhashed', $this->data['Admin']['password']);
	}
	if($this->action == 'login'){
	    $this->Session->destroy();
	}
    }
    
    function index() {
	$this->Admin->recursive = 0;
	$this->set('admins', $this->paginate());
    }

    function view($id = null) {
	if (!$id) {
		$this->Session->setFlash(__('Invalid Admin.', true));
		$this->redirect(array('action'=>'index'));
	}
	$this->set('admin', $this->Admin->read(null, $id));
    }

    function add() {
	if (!empty($this->data)) {
	    $this->Admin->create();
	    if ($this->Admin->save($this->data)) {
		    $this->Session->setFlash(__('The Admin has been saved', true));
		    $this->redirect(array('action'=>'index'));
	    } else {
		    $this->Session->setFlash(__('The Admin could not be saved. Please, try again.', true));
	    }
	}
	$adminTypes = $this->Admin->AdminType->find('list');
	$this->set(compact('adminTypes'));
    }

    function edit($id = null) {
	if (!$id && empty($this->data)) {
	    $this->Session->setFlash(__('Invalid Admin', true));
	    $this->redirect(array('action'=>'index'));
	}
	if (!empty($this->data)) {	   
	   
	    $this->Admin->set($this->data);
	    if ($this->Admin->validates()) {
		// it validated logic
		if($this->viewVars['unhashed'] == $this->data['Admin']['orig_password']) $this->data['Admin']['password'] = $this->data['Admin']['orig_password'];
		
		if ($this->Admin->save($this->data, false)) {
		    $this->Session->setFlash(__('The Admin has been saved', true));
		    $this->redirect(array('action'=>'index'));
		} else {
			$this->Session->setFlash(__('The Admin could not be saved. Please, try again.', true));
		}
	    } 
	}
	if (empty($this->data)) {
	    $this->data = $this->Admin->read(null, $id);
	}
	
	$adminTypes = $this->Admin->AdminType->find('list');
	$this->set(compact('adminTypes'));
    }

    function delete($id = null) {
	if (!$id) {
	    $this->Session->setFlash(__('Invalid id for Admin', true));
	    $this->redirect(array('action'=>'index'));
	}
	if ($this->Admin->del($id)) {
	    $this->Session->setFlash(__('Admin deleted', true));
	    $this->redirect(array('action'=>'index'));
	}
    }
    
    function login() {	//intentially empty. handled by Auth component
    }

    function logout() {
	$this->redirect($this->Auth->logout());
    }
}
?>