var addnewsite = new function(){
//add user validation
	addsite={
        txtSiteName		:ko.observable().extend({required:{message:'Site Name Required'}}),
        txtOwnerName	:ko.observable().extend({required:{message:'Owner Name Required'}}),
        txtSiteEmail	:ko.observable().extend({required:{message:'Site Email Required'}}),
        txtSitePhone	:ko.observable().extend({required:{message:'Site Phone Required'}}),
        txtSiteAddress	:ko.observable().extend({required:{message:'Site Address Required'}}),
		txtPlotNo		:ko.observable().extend({required:{message:'Site Plot No. Required'}}),
		txtSiteManager	:ko.observable().extend({required:{message:'Site Manager Name Required'}}),
    },
	addsite.errors = ko.validation.group(addsite);

//edit selected user
	var addsiteinfo = function(){
	//form data
		var data ={
			"txtSiteName"	: $("#txtSiteName").val(),
			"txtOwnerName"	: $("#txtOwnerName").val(),
			"txtSiteEmail"	: $("#txtSiteEmail").val(),
			"txtSitePhone"	: $("#txtSitePhone").val(),
			"txtSiteAddress": $("#txtSiteAddress").val(),
			"txtPlotNo"		: $("#txtPlotNo").val(),
			"txtSiteManager": $("#txtSiteManager").val(),
			"txtStatus"		: $(".txtStatus:checked").val(),
			"userid"		: localStorage.getItem("userid"),
			"session"		: localStorage.getItem("session")
		}
	//validate
		if(addsite.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//server action
			service.operationDataService('add_site_info',JSON.stringify(data),function(data){
			//check server status
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
					location.href="sites-management.php";
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
			addsite.errors.showAllMessages();
		}
	}

//initialization
    init=function(){
    };

//events
	$(document).on("change", "#txtUserType", function(){
		if($(this).val()==1){
			$("#txtWorkType").closest("div").parent("div").hide();
		}
		else{
			$("#txtWorkType").closest("div").parent("div").show();
		}
	});

//bind
    var normanweb = {
        init		: init,
		addsite		: addsite,
		addsiteinfo	: addsiteinfo,
    }
    return normanweb;
}();