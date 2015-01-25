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

//$id         = required_param('id', PARAM_INT); // course id
$select     = required_param_array('select', PARAM_BOOL); // array of emps by course for enrollment
$userids    = required_param_array('bulkuser', PARAM_INT);
$action     = optional_param('action', '', PARAM_ALPHANUMEXT);
$filter     = optional_param('ifilter', 0, PARAM_INT);

$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);
//$context = context_course::instance($course->id, MUST_EXIST);
$context = context_system::instance();
$bulkuserop = 'editselectedusers';

// Extract the list of users for bulk enrollment action
$userids    = required_param_array('bulkuser', PARAM_INT);


//if ($course->id == SITEID) {
//    redirect(new moodle_url('/'));
//}

//require_login($course);
require_login();
require_capability('moodle/course:enrolreview', $context);
$PAGE->set_pagelayout('admin');

$manager = new course_enrolment_manager($PAGE, $course, $filter);
$table = new course_enrolment_users_table($manager, $PAGE);
$returnurl = new moodle_url('/enrol/users.php', $table->get_combined_url_params());
$actionurl = new moodle_url('/enrol/bulkchange.php', $table->get_combined_url_params()+array('bulkuserop' => $bulkuserop));

$PAGE->set_url($actionurl);
$PAGE->set_context($context);
navigation_node::override_active_url(new moodle_url('/enrol/users.php', array('id' => $id)));

$ops = $table->get_bulk_user_enrolment_operations();
if (!array_key_exists($bulkuserop, $ops)) {
    throw new moodle_exception('invalidbulkenrolop');
}
$operation = $ops[$bulkuserop];

// Prepare the properties of the form
$users = $manager->get_users_enrolments($userids);

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

$pagetitle = get_string('bulkuseroperation', 'enrol');

$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);
echo $OUTPUT->header();
echo $OUTPUT->heading($operation->get_title());
//$mform->display();
echo $OUTPUT->footer();
