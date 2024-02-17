var assignmentlist = new function(){
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
			$('#assignment-user-table').dataTable( {
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
						'data'	: 'userid',
						'class' : 'text-center',
						'render': function ( data, type, full, meta ) {
							var html = "";
						//Menu assignment
							html += '<a href="user-menu.php?id='+data+'" class="btn btn-success btn-xs menu-assignment" data-userid="'+data+'" title="Menu Assignment"><i class="fa fa-plus-circle"></i> Assign</a>';
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

//initialization
    init=function(){
	//restrict views for user
		if(localStorage.getItem("type")!=0){
			$(".restricted-user").remove();
		}
	//get all user list
		getuserlist(1);
    };

//hover to zoom image
	$(document).on("mouseover", ".profile-sm", function(){
        $(this).addClass('profile-transition');
    });

//mouse out
	$(document).on("mouseout", ".profile-sm", function(){
        $(this).removeClass('profile-transition');
    });

//bind
    var normanweb = {
        init			: init,
    }
    return normanweb;
}();