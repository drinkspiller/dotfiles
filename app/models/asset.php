<?php
App::Import('Vendor', 'passgen');

class Asset extends AppModel {
    var $name = 'Asset';
    var $validate = array(
        'name' => array('notempty'),
        'collection_id' => array('numeric'),
        'admin_id' => array('numeric')
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array  (
                                'Collection' => array   ('className' => 'collection',
                                                                        'foreignKey' => 'collection_id',
                                                                        'conditions' => '',
                                                                        'fields' => '',
                                                                        'order' => ''
                                                        ),
                                'Admin' => array        ('className' => 'Admin',
                                                                        'foreignKey' => 'admin_id',
                                                                        'conditions' => '',
                                                                        'fields' => '',
                                                                        'order' => ''
                                                        )
                            );
    
    function makeRow($collectionId){
        return $this->read(null, $collectionId);
    }
    
    function getFileExtension($filepath){
        $pathInf = pathinfo($filepath);
        return low($pathInf['extension']);
    }
    
    function getFileName($filepath){
        $pathInf = pathinfo($filepath);
        return $pathInf['filename'];
    }
    
    function getMimeType($filename){
        $extension = $this->getFileExtension($filename);
        switch ($extension) {
            case 'jpg':
                return 'image/jpeg';
                break;
            case 'jpeg':
                return 'image/jpeg';
                break;
            case 'gif':
                return 'image/gif';
                break;
            case 'png':
                return 'image/png';
                break;
            case 'bmp':
                return 'image/bmp';
                break;
            case 'zip':
                return 'application/x-compressed';
                break;
            case 'rar':
                return 'application/x-compressed';
                break;
            case 'psd':
                return 'application/octet-stream';
                break;
            case 'mov':
                return 'video/quicktime';
                break;
            case 'm4v':
                return 'video/x-m4v';
                break;
            case 'mpg':
                return 'video/mpeg';
                break;
            case 'mpeg':
                return 'video/mpeg';
                break;
            case 'mp4':
                return 'video/mp4';
                break;
            case 'mp3':
                return 'audio/mpeg';
                break;
            case '3g2':
                return 'video/quicktime';
                break;
            case '3pg':
                return 'video/quicktime';
                break;
            case 'flv':
                return 'video/x-flv';
                break;
	    case 'f4v':
                return 'video/x-flv';
                break;
            case 'avi':
                return 'video/x-msvideo';
                break;
            case 'swf':
                return 'application/x-shockwave-flash';
                break;
            case 'asx':
                return 'video/x-msvideo';
                break;
            case 'asf':
                return 'video/x-msvideo';
                break;
            case 'avi':
                return 'video/x-msvideo';
                break;
            case 'wma':
                return 'video/x-msvideo';
                break;
            case 'wmv':
                return 'video/x-msvideo';
                break;
            case 'aif':
                return 'audio/x-aiff';
                break;
            case 'aiff':
                return 'audio/x-aiff';
                break;
            case 'aac':
                return 'audio/MP4A-LATM';
                break;
            case 'au':
                return 'audio/basic';
                break;
	    case 'txt':
                return 'text/plain';
                break;
	    case 'doc':
                return 'application/msword';
                break;
	    case 'docx':
                return 'application/msword';
                break;
	    case 'ppt':
                return 'application/mspowerpoint';
                break;
	    case 'xls':
                return 'application/excel';
                break;
	    case 'pdf':
                return 'application/pdf';
                break;
	    case 'eps':
                return 'application/postscript';
                break;
	    case 'ai':
                return 'application/postscript';
                break;
	    case 'sit':
                return 'application/x-stuffit';
                break;
	    case 'sitx':
                return 'application/x-stuffit';
                break;
	    case 'tif':
                return 'image/tiff';
                break;
	    case 'tiff':
                return 'image/tiff';
                break;
            default:
                return '';
        }
    }
    
    function setSequence($sequence=null){
	if(!empty($sequence)){
	    $sql = "";
	    
	    for ($i = 1; $i < count($sequence); $i++){
		/*/
		$sql .= "UPDATE assets SET ";
		$sql .= 'sequence = ' . $i;
		$sql .= ' WHERE id = ' . $sequence[$i] . "; " ;
		//*/
		$this->id = $sequence[$i];
		$result = $this->saveField('sequence', $i);
	    }
	    
	    if ($result !== false) { 
		// success
		print "success";
	    } else { 
		// failed
		print "failed";
	    } 
	}
    }
}
?>