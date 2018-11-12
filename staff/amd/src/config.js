define([], function () {
    window.requirejs.config({
        paths: {
           //Enter the paths to your required java-script files  
           "datatables.net": M.cfg.wwwroot + '/enrol/staff/js/DataTables-1.10.18/js/jquery.dataTables.min.js',
           "datatables.net-select": M.cfg.wwwroot + '/enrol/staff/js/Select-1.2.6/js/select.dataTables.js'
        },
        shim: {
           //Enter the "names" that will be used to refer to your libraries
           'datatables.net': {exports: 'datatables.net'},
           'datatables.net-select': {exports: 'select'}
        }
    });
});
