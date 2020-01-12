<?php
/*
 * idnumber_cron connects to the Moodle database on its own
 */

// System Platforms: uncomment the right one...
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

/*
 * Check for new employees added to the database. If so, fill in the idnumber
 * field with the id field value. This is required by flat file enrollment
 * procedure, which uses the idnumber field to identify enrollees.
 *
 * Log all changes.
 */

$newRoster = $db->query("SELECT *
    FROM mdl_user
    WHERE mdl_user.idnumber=''");

if ($newRoster) {
    $setidnumber = $db->query("UPDATE mdl_user SET idnumber=id WHERE mdl_user.idnumber=''");
    printf("Employee idnumber updated in %d records.\n", $db->affected_rows);
} else {
    printf("No employee records updated.\r");
}

$db->close();

?>
