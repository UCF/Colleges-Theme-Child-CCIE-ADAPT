jQuery(document).ready(function(){

    var datatable = jQuery('#adapt-data').DataTable( {
        "ajax": ajax_url,
        "language": { search: "" },
    } );

    jQuery('input[type="checkbox"]'). click(function(){
        var form = jQuery(this).closest("form"),
            //len = jQuery('input[type=checkbox]:checked').length,
            data = form.serialize(),
            ajax_source = ajax_url + '&' + data;
        
        datatable.ajax.url(ajax_source).load();
    });

    // jQuery('input#left-search').on("keyup search input paste cut", function() {
    //    datatable.search(this.value).draw();
    // });

    jQuery("#left-dt-search").html(jQuery(".dataTables_filter"));

    jQuery(".dataTables_filter input[type='search']")
          .addClass("form-control")
          .attr("placeholder", "Search...");

});