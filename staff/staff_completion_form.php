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
 * @package    custom_mods_staff
 * @copyright  2018 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


// moodleform is defined in formslib.php
require_once($CFG->libdir . "/formslib.php");

class staff_completion_form extends moodleform
{
  // Add elements to form
  public function definition()
  {
    global $CFG;

    $mform =& $this->_form;

    $mform->MoodleQuickForm("staff_completion_form", "POST", "finish.php");

    $staffenrolledList = $this->_customdata['staffenrolled'];

    //--------------------------------------------------------------

    $mform->addElement("html", "<div><p>The following staff members have been enrolled...</p>" );
    $mform->addElement('html', $staffenrolledList);

    $this->add_action_buttons( $cancel=false, $submitlabel='OK');

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
