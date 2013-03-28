<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <script src="/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="/js/jquery.media.js" type="text/javascript"></script>
    <script type="text/javascript">
		console.log("pre");
		$(document).ready(function(){
			$.fn.media.defaults.flvPlayer = '/swf/mediaplayer.swf';
			$.fn.media.defaults.mp3Player = '/swf/mediaplayer.swf';
				$('.media').media(	{	attrs:  {"id": "dafile_media_file",
									 	"name": "dafile_media_file"
									},
								caption:   false
							}
						);
			$(".media[href$='jpg'], .media[href$='jpeg'], .media[href$='gif'], .media[href$='png']").each(function(){
				$(this).replaceWith("<div class='asset_container'><img src='" + $(this).attr("href") + "' /></div");									   
			});
			
			var n = $(".media").html();
			$(".view").html(n);
			$(".actions").html("");
		});
    </script>
    
    <style type="text/css">
    	/*CSS RESET (http://www.ejeliot.com/blog/85) */
		body{padding:0;margin:0;font:13px Arial,Helvetica,Garuda,sans-serif;*font-size:small;*font:x-small;}
		h1,h2,h3,h4,h5,h6,ul,li,em,strong,pre,code{padding:0;margin:0;line-height:1em;font-size:100%;font-weight:normal;font-style: normal;}
		table{font-size:inherit;font:100%;font-family:inherit;}
		ul{list-style:none;}
		img{border:0;}
		p{margin:1em 0;}
		
		html,body{
			height: 100%;	
		}
    </style>
</head>

<body>
	<table width="100%" height="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td align="center" valign="middle"><?= $content_for_layout; ?></td>
        </tr>
    </table>
</body>
</html>
