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
 * Prints the main page for the bulkenroll administrative functions
 *
 *
 * @package    mod_bulkenroll
 * @copyright  2014 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG, $DATA

require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/mod/bulkenroll/lib.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/mod/bulkenroll/bulkenroll_edit_form.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/mod/bulkenroll/bulkenroll_select_form.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$res = optional_param('res', 0, PARAM_TEXT); // name of residence
$cat = optional_param('cat', 0, PARAM_INT);  // course category id

// Print the page header and confirm that user is logged in
$PAGE->set_url('/mod/bulkenroll/view.php', array('id' => "Whiting" ));
require_login();

//add_to_log($course->id, 'bulkenroll', 'view', "view.php?id={$cm->id}", $bulkenroll->name, $cm->id);
if( $id )
{

// Set up the residences list for selection
$all_residences = array( "ressel" => "Select a residence",
                         "AMANDA"=>"Amanda",
                         "APARTMENTS"=>"Apartments",
                         "BRACKEN"=>"Bracken",
                         "BURDICK"=>"Burdick",
                         "CELESTIA"=>"Celestia",
                         "CENTRAL"=>"Central",
                         "CHURCH"=>"Church",
                         "CLAYPOOL"=>"Claypool",
                         "DARLENE"=>"Darlene",
                         "DAWN"=>"Dawn",
                         "DAY PROGRAM"=>"Day Program",
                         "EVERGREEN"=>"Evergreen",
                         "FAIRWAY"=>"Fairway",
                         "GLEN HILLS"=>"Glen Hills",
                         "GRAND"=>"Grand",
                         "GREENWICH"=>"Greenwich",
                         "HAVERHILL"=>"Haverhill",
                         "HELEN"=>"Helen",
                         "IMERA"=>"Imera",
                         "KNOLLWOOD"=>"Knollwood",
                         "LANCELOTTA"=>"Lancelotta",
                         "LILLIAN"=>"Lillian",
                         "MARIE"=>"Marie",
                         "NATICK"=>"Natick",
                         "OAKLAND"=>"Oakland",
                         "OFFICE"=>"Office",
                         "REDDINGTON"=>"Reddington",
                         "SHERWOOD"=>"Sherwood",
                         "TARTAGLIA"=>"Tartaglia",
                         "THISTLE"=>"Thistle",
                         "WHITING"=>"Whiting");

$all_categories = array( "catsel" => "Select a category",
                         "sixty" => "Within 60 days",
                         "ninety" => "Within 90 days",
                         "onetwenty" => "Within 120 days",
                         "annual" => "Annual",
                         "biannual" => "Biannual"
                       );

// Instantiate the parameter selection form for use on this page
$mform = new bulkenroll_select_form( null, array( 'residencelist'=>$all_residences,
  'categorylist'=>$all_categories ));
} elseif ( !($res) && !($cat) )
  {
  
  // Get employee roster and count of selected residence
  $sql = "SELECT a.id, a.lastname, a.firstname, b.fieldid, b.data
          FROM mdl_user a
          JOIN mdl_user_info_data b ON a.id = b.userid 
          WHERE a.deleted=0
          AND b.fieldid=7
          AND b.data LIKE '%" . $res . "%'
          ORDER BY a.lastname";
  $emplRoster = $DB->get_records_sql( $sql );
  $emplCount = count( $emplRoster );


  // Get list of courses in selected category
  $sql = "SELECT shortname, idnumber 
          FROM mdl_course 
          WHERE category=" . $cat . 
          " ORDER BY idnumber");

  $table = "course";
  $select = $cat;
  $params = NULL;
  $fields = 'shortname, idnumber';
  $sort = 'idnumber';

  $courses = $DB->get_records_select_menu( $table, $select, $params, $fields, $sort )
  $coursesCount = count( $courses );


  } else
    {    
       // Make a selection for both residence and course category
    }

// Output starts here
echo $OUTPUT->header();

// Replace the following lines with you own code
echo $OUTPUT->heading('This is bulkenroll talking...');

$mform->display();

// Finish the page
echo $OUTPUT->footer();
