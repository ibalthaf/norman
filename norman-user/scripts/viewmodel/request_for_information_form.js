var report = new function(){
//get request for information form
	var gettoolboxreport = function(txtDateFrom, txtDateTo){
	//show loader
		$(".ajax-loader").show();
	//JSON data
		var data ={
			"txtDateFrom"	: txtDateFrom,
			"txtDateTo"		: txtDateTo,
			"visibility"	: 1,
			"siteid"		: localStorage.getItem("siteid"),
			"session"		: localStorage.getItem("session"),
			"worktype"		: GetURLParameter('worktype')
		};
	//server action
        service.operationDataService('get_request_for_information_report', JSON.stringify(data), function(data){
		//prepare datatable
			$('#site-supervisor-table').dataTable( {
				data: data.data,
				columns: [
					{
						'data'	: null,
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							return  meta.row+1;
						}
					},
					{'data': 'createdon'},
					{'data': 'sitename'},
					{'data': 'ownername'},
					{'data': 'inspectedby'},
					{
						'data': 'pdfname',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = '';
						//check null value
							if(data!=null){
								html = '<a href="'+serverFolderBase+"/"+data+'" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-download"></i></a>';
							}
							else{
								html = '<button type="button" class="btn btn-danger btn-xs" title="File Not Found" disabled><i class="fa fa-download"></i></button>';
							}
						//return data
							return html;
						}
					},
				],
				"bDestroy": true
			});
		//records not found
			if(data.HTTP_StatusCode==201){
				$(".downloadreport").attr("disabled", true);
			}else{
				$(".downloadreport").attr("disabled", false);
			}
		//hide loader
			$(".ajax-loader").hide();
        });
	},

//download report
	downloadreport = function(){
	//show loader
		$(".ajax-loader").show();
	//JSON data
		var data ={
				"txtFormId"		: GetURLParameter('formid'),
				"txtDateFrom"	: $("#txtDateFrom").val(),
				"txtDateTo"		: $("#txtDateTo").val(),
				"worktype"		: GetURLParameter('worktype'),
				"visibility"	: 1,
				"siteid"		: localStorage.getItem("siteid"),
				"session"		: localStorage.getItem("session")
			};
	//server action
        service.operationDataService('prepare_pdf_file', JSON.stringify(data), function(datas){
			if(datas.HTTP_StatusCode==200){
				window.open(serverFolderBase+"/"+datas.data.filename,'_blank');
			}
		//hide loader
			$(".ajax-loader").hide();
		});
	},

//initialization
    init=function(){
	//from date
		$('#txtDateFrom').daterangepicker({
			singleDatePicker: true,
			singleClasses	: "picker_4",
			maxDate			: new Date(),
		}, function(start, end, label) {
			$('#txtDateTo').focus();
		//end date
			$('#txtDateTo').daterangepicker({
				singleDatePicker: true,
				singleClasses	: "picker_4",
				minDate			: start,
				maxDate			: new Date(),
			}, function(start, end, label) {
			//sort data
				var date = new Date(start);
				gettoolboxreport($("#txtDateFrom").val(), date.format('Y-m-d'));
			});
		});
	//get data
		gettoolboxreport("", "");
    };

//bind
    var normanweb = {
        init			: init,
		downloadreport	: downloadreport,
    }
    return normanweb;
}();