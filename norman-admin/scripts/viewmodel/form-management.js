var formslist = new function(){
	editform={
        txtFormName:ko.observable().extend({required:{message:'Form Name Required'}}),
    },
	editform.errors = ko.validation.group(editform);
//get registered user list
	var getformslist = function(visibility){
	//JSON data
		var data ={
				"viewuserid": localStorage.getItem("userid"),
				"visibility": visibility,
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_assigned_forms', JSON.stringify(data), function(data){
		//check condition (TRUE||FALSE)
			if(data.HTTP_StatusCode!="200"){
				toastr.options.positionClass = "toast-bottom-left";
				toastr.options.timeout = 1000;
				toastr.error(data.data.message);
			}
		//prepare datatable
			$('#form-list-table').dataTable( {
				data: data.data,
				columns: [
					{
						'data'	: null,
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							return  meta.row+1;
						}
					},
					{'data': 'formname'},
					{
						'data':'worktype',
						'class': 'text-center',
						'render': function ( data, type, full, meta) {
							var html= "";
						//return 
							if(data==1){
								html += '<label class="label label-info">Scaffold</label>'; 
							}
							else if(data==2){
								html += '<label class="label label-primary">Brickwork</label>';
							}
							return html;
						}
					},
					{
						'data'	: 'status',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
						//check condition
							if(data==1){
								html = '<label class="label label-success">Active</label>';
							}
							else{
								html = '<label class="label label-danger">InActive</label>';
							}
						//return
							return html;
						}
					},
					{
						'data'	: null,
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
						//view
							html += '<a href="generate-report.php?formid='+data.formid+'&worktype='+data.worktype+'" class="btn btn-primary btn-xs view-form" data-formid="'+data.formid+'" title="Generate Reports"><i class="fa fa-file-pdf-o"></i></a>';
							return html;
						}
					},
					{
						'data'	: 'formid',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
						//view
							html += '<a href="" class="btn btn-info btn-xs view-form" data-formid="'+data+'" title="view" data-toggle="modal" data-target=".view-form-info"><i class="fa fa-eye"></i></a>';
						//check login view for users
							if(localStorage.getItem("type")==0){
							//edit
								html += '<a href="" class="btn btn-primary btn-xs edit-form" data-formid="'+data+'" title="Edit" data-toggle="modal" data-target=".edit-form-info"><i class="fa fa-edit"></i></a>';
							//delete
								html += '<button type="button" class="btn btn-'+(visibility==1 ? 'danger' : 'success')+' btn-xs visibility-form" data-status="'+(visibility==1 ? 0 : 1)+'" data-formid="'+data+'" title="Delete"><i class="fa fa-'+(visibility==1 ? 'trash' : 'eye')+'"></i></button>';
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

//edit selected form
	updateforminfo = function(){
	//form data
		var data ={
			"txtFormName"	: $("#txtFormName").val(),
			"txtStatus"		: $(".txtStatus:checked").val(),
			"txtFormId"		: $("#txtFormId").val(),
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		}
	//validate
		if(editform.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//server action
			service.operationDataService('edit_form_info',JSON.stringify(data),function(data){
			//check server status
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//get user list
					getformslist($("#txtView").val());
					$('.edit-form-info').modal('toggle');
					$('.modal-backdrop').hide();
				}
				else
				{
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
				}
			//pre-loader hide
				$(".ajax-loader").hide();
			});
		}
		else{
			editform.errors.showAllMessages();
		}
	},

//active user list
	activeforms = function(){
		$(".activeforms").hide();
		$(".inactiveforms").show();
	//get user list
		getformslist(1);
	},

//inactive user list
	inactiveforms = function(){
		$(".inactiveforms").hide();
		$(".activeforms").show();
	//get user list
		getformslist(0);
	}

//click event
	$(document).on("click", ".view-form", function(){
	//JSON data
		var data ={
				"formid": $(this).attr("data-formid"),
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_form_info', JSON.stringify(data), function(data){
			$(".lblFormName").html("").html(data.data.formname);
			$(".lblStatus").html("").html((data.data.status==1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">InActive</span>'));
		});
	});

//click edit form view
	$(document).on("click", ".edit-form", function(){
	//JSON data
		var data ={
				"formid"	: $(this).attr("data-formid"),
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_form_info', JSON.stringify(data), function(data){
		//set value for validation
			editform.txtFormName(data.data.formname);

		//set value
			$("#txtFormId").val("").val(data.data.formid);
			$("#txtFormName").val("").val(data.data.formname);
			$(".txtStatus[value=" + data.data.status + "]").prop('checked', true);
			$("#txtView").val(data.data.status);
		});
	});

//change user visibility
	$(document).on("click", ".visibility-form", function(){
		if(confirm("Are you sure do you want to change the visibility?")){
			var formid = $(this).attr("data-formid");
			var txtVisibility = $(this).attr("data-status");
		//JSON data
			var data ={
					"txtVisibility"	: txtVisibility,
					"formid"		: formid,
					"userid"		: localStorage.getItem("userid"),
					"session"		: localStorage.getItem("session")
				};
		//server action
			service.operationDataService('update_form_visibility', JSON.stringify(data), function(data){
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//get user list
					getformslist((txtVisibility==1 ? 0 : 1));
				}
				else
				{
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
				}
			});
		}
	});

//initialization
    init=function(){
		getformslist(1);
    };
//bind
    var normanweb = {
        init			: init,
		editform		: editform,
		updateforminfo	: updateforminfo,
		activeforms		: activeforms,
        inactiveforms	: inactiveforms
    }
    return normanweb;
}();