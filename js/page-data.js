jQuery(document).ready(function(){
    var groupColumn = 4;

    var datatable = jQuery('#adapt-data').DataTable( {
        "ajax": ajax_url,
        "language": { search: "" },

        "columnDefs": [
            { "visible": false, "targets": groupColumn }
        ],
        "order": [[ groupColumn, 'asc' ]],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if (i%2 != 0){
                    var row_class = 'even';
                }
                else{
                    var row_class = 'odd';
                }
                if ( last !== group ) {
                    $(rows).eq(i).after(
                        '<tr class="group ' + row_class + '"><td colspan="4">'+ group +'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }


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

    jQuery("#left-dt-search").html($(".dataTables_filter"));

    jQuery(".dataTables_filter input[type='search']")
          .addClass("form-control")
          .addClass("search-field")
          .attr("placeholder", "Search...")
          .wrap( "<div class='form-group has-search'></div>")
          .before("<span class='fa fa-search form-control-search mr-2'></span>");


});