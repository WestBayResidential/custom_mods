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

function get_column(course_num, column_order){
    var enrolltbl = $('#enroltable').DataTable();

    var col_checks = enrolltbl.column(`${course_num}-${column_order}`:name).data();

    return col_checks;
}

