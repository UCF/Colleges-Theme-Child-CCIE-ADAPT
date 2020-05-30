jQuery(document).ready(function(){    
    var datatable = jQuery('#adapt-data').DataTable( {
        "ajax": ajax_url,
    } );


    jQuery('input[type="checkbox"]'). click(function(){
        var form = jQuery(this).closest("form"),
            //len = jQuery('input[type=checkbox]:checked').length,
            data = form.serialize(),
            ajax_source = ajax_url + '&' + data;
        
        datatable.ajax.url(ajax_source).load();
    }); 


});