var extension;
jQuery.easing.def = "easeInOutCirc";

$(document).ready(function() {
    if ($(".media").length) {
        extension = $(".media").attr("href").split(".")[$(".media").attr("href").split(".").length-1]
       
        $.fn.media.defaults.flvPlayer = '/swf/mediaplayer.swf';
        $.fn.media.defaults.mp3Player = '/swf/mediaplayer.swf';
        $('.media').media({
            attrs: {
                "id": "dafile_media_file",
                "name": "dafile_media_file"
            },
            caption: false
        });
        $(".media[href$='jpg'], .media[href$='JPG'], .media[href$='jpeg'], .media[href$='JPEG'], .media[href$='gif'], .media[href$='GIF'], .media[href$='png', .media[href$='PNG']").each(function() {
            $(this).replaceWith("<div class='asset_container'><img src='" + $(this).attr("href") + "' /></div");
        });


        $(".asset_container img").maxImage({
            horizontalOffset: 50,
            maxAtOrigImageSize: true,
            maxFollows: 'width',
            position: 'relative',
            resizeMsg: {
                show: true,
                location: 'before',
                message: 'NOTE: This image has been scaled down to fit your browser window. Downloaded image will be actual size: [w]x[h].  '
            }
        });

        $(".2x").click(function() {
            $.metadata.setType("class");
            var w = $(".media").metadata().width * 2;
            var h = $(".media").metadata().height * 2;
            scalePlayer(w, h);
        });

        $(".1x").click(function() {
            $.metadata.setType("class");
            var w = $(".media").metadata().width;
            var h = $(".media").metadata().height;
            scalePlayer(w, h);
        });

        //CLEANUP NON TRANSFORMED LINK
       //s $("a.media").remove();
    }
});

function scalePlayer(w, h) {
    $("#dafile_media_file").animate({
        width: w,
        height: h
    }, 1000);
}