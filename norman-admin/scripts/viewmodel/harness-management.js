var harnesslist = new function(){
//edit validation
	editharness={
        txtIDNo		:ko.observable().extend({required:{message:'Employee ID Required'}}),
        txtMake		:ko.observable().extend({required:{message:'First Name Required'}}),
        txtModel	:ko.observable().extend({required:{message:'Last Name Required'}}),
		txtSerialNo	:ko.observable().extend({required:{message:'Email Required'}}),
		txtDOM		:ko.observable().extend({required:{message:'Phone Required'}}),
		txtDOP		:ko.observable().extend({required:{message:'Phone Required'}}),
		txtOwnerName:ko.observable().extend({required:{message:'Phone Required'}}),
		txtFrequency:ko.observable().extend({required:{message:'Phone Required'}}),
    },
	editharness.errors = ko.validation.group(editharness);

//get harness detail list
	var getharnesslist = function(visibility){
	//JSON data
		var data ={
				"visibility": visibility,
				"usertype"	: localStorage.getItem("type"),
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_harness_list', JSON.stringify(data), function(data){
		//prepare datatable
			$('#harness-details-table').dataTable( {
				data: data.data,
				columns: [
					{
						'data'	: null,
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							return  meta.row+1;
						}
					},
					{'data': 'owner'},
					{'data': 'idno'},
					{'data': 'make'},
					{'data': 'model'},
					{'data': 'serialno'},
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
						'data'	: 'harnessid',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
						//view
							html += '<a href="" class="btn btn-info btn-xs view-user" data-harnessid="'+data+'" title="view" data-toggle="modal" data-target=".view-harness-details"><i class="fa fa-eye"></i></a>';
						//check login view for users
							if(localStorage.getItem("type")==0){
							//edit
								html += '<a href="" class="btn btn-primary btn-xs edit-user" data-harnessid="'+data+'" title="Edit" data-toggle="modal" data-target=".edit-harness-details"><i class="fa fa-edit"></i></a>';
							//delete
								html += '<button type="button" class="btn btn-'+(visibility==1 ? 'danger' : 'success')+' btn-xs visibility-harness" data-status="'+(visibility==1 ? 0 : 1)+'" data-harnessid="'+data+'" title="Delete"><i class="fa fa-'+(visibility==1 ? 'trash' : 'eye')+'"></i></button>';
							}
						//return
							return html;
						}
					}
				],
				"bDestroy": true,
			});
        });
	},

