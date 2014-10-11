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
 * recertification policy test
 *
 * @package    mod_recertpol
 * @category   phpunit
 * @copyright  2014 Paul R LaRiviere (Augury LLC, plariv@augurynet.com)
 * @author     Paul LaRiviere
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//TODO: Are these next 2 lines needed?
//defined('MOODLE_INTERNAL') || die();
global $CFG, $DB;

class recertpol
{
  
  private $id;
  private $cur_course_id;
  private $nxt_course_id;

  public function __construct($currentCourseId, $nextCourseId)
  {
    $this->set_cur_course_id($currentCourseId);
    $this->set_nxt_course_id($nextCourseId);
    $recertpol_id = $DB->insert_record( 'recertpol', $this );
  }

  
  public function set_cur_course_id( $course_id )
  {
    $this->cur_course_id = $course_id;
  }


  public function set_nxt_course_id( $course_id )
  {
    $this->nxt_course_id = $course_id;
  }


  public function get_id()
  {
    return $this->id;
  }


  public function get_cur_course_id()
  {
    return $this->cur_course_id;
  }


  public function get_nxt_course_id()
  {
    return $this->nxt_course_id;
  }

}


