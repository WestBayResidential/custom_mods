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


define(['jquery', 'enrol_staff/datatables'], function ($, dataTables) {

    return {

        function myTableInit() {

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

        }
    }

});
    
    
