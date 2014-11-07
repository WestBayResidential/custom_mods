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

global $GLOBALS;

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

    require_once(__DIR__ . "/../classes/recertpol.php");

    $this->resetAfterTest(true);
    $startingcourse_id = '2';
    $promocourse_id = '3';

    $start_recertpol = new recertpol($startingcourse_id, $promocourse_id);
    print_r($start_recertpol);
    
    $this->assertInstanceOf('recertpol', $start_recertpol);
    $this->assertClassHasAttribute( 'id', 'recertpol' );
    $this->assertEquals('1', $start_recertpol->get_id());
    $this->assertClassHasAttribute('cur_course_id', 'recertpol');
    $this->assertEquals('2', $start_recertpol->get_cur_course_id()); $this->assertClassHasAttribute('nxt_course_id', 'recertpol');
    $this->assertEquals('3', $start_recertpol->get_nxt_course_id());
  }


  public function test_get_current_recertpol()
  {
    global $CFG, $DB;

    require_once(__DIR__ . "/../classes/recertpol.php");

    $this->resetAfterTest(true);
    $startingcourse_id = '3';
    $promocourse_id = '7';
    $wpolicy = new recertpol( $startingcourse_id, $promocourse_id );
    
    $rpolicy = new recertpol();
    $rpolicy->get_policy( $startingcourse_id );
    $this->assertEquals('3', $rpolicy->get_cur_course_id() );
    $this->assertEquals('7', $rpolicy->get_nxt_course_id() );
  }


  public function test_update_recertpol_object()
  {
    global $CFG, $DB;

    require_once(__DIR__ . "/../classes/recertpol.php");

    $this->resetAfterTest(true);

    $startingcourse_id = '2';
    $promocourse_id = '3';

    $start_recertpol = new recertpol($startingcourse_id, $promocourse_id);

    $upd_promocourse_id = '9';
    $start_recertpol->update_recertpol( $start_recertpol->get_id(), $upd_promocourse_id);
    print_r($start_recertpol);
    
    $this->assertInstanceOf('recertpol', $start_recertpol);
    $this->assertClassHasAttribute( 'id', 'recertpol' );
//    $this->assertEquals('1', $start_recertpol->get_id());
//    $this->assertClassHasAttribute('cur_course_id', 'recertpol');
//    $this->assertEquals('2', $start_recertpol->get_cur_course_id());
//    $this->assertClassHasAttribute('nxt_course_id', 'recertpol');
//    $this->assertEquals('9', $start_recertpol->get_nxt_course_id());
  }

}


