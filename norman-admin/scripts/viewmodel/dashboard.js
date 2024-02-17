var dashbaord = new function(){
//get registered user list
	var getuserlist = function(visibility){
	//JSON data
		var data ={
				"visibility": visibility,
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_new_users_list', JSON.stringify(data), function(data){
		//prepare datatable
			var html = "";
		//loop data start
			if(data.status=="true"){
				$.each(data.data, function(key, rows){
					html += "<tr>";	
						html += "<td>"+rows.username+"</td>";
						html += "<td>"+rows.useremail+"</td>";
						html += "<td>"+rows.createdon+"</td>";
					html += "</tr>";
				});
			//fetch data HTML
				$("#new-user-table").html(html);
			}
			else{
				$(".users-added").html("<h3 class='text-center'>Records Not Found!</h3>");
			}
        });
	},

//get site list
	getsitelist = function(visibility){
	//JSON data
		var data ={
				"visibility": visibility,
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session"),
				"usertype"	: localStorage.getItem("type")
			};
	//server action
        service.operationDataService('get_new_sites_list', JSON.stringify(data), function(data){
		//prepare datatable
			var html = "";
		//loop data start
			if(data.status=="true"){
				$.each(data.data, function(key, rows){
					html += "<tr>";	
						html += "<td>"+rows.sitename+"</td>";
						html += "<td>"+rows.siteemail+"</td>";
						html += "<td>"+rows.createdon+"</td>";
					html += "</tr>";
				});
			//fetch data HTML
				$("#new-site-table").html(html);
			}
			else{
				$(".event-EQ").html("<h3 class='text-center'>Records Not Found!</h3>");
			}
        });
	}

//initialization
    init=function(){
	//get users list
		getuserlist(1);
	//get sites list
		getsitelist(1);
    };
//bind
    var normanweb = {
        init			: init
    }
    return normanweb;
}();