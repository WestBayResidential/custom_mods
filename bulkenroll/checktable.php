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

function build_checktable($courselist, $coursecount, $category, $namelist, $namecount, $residence)
{
  $checktable_top = '<table class="draggable"><thead><tr>';
  // Print header row with residence name and course designations
  $checktable_top .= '<th>' . $residence . '</th><th>Select all courses</th>';

  // Make a column heading for each course and include a 'select all' checkbox
  $ordinal = 0;
  foreach( $courselist as $crs)
  {
    $ordinal++;
    $cname[$ordinal] = $crs->shortname;
    $cnum[$ordinal] = $crs->idnumber;
    $checktable_top .= '<th>' . $cname . '/' . $cnum . '<input type="checkbox" id="col_' . $ordinal . '"></th>';
  }
  $checktable_top .= '</tr></thead><tbody></tbody>';

  return $checktable_top;

}
