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
 * This module prints the main page for the staff administrative functions
 *
 *
 * @package    custom_mods_staff
 * @copyright  2018 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This module builds the checkbox table HTML to display in the staff_edit_form
//
// @param array $courselist   Array of courses objects from a select category
// @param integer $coursecount  Number of courses in the selected category
// @param string $category  Name of category for the course list
// @param array $namelist  Array of employee names from a select residence
// @param integer $namecount  Number of emloyees listed from the selected residence
//

function build_checktable($courselist, $namelist)
{
  $checktable = '<table id="enroltable" class="display"><thead><tr>';
  // Print header row with residence name and course designations
  $checktable .= '<th>Employee</th>';

  // Make a column heading for each course and include a 'select all' checkbox,
  // in the heading cell. Then set up an array of the course numbers for use 
  // later on to create the input checkbox value
  $corder = 0;
  $cnumlist = array();

  foreach( $courselist as $cname=>$cnum )
  {
    $corder++;
    $cnum = $cnum ? $cnum : "NA";
    $cnumlist[ $corder ] = $cnum;
    $checktable .= "<th class=\"dt-head-center\"> {$cname} <br />";
    $checktable .= "<input type=\"checkbox\" name=\"col_{$corder}\" class=\"th-checkit\" onclick=\"get_column(";
    $checktable .= $cnum . ", " . $corder;
    $checktable .= ")\" /></th>";
  }
  $checktable .= '</tr></thead><tbody>';
  
  // Add a row of uniquely identifiable checkboxes for each employee for each course in the list
  $sorder = 0;  
  foreach( $namelist as $empuser )
  {

    $checktable .= '<tr><td class="dt-head-right"> ' . $empuser->lastname . ', ' . $empuser->firstname . '</td>';
    foreach( $cnumlist as $coursenumb )
    {
      $checktable .= '<td class="dt-body-center"><input type="checkbox" name="select[' . $coursenumb . '-' . $sorder . ']" value="' . $empuser->id . '" /></td>';
      ++$sorder;
    }
    $checktable .= '</tr>';
  }
  $checktable .= '</tbody></table>';

  //xdebug_break();

  return $checktable;

}
