var commonlogin = new function(){
//validation
	loginPage = {
		txtUserName:ko.observable().extend({required:{message:'Username Required'}, minLength:5, maxLength:50}),
		txtPassword:ko.observable().extend({required:{message:'Password Required'}, minLength:5, maxLength:20})
	}
	loginPage.errors = ko.validation.group(loginPage);
//login
	var login = function(){
		var data ={
			"txtUserName"	: loginPage.txtUserName(),
			"txtPassword"	: loginPage.txtPassword()
		};
		if(loginPage.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//service call
			service.operationDataService('customer_login',JSON.stringify(data),function(data){
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success("Login Successfully");
				//set session data
					localStorage.setItem("siteid"	, data.data.pk_site_id);
					localStorage.setItem("session"	, data.data.mobile_token);
					location.href = "dashboard.php";
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
			loginPage.errors.showAllMessages();
		}
	},
//init
	init = function(){
	//check login page
		if (window.history && window.history.pushState) {
			window.history.pushState('forward', null, './#forward');
		//location reload
			$(window).on('popstate', function() {
				location.href="index.php";
			});
		}

	//check session
		var userId = localStorage.getItem('siteid');
        if(userId != null){
            location.href = "dashboard.php";
        }
		else{
			$(".ajax-loader").hide();
			$(".login-page").show();
		}
	}
//initialization
	var normanweb={
		init		: init,
		loginPage	: loginPage,
		login		: login
	}
	return normanweb;
}