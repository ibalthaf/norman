var addnewharness = new function(){
//add user validation
	addharness={
        txtOwnerName:ko.observable().extend({required:{message:'Owner Name Required'}}),
        txtIDNo		:ko.observable().extend({required:{message:'ID No. Required'}}),
        txtMake		:ko.observable().extend({required:{message:'Make Required'}}),
        txtModel	:ko.observable().extend({required:{message:'Model Required'}}),
        txtSerialNo	:ko.observable().extend({required:{message:'Serial Number Required'}}),
		txtDOM		:ko.observable().extend({required:{message:'Date of Manufatcure Required'}}),
		txtDOP		:ko.observable().extend({required:{message:'Date of Purchase Required'}}),
		txtFrequency:ko.observable().extend({required:{message:'Frequency Required'}}),
    },
	addharness.errors = ko.validation.group(addharness);

//edit selected user
	var addharnessinfo = function(){
	//JSON data
		var data ={
			"txtOwnerName"	: $("#txtOwnerName").val(),
			"txtIDNo"		: $("#txtIDNo").val(),
			"txtMake"		: $("#txtMake").val(),
			"txtModel"		: $("#txtModel").val(),
			"txtSerialNo"	: $("#txtSerialNo").val(),
			"txtDOM"		: $("#txtDOM").val(),
			"txtFrequency"	: $("#txtFrequency").val(),
			"txtDOP"		: $("#txtDOP").val(),
			"txtStatus"		: $(".txtStatus:checked").val(),
			"userid"		: localStorage.getItem("userid"),
			"session"		: localStorage.getItem("session")
		}
	//validate
		if(addharness.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//server action
			service.operationDataService('add_harness_info',JSON.stringify(data),function(data){
			//check server status
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
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
			addharness.errors.showAllMessages();
		}
	}

//initialization
    init=function(){
	//date picker
		$('#txtDOM').daterangepicker({
			singleDatePicker: true,
			singleClasses	: "picker_4",
			maxDate			: new Date(),
		}, function(start, end, label) {
		//purchase date
			$('#txtDOP').daterangepicker({
				singleDatePicker: true,
				singleClasses	: "picker_4",
				setDate			: start,
				minDate			: start,
				maxDate			: new Date(),
			}, function(start, end, label) {
			});
		});
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
		addharness		: addharness,
		addharnessinfo	: addharnessinfo,
    }
    return normanweb;
}();