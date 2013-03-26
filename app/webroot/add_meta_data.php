<?php
    require_once("/home/deploy/media.cannonballagency.com/app/vendors/aws-sdk-1.5.3/sdk.class.php");
    require_once("/home/deploy/media.cannonballagency.com/app/vendors/getid3/getid3.php");
    $getID3 = new getID3;
    
    $s3 = new AmazonS3();
    $bucket = 'cbdam';
    
    $MIME_TYPES = array(    'jpg'=>'image/jpeg',  
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
    
    // GET ALL S3 OBJECTS
    $s3_folders = $s3->get_object_list($bucket);
    /*/    
    print "<pre>";    
    print_r($s3_folders);
    print "</pre>";
    die();
    //*/
    
    // LOOP THROUGH ALL S3 Folders
    foreach($s3_folders as $s3obj){
        fwrite(STDOUT, "\n\n///////////////////////////////////////////////");
        fwrite(STDOUT, "\nWorking on object $s3obj...");      
        $tmpfilename = "/tmp/" . time();         
        
        try {
            $response = $s3->get_object($bucket, $s3obj, array(
                'fileDownload' => $tmpfilename
            ));
        } catch (Exception $e) {
            fwrite(STDERR, "\nCaught exception: " . $e->getMessage() . " getting object " . $s3obj);
        }
        if($response->isOK()){
             try {         
                // ANALYZE EACH DOWNLOADED FILE            
                $info = $getID3->analyze($tmpfilename);
                $meta_array = array();
                if(isset($info['video']['resolution_x'])) $meta_array['width'] = $info['video']['resolution_x'];
                if(isset($info['video']['resolution_y'])) $meta_array['height'] = $info['video']['resolution_y'];
                if(isset($info['video']['frame_rate'])) $meta_array['frame_rate'] = $info['video']['frame_rate'];
                if(isset($info['swf']['header']['version'])) $meta_array['swf_version'] = $info['swf']['header']['version'];
                if(isset($info['bitrate'])) $meta_array['bitrate'] = $info['bitrate'];
            } catch (Exception $e) {
                fwrite(STDERR, "\nCaught exception: " . $e->getMessage() . " getting meta data on " . $s3obj);
            }
            
            try {   
                //COPY OBJECT WITH SAME NAME BUT NEW INFO
                $result = $s3->copy_object(     array(  'bucket'=> $bucket,
                                                        'filename'=>$s3obj),
                                                array(  'bucket'=> $bucket,
                                                        'filename'=>$s3obj),
                                                array( 
                                                    'storage' => AmazonS3::STORAGE_REDUCED,
                                                    'acl' => AmazonS3::ACL_PUBLIC,
                                                    'contentType' => $MIME_TYPES[pathinfo($s3obj, PATHINFO_EXTENSION)],
                                                    'meta' => $meta_array
                                                ));
                if($result->isOK()){
                    fwrite(STDOUT, "\nSuccessfully added meta data to " . $s3obj);
                    try {                       
                        // DELETE DOWNLOADED TMP FILE
                        @unlink($tmpfilename);
                    } catch (Exception $e) {
                        fwrite(STDERR, "\nCaught exception: " . $e->getMessage() . " deleting " . $tmpfilename);
                    }
                }
            } catch (Exception $e) {
                fwrite(STDERR, "\nCaught exception: " . $e->getMessage() . " copy_object object: " . $s3obj);
            }   
        } else {
            fwrite(STDOUT, "\nProblem with " . $s3obj . ". Status: " . $response->status);
        }
    }
    
    fwrite(STDOUT,  "\n\n///////////////////////////////////////////////");
    fwrite(STDOUT,  "\nCOMPLETE!");
    exit(0);
?>