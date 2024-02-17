var sliderlist = new function(){
//get slider image list
	var getsliderslist = function(visibility){
	//show loader
		$(".ajax-loader").show();
	//JSON data
		var data ={
			"visibility": visibility,
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
        service.operationDataService('get_slider_images', JSON.stringify(data), function(data){
		//prepare datatable
			$('#registered-user-table').dataTable({
				data: data.data,
				columns: [
					{
						'data'	: null,
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							return  meta.row+1;
						}
					},
					{
						'data'	: 'slider_image',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
						//return data
							return "<img src='data:image/png;base64,"+data+"' class='preview-img' />";
						}
						
					},
					{'data': 'slider_description'},
					{
						'data'	: 'is_visible',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
						//check condition
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
						'data'	: 'pk_slider_id',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
						//view
							html += '<a href="" class="btn btn-info btn-xs view-slider" data-sliderid="'+data+'" title="view" data-toggle="modal" data-target=".view-slider-image"><i class="fa fa-eye"></i></a>';
						//check login view for users
							if(localStorage.getItem("type")==0){
							//delete
								html += '<button type="button" class="btn btn-'+(visibility==1 ? 'danger' : 'success')+' btn-xs visibility-slider" data-status="'+(visibility==1 ? 0 : 1)+'" data-sliderid="'+data+'" title="Delete"><i class="fa fa-'+(visibility==1 ? 'trash' : 'eye')+'"></i></button>';
							}
						//return
							return html;
						}
					}
				],
				"bDestroy"	: true,
				//"scrollY"	: false,
			});
		//hide loader
			$(".ajax-loader").hide();
        });
	},

//active sldier image list
	activesliders = function(){
		$(".activesliders").hide();
		$(".inactivesliders").show();
	//get slider image list
		getsliderslist(1);
	},

//inactive slider image list
	inactivesliders = function(){
		$(".inactivesliders").hide();
		$(".activesliders").show();
	//get slider image list
		getsliderslist(0);
	}

//click event
	$(document).on("click", ".view-slider", function(){
	//JSON data
		var data ={
			"txtSliderId": $(this).attr("data-sliderid"),
			"userid"	: localStorage.getItem("userid"),
			"session"	: localStorage.getItem("session")
		};
	//server action
        service.operationDataService('get_slider_info', JSON.stringify(data), function(data){
			$(".lblImage").html("").html("<img src='data:image/png;base64, "+data.data.slider_image+"' width='300px' />");
			$(".lblDesc").html("").html(data.data.slider_description);
			$(".lblStatus").html("").html((data.data.is_visible==1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">InActive</span>'));
		});
	});

//hover to zoom image
	$(document).on("mouseover", ".preview-img", function(){
        $(this).addClass('transition');
    });

//mouse out
	$(document).on("mouseout", ".preview-img", function(){
        $(this).removeClass('transition');
    });

//change user visibility
	$(document).on("click", ".visibility-slider", function(){
		var sliderid	 = $(this).attr("data-sliderid");
		var txtVisibility= $(this).attr("data-status");
	//confirm condition
		if(confirm("Are you sure do you want to change the visibility?")){
		//JSON data
			var data ={
				"txtVisibility"	: txtVisibility,
				"txtSliderId"	: sliderid,
				"userid"		: localStorage.getItem("userid"),
				"session"		: localStorage.getItem("session")
			};
		//server action
			service.operationDataService('update_slider_visibility', JSON.stringify(data), function(data){
			//check condition (TRUE||FALSE)
				if(data.HTTP_StatusCode=="200"){
					toastr.options.positionClass = "toast-bottom-left";
					toastr.options.timeout = 1000;
					toastr.success(data.data.message);
				//get slider image list
					getsliderslist((txtVisibility==1 ? 0 : 1));
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
	//get slider image list
		getsliderslist(1);
    };

//bind
    var normanweb = {
        init			: init,
		activesliders	: activesliders,
        inactivesliders	: inactivesliders
    }
    return normanweb;
}();