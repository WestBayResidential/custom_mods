<?php

/* Use Moodle page construction
 * These calls to Moodle core allows this application to exist inside its framework
 * without being a completely integrated module. For now, this is the shortest
 * path to having the functionality running.
 */
require_once('../config.php');
require_once($CFG->libdir.'/weblib.php');
print_header_simple($title='Bulk Enrollment', $heading='Bulk Enrollment', $navigation='navigation', $focus='', $meta='', $cache=true, $button='&nbsp;', $menu='', $usexml=false, $bodytags='', $return=false);

/******************************************************************************
 * Custom code starts here
 *****************************************************************************/

/*
 * The page request has $_POSTed a checklist array representing courses in which
 * an employee should be enrolled.
 */

$selectRes = $_POST['residence'];
$selectMat = $_POST['field'];
//var_dump($selectMat);

if ( isset ($selectMat)) {
    echo "<h2>Confirm enrollment submissions by course number:</h2><p>For residence: $selectRes</p>" ;
    echo "<ul id=\"red\" class=\"treeview-red treeview\">";


    /*
     * Build the enrollment changes matrix to submit to Moodle "flat file" function
     * based on the user selection.
     */
    $namesList = array_keys($selectMat);
    $idxC = 0;
    $idxE = 0;
    foreach ($selectMat as $emplLastName) {
        $shortCourseNames = array_keys($emplLastName);
        $firstCourse = $shortCourseNames[0];
        $emplUserId = $emplLastName[$firstCourse];
        echo "<li>{$namesList[$idxE]}<ul>";
        $idxD = 0;
        foreach ($emplLastName as $courseKey) {
            $enroll[$idxC] = array("add", "student", $emplUserId, $shortCourseNames[$idxD]);
            echo "<li>{$shortCourseNames[$idxD]}</li>";
            $idxC++;
            $idxD++;
        }
        $idxE++;
        echo "</ul></li>";
    }

        echo "</ul>";
        echo "<form method=\"post\" action=\"bulkenrollWrite.php\">";

    /*
     * On user's confirmation, pass the block of enrollment records on to the file
     * write routine as hidden input data, sent by via the $_POST
     */
        $ixG=0;
        foreach ($enroll as $addrecord) {
            for ($ixF=0; $ixF < 4; $ixF++) {
                echo "<input type=\"hidden\" name=\"enrollrecs[".$ixG."][".$ixF."]\" value=\"".$addrecord[$ixF]."\">";
            }
            $ixG++;
        }

        echo "<p id=\"button\"><input type=\"submit\" value=\"Confirm\">     <input type=button value=\"Back\" onClick=\"history.go(-1)\"></p></form>";
} else {
    echo "<h1>No enrollments specified</h1><p><input type=button value=\"Back\" onClick=\"history.go(-1)\"></p>";
  };

echo "<script type=\"text/javascript\" src=\"http://code.jquery.com/jquery-1.4.4.min.js\"></script>";
echo "<script type=\"text/javascript\" src=\"jquery.treeview.min.js\"</script>";
echo "<script type=\"text/javascript\" src=\"dragtable.js\"></script>";
echo "<script type=\"text/javascript\" src=\"aug_script.js\"></script>";


//echo "</body></html>";

print_footer($course=NULL, $usercourse=NULL, $return=false);

?>
