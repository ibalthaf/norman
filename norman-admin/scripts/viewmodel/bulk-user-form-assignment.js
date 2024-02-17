var bulkassignform = new function(){
//get registered user list
    var getsiteslist = function(visibility){
            //JSON data
            var data ={
                "visibility": visibility,
                "userid"	: localStorage.getItem("userid"),
                "session"	: localStorage.getItem("session"),
                "usertype"  : localStorage.getItem("type")
            };
            //server action
            service.operationDataService('get_sites_list', JSON.stringify(data), function(data){
                //prepare datatable
                $('#menu-user-table').dataTable( {
                    data: data.data,
                    columns: [
                        {
                            'data'	: 'siteid',
                            'class'	: 'text-center',
                            'render': function ( data, type, full, meta ) {
                                //return
                                return "<input type='checkbox' name='chkUsers' class='chkUsers' value='"+data+"' />";
                            }
                        },
                        {'data': 'sitename'},
                        {'data': 'siteowner'},
                    ],
                    "scrollY"		: '60vh',
                    "bDestroy"		: true,
                    "searching"		: false,
                    "lengthChange"	: false,
                    "paging"		: false
                });
            });
        },

//get menu list
        getformlist = function(visibility){
            //JSON data
            var data ={
                "visibility": visibility,
                "userid"	: localStorage.getItem("userid"),
                "session"	: localStorage.getItem("session")
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
                        {'data': 'formname'},
                    ],
                    "bDestroy"		: true,
                    "searching"		: false,
                    "lengthChange"	: false,
                    "paging"		: false
                });
            });
        },

//edit selected user
        addformassign = function(){
            $(".chkUsers,.chkMenu").closest("tr").removeClass("check-error");
            //users list
            var siteslist = $('input:checkbox:checked.chkUsers').map(function () {
                return this.value;
            }).get();
            //menu list
            var formlist  = $('input:checkbox:checked.chkMenu').map(function () {
                return this.value;
            }).get();
            //check validation
            if((siteslist.length>0) && (formlist.length>0))
            {
                //form data
                var data ={
                    "siteslist"	: siteslist,
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
                if(siteslist.length==0){
                    $(".chkUsers").closest("tr").addClass("check-error");
                    //error
                    toastr.options.positionClass = "toast-bottom-left";
                    toastr.options.timeout = 1000;
                    toastr.error("Site Not Selected");
                }
                if(formlist.length==0){
                    $(".chkMenu").closest("tr").addClass("check-error");
                    //error
                    toastr.options.positionClass = "toast-bottom-right";
                    toastr.options.timeout = 1000;
                    toastr.error("Form Not Selected");
                }
            }
        },

//initialization
        init=function(){
            //get users list
            getsiteslist(1);

            //get users list
            getformlist(1);
        };

//user list checkbox selected
    $(document).on("click", "#menu-user-table tr", function(){
        $(this).find(".chkUsers").click();
    });

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