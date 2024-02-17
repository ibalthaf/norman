//global variable declaration
	var userTypeArray = [{value : 0, title : "Administrator", label : "label label-success"}, {value : 1, title : "Manager", label : "label label-primary"}, {value : 2, title : "Supervisor", label : "label label-warning"}];
	var workTypeArray = [{value : 0, title : "Scaffolding"}, {value : 1, title : "Brickwork"}];

//url parameter seperator
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