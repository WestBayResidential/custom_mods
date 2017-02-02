<?php

// This file is part of Moodle - http://moodle.org/
//
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
 * Prints the main page for the staff administrative functions
 *
 *
 * @package    mod_staff
 * @copyright  2014 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG, $DATA;

require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/enrol/staff/lib.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/enrol/staff/staff_edit_form.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/enrol/staff/staff_select_form.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/enrol/staff/checktable.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID
$cat = optional_param('cat', 'catsel', PARAM_TEXT);  // course category id

// Set up the page here
// 
$PAGE->set_url('/enrol/staff/view.php', array( 'id'=>'1'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(format_string('Staff Enrollment'));
$PAGE->set_heading(format_string('Set up employee enrollments'));

$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin( 'staff-datatable', 'enrol_staff' );
$PAGE->requires->jquery_plugin( 'staff-datatable-css', 'enrol_staff' );

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

// Instantiate the parameter selection form for use on this page
$mform = new staff_select_form( null, array( 'categorylist'=>$all_categories ));

if( $mform->is_cancelled() )
{

  redirect( $CFG->wwwroot );
  
} elseif ( $cat != 'catsel' )
  {
  
  // Get employee roster and count of selected residence
  $sql = "SELECT a.id, a.lastname, a.firstname, b.fieldid, b.data
          FROM mdl_user a
          JOIN mdl_user_info_data b ON a.id = b.userid 
          WHERE a.deleted=0
          AND b.fieldid=7
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
