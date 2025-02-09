var userslist = new function(){
//edit validation
	edituser={
        txtEmpID	:ko.observable().extend({required:{message:'Employee ID Required'}}),
        txtFirstName:ko.observable().extend({required:{message:'First Name Required'}}),
        txtLastName	:ko.observable().extend({required:{message:'Last Name Required'}}),
		txtEmail	:ko.observable().extend({required:{message:'Email Required'},email: true}),
		txtPhone	:ko.observable().extend({required:{message:'Phone Required'}}),
    },
	edituser.errors = ko.validation.group(edituser);

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
			$('#registered-user-table').dataTable( {
				data: data.data,
				columns: [
					{
						'data'	: null,
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							return  meta.row+1;
						}
					},
					{'data': 'username'},
					{'data': 'useremail'},
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
					{
						'data': 'userimg',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
						//return
							return "<img src='data:image/png;base64,"+data+"' class='profile-sm' />";
						}
					},
					{
						'data'	: 'status',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
							if(data==1){
								html = '<span class="label label-success">Active</span>';
							}
							else{
								html = '<span class="label label-danger">InActive</sapn>';
							}
						//return
							return html;
						}
					},
					{
						'data'	: 'userid',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
						//view
							html += '<a href="" class="btn btn-info btn-xs view-user" data-userid="'+data+'" title="view" data-toggle="modal" data-target=".view-registered-user"><i class="fa fa-eye"></i></a>';
						//check login view for users
							if(localStorage.getItem("type")==0){
							//edit
								html += '<a href="" class="btn btn-primary btn-xs edit-user" data-userid="'+data+'" title="Edit" data-toggle="modal" data-target=".edit-registered-user"><i class="fa fa-edit"></i></a>';
							//dele""
								html += '<button type="button" class="btn btn-'+(visibility==1 ? 'danger' : 'success')+' btn-xs visibility-user" data-status="'+(visibility==1 ? 0 : 1)+'" data-userid="'+data+'" title="Delete"><i class="fa fa-'+(visibility==1 ? 'trash' : 'eye')+'"></i></button>';
							}
						//return
							return html;
						}
					}
				],
				"bDestroy"	: true,
				//"scrollY"	: false,
			});
        });
	},

