<?php
App::import('Vendor', 'AWS_SDK', array('file' => 'aws-sdk-1.5.3'.DS.'sdk.class.php'));

class AssetsController extends AppController {

    var $name = 'Assets';
    var $helpers = array('Html', 'Form');
    var $MIME_TYPES = array(    'jpg'=>'image/jpeg',
                                'jpeg'=>'image/jpeg',
                                'gif'=> 'image/gif',
                                'png'=> 'image/png',
                                'bmp'=> 'image/bmp',
                                'zip'=> 'application/x-compressed',
                                'rar'=> 'application/x-compressed',
                                'psd'=> 'application/octet-stream',
                                'mov'=> 'video/quicktime',
                                'm4v'=> 'video/x-m4v',
                                'mpg'=> 'video/mpeg',
                                'mpeg'=> 'video/mpeg',
                                'mp4'=> 'video/mp4',
                                'mp3'=> 'audio/mpeg',
                                '3g2'=> 'video/quicktime',
                                '3pg'=> 'video/quicktime',
                                'flv'=> 'video/x-flv',
                                'f4v'=> 'video/x-flv',
                                'avi'=> 'video/x-msvideo',
                                'swf'=> 'application/x-shockwave-flash',
                                'asx'=> 'video/x-msvideo',
                                'asf'=> 'video/x-msvideo',
                                'avi'=> 'video/x-msvideo',
                                'wma'=> 'video/x-msvideo',
                                'wmv'=> 'video/x-msvideo',
                                'aif'=> 'audio/x-aiff',
                                'aiff'=> 'audio/x-aiff',
                                'aac'=> 'audio/MP4A-LATM',
                                'au'=> 'audio/basic',
                                'ai'=> 'application/postscript',
                                'txt'=> 'text/plain',
                                'doc'=> 'application/msword',
                                'docx'=> 'application/msword',
                                'ppt'=> 'application/mspowerpoint',
                                'xls'=> 'application/excel',
                                'pdf'=> 'application/pdf',
                                'eps'=> 'application/postscript',
                                'ai'=> 'application/postscript',
                                'sit'=> 'application/x-stuffit',
                                'sitx'=> 'application/x-stuffit',
                                'tif'=> 'image/tiff',
                                'tiff'=> 'image/tiff');



    function beforeFilter(){
        parent::beforeFilter();
        if($this->action == "add" && isset($this->passedArgs[0])){
            session_id($this->passedArgs[0]);
            session_start();
        }
    }

    function index() {
        $this->Asset->recursive = 0;
        $this->set('assets', $this->paginate());
    }

    function sequence(){
        Configure::write('debug', '0');	//disable debug writing to keep ajax response clean from SQL output
        $this->autoRender = false;

        if(isset($this->params['form']['assets_tbl'])){
            $this->Asset->setSequence($this->params['form']['assets_tbl']);
        }
    }

    function search(){
        if (!empty($this->data)) {
            $this->set('data', true);
                $asset_results = $this->Asset->find('all', array(
                                'conditions' => array("Asset.name LIKE" => "%" . $this->data['keywords'] . "%"),
                                'recursive' => 1,
                                'order'=> 'Asset.created DESC',
                                  )
                               );
            $this->loadModel('Collection');

            $collection_results = $this->Collection->find('all', array(
                                'conditions' => array("Collection.name LIKE" => "%" . $this->data['keywords'] . "%"),
                                'order'=> 'Collection.created DESC',
                                'recursive' => -1
                                  )
                               );

            $tr_sql = 'SELECT * FROM collections, collections_tags, tags ';
            $tr_sql .= 'WHERE collections.id = collections_tags.collection_id ';
            $tr_sql .= 'AND collections_tags.tag_id = tags.id ';
            $tr_sql .= 'AND tags.name LIKE  \'%' . $this->data['keywords'] . '%\'';

            $tag_results = $this->Collection->query($tr_sql);

            $this->set('asset_results', $asset_results);
            $this->set('collection_results', $collection_results);
            $this->set('tag_results', $tag_results);
            $this->set('search_term', $this->data['keywords']);
            }
        else{
            $this->set('data', false);
        }
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Asset.', true));
            $this->redirect(array('action'=>'index'));
        }

