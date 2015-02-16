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
 * Bulk user enrollment processing.
 * Adapted from a core Moodle module by Sam Hemelryk (c)2013
 *
 * @package    enrol_staff
 * @copyright  2015 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require($_SERVER['DOCUMENT_ROOT'] . "/moodle/config.php");
//require($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/staff/enroll_staff.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/staff/lib.php");
//require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/locallib.php");
//require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/users_forms.php");
//require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/renderer.php");
//require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/enrollib.php");

global $CFG, $DATA, $PAGE, $OUTPUT;

//Retrieve the array of staff selected for enrollment
$select     = required_param_array('select', PARAM_INT); 
$context = context_system::instance();
//$bulkuserop = 'editselectedusers';

// From this request, extract the lists of targeted courses and staff for the enrollment action
// Isolate and sort the keys from the checktable where the enrollment choices
// have been made by the administrator.
$courses_target = array_keys( $select );
$courses_srtd = natsort( $courses_target );

// Init lists of course ids and user ids
$cids_list = array();
$uids_list = array();

// Now make separate lists of courses and userids
foreach($courses_target as $cid_targ)
{
  // The first half of the retrieved key is the course_id
  $cid = explode( "-", $cid_targ );
  // and use course ids as a key if its not already there
  if( array_key_exists( $cid[0], $cids_list ))
  {
    // Pick up the user id and add it to the list
    $uids_list[] = $select[ $cid_targ ];
    // and attach list of users to the course id key
    $cids_list[ $cid[0] ] = $uids_list;
  } else 
    {
      // If the course id not already there start a new list of user ids
      $uids_list = array();
      $uids_list[] = $select[ $cid_targ ];
      // and attach that list of users to the course id key
      $cids_list[ $cid[0] ] = $uids_list;
    }
}

$staff_enroller = new enrol_staff_plugin();

// With lists for the courseids and userids that need to be enrolled in them,
foreach( $cids_list as $enr_course=>$enr_users )
{
  $enr_instance = $DB->get_record( 'enrol', array( 'courseid'=>$enr_course, 'enrol' => 'staff' ), '*', MUST_EXIST );
  $context = context_course::instance($enr_course, MUST_EXIST);
  
  //require_login($course);
//  require_login();
//  require_capability('moodle/course:enrolreview', $context);
  //$PAGE->set_pagelayout('admin');

  foreach( $enr_users as $usr )
  {
    if( !$staff_enroller->enrol_user( $enr_instance, $usr, "5" ))  
    {
      print_r( "Enrollment failed for user $usr" );
      continue;
    }
  }

}


$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);
echo $OUTPUT->header();
echo $OUTPUT->heading($operation->get_title());
//$mform->display();
echo $OUTPUT->footer();
