<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// ( )at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/* WBLMS customized event handler definition.
 * This observer will watch for completed quiz submissions
 * and award a certificate if the quiz grade is 80% or greater.
 *
 * @package local_getcertificate
 * @category event
 * @copyright 2014  Paul LaRiviere ( )plariv@augurynet.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Observer list

$observers = array(
                   array( 
                     'eventname'   => '\mod_quiz\event\attempt_submitted',
                     'includefile' => '/mod/quiz/locallib.php',
                     'callback'    => '\local_getcertificate\get_certificate_handler'
                   )
                 );

