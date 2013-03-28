<?php
class RecipientsController extends AppController {

    var $name = 'Recipients';
    var $helpers = array('Html', 'Form');
    var $scaffold;
    
    /*/
    function index() {
	$this->Recipient->recursive = 0;
	$this->set('recipients', $this->paginate());
    }

    function view($id = null) {
	if (!$id) {
	    $this->Session->setFlash(__('Invalid Recipient.', true));
	    $this->redirect(array('action'=>'index'));
	}
	$this->set('recipient', $this->Recipient->read(null, $id));
    }

    function add() {
	if (!empty($this->data)) {
	    $this->Recipient->create();
	    if ($this->Recipient->save($this->data)) {
		$this->Session->setFlash(__('The Recipient has been saved', true));
		$this->redirect(array('action'=>'index'));
	    } else {
		$this->Session->setFlash(__('The Recipient could not be saved. Please, try again.', true));
	    }
	}
	$collections = $this->Recipient->Collection->find('list');
	$this->set(compact('collections'));
    }

    function edit($id = null) {
	if (!$id && empty($this->data)) {
	    $this->Session->setFlash(__('Invalid Recipient', true));
	    $this->redirect(array('action'=>'index'));
	}
	if (!empty($this->data)) {
	    if ($this->Recipient->save($this->data)) {
		$this->Session->setFlash(__('The Recipient has been saved', true));
		$this->redirect(array('action'=>'index'));
	    } else {
		$this->Session->setFlash(__('The Recipient could not be saved. Please, try again.', true));
	    }
	}
	if (empty($this->data)) {
	    $this->data = $this->Recipient->read(null, $id);
	}
	$collections = $this->Recipient->Collection->find('list');
	$this->set(compact('collections'));
    }

    function delete($id = null) {
	if (!$id) {
	    $this->Session->setFlash(__('Invalid id for Recipient', true));
	    $this->redirect(array('action'=>'index'));
	}
	if ($this->Recipient->del($id)) {
	    $this->Session->setFlash(__('Recipient deleted', true));
	    $this->redirect(array('action'=>'index'));
	}
    }
    //*/

}
?>