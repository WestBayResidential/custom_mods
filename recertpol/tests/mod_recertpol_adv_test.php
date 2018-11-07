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
    
    $this->assertInstanceOf('recertpol', $start_recertpol);
    $this->assertEquals('1', $start_recertpol->get_id());
    $this->assertEquals('2', $start_recertpol->get_cur_course_id()); 
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
    
    // Create, record and confirm a new policy
    $rcp = new recertpol( $startingcourse_id, $promocourse_id);
    $this->assertInstanceOf('recertpol', $rcp);
    $this->assertEquals('1', $rcp->get_id());
    $this->assertEquals('2', $rcp->get_cur_course_id());
    $this->assertEquals('3', $rcp->get_nxt_course_id());
    
    // Change the promo course id in this object
    $rcp->set_nxt_course_id( '9' );
    // Now record the update
    $rcp->update_recertpol();
    // Confirm that the record is updated
    $new_rcp = new recertpol();
    $new_rcp->get_policy( $rcp->get_cur_course_id() );
    $this->assertInstanceOf('recertpol', $new_rcp);
    $this->assertEquals('1', $new_rcp->get_id());
    $this->assertEquals('2', $new_rcp->get_cur_course_id());
    $this->assertEquals('9', $new_rcp->get_nxt_course_id());
  }

  public function test_empty_recertpol_object()
  {
    global $CFG, $DB;

    require_once(__DIR__ . "/../classes/recertpol.php");

    $this->resetAfterTest(true);

    $startingcourse_id = '2';
    $promocourse_id = '3';

    $my_rcpol = new recertpol($startingcourse_id, $promocourse_id);

    $empty_course_id = '9';
    $my_rcpol->get_policy( $empty_course_id);
    
    $this->assertInstanceOf('recertpol', $my_rcpol);
    $this->assertClassHasAttribute( 'id', 'recertpol' );
    $this->assertEquals('0', $my_rcpol->get_id());
    $this->assertClassHasAttribute('cur_course_id', 'recertpol');
    $this->assertEquals('0', $my_rcpol->get_cur_course_id());
    $this->assertClassHasAttribute('nxt_course_id', 'recertpol');
    $this->assertEquals('0', $my_rcpol->get_nxt_course_id());
  }
}


