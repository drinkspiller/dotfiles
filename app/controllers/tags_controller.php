<?php
class TagsController extends AppController {
    var $name = 'Tags';
    var $helpers = array('Html', 'Form');
    var $paginate = array(
        'limit' => 25,
        'order' => array(
            'Tag.name' => 'asc'
        )
    );


    function index() {
	$this->Tag->recursive = 0;
	$this->set('tags', $this->paginate());
    }

    function view($id = null) {
	if (!$id) {
	    $this->flash(__('Invalid Tag', true), array('action'=>'index'));
	}
	$this->set('tag', $this->Tag->read(null, $id));
    }

    function add() {
	if (!empty($this->data)) {
		$this->Tag->create();
		if ($this->Tag->save($this->data)) {
			$this->flash(__('Tag saved.', true), array('action'=>'index'));
		} else {
		}
	}
	$collections = $this->Tag->Collection->find('list');
	$this->set(compact('collections'));
    }

    function edit($id = null) {
	if (!$id && empty($this->data)) {
		$this->flash(__('Invalid Tag', true), array('action'=>'index'));
	}
	if (!empty($this->data)) {
	    if ($this->Tag->save($this->data)) {
		    $this->flash(__('The Tag has been saved.', true), array('action'=>'index'));
	    } else {
	    }
	}
	if (empty($this->data)) {
	    $this->data = $this->Tag->read(null, $id);
	}
	$collections = $this->Tag->Collection->find('list');
	$this->set(compact('collections'));
    }

    function delete($id = null) {
	if (!$id) {
	    $this->flash(__('Invalid Tag', true), array('action'=>'index'));
	}
	if ($this->Tag->del($id)) {
	    $this->flash(__('Tag deleted', true), array('action'=>'index'));
	}
    }
}
?>