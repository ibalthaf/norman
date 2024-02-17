var siteslist = new function(){
	editsiteinfo={
        txtSiteName		: ko.observable().extend({required:{message:'Site Name Required'}}),
        txtOwnerName	: ko.observable().extend({required:{message:'Site Owner Name Required'}}),
		txtSiteEmail	: ko.observable().extend({required:{message:'Site Email Required'},email: true}),
		txtSitePhone	: ko.observable().extend({required:{message:'Site Phone Required'}}),
		txtSiteAddress	: ko.observable().extend({required:{message:'Site Address Required'}}),
		txtPlotNo		: ko.observable().extend({required:{message:'Plot No. Required'}}),
		txtSiteManager	: ko.observable().extend({required:{message:'Site Manager Required'}}),
    },
	editsiteinfo.errors = ko.validation.group(editsiteinfo);

//get registered sites list
	var getsiteslist = function(visibility){
	//JSON data
		var data ={
			"visibility": visibility,
			"usertype"	: localStorage.getItem("type"),
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
        service.operationDataService('get_sites_list', JSON.stringify(data), function(data){
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
					{'data': 'sitename'},
					{'data': 'siteemail'},
					{
						'data'	: 'siteid',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
						//return
							if(localStorage.getItem("type")==0){
								return '<button class="btn btn-default btn-xs btn-massign" type="button" title="Assign Manager" data-toggle="modal" data-target=".manager-site-assignment" data-siteid="'+data+'"><i class="fa fa-user"></i> Assign</button>';
							}
							else if(localStorage.getItem("type")==1){
								return '<button class="btn btn-default btn-xs btn-sassign" type="button" title="Assign Supervisor" data-toggle="modal" data-target=".supervisor-site-assignment" data-siteid="'+data+'"><i class="fa fa-users"></i> Assign</button>';
							}
							else{
								return '<button class="btn btn-danger btn-xs" type="button" data-siteid="'+data+'"><i class="fa fa-ban"></i> Not Allowed</button>';
							}
						}
					},
					{
                        'data'	: 'siteid',
                        'class' : 'text-center',
                        'render': function ( data, type, full, meta ) {
                            var html = "";
                            //Form assignment
                            html += '<a href="site-form.php?id='+data+'&type=0" class="btn btn-success btn-xs menu-assignment" data-userid="'+data+'" title="Menu Assignment"><i class="fa fa-plus-circle"></i> Assign</a>';
                            //return
                            return html;
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
								html = '<span class="label label-danger">InActive</span>';
							}
						//return
							return html;
						}
					},
					{
						'data'	: 'siteid',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
						//view
							html += '<a href="" class="btn btn-warning btn-xs view-site" data-siteid="'+data+'" title="view" data-toggle="modal" data-target=".view-site-info"><i class="fa fa-eye"></i></a>';
						//check login view for users
							if(localStorage.getItem("type")==0){
								//edit
									html += '<a href="" class="btn btn-info btn-xs edit-site" data-siteid="'+data+'" title="Edit" data-toggle="modal" data-target=".edit-site-info"><i class="fa fa-edit"></i></a>';
								//delete
									html += '<button type="button" class="btn btn-'+(visibility==1 ? 'danger' : 'success')+' btn-xs visibility-site" data-status="'+(visibility==1 ? 0 : 1)+'" data-siteid="'+data+'" title="Delete"><i class="fa fa-'+(visibility==1 ? 'trash' : 'eye')+'"></i></button>';
							}
						//return
							return html;
						}
					}
				],
				"bDestroy": true
			});
        });
	},

