var addnewslider = new function(){
//edit selected user
	var addsliderinfo = function(){
		$(".file-error").remove();
	//check file size
		if($("#txtSliderImg").val()!=""){
		//pre-loader show
			$(".ajax-loader").show();
		//form data
			var data ={
				"txtSliderImg"	: $("#txtSliderData").val(),
				"txtStatus"		: $(".txtStatus:checked").val(),
				"userid"		: localStorage.getItem("userid"),
				"session"		: localStorage.getItem("session")
			}
		//server action
			service.operationDataService('add_slider_images', JSON.stringify(data), function(data){
			//check server status
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
					location.href="slider-management.php";
				}
				else{
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
				}
			//pre-loader hide
				$(".ajax-loader").hide();
			});
		}
		else{
			$("#txtSliderImg").after("<lable class='file-error'>Profile image required!</lable>");
		}
	},

//initialization
    init=function(){
    };

//image uploaded
	$(document).on("change", "#txtSliderImg", function(){
		$(".file-error").remove();
	});

//bind
    var normanweb = {
        init			: init,
		addsliderinfo	: addsliderinfo
    }
    return normanweb;
}();

function encodeImageFileAsURL()
{
	var filesSelected = document.getElementById("txtSliderImg").files;
	if (filesSelected.length > 0)
	{
		var fileToLoad = filesSelected[0];
		var fileReader = new FileReader();
		fileReader.onload = function(fileLoadedEvent)
		{
			var srcData = fileLoadedEvent.target.result;
			$(".slider-thumbnail").attr("src", srcData);
			imgdata = srcData.replace('data:image/png;base64,'	, '');
			imgdata = imgdata.replace('data:image/jpeg;base64,'	, '');
			imgdata = imgdata.replace('data:image/jpg;base64,'	, '');
			$("#txtSliderData").val(imgdata);
		}
		fileReader.readAsDataURL(fileToLoad);
	}
}