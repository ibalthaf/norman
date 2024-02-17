var sitemenu = new function(){
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
                $('#menu-list-table').dataTable( {
                    data: data.data,
                    columns: [
                        {
                            'data'	: 'formid',
                            'class'	: 'text-center',
                            'render': function ( data, type, full, meta ) {
                                //return
                                return "<input type='checkbox' name='chkMenu' class='chkMenu' value='"+data+"' />";
                            }
                        },
						{
							'data': 'worktype',
						 	'class': 'text-center',
							'render': function ( data, type, full, meta) {
								var html= "";
							//return 
								if(data==1){
									html += '<span class="label label-info">Scaffold</span>'; 
								}
								else if(data==2){
									html += '<span class="label label-primary">Brickwork</span>';
								}
								return html;
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
                    "viewsiteid"    : GetURLParameter("id"),
                    "visibility"    : visibility,
                    "userid"	    : localStorage.getItem("userid"),
                    "session"	    : localStorage.getItem("session")
                };
                //server action
                service.operationDataService('get_user_assigned_forms', JSON.stringify(datas), function(data){
                    //check condition (TRUE||FALSE)
                    if(data.HTTP_StatusCode=="200"){
                        $('input:checkbox.chkMenu').map(function () {
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
        addformassign = function(){
            $(".chkMenu").closest("tr").removeClass("check-error");
            //menu list
            var formlist  = $('input:checkbox:checked.chkMenu').map(function () {
                return this.value;
            }).get();
            //check validation
            if((formlist.length>0))
            {
                //form data
                var data ={
                    "siteslist"	: [GetURLParameter("id")],
                    "formlist"	: formlist,
                    "userid"	: localStorage.getItem("userid"),
                    "session"	: localStorage.getItem("session")
                }
                //pre-loader show
                $(".ajax-loader").show();
                //server action
                service.operationDataService('add_user_form_assign',JSON.stringify(data),function(data){
                    //check condition (TRUE||FALSE)
                    if(data.HTTP_StatusCode=="200"){
                        toastr.options.positionClass = "toast-bottom-left";
                        toastr.options.timeout = 1000;
                        toastr.success(data.data.message);
                        //redirect page
                        location.href = "sites-management.php";
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
                    $(".chkMenu").closest("tr").addClass("check-error");
                    //error
                    toastr.options.positionClass = "toast-bottom-right";
                    toastr.options.timeout = 1000;
                    toastr.error("Form Not Selected");
                }
            }
        },

//get selected user information
        getsiteinfo=function(visibility){
            //JSON data
            var data ={
                "siteid"    : GetURLParameter("id"),
                "userid"	: localStorage.getItem("userid"),
                "session"	: localStorage.getItem("session")
            };
            //server action
            service.operationDataService('get_site_info', JSON.stringify(data), function(data){
                $(".lblSiteName").html("").html(data.data.sitename);
                $(".lblEmail").html("").html(data.data.siteemail);
                $(".lblSiteOwner").html("").html(data.data.ownername);
                $(".lblSiteManager").html("").html(data.data.sitemanager);
                $(".lblSitePhone").html("").html(data.data.sitephone);
                $(".lblSiteAddress").html("").html(data.data.siteaddress);
            });
        },

//initialization
        init=function(){
            //get user information
            getsiteinfo(1);
            //get users list
            getformlist(1);
        };

//menu list checkbox selected
    $(document).on("click", "#menu-list-table tr", function(){
        $(this).find(".chkMenu").click();
    });

//bind
    var normanweb = {
        init			: init,
        addformassign	:	addformassign
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