		if(isset($this->params['pass'][2])){
			if($this->params['pass'][2] == "plain"){
				$this->layout = 'empty';
			}
		}

        $asset = $this->Asset->read(null, $id);
		$this->set  ('download_only', array (
					    'xls',
					    'ppt',
					    'docx',
					    'doc',
					    'psd',
					    'rar',
					    'zip',
					    'txt',
					    'pdf',
					    'eps',
					    'tif',
					    'tiff',
					    'ai'
					    )
		    );
        $this->set('asset', $asset);
        $this->set('filename', $this->Asset->getFileName($asset['Asset']['name']));
        $this->set('extension', $this->Asset->getFileExtension($asset['Asset']['name']));
    }

    function add() {
        $this->layout = 'nolayout';

        Configure::write('debug', '0');	//disable debug writing to keep ajax response clean from SQL output

        if (!empty($_FILES)) {
	    $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_POST['folder'] . '/'. $_POST['collection_id'] . $_POST['access_code'] . '/';
            //clean file
	    $fileName = str_replace( array(
					    ' ',
					    '&',
					    '%',
					    '#',
					    '@',
					    '!',
					    '?',
					    '$',
					    '^',
					    '+',
					    '='
					  ),
				    '_',
				    $_FILES['Filedata']['name']);
                    $fileName = str_replace (array  ('(',
					     ')',
					     '|',
					    ),
				    '',
				    $fileName
				    );



            $filePath = $_POST['collection_id'] . $_POST['access_code'] .  '/' . $fileName;

            /*/
            echo "<br />DEBUG:" ;
            print_r($_FILES);
            print"<pre>";
            print_r($info);
            print"</pre>";
            echo "<br />fileExtension= " . $fileExtension . "\n";
            echo "<br />fileNameOnly= " . $fileNameOnly . "\n";
            echo "<br />fileName= " . $fileName . "\n";
            echo "<br />filePath= " . $filePath . "\n";
            echo "<br />targetPath= " . $targetPath;
            echo "<br />tempFile= " . $tempFile;
            print(pathinfo($fileName, PATHINFO_EXTENSION) . "<br/>");
            print("<br/>" . $this->MIME_TYPES[pathinfo($fileName, PATHINFO_EXTENSION)]);
            die();
            //*/

            $s3 = new AmazonS3();
            // make sure filename is unique
            $i =1;
            while($s3->if_object_exists(BUCKET, $filePath)){
                $filePath = $_POST['collection_id'] . $_POST['access_code'] .  '/' .  pathinfo($fileName, PATHINFO_FILENAME ) . $i . "." . pathinfo($fileName, PATHINFO_EXTENSION );
                $i++;
            }

            App::Import('Vendor', 'getid3/getid3');
            $getID3 = new getID3;
            $info = $getID3->analyze($_FILES['Filedata']['tmp_name']);
            $meta_array = array();
            if(isset($info['video']['resolution_x'])) $meta_array['width'] = $info['video']['resolution_x'];
            if(isset($info['video']['resolution_y'])) $meta_array['height'] = $info['video']['resolution_y'];
            if(isset($info['video']['frame_rate'])) $meta_array['frame_rate'] = $info['video']['frame_rate'];
            if(isset($info['swf']['header']['version'])) $meta_array['swf_version'] = $info['swf']['header']['version'];
            if(isset($info['bitrate'])) $meta_array['bitrate'] = $info['bitrate'];

            //print "uploading " . $filePath . " to " . BUCKET;die();

            $upload_result = $s3->create_object(BUCKET, $filePath, array(
				'fileUpload' => $tempFile,
                'storage' => AmazonS3::STORAGE_REDUCED,
                'acl' => AmazonS3::ACL_PUBLIC,
                'contentType' => $this->MIME_TYPES[pathinfo($fileName, PATHINFO_EXTENSION)],
                'meta' => $meta_array
			));

            $obj_url = $s3->get_object_url(BUCKET, $filePath);

            if($upload_result->status!=200) die("ERROR: S3 upload status" . $upload_result->status);
            $this->Asset->create();
            $uploader_id =  ($this->Auth->user('id') == null)? 0 : $this->Auth->user('id');

            $this->data = array('Asset'=>   array(  'collection_id'=>$_POST['collection_id'],
                                                    'access_code'=>$_POST['access_code'],
                                                    'admin_id'=> $uploader_id,
                                                    'name'=>$filePath)
                                            );

            if ($result = $this->Asset->save($this->data)) {
                $this->Session->setFlash(__('The Asset has been saved', true));
                $this->set('asset', $this->Asset->makeRow($this->Asset->id));
                $this->set('uploader_id', $uploader_id);

            } else {
                $this->Session->setFlash(__('The Asset could not be saved. Please, try again.', true));
            }

        }else {
            echo 'ERROR: NO FILE(S) RECEIVED! Check post_max_size and upload_max_filesize in php.ini';
            exit;
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Asset', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            //print_r($this->data);

	    $orig = $this->data['Asset']['orig_name'];
	    $new =  $this->data['Asset']['prefix'] . $this->data['Asset']['name'];
	    /*/
	    print "<br />";
        print_r($this->data);
	    print "<br />";
	    print  "ORIG: " . $orig;
	    print "<br />";
	    print  "NEW: " . $new;
	    //*/


	    // CHECK TO SEE IF NEW FILE NAME PASSED
	    if($new != $orig){
            // CHECK TO SEE IF THE NEW NAME ALREADY EXISTS
            $s3 = new AmazonS3();
            if(!$s3->if_object_exists(BUCKET, $new)){
                $info = $s3->get_object(BUCKET, $orig, array('range' => '0-1'));
                //copy metadata
                $meta_array = array();
                if(isset($info->header['x-amz-meta-width'])) $meta_array['width'] = $info->header['x-amz-meta-width'];
                if(isset($info->header['x-amz-meta-height'])) $meta_array['height'] = $info->header['x-amz-meta-height'];
                if(isset($info->header['x-amz-meta-frame_rate'])) $meta_array['frame_rate'] = $info->header['x-amz-meta-frame_rate'];
                if(isset($info->header['x-amz-meta-swf_version'])) $meta_array['swf_version'] = $info->header['x-amz-meta-swf_version'];
                if(isset($info->header['x-amz-meta-bitrate'])) $meta_array['bitrate'] = $info->header['x-amz-meta-bitrate'];

                //copy object to new name
                $result = $s3->copy_object( array('bucket'=> BUCKET,
                                                    'filename'=>$orig),
                                            array('bucket'=> BUCKET,
                                                    'filename'=>$new),
                                            array(
                                                'storage' => AmazonS3::STORAGE_REDUCED,
                                                'acl' => AmazonS3::ACL_PUBLIC,
                                                'contentType' => $this->MIME_TYPES[pathinfo($fileName, PATHINFO_EXTENSION)],
                                                'meta' => $meta_array
                                            ));

                if($result->isOK()) {
                    $this->data['Asset']['name'] = $new;
                    //delete object with old name
                    $s3->delete_object(BUCKET, $orig);
                    $save_msg = __('The Asset has been saved with the file renamed.', true);
                } else {
                    $save_msg = __('ERROR: ' . $result->status, true);
                }

            } else{ // IF THE NEW NAME ALREADY EXISTS, SET NAME BACK TO ORIG BEFORE SAVING
                $this->data['Asset']['name'] =  $this->data['Asset']['orig_name'];
                $save_msg = __('The Asset has been saved but the file was NOT renamed (there may already be a file with the specified name). ', true);
            }
	    }

	    /*/
	    print "<br />DATA: <br />";
	    print_r($this->data);
	    print "<br />SAVE MSG: <br />";
	    print_r($save_msg);
	    die();
	    //*/

	    if ($this->Asset->save($this->data)) {
		$this->Session->setFlash($save_msg);
                $this->redirect(array('controller'=>'collections','action'=>'view', $this->data['Asset']['collection_id']));
            } else {
                $this->Session->setFlash(__('The Asset could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Asset->read(null, $id);
        }

        $collections = $this->Asset->Collection->find('list');
        $admin_options = $this->Asset->Admin->find('all', array('recursive' => 0));
        $admins = Set::combine($admin_options, "{n}.Admin.id", array("{0} {1}", "{n}.Admin.fname", "{n}.Admin.lname"));
        $this->set(compact('collections','admins'));
    }

    function delete($id = null, $redirect = true) {
        $asset = $this->Asset->read(null, $id);
        $targetPath = $asset['Asset']['name'];

        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Asset', true));
        }


        if ($this->Asset->del($id)) {
            $s3 = new AmazonS3();

            $response = $s3->delete_object(BUCKET, $targetPath);
            if($response->isOK()){
                $this->Session->setFlash('Asset' . (($redirect)?'':'s') . ' deleted');
            } else {
                 $this->Session->setFlash('Asset' . (($redirect)?'':'s') . ' NOT deleted. Status: ' . $response->status);
            }
    }


        if($redirect){
            $this->redirect(array('controller'=>'collections','action'=>'view', $asset['Asset']['collection_id']));
        } else{
            return  $asset['Asset']['collection_id'];
        }
    }

    function delete_multi(){
        $this->autoRender = false;
        foreach($this->passedArgs as $delete_id){
            $collection_id = $this->delete($delete_id, false);
        }
        $this->redirect(array('controller'=>'collections','action'=>'view', $collection_id));
        }

    function download_multi(){
        App::Import('Vendor', 'utils');
        $this->autoRender = false;

        if (!count($this->params['form']['assets'])) {
                $this->Session->setFlash(__('Invalid or no files specified for zipping.', true));
                $this->redirect(array('action'=>'index'));
            }

        /*/
        print "<pre>";
        print_r($this->params['form']['assets']);
        print "</pre>";
        die();
        //*/
        $files_to_zip = array();
        foreach($this->params['form']['assets'] as $asset){
            //DOWNLOAD TO LOCAL FROM S3
            $tmp_dir = '/tmp/assets/';
            if(!file_exists($tmp_dir)) {
                mkdir($tmp_dir);
                chmod($tmp_dir, 777);
            }
            $tmp_file = '/tmp/assets/' . pathinfo($asset, PATHINFO_BASENAME);
            array_push($files_to_zip, $tmp_file);
            $s3 = new AmazonS3();
            $s3->get_object(BUCKET, $asset, array('fileDownload' => $tmp_file));
            $filepath = realpath('.');
            $filepath .= DS . $asset;
        }

        $zip_base = time() . '.zip';

        $zip_file = WWW_ROOT . ASSET_FOLDER . "/" . $zip_base;
        $result = create_zip($files_to_zip, $zip_file);

        if($result){
            print "/" . ASSET_FOLDER . "/" . $zip_base;
            die();
        } else {
            print "Error creating .zip archive.";
        }
    }


    function download($p = null){

        if ((!isset($this->passedArgs[0]) || !isset($this->passedArgs[1]))) {
            $this->Session->setFlash(__('Invalid or no download file specified.', true));
            $this->redirect(array('action'=>'index'));
        }

        $download_path = ASSET_PATH . $this->passedArgs[0] . "/" . $this->passedArgs[1];

        //die("$download_path");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Transfer-Encoding: binary");
        header('Content-Type: ' . $this->MIME_TYPES[pathinfo($this->passedArgs[1], PATHINFO_EXTENSION)]);
        header('Content-Disposition: attachment; filename=' . fopen($this->passedArgs[1]));
        ob_clean();
        flush();
        readfile($download_path);

        exit();
    }

}
?>
