$(document).ready(function() {
    //handle multi-upload
    $('#asset_upload').uploadify({
	'uploader': '/swf/uploadify.swf',
	'script': '/assets/add/' + $.cookie('CAKEPHP'),
	'scriptData': {'collection_id': $("#collection_id").val(), 'access_code': $("#access_code").val()},
	'folder': asset_folder,
	'cancelImg': '/img/cancel.png',
	'scriptAccess': 'always',
	'multi': true,
	'auto': true,
	'sizeLimit': 5368709120,	//5GB
	'fileDesc': 'ctrl-click (PC) or command-click (MAC) to select multiple',
	'fileExt': '*.jpg;*.jpeg;*.gif;*.png;*.bmp;*.zip;*.rar;*.psd;*.mov;*.m4v;*.mpg;*.mpeg;*.mp4;*.3g2;*.3pg;*.flv;*.f4v;*.swf;*.asx;*.asf;*.avi;*.wma;*.wmv;*.ai;*.aif;*.aiff;*.aac;*.au;*.mp3;*.txt;*.doc;*.docx;*.ppt;*.xls;*.pdf;*.eps;*.sit;*.sitx;*.tif;*.tiff;*.pptx;*.xlsx',
	'displayData': 'percentage',
	'buttonText': 'ADD ASSETS',
	'method': 'post',
	'removeCompleted' : true,
	'onComplete': onFileUploaded,
	'onOpen' : setBeforeUnload,
	'onAllComplete' : unsetBeforeUnload,
	'onError'     : function (event,ID,fileObj,errorObj) {
			    console.log(' event: ' + event);
			    console.log(' ID: ' + ID);
			    console.log(' fileObj: ' + fileObj);
			    console.log(errorObj.type + ' Error: ' + errorObj.info);
			  }
    });
    
});

var extensions = ['','jpg', 'jpeg', 'gif', 'png', 'bmp', 'zip', 'rar', 'psd', 'mov', 'm4v', 'mpg', 'mpeg', 'mp4', '3g2', '3pg', 'flv', 'f4v', 'swf', 'asx', 'asf', 'avi', 'wma', 'wmv', 'ai', 'aif', 'aiff', 'aac', 'au', 'mp3', 'txt', 'doc', 'docx', 'ppt', 'xls', 'pdf', 'eps', 'sit', 'sitx', 'tif', 'tiff','pptx','xlsx'];

function setBeforeUnload(){
    window.onbeforeunload = confirmExit;
    function confirmExit()
    {
        return "One or more upload is currently in progress. Are you sure you want to leave this screen?";
    }
}

function unsetBeforeUnload(){
    window.onbeforeunload = null;
}

function onFileUploaded(evt, qid, fileObj, response, data){
    if(response.toLowerCase().indexOf("error") != -1 || response.toLowerCase().indexOf("debug") != -1){
        $("h3").before('<p class="error">' + response + '</p>');
    }
    else{
	//insert new row
	$("#assets_tbl tr:eq(0)").after(response);
	zebraStripe();
	
	//highlight fade
	var newRow = $("#assets_tbl tr:eq(1)");
	var origColor = $("#assets_tbl tr:eq(1)").css("backgroundColor");
	//console.log('origColor: ' + origColor);
	$(newRow).css({backgroundColor: '#C9FF9B'});
	setTimeout(function()	{
				    $(newRow).animate({ backgroundColor: origColor }, 1250, function()	{
													    $(this).attr("style", "");
													});
				}, 1500, null, zebraStripe);
    }
}

///////////////////////////////////
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

$(function(){
    $('#dropzone').filedrop({
        url: '/assets/add/' + $.cookie('CAKEPHP'),
        paramname: 'Filedata',
        maxfiles: 99999999,   
        maxfilesize: 5368709120,    // max file size in MBs 
        error: function(err, file) {
            switch(err) {
                case 'BrowserNotSupported':
                    alert('browser does not support html5 drag and drop')
                    break;
                case 'TooManyFiles':
                    // user uploaded more than 'maxfiles'
                    alert('uploaded more than maxfiles');
                    break;
                case 'FileTooLarge':
                    // program encountered a file whose size is greater than 'maxfilesize'
                    // FileTooLarge also has access to the file which was too large
                    // use file.name to reference the filename of the culprit file
                    alert('FileTooLarge');
                    break;
                default:
                    break;
            }
        },
        dragOver: function() {
            // user dragging files over #dropzone
            $("#dropzone").addClass("dropzone_over");
        },
        dragLeave: function() {
            // user dragging files out of #dropzone
            $("#dropzone").removeClass("dropzone_over");
        },
        docOver: function() {
            // user dragging files anywhere inside the browser document window
            $("#dropzone").show();
        },
        data:   {   collection_id: $("#collection_id").val(),
                    access_code: $("#access_code").val()
                },
        docLeave: function() {
            // user dragging files out of the browser document window
            $("#dropzone").hide();
        },
        drop: function() {
            // user drops file
            $("#dropzone").removeClass("dropzone_over");
            $("#dropzone").hide();
        },
        uploadStarted: function(i, file, len){
            // a file began uploading
            // i = index => 0, 1, 2, 3, 4 etc
            // file is the actual file of the index
            // len = total files user dropped
            setBeforeUnload();
            console.log("uploaded started for fileID " + i + " (" + file + ") of " + len + " files" );
            var name =  file.fileName ||  file.name;
            var size =  file.fileSize ||  file.size;
            $("#assets_tbl").before("<div id='upload_" + i + "' class='upload'>" + name + " (" + bytesToSize(size) + ")  &mdash;  <span class='percent_uploaded'>0%</span> &mdash; <span class='upload_speed'>0%</span><div class='progress_bar'/ ></div>" );
        },
        uploadFinished: function(i, file, response, time) {
            // response is the data you got back from server in JSON format.
            //console.log("uploadFinished for fileID " + i + ". Response: " + response);
            onFileUploaded(null,i, file, response, time);
            $("#upload_" + i).remove();
        },
        progressUpdated: function(i, file, progress) {
            // this function is used for large files and updates intermittently
            // progress is the integer value of file being uploaded percentage to completion
            // console.log("progressUpdated for fileID " + i + " (" + file + ") — "  + progress + "%");
            $("#upload_" + i).find('.percent_uploaded').text(progress + "%");
            $("#upload_" + i).find(".progress_bar").css("width", progress + "%");
        },
        speedUpdated: function(i, file, speed) {
            // speed in kb/s
            //console.log("speedUpdated for fileID " + i  + " (" + file + ") — " + speed);
            $("#upload_" + i).find('.upload_speed').text(speed.toFixed(2) + " kb/s");
        },
        
        beforeEach: function(file) {
            // file is a file object
            // return false to cancel upload
            //console.log("beforeEach");
            var name =  file.fileName ||  file.name;
            var parts = name.split(".");
            var ext = parts[parts.length -1];

            if(!$.inArray(ext, extensions)){
                alert("." + ext + " is not a supported file type");
                return false;
            } else {
                return true;    
            }
            
        },
        afterAll: function() {
            // runs after all files have been uploaded or otherwise dealt with
            console.log("All files uploaded");
            unsetBeforeUnload();
        },
        queuefiles : 20000
        
    });
});
