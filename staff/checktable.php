<?php

// Build the checkbox table HTML to display in the staff_edit_form
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
    $checktable .= "<input type=\"checkbox\" name=\"{$cnum}{$corder}\" onclick=\"get_column(";
    $checktable .= $cnum . ", " . $corder;
    $checktable .= ")\" /></th>";
  }
  $checktable .= '</tr></thead><tbody>';
  
  // Add a row of uniquely identifiable checkboxes for each employee for each course in the list
  foreach( $namelist as $empuser )
  {
    $sorder = 1;
    $checktable .= '<tr><td class="dt-head-right"> ' . $empuser->lastname . ', ' . $empuser->firstname . '</td>';
    foreach( $cnumlist as $coursenumb )
    {
      $checktable .= '<td class="dt-body-center"><input type="checkbox" name="select[' . $coursenumb . '.' . $sorder . ']" value="' . $empuser->id . '" /></td>';
      ++$sorder;
    }
    $checktable .= '</tr>';
  }
  $checktable .= '</tbody></table>';

  //xdebug_break();

  return $checktable;

}
