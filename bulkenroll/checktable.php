<?php

// Build the checklist table HTML for use in the bulkenroll_edit_form
//
// @param array $courselist   Array of courses objects from a select category
// @param integer $coursecount  Number of courses in the selected category
// @param string $category  Name of category for the course list
// @param array $namelist  Array of employee names from a select residence
// @param integer $namecount  Number of emloyees listed from the selected residence
// @param string $residence  Name of the selected residence
//

function build_checktable($courselist, $namelist, $residence)
{
  $checktable = '<table class="draggable"><thead><tr>';
  // Print header row with residence name and course designations
  $checktable .= '<th>' . $residence . '</th>';

  // Make a column heading for each course and include a 'select all' checkbox,
  // and set up an array of the course numbers for use later to create the input checkbox value
  $corder = 0;
  $cnumlist = array();

  foreach( $courselist as $cname=>$cnum )
  {
    $corder++;
    $cnum = $cnum ? $cnum : "NA";
    $cnumlist[ $corder ] = $cnum;
    $checktable .= '<th>' . $cname . '<br />' . $cnum . '<br /><input type="checkbox" id="col_' . $corder . '" name="coursesel" value="' . $cnum . '"></th>';
  }
  $checktable .= '</tr></thead><tbody><tr><td>';
  
  // Add a row of identifiable checkboxes for each employee in the list
  foreach( $namelist as $empuser )
  {
    $checktable .= '<tr><td> ' . $empuser->lastname . ', ' . $empuser->firstname . '</td>';
    foreach( $cnumlist as $coursenumb )
    {
      $checktable .= '<td><input type="checkbox" name="select[]" value="(' . $coursenumb . ',' . $empuser->id . ')" /></td>';
    }
    unset( $coursenumb ); // Clear the variable for reuse
    $checktable .= '</tr>';
  }
  $checktable .= '</tbody></table>';

  xdebug_break();

  return $checktable;

}
