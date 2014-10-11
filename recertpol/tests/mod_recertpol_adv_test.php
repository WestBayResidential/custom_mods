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
//global $CFG;

//global $CFG, $DB;

require_once(__DIR__ . "/../classes/recertpol.php");


class mod_recertpol_adv_testcase extends advanced_testcase 
{
  
  public function setUp()
  {

  }


  public function tearDown()
  {

  }

  public function test_create_recertpol_object() 
  {
    global $CFG, $DB;

    $startingcourse_id = '2';
    $promocourse_id = '3';

    $start_recertpol = new recertpol($startingcourse_id, $promocourse_id);
    $this->assertInstanceOf('recertpol', $start_recertpol);
    $this->assertClassHasAttribute( 'id', 'recertpol' );
    $this->assertEquals('1', $start_recertpol->get_id());
    $this->assertClassHasAttribute('cur_course_id', 'recertpol');
    $this->assertEquals('2', $start_recertpol->get_cur_course_id());
    $this->assertClassHasAttribute('nxt_course_id', 'recertpol');
    $this->assertEquals('3', $start_recertpol->get_nxt_course_id());
  }


}


