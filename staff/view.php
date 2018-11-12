<?php
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

/**
 * Staff enrollment processing.
 * Adapted from flatfile enrollment by Eugene Venter(c)2010
 * This module prints the main page for the staff administrative functions
 *
 *
 * @package    custom_mods_staff
 * @copyright  2018 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG, $DATA;

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/enrol/staff/lib.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/enrol/staff/staff_edit_form.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/enrol/staff/staff_select_form.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/enrol/staff/checktable.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID
$cat = optional_param('cat', 'catsel', PARAM_TEXT);  // course category id
$res = optional_param('res', 'all', PARAM_TEXT); // selected residence

// Set up the page here
// 
$PAGE->set_url('/enrol/staff/view.php', array( 'id'=>'1'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(format_string('Staff Enrollment'));
$PAGE->set_heading(format_string('Set up employee enrollments'));

//$PAGE->requires->jquery();
//$PAGE->requires->jquery_plugin( 'staff-datatable', 'enrol_staff' );
//$PAGE->requires->jquery_plugin( 'staff-datatable-css', 'enrol_staff' );
//$PAGE->requires->jquery_plugin( 'staff-datatable-checkboxes', 'enrol_staff' );
//$PAGE->requires->jquery_plugin( 'staff-datatable-checkboxes-css', 'enrol_staff' );
//$PAGE->requires->jquery_plugin( 'staff-datatable-checkenrolfx', 'enrol_staff' );
$PAGE->requires->js_call_amd( 'mod_staff/config', 'initialise' );
$PAGE->requires->css( '/mod/staff/js/DataTables-1.10.18/css/jquery.dataTables.min.css', true );
$PAGE->requires->css( '/mod/staff/js/Select-1.2.6/css/select.dataTables.min.css', true );
$PAGE->requires->css( '/mod/staff/js/jquery-datatables-checkboxes-1.2.11/css/dataTables.checkboxes.css', true );

// Note that the following index values correspond to record values in
// the mdl_course table. User selection returns the index, which is used
// directly in the course table query below.

$all_categories = array( "catsel" => "Select a category",
 //                         1 => "Miscellaneous",
                         2 => "Introductory",
                         3 => "Three Day Training",
 //                         4 => "Within 2 months",
 //                         5 => "Within 4 months",
                         7 => "Annual",
                         8 => "Biannual",
 //                         9 => "Within 10 days",
                         10 => "Within 1 month",
 //                         11 => "Within 3 months",
                         12 => "Archive"
                     );


/* Added by PRL 2018-04-30:
 *
 * Create a list of established locations, including
 * multi-site residence names which can be created and
 * attached to individual employees by the site
 * administrator.
 */

// Build a list of all available residence assignments including
// administrator added custom multi-site names.

$sql_res = "SELECT DISTINCT data
            FROM mdl_user_info_data
            WHERE fieldid=7
            ORDER BY data ASC";

// This call returns array of objects with residence names as keys.
// Transform it into an indexed array of names for the select list.
$all_res_objs = $DB->get_records_sql( $sql_res );
$all_residences = array_keys( $all_res_objs );


// Instantiate the parameter selection form for use on this page
$mform = new staff_select_form( null, array( 'categorylist'=>$all_categories,
                                             'residencelist'=>$all_residences));


// On SUBMIT ...
if( $mform->is_cancelled() )
{

  redirect( $CFG->wwwroot );

// Prepare checkbox page from the selected category and residence 
} elseif ( $cat != 'catsel' )
  {
  // Retrieve the string-name of selected residence, since only
  // index value is returned by the page, using the array
  // of residences that has been reformed on the select page submit.
  $resName = $all_residences[ $res ];
  
  // Get employee roster and count of selected residence
  $sql = "SELECT a.id, a.lastname, a.firstname, b.fieldid, b.data
          FROM mdl_user a
          JOIN mdl_user_info_data b ON a.id = b.userid 
          WHERE a.deleted=0
          AND b.fieldid=7
          AND b.data=\"{$resName}\"
          ORDER BY a.lastname";
  $emplRoster = $DB->get_records_sql( $sql );
  $emplCount = count( $emplRoster );

  // Get list of courses and count in the selected category
  $table = "course";
  $select = "category = \"{$cat}\" AND visible = TRUE";
  $params = NULL;
  $fields = 'shortname, id';
  $sort = '';

  $courses = $DB->get_records_select_menu( $table, $select, $params, $sort, $fields );
  $coursesCount = count( $courses );

  $tablestuff = build_checktable( $courses, $emplRoster);

  $mform = new staff_edit_form( NULL, array( 'coursetable'=>$tablestuff ));
  
  } //else
    //{    
       // Make a selection for both residence and course category
    //}

// Output starts here
echo $OUTPUT->header();

$mform->display();

// Finish the page
echo $OUTPUT->footer();
