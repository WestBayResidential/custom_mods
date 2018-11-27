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


define(['jquery', 'enrol_staff/datatables'], function ($, dataTable) {
 
    return {

        get_column: function(course_num, column_order){
            var enrolltbl = $('#enroltable').dataTable().api();
            var col_nodes = enrolltbl.column( column_order ).nodes();

            // Check or uncheck all cells in the column
            $('input[type="checkbox"]', col_nodes).prop('checked', function(i,val){
              return !val;
            });
            return;
        }

    };
});
