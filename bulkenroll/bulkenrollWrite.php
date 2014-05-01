<!--
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Enrollment Manager</title>
        <link href="jquery.treeview.css" rel="stylesheet">
        <link href="EnrollmentMgr.css" rel="stylesheet">
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="jquery.treeview.min.js"</script>
        <script type="text/javascript" src="dragtable.js"></script>
        <script type="text/javascript" src="aug_script.js"></script>
    </head>
    <body id="wpage">
-->
<?php

/* Use Moodle page construction
 * These calls to Moodle core allows this application to exist inside its framework
 * without being a completely integrated module. For now, this is the shortest
 * path to having the functionality running.
 */
require_once('../config.php');
require_once($CFG->libdir.'/weblib.php');
print_header_simple($title='Bulk Enrollment', $heading='Bulk Enrollment', $navigation='navigation', $focus='', $meta='', $cache=true, $button='&nbsp;', $menu='', $usexml=false, $bodytags=' id=\"wpage\"', $return=false);

/******************************************************************************
 * Custom code starts here
 *****************************************************************************/

/********************************************
 * Parameter settings
 ********************************************/

//Test system on plariv laptop localhost
//$ffLocation = 'C:\tmp\flatfile.csv';
// Test system on www.augurynet.com
//$ffLocation = '/home/auguryne/tmp/flatfile.csv';
// Production system LMS platform on fedora01
$ffLocation = '/tmp/flatfile.csv';

//Redirect to this page when finished
//Test system on plariv laptop localhost
//$finished = 'http://localhost/wbres/';
//Test system on www.augurynet.com
//$finished = 'http://www.augurynet.com/wbres/';
// Production system LMS platform on fedora01
$finished = 'javascript:window.close();';

$writeBlock = $_POST['enrollrecs'];
//var_dump($writeBlock);

/****************************************************************
 * Filename to be passed to Moodle is hardcoded as "flatfile.csv"
 *
 *      @TODO: Get the flatfile location from Moodle spec.
 ***************************************************************/

$fp = fopen($ffLocation, 'a');

if($fp) {
    foreach($writeBlock as $fields) {
        fputcsv($fp, $fields);
    }
    fclose($fp);
} else {
    printf ("File open failed.");
}

?>

<!-- this belongs to jquery-driven lightbox which doesnt work well in Moodle
        <div id="overlay">
            <div id="blanket"></div>
        </div>
        - Dialog box contents -
        <div id="writedone" class="dialog">

-->
            <h2>These enrollments have been submitted!</h2>
            <div class="buttons">
                <a href="index.php" class="continue">Continue</a>
                <a href=<?php print $finished; ?> class="finished">Finished</a>
            </div>
        <!--/div -->

<?PHP

print_footer($course=NULL, $usercourse=NULL, $return=false);

?>

