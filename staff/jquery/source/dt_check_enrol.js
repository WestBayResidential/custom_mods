// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/*
 * Staff enrollment processing.
 * Adapted from flatfile enrollment by Eugene Venter(c)2010
 * This module prints the main page for the staff administrative functions
 *
 *
 * @package    custom_mods_staff
 * @copyright  2018 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// The scrollY setting in the DataTable plugin below sets the height 
// of the viewport for the multiple enrolments selection matrix.
// $(document).ready( function(){
//     var table = $('#enroltable').DataTable({
//         'scrollY':'500',
//         'scrollX':true,
//         'scrollCollapse':true,
//         'columnDefs': [
//             {
//             'targets':'enr_check',
//             'searchable':false,
//             'orderable':false,
//             'className':'dt-body-center',
//             'render': function (data, type, full, meta){
//                 return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
//                 }
//             }
//         ]
//     });
// });
// 
// function get_column(course_num, column_order){
//     var enrolltbl = $('#enroltable').DataTable();
// 
//     var col_nodes = enrolltbl.column( column_order ).nodes();
//     // Check or uncheck all cells in the column
//     $('input[type="checkbox"]', col_nodes).prop('checked', function(i,val){
//       return !val;
//     });
// 
//     
// 
//     return;
// };


define(['jquery', 'mod_staff/datatables.net', 'mod_staff/datatables.net-select', 'mod_staff/checkboxes'], function ($, dataTables, select, checkboxes) {
var wwwroot = M.cfg.wwwroot;
 
    function initManage() {
        //Do your java-script magic here

        var table = $('#enroltable').dataTables({
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


        function get_column(course_num, column_order){
            var enrolltbl = $('#enroltable').dataTables();
        
            var col_nodes = enrolltbl.column( column_order ).nodes();
            // Check or uncheck all cells in the column
            $('input[type="checkbox"]', col_nodes).prop('checked', function(i,val){
              return !val;
            });
            return;
        };

        
    }
 
    return {
        init: function () {
            initManage();
        }
    };
});
