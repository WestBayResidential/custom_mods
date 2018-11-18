define([], function () {
    window.requirejs.config({
        paths: {
           //Enter the paths to your required java-script files  
           "datatables": M.cfg.wwwroot + '/enrol/staff/js/DataTables-1.10.18/js/jquery.dataTables.min',
           "select": M.cfg.wwwroot + '/enrol/staff/js/Select-1.2.6/js/select.dataTables',
           "dtinit": M.cfg.wwwroot + '/enrol/staff/js/dtInit'
        },
        shim: {
           //Enter the "names" that will be used to refer to your libraries
           'datatables': {exports: 'dataTables'},
           'select': {exports: 'select'},
           'dtinit': {exports: 'dtInit'}
        }
    });
});
