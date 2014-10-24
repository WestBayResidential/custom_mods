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

class recertpol
{
  
  private $id;
  private $cur_course_id;
  private $nxt_course_id;

  public function __construct($currentCourseId=0, $nextCourseId=0)
  {
    global $CFG, $DB;

    $this->set_cur_course_id($currentCourseId);
    $this->set_nxt_course_id($nextCourseId);

    // If a new recertification policy is not specified...
    if ( $currentCourseId == 0 && $nextCourseId == 0)
    {
      // ...return an empty recert policy object without saving anything
      $this->set_id( NULL );
      return;
    } else
      {
        // ...otherwise, save the specified recertification policy detail 
        $this->savePolicy();
        return;
      }
    }

  private function savePolicy()
  {
    global $CFG, $DB;

    $recertpol_record = new stdClass();
    $recertpol_record->cur_course_id = $this->get_cur_course_id();
    $recertpol_record->nxt_course_id = $this->get_nxt_course_id();
    $recertpol_record->timecreated = time();
    $recertpol_record->timemodified = time();
    $recertpol_id = $DB->insert_record( 'recertpol', $recertpol_record );

    $this->set_id($recertpol_id);
    return;
  }

  private function getPolicy()
  {
    
  }


  public function save_policy( $currentRecertpol )
  {
    
  }


  public function get_policy( $currentId )
  {
    $rcpolicy = $DB->get_record( 'recertpol', array( cur_course_id => $currentId ));
    return $rcpolicy;
  }

  public function update_recertpol( $id, $upd_recertpol )
  {
   // TODO Have to know what's in the original policy 
  }

  
  public function set_id( $rec_id )
  {
    $this->id = $rec_id;
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


