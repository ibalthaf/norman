var report = new function(){
//get registered user list
	var gettoolboxreport = function(txtDateFrom, txtDateTo){
	//show loader
		$(".ajax-loader").show();
	//JSON data
		var data ={
				"txtDateFrom"	: txtDateFrom,
				"txtDateTo"		: txtDateTo,
				"visibility"	: 1,
				"usertype"		: localStorage.getItem("type"),
				"userid"		: localStorage.getItem("userid"),
				"session"		: localStorage.getItem("session"),
				"worktype"		: GetURLParameter('worktype')
			};
	//server action
        service.operationDataService('get_new_starter_report', JSON.stringify(data), function(data){
		//prepare datatable
			$('#new-starter-table').dataTable( {
				data: data.data,
				columns: [
					{
						'data'	: null,
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							return  meta.row+1;
						}
					},
					{'data': 'surname'},
					{'data': 'forenames'},
					{'data': 'dob'},
					{'data': 'address'},
					{'data': 'mobile_no'},
					{'data': 'home_no'},
					{'data': 'email'},
					{'data': 'utr_no'},
					{'data': 'ni_no'},
					{'data': 'next_of_kin'},
					{'data': 'relationship'},
					{'data': 'next_of_kin_contact_no'},
					{'data': 'card_no'},
					{'data': 'grade'},
					{'data': 'expiredon'},
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
				columnDefs: [
                { "type": "date-br","targets":3,
                "type": "date-br","targets":15  }
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

//active user list
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
				"usertype"		: localStorage.getItem("type"),
				"userid"		: localStorage.getItem("userid"),
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
			locale: {
      				format: 'D/M/Y'
				}
		}, function(start, end, label) {
			$('#txtDateTo').focus();
		//end date
			$('#txtDateTo').daterangepicker({
				singleDatePicker: true,
				singleClasses	: "picker_4",
				minDate			: start,
				maxDate			: new Date(),
				locale: {
      				format: 'D/M/Y'
				}
			}, function(start, end, label) {
			//sort data
				//var date = new Date(start);
				//gettoolboxreport($("#txtDateFrom").val(), date.format('Y-m-d'));
				var date = new Date(start);
				var datefrom = $("#txtDateFrom").val();
				var from_date='';
				if(datefrom!="")
				{
				datefrom=datefrom.split("/");

				var from_day=datefrom[0];
				var from_month=datefrom[1];
				var from_year=datefrom[2];
				from_date=from_year+'-'+from_month+'-'+from_day; 
				}
				gettoolboxreport(from_date, date.format('Y-m-d'));
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