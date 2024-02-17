var bulkassignmenu = new function(){
//get registered user list
	var getuserlist = function(visibility){
	//JSON data
		var data ={
				"visibility": visibility,
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_users_list', JSON.stringify(data), function(data){
		//prepare datatable
			$('#menu-user-table').dataTable( {
				data: data.data,
				columns: [
					{
						'data'	: 'userid',
						'class'	: 'text-center',
						'render': function ( data, type, full, meta ) {
						//return
							return "<input type='checkbox' name='chkUsers' class='chkUsers' value='"+data+"' />";
						}
					},
					{'data': 'username'},
					{
						'data':'worktype',
						'class': 'text-center',
						'render': function ( data, type, full, meta) {
							var html= "";
						//return 
							if(data==0){
								html += '<span class="label label-success">Admin</span>'; 
							}
							else if(data==1){
								html += '<span class="label label-primary">Scaffold</span>'; 
							}
							else if(data==2){
								html += '<span class="label label-warning">Brickwork</span>';
							}
							return html;
						}
					},
				],
				"scrollY"		: '60vh',
				"bDestroy"		: true,
				"searching"		: false,
				"lengthChange"	: false,
				"paging"		: false
			});
        });
	},

//get menu list
	getmenulist = function(visibility){
	//JSON data
		var data ={
				"visibility": visibility,
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_menu_list', JSON.stringify(data), function(data){
		//prepare datatable
			$('#menu-list-table').dataTable( {
				data: data.data,
				columns: [
					{
						'data'	: 'pk_menu_id',
						'class'	: 'text-center',
						'render': function ( data, type, full, meta ) {
						//return
							return "<input type='checkbox' name='chkMenu' class='chkMenu' value='"+data+"' />";
						}
					},
					{'data': 'menu_name'},
				],
				"bDestroy"		: true,
				"searching"		: false,
				"lengthChange"	: false,
				"paging"		: false
			});
        });
	},

//edit selected user
	addmenuassign = function(){
		$(".chkUsers,.chkMenu").closest("tr").removeClass("check-error");
	//users list
		var userslist = $('input:checkbox:checked.chkUsers').map(function () {
			return this.value;
		}).get();
	//menu list
		var menulist  = $('input:checkbox:checked.chkMenu').map(function () {
			return this.value;
		}).get();
	//check validation
		if((userslist.length>0) && (menulist.length>0))
		{
		//form data
			var data ={
				"userslist"	: userslist,
				"menulist"	: menulist,
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			}
		//pre-loader show
			$(".ajax-loader").show();
		//server action
			service.operationDataService('add_menu_assign',JSON.stringify(data),function(data){
			//check condition (TRUE||FALSE)
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//redirect page
					location.href = "menu-management.php";
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
			if(userslist.length==0){
				$(".chkUsers").closest("tr").addClass("check-error");
			//error
				toastr.options.positionClass = "toast-bottom-left";
				toastr.options.timeout = 1000;
				toastr.error("User Not Selected");
			}
			if(menulist.length==0){
				$(".chkMenu").closest("tr").addClass("check-error");
			//error
				toastr.options.positionClass = "toast-bottom-right";
				toastr.options.timeout = 1000;
				toastr.error("Menu Not Selected");
			}
		}
	},

//initialization
    init=function(){
	//get users list
		getuserlist(1);
	
	//get users list
		getmenulist(1);
    };

//user list checkbox selected
	$(document).on("click", "#menu-user-table tr", function(){
		$(this).find(".chkUsers").click();
	});

//menu list checkbox selected
	$(document).on("click", "#menu-list-table tr", function(){
		$(this).find(".chkMenu").click();
	});

//bind
    var normanweb = {
        init			: init,
		addmenuassign	:	addmenuassign
    }
    return normanweb;
}();