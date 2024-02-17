var resetpasssword = new function(){
//validation
	formvalidation = {
		txtNewPassword		:ko.observable().extend({required:{message:'New Password Required'}, minLength: 6}),
		txtConfirmPassword	:ko.observable().extend(
			{
				required:{
					message:'Confirm Password Required'
				},
				validation: {
					validator: function (val) {
						if ($("#txtNewPassword").val() != $("#txtConfirmPassword").val()) {
							return false;
						}
						return true;
					},
					message: 'Password does not match!'
				},
				minLength: 6
			}
		)
	}
	formvalidation.errors = ko.validation.group(formvalidation);
//login
	var reset = function(){
		var data ={
			"txtUserId"				: GetURLParameter("id"),
			"txtNewPassword"		: formvalidation.txtNewPassword(),
			"txtConfirmPassword"	: formvalidation.txtConfirmPassword()
		};
		if(formvalidation.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//service call
			service.operationDataService('reset_password',JSON.stringify(data),function(data){
				if(data.HTTP_StatusCode=="200"){
					alert(data.data.message);
				//set session data
					location.href = "index.php";
				}
				else
				{
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
				}
			//pre-loader show
				$(".ajax-loader").hide();
			});
		}
		else{
			formvalidation.errors.showAllMessages();
		}
	},

//init
	init = function(){
	};

//initialization
	var normanweb={
		init			: init,
		formvalidation	: formvalidation,
		reset			: reset
	}
	return normanweb;
}


//get parameter
	function GetURLParameter(sParam)
	{
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++)
		{
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam)
			{
				return sParameterName[1];
			}
		}
	}