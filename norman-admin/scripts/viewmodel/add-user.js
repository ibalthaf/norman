var addnewuser = new function(){
	var userTypeObservableArray = ko.observableArray();
	var workTypeObservableArray = ko.observableArray();

//add user validation
	adduser={
        txtUserType	:ko.observable().extend({required:{message:'User Type Required'}}),
        txtEmpID	:ko.observable().extend({required:{message:'Employee ID Required'}}),
        txtFirstName:ko.observable().extend({required:{message:'First Name Required'}}),
        txtLastName	:ko.observable().extend({required:{message:'Last Name Required'}}),
		txtEmail	:ko.observable().extend({required:{message:'Email Required'},email: true}),
		txtPhone	:ko.observable().extend({required:{message:'Phone Required'}}),
    },
	adduser.errors = ko.validation.group(adduser);

//edit selected user
	var adduserinfo = function(){
	//form data
		var data ={
			"txtUserType"	: $("#txtUserType").val(),
			"txtWorkType"	: ($("#txtUserType").val()==2 ? $("#txtWorkType").val() : 0),
			"txtEmpID"		: $("#txtEmpID").val(),
			"txtFirstName"	: $("#txtFirstName").val(),
			"txtLastName"	: $("#txtLastName").val(),
			"txtEmail"		: $("#txtEmail").val(),
			"txtPhone"		: $("#txtPhone").val(),
			"txtProfileImg"	: $("#txtProfileData").val(),
			"txtStatus"		: $(".txtStatus:checked").val(),
			"userid"		: localStorage.getItem("userid"),
			"session"		: localStorage.getItem("session")
		}
		console.log(data);
	//validate
		if(adduser.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//server action
			service.operationDataService('add_user_info',JSON.stringify(data),function(data){
			//check status code
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//redirect page
					location.href="users-management.php";
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
			adduser.errors.showAllMessages();
		}
	}

//initialization
    init=function(){
		userTypeObservableArray(userTypeArray);
		workTypeObservableArray(workTypeArray);
    };

//events
	$(document).on("change", "#txtUserType", function(){
		if($(this).val()==0 || $(this).val()==1){
			$("#txtWorkType").closest("div").parent("div").hide();
		}
		else{
			$("#txtWorkType").closest("div").parent("div").show();
		}
	});

//bind
    var normanweb = {
        init					: init,
		adduser					: adduser,
		adduserinfo				: adduserinfo,
		userTypeObservableArray : userTypeObservableArray,
		workTypeObservableArray	: workTypeObservableArray
    }
    return normanweb;
}();

//profile image
	function encodeImageFileAsURL()
	{
		var filesSelected = document.getElementById("txtProfileImg").files;
		if (filesSelected.length > 0)
		{
			var fileToLoad = filesSelected[0];
			var fileReader = new FileReader();
			fileReader.onload = function(fileLoadedEvent)
			{
				var srcData = fileLoadedEvent.target.result;
				$(".addImage").attr("src", srcData);
				imgdata = srcData.replace('data:image/png;base64,'	, '');
				imgdata = imgdata.replace('data:image/jpeg;base64,'	, '');
				imgdata = imgdata.replace('data:image/jpg;base64,'	, '');
				$("#txtProfileData").val(imgdata);
			}
			fileReader.readAsDataURL(fileToLoad);
		}
	}