//edit selected sites
	updatesiteinfo = function(){
	//form data
		var data ={
			"txtSiteName"	: $("#txtSiteName").val(),
			"txtOwnerName"	: $("#txtOwnerName").val(),
			"txtSiteEmail"	: $("#txtSiteEmail").val(),
			"txtSitePhone"	: $("#txtSitePhone").val(),
			"txtSiteAddress": $("#txtSiteAddress").val(),
			"txtPlotNo"		: $("#txtPlotNo").val(),
			"txtSiteManager": $("#txtSiteManager").val(),
			"txtStatus"		: $(".txtStatus:checked").val(),
			"txtSiteId"		: $("#txtSiteId").val(),
			"userid"		: localStorage.getItem("userid"),
			"session"		: localStorage.getItem("session")
		}
	//validate
		if(editsiteinfo.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//server action
			service.operationDataService('edit_site_info',JSON.stringify(data),function(data){
			//check server status
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//get site list
					getsiteslist($("#txtView").val());
					$('.edit-site-info').modal('toggle');
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
			editsiteinfo.errors.showAllMessages();
		}
	},

//assign sites to manager
	assignmanager = function(){
		var data ={
			"txtSiteId"		: $("#txtMSiteId").val(),
			"txtUserType"	: 0,
			"txtWorkType"	: 0,
			"txtAssignId"	: $("#txtMUserName").find(":selected").val(),
			"txtAssignedId"	: $("#txtMAssignmentId").val(),
			"userid"		: localStorage.getItem("userid"),
			"session"		: localStorage.getItem("session")
		}
		var servicecall = ($("#txtMAssignmentId").val()!="" ? "site_reassignment" : "site_assignment");
	//server action
		service.operationDataService(servicecall, JSON.stringify(data), function(data){
			if(data.HTTP_StatusCode=="200"){
				toastr.options.positionClass = "toast-bottom-left";
				toastr.options.timeout = 1000;
				toastr.success(data.data.message);
			//hide modal
				$('.manager-site-assignment').modal('toggle');
				$('.modal-backdrop').hide();
			}
		});
	},

//assign sites to manager
	assignsupervisor = function(){
		var data ={
			"txtSiteId"		: $("#txtSSiteId").val(),
			"txtUserType"	: 2,
			"txtWorkType"	: $("#txtSWorkType").find(":selected").val(),
			"txtAssignId"	: $("#txtSUserName").find(":selected").val(),
			"txtAssignedId"	: $("#txtSAssignmentId").val(),
			"userid"		: localStorage.getItem("userid"),
			"session"		: localStorage.getItem("session")
		}
		var servicecall = ($("#txtSAssignmentId").val()!="" ? "site_reassignment" : "site_assignment");
	//server action
		service.operationDataService(servicecall, JSON.stringify(data), function(data){
			if(data.HTTP_StatusCode=="200"){
				toastr.options.positionClass = "toast-bottom-left";
				toastr.options.timeout = 1000;
				toastr.success(data.data.message);
			//hide modal
				$('.supervisor-site-assignment').modal('toggle');
				$('.modal-backdrop').hide();
			}
		});
	},

//active sites list
	activesites = function(){
		$(".activesites").hide();
		$(".inactivesites").show();
	//get site list
		getsiteslist(1);
	},

//inactive sites list
	inactivesites = function(){
		$(".inactivesites").hide();
		$(".activesites").show();
	//get site list
		getsiteslist(0);
	}

//click view site info
	$(document).on("click", ".view-site", function(){
	//pre-loader show
		$(".ajax-loader").show();
	//JSON data
		var data ={
			"siteid"	: $(this).attr("data-siteid"),
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action (get site information)
        service.operationDataService('get_site_info', JSON.stringify(data), function(data){
			$(".lblSiteName").html("").html(data.data.sitename);
			$(".lblOwnerName").html("").html(data.data.ownername);
			$(".lblEmail").html("").html(data.data.siteemail);
			$(".lblPhone").html("").html(data.data.sitephone);
			$(".lblAddress").html("").html(data.data.siteaddress);
			$(".lblPlotNo").html("").html(data.data.plotno);
			$(".lblManger").html("").html(data.data.sitemanager);
			$(".lblJobNo").html("").html(data.data.jobnumber);
			$(".lblCreatedBy").html("").html(data.data.createdby);
			//$(".lblSignature").html("").html("<img src='data:image/png;base64, "+data.data.signature+"' class='user-signature' />");
			$(".lblStatus").html("").html((data.data.status==1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">InActive</span>'));
		});

	//server action (get site assignment information)
		service.operationDataService('get_site_assignment', JSON.stringify(data), function(data){
			var html = "";
			$.each(data.data, function(key, rows){
				html += "<tr>";
					html += "<td class='text-center'>"+(key+1)+"</td>";
					html += "<td>"+rows.username+"</td>";
					html += "<td>"+(rows.worktype==0 ? "SCAFFOLDING" : "BRICKWORK")+"</td>";
					html += "<td class='text-center'><img src='data:image/png;base64,"+rows.userimg+"' class='assign-user-img' /></td>";
				html += "</tr>";
			});
			$("#table-user-assignment").find("tbody").html(html);
		//pre-loader hide
			$(".ajax-loader").hide();
		});
	});

//click edit site view
	$(document).on("click", ".edit-site", function(){
	//pre-loader show
		$(".ajax-loader").show();
	//JSON data
		var data ={
			"siteid": $(this).attr("data-siteid"),
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
        service.operationDataService('get_site_info', JSON.stringify(data), function(data){
		//set value for validation
			editsiteinfo.txtSiteName(data.data.sitename);
			editsiteinfo.txtOwnerName(data.data.ownername);
			editsiteinfo.txtSiteEmail(data.data.siteemail);
			editsiteinfo.txtSitePhone(data.data.sitephone);
			editsiteinfo.txtSiteAddress(data.data.siteaddress);
			editsiteinfo.txtPlotNo(data.data.plotno);
			editsiteinfo.txtSiteManager(data.data.sitemanager);
		//set value
			$("#txtSiteId").val("").val(data.data.siteid);
			$("#txtSiteName").val("").val(data.data.sitename);
			$("#txtOwnerName").val("").val(data.data.ownername);
			$("#txtSiteEmail").val("").val(data.data.siteemail);
			$("#txtSitePhone").val("").val(data.data.sitephone);
			$("#txtSiteAddress").val("").val(data.data.siteaddress);
			$("#txtPlotNo").val("").val(data.data.plotno);
			$("#txtSiteManager").val("").val(data.data.sitemanager);
			$(".txtStatus[value=" + data.data.status + "]").prop('checked', true);
			$("#txtView").val(data.data.status);
		//pre-loader hide
			$(".ajax-loader").hide();
		});
	});

//change site visibility
	$(document).on("click", ".visibility-site", function(){
		if(confirm("Are you sure do you want to change the visibility?")){
			var siteid = $(this).attr("data-siteid");
			var txtVisibility = $(this).attr("data-status");
		//JSON data
			var data ={
				"txtVisibility"	: txtVisibility,
				"siteid"		: siteid,
				"userid"		: localStorage.getItem("userid"),
				"session"		: localStorage.getItem("session")
			};
		//server action
			service.operationDataService('update_site_visibility', JSON.stringify(data), function(data){
			//check condition
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//get site list
					getsiteslist((txtVisibility==1 ? 0 : 1));
				}
				else{
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
				}
			});
		}
	});

//assign button click event
	$(document).on("click", ".btn-sassign" ,function(){
		$("#txtSSiteId").val($(this).attr("data-siteid"));
	//JSON data
		var data ={
			"siteid"	: $(this).attr("data-siteid"),
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
        service.operationDataService('get_site_info', JSON.stringify(data), function(data){
			$("#txtSSiteName").val(data.data.sitename);
			$("#txtSWorkType").val("").change();
			$("#txtSEmail, #txtSPhone").val("");
		});
	});

//work type changed event
	$(document).on("change", "#txtSWorkType", function(){
	//show loader
		$(".ajax-loader").show();
	//get user list
	//JSON data
		var data ={
			"visibility": 1,
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
		service.operationDataService('get_users_list', JSON.stringify(data), function(data){
			var html = "<option value=''>-- SELECT --</option>";
		//check condition
			if(data.HTTP_StatusCode=="200"){
				$.each(data.data, function(key, rows){
					if(localStorage.getItem("type")==1){
						if((rows.usertypeid==2) && ($("#txtSWorkType").find(":selected").val() == rows.worktype)){
							html += "<option value='"+rows.userid+"'>"+rows.username+"</option>";
						}
					}
				});
			}
		//append data
			$("#txtSUserName").html(html);
		//get site assignment details
		//JSON data
			var data ={
				"siteid"		: $("#txtSSiteId").val(),
				"userid"		: localStorage.getItem("userid"),
				"session"		: localStorage.getItem("session")
			};
		//server action
			service.operationDataService('get_site_assignment', JSON.stringify(data), function(data){
			//check condition
				$.each(data.data, function(key, rows){
					if((rows.usertypeid==2) && (localStorage.getItem("type")==1)){
						if($("#txtSWorkType").find(":selected").val()==rows.worktype){
							$("#txtSUserName").val(rows.userid).change();
							$("#txtSAssignmentId").val(rows.assignid);
						}
						else{
							$("#txtSAssignmentId").val("");
						}
					}
					else{
						$("#txtSAssignmentId").val("");
						$("#txtSEmail, #txtSPhone").val("");
					}
				//hide loader
					$(".ajax-loader").hide();
				});
			});
		});
	});

//user name change event
	$(document).on("change", "#txtSUserName", function(){
		//JSON data
		var data ={
			"edituserid": $(this).find(":selected").val(),
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
        service.operationDataService('get_user_info', JSON.stringify(data), function(data){
		//check condition
			if(data.HTTP_StatusCode=="200"){
				$("#txtSEmail").val(data.data.useremail);
				$("#txtSPhone").val(data.data.userphone);
			}
			else{
				$("#txtSEmail, #txtSPhone").val("");
			}
		});
	});

//assign button click event
	$(document).on("click", ".btn-massign" ,function(){
	//show loader
		$(".ajax-loader").show();
	//set site id
		$("#txtMSiteId").val($(this).attr("data-siteid"));
	//JSON data
		var data ={
			"siteid"	: $(this).attr("data-siteid"),
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
        service.operationDataService('get_site_info', JSON.stringify(data), function(data){
			$("#txtMSiteName").val(data.data.sitename);
			$("#txtMEmail, #txtMPhone").val("");
		});
	/********************************** get user list **********************************/
	//JSON data
		var udata ={
			"visibility": 1,
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
		service.operationDataService('get_users_list', JSON.stringify(udata), function(data){
			var html = "<option value=''>-- SELECT --</option>";
		//check condition
			if(data.HTTP_StatusCode=="200"){
				//loop start
				$.each(data.data, function(key, rows){
					if(localStorage.getItem("type")==0){
						if(rows.usertypeid==1){
							html += "<option value='"+rows.userid+"'>"+rows.username+"</option>";
						}
					}
				});
				//loop end
			}
		//append data
			$("#txtMUserName").html(html);
		//get site assignment details
		//JSON data
			var data ={
				"siteid"		: $("#txtMSiteId").val(),
				"userid"		: localStorage.getItem("userid"),
				"session"		: localStorage.getItem("session")
			};
		//server action
			service.operationDataService('get_site_assignment', JSON.stringify(data), function(data){
			//check condition
				$.each(data.data, function(key, rows){
				//check 
					if(data.HTTP_StatusCode=="200"){
					//check manager
						if((rows.usertypeid==1) && (localStorage.getItem("type")==0)){
							$("#txtMUserName").val(rows.userid).change();
							$("#txtMAssignmentId").val(rows.assignid);
						}
						else{
							$("#txtMAssignmentId").val("");
						}
					}
					else{
						$("#txtMAssignmentId").val("");
					}
				});
			//hide loader
				$(".ajax-loader").hide();
			});
		});
	});

//user name change event
	$(document).on("change", "#txtMUserName", function(){
		//JSON data
		var data ={
			"edituserid": $(this).find(":selected").val(),
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
        service.operationDataService('get_user_info', JSON.stringify(data), function(data){
		//check condition
			if(data.HTTP_StatusCode=="200"){
				$("#txtMEmail").val(data.data.useremail);
				$("#txtMPhone").val(data.data.userphone);
			}
			else{
				$("#txtMEmail, #txtMPhone").val("");
			}
		});
	});

//initialization
    init=function(){
	//restrict views for user
		if(localStorage.getItem("type")!=0){
			$(".restricted-user").remove();
		}
	//get all sites list
		getsiteslist(1);
    };

//bind
    var normanweb = {
        init			: init,
        editsiteinfo	: editsiteinfo,
        updatesiteinfo	: updatesiteinfo,
        assignmanager	: assignmanager,
        assignsupervisor: assignsupervisor,
		activesites 	: activesites,
		inactivesites 	: inactivesites,
    }
    return normanweb;
}();