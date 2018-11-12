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

require($_SERVER['DOCUMENT_ROOT'] . "/config.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/enrol/staff/lib.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/enrol/staff/staff_completion_form.php");

global $CFG, $DATA, $PAGE, $OUTPUT;


redirect( $CFG->wwwroot . '/enrol/staff/view.php' );
