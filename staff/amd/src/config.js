define([], function () {
    window.requirejs.config({
        paths: {
           //Enter the paths to your required java-script files  
           "datatables": M.cfg.wwwroot + '/enrol/staff/js/datatables.min',
           "dtinit": M.cfg.wwwroot + '/enrol/staff/js/dtInit'
        },
        shim: {
           //Enter the "names" that will be used to refer to your libraries
           'datatables': {exports: 'dataTables'},
           'dtinit': {exports: 'dtInit'}
        }
    });
});
