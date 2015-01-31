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
 * @package    bulkenroll
 * @copyright  2015 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require($_SERVER['DOCUMENT_ROOT'] . "/moodle/config.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/locallib.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/users_forms.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/renderer.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/group/lib.php");

global $CFG, $DATA, $PAGE, $OUTPUT;

$select     = required_param_array('select', PARAM_INT); // array of course/emps for enrollment
$context = context_system::instance();
$bulkuserop = 'editselectedusers';

// Extract lists of targeted courses and employees/users for bulk enrollment actionA
// Isolate and sort the select array's keys
$courses_target = array_keys( $select );
$courses_srtd = natsort( $courses_target );
// Init lists of course ids and user ids
$cids_list = array();
$uids_list = array();
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

foreach( $cids_list as $enr_course=>$enr_users )
{
  $course = $DB->get_record( 'course', array( 'id'=>$enr_course ), '*', MUST_EXIST );
  $context = context_course::instance($course->id, MUST_EXIST);
  
  //require_login($course);
  require_login();
  require_capability('moodle/course:enrolreview', $context);
  $PAGE->set_pagelayout('admin');
//  
  $manager = new course_enrolment_manager($PAGE, $course, 'manual');
  $table = new course_enrolment_users_table($manager, $PAGE);
//  $returnurl = new moodle_url('/bulkenroll/view.php', $table->get_combined_url_params());
//  $actionurl = new moodle_url('/bulkenroll/bulkenroll_change.php', $table->get_combined_url_params()+array('bulkuserop' => $bulkuserop));
//  
//  $PAGE->set_url($actionurl);
//  $PAGE->set_context($context);
//  navigation_node::override_active_url(new moodle_url('/bulkenroll/view.php', array('id' => $enr_course)));
//  
  $ops = $table->get_bulk_user_enrolment_operations();
  //if (!array_key_exists($bulkuserop, $ops)) {
  //    throw new moodle_exception('invalidbulkenrolop');
  //}
  $operation = $ops[$bulkuserop];
  
  // Prepare the properties of the form
  $users = $manager->get_users_enrolments($enr_users);
  
  // Get the form for the bulk operation
  //$mform = $operation->get_form($actionurl, array('users' => $users));
  // If the mform is false then attempt an immediate process. This may be an immediate action that
  // doesn't require user input OR confirmation.... who know what but maybe one day
  //if ($mform === false) {
      if ($operation->process($manager, $users, new stdClass)) {
          redirect($returnurl);
      } else {
          print_error('errorwithbulkoperation', 'enrol');
      }
  //}
  // Check if the bulk operation has been cancelled
  //if ($mform->is_cancelled()) {
  //    redirect($returnurl);
  //}
  //if ($mform->is_submitted() && $mform->is_validated() && confirm_sesskey()) {
  //    if ($operation->process($manager, $users, $mform->get_data())) {
  //        redirect($returnurl);
  //    }
  //}
}

$pagetitle = get_string('bulkuseroperation', 'enrol');

$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);
echo $OUTPUT->header();
echo $OUTPUT->heading($operation->get_title());
//$mform->display();
echo $OUTPUT->footer();
