var userforms = new function(){
//get menu list
	var getformlist = function(visibility){
	//JSON data
		var data ={
			"visibility": visibility,
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session"),
			"worktype"  : GetURLParameter("type")
		};
	//server action
        service.operationDataService('get_forms_list', JSON.stringify(data), function(data){
		//prepare datatable
			$('#form-list-table').dataTable( {
				data: data.data,
				columns: [
					{
						'data'	: 'formid',
						'class'	: 'text-center',
						'render': function ( data, type, full, meta ) {
						//return
							return "<input type='checkbox' name='chkForm' class='chkForm' value='"+data+"' />";
						}
					},
					{'data': 'formname'},
				],
				"bDestroy"		: true,
				"searching"		: false,
				"lengthChange"	: false,
				"paging"		: false
			});
		//JSON data
			var datas ={
				"viewuserid": GetURLParameter("id"),
				"visibility": visibility,
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
		//server action
			service.operationDataService('get_assigned_forms', JSON.stringify(datas), function(data){
			//check condition (TRUE||FALSE)
				if(data.HTTP_StatusCode=="200"){
					$('input:checkbox.chkForm').map(function () {
						var event = this;
						var mainmenu = this.value;
					//loop start
						$.each(data.data, function(rows, menu){
							if(mainmenu==menu.formid){
								$(event).prop("checked", true);
							}
						});
					//loop end
					});
				}
				else{
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
				}
			});
        });
	},

//edit selected user
	addmformassign = function(){
		$(".chkForm").closest("tr").removeClass("check-error");
	//menu list
		var formlist  = $('input:checkbox:checked.chkForm').map(function () {
			return this.value;
		}).get();
	//check validation
		if((formlist.length>0))
		{
		//form data
			var data ={
				"userslist"	: [GetURLParameter("id")],
				"formlist"	: formlist,
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			}
		//pre-loader show
			$(".ajax-loader").show();
		//server action
			service.operationDataService('add_form_assign',JSON.stringify(data),function(data){
			//check condition (TRUE||FALSE)
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//redirect page
					location.href = "form-assignment.php";
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
			if(formlist.length==0){
				$(".chkForm").closest("tr").addClass("check-error");
			//error
				toastr.options.positionClass = "toast-bottom-right";
				toastr.options.timeout = 1000;
				toastr.error("Menu Not Selected");
			}
		}
	},

//get selected user information
    getuserinfo=function(visibility){
	//JSON data
		var data ={
				"edituserid": GetURLParameter("id"),
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_user_info', JSON.stringify(data), function(data){
			$(".lblProfileName").html("").html(data.data.firstname+" "+data.data.lastname);
			$(".lblEmail").html("").html(data.data.useremail);
			$(".lblUserType").html("").html('<span class="'+userTypeArray[data.data.usertypeid].label+'">'+userTypeArray[data.data.usertypeid].title+'</span>');
			$(".profile-image").attr("src", "").attr("src", "data:image/png;base64, "+data.data.userimg);
		});
	},

//initialization
    init=function(){
	//get user information
		getuserinfo(1);
	//get users list
		getformlist(1);
    };

//menu list checkbox selected
	$(document).on("click", "#form-list-table tr", function(){
		$(this).find(".chkForm").click();
	});

//bind
    var normanweb = {
        init			: init,
		addmformassign	: addmformassign
    }
    return normanweb;
}();


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