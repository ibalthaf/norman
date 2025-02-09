/*var serverServiceBase	= 'http://212.48.68.52/admin.php';
var serverFolderBase	= 'http://212.48.68.52';*/
var serverServiceBase   = 'https://app.norman-group.com/admin.php';
var serverFolderBase    = 'https://app.norman-group.com';

service = {
    getDataService: function (url, callback, isAsynch, itemData) {
        var syn = isAsynch !== undefined ? isAsynch : true;
        $.ajax({
            url: serverServiceBase + '/' + url,
            crossDomain:true,
            dataType: 'json',
            type: 'GET',
            async: syn
        }).done(function (data) {
            callback(data, itemData);
        });
    },

    operationDataService: function (url, data, callback, isAsynch) {
        var syn = isAsynch !== undefined ? isAsynch : true;
        $.ajax({
            url: serverServiceBase + '/' + url,
            dataType: 'json',
            type: 'POST',
          //  data: {data:data},
            data: data,
            async: syn
        }).done(function (data) {
            callback(data);
        });
    }
}







