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

// require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
// require_once(dirname(__FILE__).'/lib.php');
// require_once('policies.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/config.php');
require_once('lib.php');
require_once('policies.php');
require_once('classes/recertpol.php');



$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // recertpol instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('recertpol', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $recertpol  = $DB->get_record('recertpol', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $recertpol  = $DB->get_record('recertpol', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $recertpol->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('recertpol', $recertpol->id, $course->id, false, MUST_EXIST);
} else 
  {
    $clist = get_courses();
  }

require_login();
$context = context_system::instance();

// add_to_log($course->id, 'recertpol', 'view', "view.php?id={$cm->id}", $recertpol->name, $cm->id);
// 
// /// Print the page header
// 
$PAGE->set_url('/mod/recertpol/view.php');
$PAGE->set_title(format_string('Recertification Policies'));
$PAGE->set_heading(format_string('Promotion policy'));
$PAGE->set_context($context);
// 
// // Modify the Settings Navigation menu
// $settingnode = $PAGE->settingsnav->add('Recert policy', new moodle_url('/mod/recertpol/policies.php'), navigation_node::TYPE_CONTAINER);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('recertpol-'.$somevar);

// Output starts here
echo $OUTPUT->header();
echo $OUTPUT->heading('Current Courses:');
// echo '<pre>' . print_r( $clist, true ) . '</pre>';

$course_pol = new recertpol();

$courseslist = '';
$listitem = '';

// Create a list of current policies
foreach( $clist as $courseObj )
{
 if( ($courseObj->category != 0) && ($courseObj->visible == 1) )
 {
   $course_pol->get_policy( $courseObj->id );
   $listitem = '';
   $listitem = '<li> Title: ' . $courseObj->fullname . '</li>';
   $listitem .= '<ul><li> Course ID number: ' . $courseObj->id . '</li>';
   $listitem .= '<li> Category: ' . $courseObj->category . '</li>';
   $listitem .= '<li> Next recertification course: ' . $course_pol->nxt_course_id . '</li></ul>';
 }
 $courseslist .= $listitem;
}

echo '<ul>' . $courseslist . '</ul>';

// Finish the page
echo $OUTPUT->footer();
