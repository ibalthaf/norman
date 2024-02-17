var forgotpasssword = new function(){
//validation
	formvalidation = {
		txtEmail:ko.observable().extend({required:{message:'Email Address Required'}, email:true}),
	}
	formvalidation.errors = ko.validation.group(formvalidation);
//login
	var sendotp = function(){
		var data ={
			"txtEmail"	: formvalidation.txtEmail()
		};
		if(formvalidation.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//service call
			service.operationDataService('reset_password_otp',JSON.stringify(data),function(data){
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
		sendotp			: sendotp
	}
	return normanweb;
}