var rowColor = '#fff';
var altRowColor = '#f4f4f4';
var hoverRowColor = '#f3eadf';
var hiliteRowColor = '#e46739';

$(document).ready(function(){
    zebraStripe();

    //configure event handlers
    $('.delete_selected').click(deleteSelected);
    $('.download_selected').click(downloadSelected);

    //round corners
   // $('.rounded').corner('10px top');
   // $('#content').corner('30px');
   // $('.submit').corner('10px');
});

function zebraStripe(){
    $('.alt_color_table').colorize({bgColor: rowColor, altColor: altRowColor, hoverColor: hoverRowColor, hiliteColor: hiliteRowColor, oneClick: false, hiliteClass:'colorize_selected_row'}) ;
}

function deleteSelected(){
    if($('.colorize_selected_row').length){
	var delete_multi = "/assets/delete_multi";
	if(confirm('Are you sure you want to PERMANENTLY delete the selected assets?')){
	    $(".colorize_selected_row td a[href*='edit']").each(function(){
		var pieces = $(this).attr("href").split('/');
		var id = pieces[pieces.length-1];
		delete_multi += "/" + id;
	    });

	    window.location = delete_multi;
	}
    } else{
	alert('No assets are selected.');
    }
}

function downloadSelected(){
    if($('.colorize_selected_row').length > 1){
        var download_multi = "/assets/download_multi";
        $("#dl_multi_form").remove();
        $("body").append("<form id='dl_multi_form' method='post'></form>");
        $("#dl_multi_form").attr("action", download_multi);

        $(".colorize_selected_row td a[class*='filename_list']").each(function(){
            var dl_asset = $("#collection_id").val() + $("#access_code").val() + "/" + $(this).text();
            $("#dl_multi_form").append('<input type="hidden" name="assets[]" value="' + dl_asset + '" />')
        });

        $("#file_prep_trigger").trigger('click');
        //*
        $.ajax({
            type: 'POST',
            url: download_multi,
            data: $("#dl_multi_form").serialize(),
            success: dl_multi_result
        });
        //*/

      //$("#dl_multi_form").submit();
    } else{
        alert('Sorry, fewer than two files have been selected. Click on the FILE ICON for each asset you\'d like to download then click the "Download Multiple" button. All selected files will be bundled into a single .ZIP archive.');
    }
}

function dl_multi_result(data, textStatus, XMLHttpRequest){
    var dest = data;
    $("#assets_tbl").before(data);
    // debugger;
    //console.log(dest);
    window.location = dest;
    tb_remove();
}