//edit selected user
	edituserinfo = function(){
	//form data
		var data ={
			"txtEmpID"		: $("#txtEmpID").val(),
			"txtFirstName"	: $("#txtFirstName").val(),
			"txtLastName"	: $("#txtLastName").val(),
			"txtEmail"		: $("#txtEmail").val(),
			"hiddenEmail"	: $("#hiddenEmail").val(),
			"txtPhone"		: $("#txtPhone").val(),
			"txtUserType"	: $("#txtUserType").find(":selected").val(),
			"txtWorkType"	: ($("#txtWorkType").find(":selected").val()==undefined ? 0 : $("#txtWorkType").find(":selected").val()),
			"txtProfileImg"	: ($("#txtProfileData").val()),
			"txtStatus"		: $(".txtStatus:checked").val(),
			"txtUserId"		: $("#txtUserId").val(),
			"userid"		: localStorage.getItem("userid"),
			"session"		: localStorage.getItem("session")
		}
	//validate
		if(edituser.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//server action
			service.operationDataService('edit_user_info',JSON.stringify(data),function(data){
			//check condition (TRUE||FALSE)
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//get user list
					login_info();
					getuserlist($("#txtView").val());
					$('.edit-registered-user').modal('toggle');
					$('.modal-backdrop').hide();
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
			edituser.errors.showAllMessages();
		}
	},

//active user list
	activeusers = function(){
		$(".activeusers").hide();
		$(".inactiveusers").show();
	//get user list
		getuserlist(1);
	},

//inactive user list
	inactiveusers = function(){
		$(".inactiveusers").hide();
		$(".activeusers").show();
	//get user list
		getuserlist(0);
	}

//click event
	$(document).on("click", ".view-user", function(){
	//pre-loader show
		$(".ajax-loader").show();
	//JSON data
		var data ={
				"edituserid": $(this).attr("data-userid"),
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_user_info', JSON.stringify(data), function(data){

			$(".lblUserType").html("").html('<span class="'+userTypeArray[data.data.usertypeid].label+'">'+userTypeArray[data.data.usertypeid].title+'</span>');
			$(".lblEmpId").html("").html(data.data.empid);
			$(".lblUserName").html("").html(data.data.firstname+" "+data.data.lastname);
			$(".lblEmail").html("").html(data.data.useremail);
			$(".lblPhone").html("").html(data.data.userphone);
			$(".lblImage").attr("src", "").attr("src", "data:image/png;base64, "+data.data.userimg);
			var signature = (data.data.signature==null ? "images/broken.png" : "data:image/png;base64,"+data.data.signature);
			$(".lblSignature").html("").html("<img src='"+signature+"' class='user-signature' />");
			$(".lblStatus").html("").html((data.data.status==1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">InActive</span>'));
		//pre-loader hide
			$(".ajax-loader").hide();
		});
	});

//click edit user view
	$(document).on("click", ".edit-user", function(){
	//pre-loader show
		$(".ajax-loader").show();
	//JSON data
		var data ={
				"edituserid": $(this).attr("data-userid"),
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_user_info', JSON.stringify(data), function(data){
		//set value for validation
			edituser.txtEmpID(data.data.empid);
			edituser.txtFirstName(data.data.firstname);
			edituser.txtLastName(data.data.lastname);
			edituser.txtEmail(data.data.useremail);
			edituser.txtPhone(data.data.userphone);
		//set value
			$("#txtUserId").val("").val(data.data.userid);
			$("#txtUserType").val(data.data.usertypeid).change();
			$("#txtWorkType").val(data.data.worktype).change();
			$("#txtEmpID").val("").val(data.data.empid);
			$("#txtFirstName").val("").val(data.data.firstname);
			$("#txtLastName").val("").val(data.data.lastname);
			$("#txtEmail").val("").val(data.data.useremail);
			$("#hiddenEmail").val("").val(data.data.useremail);
			$("#txtPhone").val("").val(data.data.userphone);
			$(".txtStatus[value=" + data.data.status + "]").prop('checked', true);
			$("#txtView").val(data.data.status);
			$("#txtProfileData").val("").val(data.data.userimg);
			$(".editImage").attr("src", "").attr("src", "data:image/png;base64, "+data.data.userimg);
		//hide work type for MANAGER user type
			if(data.data.usertypeid==2){
				$("#txtWorkType").closest("div").parent("div").show();
			}
			else{
				$("#txtWorkType").closest("div").parent("div").hide();
			}
		//pre-loader hide
			$(".ajax-loader").hide();
		});
	});

//change user visibility
	$(document).on("click", ".visibility-user", function(){
		if(confirm("Are you sure do you want to change the visibility?")){
			var edituserid = $(this).attr("data-userid");
			var txtVisibility = $(this).attr("data-status");
		//JSON data
			var data ={
					"txtVisibility"	: txtVisibility,
					"edituserid"	: edituserid,
					"userid"		: localStorage.getItem("userid"),
					"session"		: localStorage.getItem("session")
				};
		//server action
			service.operationDataService('update_user_visibility', JSON.stringify(data), function(data){
			//check condition (TRUE||FALSE)
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//get user list
					getuserlist((txtVisibility==1 ? 0 : 1));
				}
				else{
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
				}
			});
		}
	});

//hover to zoom image
	$(document).on("mouseover", ".profile-sm", function(){
        $(this).addClass('profile-transition');
    });

//mouse out
	$(document).on("mouseout", ".profile-sm", function(){
        $(this).removeClass('profile-transition');
    });

//initialization
    init=function(){
	//restrict views for user
		if(localStorage.getItem("type")!=0){
			$(".restricted-user").remove();
		}
	//get all user list
		getuserlist(1);
    };

//bind
    var normanweb = {
        init			: init,
		edituser		: edituser,
		edituserinfo	: edituserinfo,
		activeusers		: activeusers,
        inactiveusers	: inactiveusers
    }
    return normanweb;
}();

//profile image
	function encodeImageFileAsURL()
	{
		var filesSelected = document.getElementById("txtProfileImg").files;
		if (filesSelected.length > 0)
		{
			var fileToLoad = filesSelected[0];
			var fileReader = new FileReader();
			fileReader.onload = function(fileLoadedEvent)
			{
				var srcData = fileLoadedEvent.target.result;
				$(".editImage").attr("src", srcData);
				imgdata = srcData.replace('data:image/png;base64,'	, '');
				imgdata = imgdata.replace('data:image/jpeg;base64,'	, '');
				imgdata = imgdata.replace('data:image/jpg;base64,'	, '');
				$("#txtProfileData").val(imgdata);
			}
			fileReader.readAsDataURL(fileToLoad);
		}
	}