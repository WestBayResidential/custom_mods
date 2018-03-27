$(document).ready( function(){
    var table = $('#enroltable').DataTable({
        'scrollY':'500',
        'scrollX':true,
        'scrollCollapse':true,
        'columnDefs': [
            {
            'targets':'enr_check',
            'searchable':false,
            'orderable':false,
            'className':'dt-body-center',
            'render': function (data, type, full, meta){
                return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                }
            }
        ]
    });
});

function get_column(column_order){
    return column_order;
}