//edit selected user
	editharnessinfo = function(){
	//form data
		var data ={
			"txtIDNo"		: $("#txtIDNo").val(),
			"txtMake"		: $("#txtMake").val(),
			"txtModel"		: $("#txtModel").val(),
			"txtSerialNo"	: $("#txtSerialNo").val(),
			"txtDOM"		: $("#txtDOM").val(),
			"txtDOP"		: $("#txtDOP").val(),
			"txtOwnerName"	: $("#txtOwnerName").val(),
			"txtFrequency"	: $("#txtFrequency").val(),
			"txtStatus"		: $(".txtStatus:checked").val(),
			"txtHarnessId"	: $("#txtHarnessId").val(),
			"userid"		: localStorage.getItem("userid"),
			"session"		: localStorage.getItem("session")
		}
	//validate
		if(editharness.errors().length==0){
		//pre-loader show
			$(".ajax-loader").show();
		//server action
			service.operationDataService('edit_harness_info',JSON.stringify(data),function(data){
			//check condition (TRUE||FALSE)
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//get user list
					login_info();
					getharnesslist($("#txtView").val());
					$('.edit-harness-details').modal('toggle');
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
			editharness.errors.showAllMessages();
		}
	},

//active user list
	activeharness = function(){
		$(".activeharness").hide();
		$(".inactiveharness").show();
	//get user list
		getharnesslist(1);
	},

//inactive user list
	inactiveharness = function(){
		$(".inactiveharness").hide();
		$(".activeharness").show();
	//get user list
		getharnesslist(0);
	}

//click event
	$(document).on("click", ".view-user", function(){
	//pre-loader show
		$(".ajax-loader").show();
	//JSON data
		var data ={
				"txtHarnessId": $(this).attr("data-harnessid"),
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_harness_info', JSON.stringify(data), function(data){
			$(".lblUserNames").html("").html(data.data.owner);
			$(".lblIdNo").html("").html(data.data.id_no);
			$(".lblMake").html("").html(data.data.make);
			$(".lblModel").html("").html(data.data.model);
			$(".lblSerialNo").html("").html(data.data.serial_no);
			$(".lblDOM").html("").html(data.data.dom);
			$(".lblPurchaseDate").html("").html(data.data.dop);
			$(".lblInsFrequency").html("").html(data.data.inspection_frequency);
			$(".lblStatus").html("").html((data.data.sdform_visibility==1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">InActive</span>'));
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
				"txtHarnessId": $(this).attr("data-harnessid"),
				"userid"	: localStorage.getItem("userid"),
				"session"	: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('get_harness_info', JSON.stringify(data), function(data){
		//set value for validation
			editharness.txtIDNo(data.data.id_no);
			editharness.txtMake(data.data.make);
			editharness.txtModel(data.data.model);
			editharness.txtSerialNo(data.data.serial_no);
			editharness.txtDOM(data.data.dom);
			editharness.txtDOP(data.data.dop);
			editharness.txtOwnerName(data.data.owner);
			editharness.txtFrequency(data.data.inspection_frequency);
		//set value
			$("#txtHarnessId").val("").val(data.data.pk_sdform_id);
			$("#txtIDNo").val("").val(data.data.id_no);
			$("#txtMake").val("").val(data.data.make);
			$("#txtModel").val("").val(data.data.model);
			$("#txtSerialNo").val("").val(data.data.serial_no);
			$("#txtDOM").val("").val(data.data.dom);
			$("#txtDOP").val("").val(data.data.dop);
			$("#txtOwnerName").val("").val(data.data.owner);
			$("#txtFrequency").val("").val(data.data.inspection_frequency);
			$(".txtStatus[value=" + data.data.sdform_visibility + "]").prop('checked', true);
			$("#txtView").val(data.data.sdform_visibility);
		//pre-loader hide
			$(".ajax-loader").hide();
		//initiate date picker
			//date of manufacture
			$('#txtDOM').daterangepicker({
				singleDatePicker: true,
				singleClasses	: "picker_4",
				maxDate			: new Date(),
				setDate			: data.data.dom,
			}, function(start, end, label) {
				//purchase date
				$('#txtDOP').daterangepicker({
					singleDatePicker: true,
					singleClasses	: "picker_4",
					minDate			: start,
					maxDate			: new Date(),
				}, function(start, end, label) {
				});
			});
			//purchase date
			$('#txtDOP').daterangepicker({
				singleDatePicker: true,
				singleClasses	: "picker_4",
				minDate			: start,
				maxDate			: new Date(),
				setDate			: data.data.dop,
			}, function(start, end, label) {
			});
		});
	});

//change harness deails visibility
	$(document).on("click", ".visibility-harness", function(){
		if(confirm("Are you sure do you want to change the visibility?")){
			var editharnessid = $(this).attr("data-harnessid");
			var txtVisibility = $(this).attr("data-status");
		//JSON data
			var data ={
					"txtVisibility"	: txtVisibility,
					"txtHarnessId"	: editharnessid,
					"userid"		: localStorage.getItem("userid"),
					"session"		: localStorage.getItem("session")
				};
		//server action
			service.operationDataService('update_harness_visibility', JSON.stringify(data), function(data){
			//check condition (TRUE||FALSE)
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//get user list
					getharnesslist((txtVisibility==1 ? 0 : 1));
				}
				else{
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.error(data.data.message);
				}
			});
		}
	});

//initialization
    init=function(){
	//restrict views for user
		if(localStorage.getItem("type")!=0){
			$(".restricted-user").remove();
		}
	//get harness list
		getharnesslist(1);
    };

//bind
    var normanweb = {
        init			: init,
		editharness		: editharness,
		editharnessinfo	: editharnessinfo,
		activeharness	: activeharness,
        inactiveharness	: inactiveharness
    }
    return normanweb;
}();