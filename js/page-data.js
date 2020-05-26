$(document).ready(function(){    
    var datatable = $('#adapt-data').DataTable( {
        "ajax": ajax_url,
    } );


    $('input[type="checkbox"]'). click(function(){
        var form = $(this).closest("form"),
            //len = jQuery('input[type=checkbox]:checked').length,
            data = form.serialize(),
            ajax_source = ajax_url + '&' + data;
        
        datatable.ajax.url(ajax_source).load();
    }); 


});