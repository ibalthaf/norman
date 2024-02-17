//menu assignment
	function menu_assignment()
	{
		var userId 	= localStorage.getItem('userid');
		if(userId != null){
		//JSON data
			var data ={
				"viewuserid": localStorage.getItem("userid"),
				"visibility": 1,
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
		//server action
			service.operationDataService('get_assigned_menu', JSON.stringify(data), function(data){
				var html = "";
			//che
				if(data.HTTP_StatusCode=="200"){
				//each function
					$.each(data.data, function(row, menus){
						html += '<li>';
							html += '<a href="'+menus.menu_url+'"><i class="'+menus.menu_icon+'"></i> '+menus.menu_name+'</a>';
						html += '</li>';
					});
				}
				else{
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
				//prepare data
					html += '<li>';
						html += '<a ><i class="fa fa-ban"></i> Temporarily Unavailable.!</a>';
					html += '</li>';
				}
			//append data
				$(".login-side-menu").html(html);
			});
		}
	}

//menu view
	menu_assignment();

//get login user info
	function login_info()
	{
		var userId 	= localStorage.getItem('userid');
		if(userId != null){
		//JSON data
			var data ={
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
		//server action
			service.operationDataService('get_login_info', JSON.stringify(data), function(data){
				//check condition
				if(data.HTTP_StatusCode == "200"){
					var date = new Date();
					$(".last-login").html(date.format('d-M-Y'));
					$(".lbllogin").html(data.data.username);
					$(".profile_img").attr("src", "data:image/png;base64,"+data.data.userimg);
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