//get login user info
	function login_info()
	{
		var userId 	= localStorage.getItem('siteid');
		if(userId != null){
		//JSON data
			var data ={
				"siteid"	: localStorage.getItem("siteid"),
				"session"	: localStorage.getItem("session")
			};
		//server action
			service.operationDataService('get_login_info', JSON.stringify(data), function(data){
				//check condition
				if(data.HTTP_StatusCode == "200"){
					var date = new Date();
					$(".last-login").html(date.format('d-M-Y'));
					$(".lbllogin").html(data.data.site_owner_name);
				}
				else{
				//clear session values
					localStorage.clear();
				//message
					alert("Session expired!");
					location.href = "index.php?session=0";
				}
			//pre-loader hide
				$(".ajax-loader").hide();
			});
		}
		else{
			location.href = "index.php?session=0";
		}
	}

//call function
	login_info();

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