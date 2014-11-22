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

require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/mod/bulkenroll/lib.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/mod/bulkenroll/bulkenroll_edit_form.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/moodle/mod/bulkenroll/bulkenroll_select_form.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // bulkenroll instance ID - it should be named as the first character of the module

//if ($id) {
//    $cm         = get_coursemodule_from_id('bulkenroll', $id, 0, false, MUST_EXIST);
//    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
//    $bulkenroll  = $DB->get_record('bulkenroll', array('id' => $cm->instance), '*', MUST_EXIST);
//} elseif ($n) {
//    $bulkenroll  = $DB->get_record('bulkenroll', array('id' => $n), '*', MUST_EXIST);
//    $course     = $DB->get_record('course', array('id' => $bulkenroll->course), '*', MUST_EXIST);
//    $cm         = get_coursemodule_from_instance('bulkenroll', $bulkenroll->id, $course->id, false, MUST_EXIST);
//} else {
//    error('You must specify a course_module ID or an instance ID');
//}

require_login($course, true, $cm);
//$context = context_module::instance($cm->id);

//add_to_log($course->id, 'bulkenroll', 'view', "view.php?id={$cm->id}", $bulkenroll->name, $cm->id);

// Set up the residences list for selection
$all_residences = array( "AMANDA"=>"Amanda",
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



/// Print the page header
$PAGE->set_url('/mod/bulkenroll/view.php', array('id' => "Whiting" ));
//$PAGE->set_title(format_string($bulkenroll->name));
//$PAGE->set_heading(format_string($course->fullname));
//$PAGE->set_context($context);

// Instantiate the form for use on this page
$mform = new bulkenroll_select_form( null, array( 'residencelist'=>$all_residences ));



// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('bulkenroll-'.$somevar);

// Output starts here
echo $OUTPUT->header();

// if ($bulkenroll->intro) { // Conditions to show the intro can change to look for own settings or whatever
//     echo $OUTPUT->box(format_module_intro('bulkenroll', $bulkenroll, $cm->id), 'generalbox mod_introbox', 'bulkenrollintro');
// }

// Replace the following lines with you own code
echo $OUTPUT->heading('This is bulkenroll talking...');

$mform->display();

// Finish the page
echo $OUTPUT->footer();
