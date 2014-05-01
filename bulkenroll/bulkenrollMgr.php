<?php

/* Use Moodle page construction
 * These calls to Moodle core allows this application to exist inside its framework
 * without being a completely integrated module. For now, this is the shortest
 * path to having the functionality running.
 */
require_once('../config.php');
require_once($CFG->libdir.'/weblib.php');
print_header_simple($title='Bulk Enrollment', $heading='Bulk Enrollment', $navigation='navigation', $focus='', $meta='', $cache=true, $button='&nbsp;', $menu='', $usexml=false, $bodytags='', $return=false);

/*
 * Custom code starts here
 */
$residence = $_GET['residence'];
$courseCategory = $_GET['courseCategory'];
$categoryNum = array("thirty"=>6, "ninety"=>7, "oneeighty"=>8, "annual"=>24, "biannual"=>25);


/**
 * TODO: 1. Invoke Moodle's managed connection to its database
 */

/*
 * bulkenroll connects to the Moodle database on its own
 */

// Test System Platforms: uncomment the right one...
// WBLMS production platform
@ $db = new mysqli('localhost', 'root', 'ever40green', 'moodle' );
// Test system on www.augurynet.com
//@ $db = new mysqli('localhost', 'auguryne_mdl2', 'N3div999', 'auguryne_mdl1');
// Test system on plariv laptop
//@ $db = new mysqli('localhost', 'prlroot', 'ever40green', 'moodle');

if (mysqli_connect_errno ()) {
    printf("Connect failed: error %s\n", mysqli_connect_errno());
    exit;
}



/* Get employee roster of selected residence */

$employRoster = $db->query("SELECT mdl_user.id, lastname, firstname, fieldid, data
    FROM mdl_user, mdl_user_info_data
    WHERE mdl_user.deleted=0
    AND mdl_user.id=mdl_user_info_data.userid
    AND mdl_user_info_data.fieldid=7
    AND mdl_user_info_data.data LIKE '%".$residence."%'
    ORDER BY lastname");

$empl_count = $employRoster->num_rows;

// Get list of courses in selected category

$cat = $categoryNum[$courseCategory];
$courseList = $db->query ("SELECT shortname, idnumber FROM mdl_course WHERE category=".$cat." ORDER BY idnumber");

$course_count = $courseList->num_rows;

?>

<h2>Select courses for each employee to enroll them...</h2><br /><br />
<p id="instruction">NOTE: You can click on and drag a column heading cell to rearrange the columns' order.</p>
<form method="post" action="bulkenrollProc.php">
<table class="draggable" border='1'><thead><tr>

<?php
// print header row with residence name and course designations
echo "<th>$residence</th>";
echo "<th>Select <br />all courses</th>";
for($i=0; $i<$course_count; $i++) {
    $row = $courseList->fetch_assoc();
    $courseSNam[$i] = $row['shortname'];
    $courseNum[$i] = $row['idnumber'];
    echo "<th>{$courseSNam[$i]}<br />( {$courseNum[$i]} )<br /><input type=\"checkbox\" id=\"col_".$i."\"></th>";
}
?>

        </tr></thead><tbody><input type="hidden" name="residence" value="<?php print $residence; ?>" />

<?php
// print employee names and choices checkboxes
for ($idxA=0; $idxA <$empl_count; $idxA++) {
    $row = $employRoster->fetch_assoc();
    echo "<tr><td><div class=\"leftcol\">".$row['lastname'].", ".$row['firstname']."</div></td><td><div class=\"allrow\"><input type=\"checkbox\" id=\"row_".$idxA."\"></div></td>";
    for ($idxB=0; $idxB < $course_count; $idxB++) {
        echo "<td><input type=\"checkbox\" class=\"col_".$idxB." row_".$idxA."\" name=\"field[".$row['lastname']."][".$courseNum[$idxB]."]\" value=\"".$row[id]."\"> </td>";
    }
    echo "</tr>\n";
}
?>

    </tbody></table>
<p id="button"><input type="submit" value="Enroll Employees"></p>
</form>


<?php
//mysql_free_result($result);
//==============================================

$db->close();

?>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dragtable.js"></script>
        <script type="text/javascript" src="aug_script.js"></script>

<?PHP

print_footer($course=NULL, $usercourse=NULL, $return=false);

?>




