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
 *
 * @package    enrol_staff
 * @copyright  2015 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require($_SERVER['DOCUMENT_ROOT'] . "/moodle/config.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/staff/lib.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/moodle/enrol/staff/staff_completion_form.php");

global $CFG, $DATA, $PAGE, $OUTPUT;

// Pick up the returned parameters
$response_cancel = optional_param('cancel', 'continue', PARAM_TEXT);
$response_selection = optional_param_array('select', array(0), PARAM_INT);

// $PAGE setup stuff
$PAGE->set_url('/enrol/staff/change.php');
$PAGE->set_title(format_string('Staff Enrollment'));
$PAGE->set_heading(format_string('Confirmation of enrollments'));
//$context = context_system::instance();
$PAGE->set_context(context_system::instance());

xdebug_break();

if( $response_cancel <> 'continue' )
{
 
    redirect( $CFG->wwwroot );

} else
   {
    //Extract the array of staff selected for enrollment
    $select = $response_selection;
    
    // From this request, extract the lists of targeted courses and staff for 
    // the enrollment action.
    // Isolate and sort the keys from the checktable where the enrollment choices
    // have been made by the administrator.
    $courses_target = array_keys( $select );
    $courses_srtd = natsort( $courses_target );
    
    // Init lists for course ids and user ids
    $cids_list = array();
    $uids_list = array();
    
    // Now make separate lists of courses and userids
    foreach($courses_target as $cid_targ)
    {
      // The first half of the retrieved key is the course_id
      // and use course ids as a key if its not already there
      $cid = explode( "-", $cid_targ );
      if( array_key_exists( $cid[0], $cids_list ))
      {
        // Pick up the user id and add it to the list
        // and attach list of users to the course id key
        $uids_list[] = $select[ $cid_targ ];
        $cids_list[ $cid[0] ] = $uids_list;
      } else 
        {
          // If the course id not already there start a new list of user ids
          // and attach that list of users to the course id key
          $uids_list = array();
          $uids_list[] = $select[ $cid_targ ];
          $cids_list[ $cid[0] ] = $uids_list;
        }
    }
    
    $staff_enroller = new enrol_staff_plugin();
    
    // With lists for the courseids and userids that need to be enrolled in them,
    // Prepare the confirmation list for display
    $staffenrolled = "<ul>"; 
    
    foreach( $cids_list as $enr_course=>$enr_users )
    {
      // Get the course object, then similar to flatfile enrollment, ie. 
      // if there's no enrol record, add one and then retrieve it
      $enr_course_obj = $DB->get_record( 'course', array( 'id'=>$enr_course ));
      $enr_instance = $DB->get_record( 'enrol', array( 'courseid'=>$enr_course, 'enrol' => 'staff' ) );
      if( empty($enr_instance))
      {
        // Get the course object
        $enr_course_obj = $DB->get_record( 'course', array( 'id'=>$enr_course ));
        // It is ok to add an enroll instance to the course if it is not yet there
        $enroll_id = $staff_enroller->add_instance( $enr_course_obj );
        $enr_instance = $DB->get_record( 'enrol', array( 'id' => $enroll_id ));
      }
    
      // Make a list of successful enrollments for display to the user
      foreach( $enr_users as $usr )
      {
        if( !$staff_enroller->enrol_user( $enr_instance, $usr, "5" ))  
        {
          $staffenrolled .= "<li>Enrollment problem for userid $usr in courseid $enr_course</li>";
        } else
          {
            $staffenrolled .= "<li>Userid $usr enrolled in courseid $enr_course</li>";
          }
      }
    }
    // Close off the confirmation list for display
    $staffenrolled .= "</ul>";
  }

$mform = new staff_completion_form( null, array( 'staffenrolled'=>$staffenrolled ));


// Output starts here
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
