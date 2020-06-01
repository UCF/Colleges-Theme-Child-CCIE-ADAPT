jQuery(document).ready(function(){
    
    var groupColumn = 4;

    var datatable = jQuery('#adapt-data').DataTable( {
        //"order": [[ 0, "asc" ]],
        dom: 'lBfrtip',
        buttons: [
            // {
            //     extend: 'copyHtml5',
            //     text: '<i class="fa fa-copy"></i> Copy',
            //     titleAttr: 'Copy'
            // },
            // {
            //     extend: 'excelHtml5',
            //     text: '<i class="fa fa-file-excel-o"></i> Excel',
            //     titleAttr: 'Excel'
            // },
            {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-excel-o"></i> CSV',
                titleAttr: 'CSV'
            },
            // {
            //     extend: 'pdfHtml5',
            //     text: '<i class="fa fa-file-pdf-o"></i> PDF',
            //     titleAttr: 'PDF'
            // },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Print',
                titleAttr: 'Print'
            }
 
        ],
        "ajax": ajax_url,
        "language": { search: "" },

        "columnDefs": [
            { "visible": false, "targets": groupColumn }
        ],
        //"order": [[ groupColumn, 'asc' ]],
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

    jQuery('button.search-filter-reset').click(function(){
        datatable.ajax.url(ajax_url).load();
    })

    // jQuery('input#left-search').on("keyup search input paste cut", function() {
    //    datatable.search(this.value).draw();
    // });

    // jQuery("#left-dt-print").html(jQuery("button.buttons-print"));
    // jQuery("#left-dt-excel").html(jQuery("button.buttons-excel"));
    jQuery("#left-dt-export").html(jQuery(".dt-buttons"));
    jQuery(".dt-button").addClass("btn btn-primary btn-sm");
    jQuery("#left-dt-search").html(jQuery(".dataTables_filter"));

    jQuery(".dataTables_filter input[type='search']")
          .addClass("form-control")
          .addClass("search-field")
          .attr("placeholder", "Search...")
          .wrap( "<div class='form-group has-search'></div>")
          .before("<span class='fa fa-search form-control-search mr-2'></span>");


});