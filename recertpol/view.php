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
 * Prints a particular instance of recertpol
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_recertpol
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// require_once(dirname(__FILE__).'/lib.php');
// require_once('policies.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/config.php');
require_once('lib.php');
require_once('policies.php');
require_once('classes/recertpol.php');
require_once('recert_form.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // recertpol instance ID - it should be named as the first character of the module

if ($id) 
{
    $cm         = get_coursemodule_from_id('recertpol', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $recertpol  = $DB->get_record('recertpol', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) 
  {
    $recertpol  = $DB->get_record('recertpol', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $recertpol->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('recertpol', $recertpol->id, $course->id, false, MUST_EXIST);
  } //else 
    //{
    //$courseList = get_courses();
    //}

require_login();
$context = context_system::instance();

// 
// Set up the page here
// 
$PAGE->set_url('/mod/recertpol/view.php');
$PAGE->set_title(format_string('Recertification Policies'));
$PAGE->set_heading(format_string('Promotion policy'));
$PAGE->set_context($context);

// Get a list of all available courses
$courseList = get_courses();

// Instantiate the form for use on this page
$mform = new recertpol_edit_form();

// xdebug_break();

$rcPolicy = new recertpol();

// Create a list of all current policies and list of active courses for selection
$allRcPolicies = array();
$allCourses = array( '0' => 'Select a course' );
foreach( $courseList as $courseObj )
{
  // Visible courses that are not in Cat-0 are 'production' courses
  if( ($courseObj->category != 0) && ($courseObj->visible == 1) )
  {
    // It's a production course, so get its policy
    $rcPolicy = new recertpol();
    $rcPolicy->get_policy( $courseObj->id );
    if ( $rcPolicy->get_id() != '0' )
    {
      // Set up an entry in the list of policies
      $entry = array( 'curCourseId' => $rcPolicy->get_cur_course_id(),
                      'curCourseName' => $courseObj->fullname,
                      'nextCourseId' => $rcPolicy->get_nxt_course_id()
                    );
      // Add this course to the array for dropdown list
      $allCourses["{$courseObj->id}"] = $courseObj->fullname;
    } else 
      {
        // This must be a newly added course, so create an incomplete
        // recertification policy and include it in the list so
        // it will set up for completion
        $rcPolicyNew = new recertpol( $courseObj->id, '0' );
        $entry = array( 'curCourseId' => $rcPolicy->get_cur_course_id(),
                      'curCourseName' => $courseObj->fullname,
                      'nextCourseId' => $rcPolicy->get_nxt_course_id()
                      );
      }
  $allRcPolicies[] = $entry;
  }
}

// Instantiate the form for use on this page
$mform = new recertpol_edit_form( null, array( 'policylist' => $allRcPolicies,
                                               'allcourselist' => $allCourses ));


if ( $mform->is_cancelled())
{
  redirect( 'moodle' );
} else if ( $fromform = $mform->get_data())
  {
    echo print_r($fromform, true);
    for( $i=0, $i<=18, $i++ )
    {
      if ( $fromform->courseNextOri.$i <> $fromform->courseNextUpd.$i )
      {
        $recertupdate = new recertpol();
        $recertupdate->update_recertpol( $fromform->courseCur.$i, $fromform->courseNextUpd.$i );
      }
    }
  }

// Output starts here
echo $OUTPUT->header();
echo $OUTPUT->heading('Current Courses:');

$mform->display();

// Finish the page
echo $OUTPUT->footer();
