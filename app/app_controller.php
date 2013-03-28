<?
class AppController extends Controller {
    var $helpers = array('Session','Html','Form','Javascript','Cache', 'Shrink');
    //var $components = array('DebugKit.Toolbar', 'Auth');
    var $components = array('Session','Auth');
    var $cacheAction = true;
    var $persistModel = true;
    
    function beforeFilter() {
        parent::beforeFilter();
	
        if( (low($this->viewPath) == 'assets' || low($this->viewPath) == 'collections') && low($this->action) == 'view'){
           //if there are two passed params, check if the second param unlocks a collection
            if(count($this->params['pass']) > 1){
                $Collection = ClassRegistry::init('Collection');
                if(!$Collection->checkAccessCode($this->params['pass'][1])){
                    $this->Auth->deny('view');
                    $this->Session->setFlash(__('The access code is invalid.', true));
		    $this->redirect(array('action'=>'access_code_entry'));
                } else {
                    $this->Auth->allow('view');
                }
            }
        }
	
	if(low($this->viewPath) == 'assets' && low($this->action) == 'add' && isset($this->params['form']['access_code'])){
	    //if the collection is bidirectional, allow asset add from not admins
	    $Collection = ClassRegistry::init('Collection');
	    if($Collection->isBiDirectional($this->params['form']['access_code'])){
		$this->Auth->allow('add');
	    } else {
		$this->Auth->allow('deny');
	    }
	}
	
	// DEFINE EXTENSION VARS
	$scalable_extensions = array('mov','m4v', 'mpg','mpeg','mp4','3g2','3pg', 'flv', 'avi', 'swf', 'asx', 'asf', 'avi', 'wmv');
	$qt_extensions = array('mov','m4v', 'mpg','mpeg','mp4','3g2','3pg');
	$direct_dowload_extensions = array('zip','rar','sit','sitx','tif','tiff','psd','ai');
	
	$this->set('scalable_extensions', $scalable_extensions);
	$this->set('qt_extensions', $qt_extensions);
	$this->set('direct_dowload_extensions', $direct_dowload_extensions);

	//CONFIGURE AUTH COMPONENT OPTIONS
	$this->Auth->userModel = 'Admin';
	$this->Auth->authError = "Please login.";
	$this->Auth->loginRedirect = array('controller' => 'collections', 'action' => 'index');
	
	$this->Auth->fields = array(
	  'username' => 'email', 
	  'password' => 'password'
	);
	
	$this->Auth->allow('access_code_entry','download', 'download_multi');
	//$this->Auth->allow('*');
    }
}
?>
