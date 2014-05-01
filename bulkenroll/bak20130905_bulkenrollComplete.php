<?php
/********************************************
 * Platform parameters settings
 ********************************************/
//Location of 'flat file' containing unenrollment queue
    //Test system on plariv laptop localhost
//$ffLocation = 'C:\tmp\flatfile.csv';
    // Test system on www.augurynet.com
//$ffLocation = '/home/auguryne/tmp/flatfile.csv';
    // Production system LMS platform on fedora01
$ffLocation = '/tmp/flatfile.csv';


/**********************************************
 * Recertification Courses Enrollment Policy array
 **********************************************
 * The following array lists each certification course and its associated
 * follow-on re-certification course for the same topic. For example, each
 * enrollee that is certified in the Human Rights 30 Day course must then
 * be enrolled in the next available Biannual Human Rights course.
 *
 * The flag $enable_recertpolicy controls whether enrollment adds are included or skipped.
 *
 * All certification courses should be listed as a key, using the course's short
 * name. Every course listed in this array may or may not have a recertification
 * recertification course associated with it. If there is no recertification
 * follow-on, enter the associated value as "none" in the array. If a
 * certification courses listed in the array does have an associated follow-on
 * recertification course, enter that 'course short name' string as the
 * associated value.
 *
 * TODO Convert this array to a Moodle database table for administrative review and edit of policy
 * TODO Move Recertification Policy Autoenrollment flag into Moodle configuration file
 */

// Recertification Policy Autoenrollment flag
    $enable_recertpolicy = TRUE;

// Certification course Name -- Recertification follow-on course name

// ---------------------------------------------
// CPR 30 Days -- CPR First Aid 2014
    $recertpolicy["2001"]= "6027";
// CPR First Aid 2011 -- CPR First Aid 2013
    $recertpolicy["6006"]= "6026";
// CPR First Aid 2012 -- CPR First Aid 2014
    $recertpolicy["6007"]= "6027";
// CPR First Aid 2013 -- CPR First Aid 2015
    $recertpolicy["6026"]= "6031";
// CPR First Aid 2014 -- none
    $recertpolicy["6027"]= "none";
// CPR First Aid 2015 -- none
    $recertpolicy["6031"]= "none";

// ---------------------------------------------
// DayServices -- none
    $recertpolicy["4001"]= "none";

// ---------------------------------------------
// Fire Life Safety Intro -- Fire Life Safety 2013
    $recertpolicy["2002"]= "5011";
// Fire Life Safety 2011 -- Fire Life Safety 2012
    $recertpolicy["5004"]= "5010";
// Fire Life Safety 2012 -- Fire Lift Safety 2013
    $recertpolicy["5010"]= "5011";
// Fire Life Safety 2013 -- Fire Life Safety 2014
    $recertpolicy["5011"]= "5016";
// Fire Life Safety 2014 -- none
    $recertpolicy["5016"]= "none";

// ---------------------------------------------
// Healthcare -- Nursing Annual 2013
    $recertpolicy["2003"]= "5013";

// ---------------------------------------------
// HIPAA Intro -- HIPAA 2012
    $recertpolicy["2004"]= "6021";
// HIPAA 2011 -- HIPAA 2013
    $recertpolicy["6001"]= "6020";
// HIPAA 2012 -- HIPAA 2014
    $recertpolicy["6008"]= "6021";
// HIPAA 2013 -- HIPAA 2015
    $recertpolicy["6020"]= "6028";
// HIPAA 2014 -- none
    $recertpolicy["6021"]= "none";
// HIPAA 2015 -- none
    $recertpolicy["6028"]= "none";

// ---------------------------------------------
// Human Rights Intro -- Human Rights 2012
    $recertpolicy["2005"]= "6005";
// Human Rights 2011 -- Human Rights 2013
    $recertpolicy["6002"]= "6022";
// Human Rights 2012 -- Human Rights 2014
    $recertpolicy["6005"]= "6023";
// Human Rights 2012v2 -- Human Rights 2015
    $recertpolicy["6032"]= "6033";
/// Human Rights 2013 -- Human Rights 2015
    $recertpolicy["6022"]= "6033";
// Human Rights 2014 -- none
    $recertpolicy["6023"]= "none";
// Human Rights 2015 -- none
    $recertpolicy["6029"]= "none";
// Human Rights 2015v2 -- none
    $recertpolicy["6033"]= "none";


// ---------------------------------------------
// Healthcare -- Nursing Annual 2013
    $recertpolicy["2006"]= "5013";

// ---------------------------------------------
// NHIS -- none
    $recertpolicy["0001"]= "none";

// ---------------------------------------------
// Nursing Annual 2011 v2 -- Nursing Annual 2012
    $recertpolicy["5007"]= "5012";
// Nursing Annual 2012 -- Nursing Annual 2013
    $recertpolicy["5012"]= "5013";
// Nursing Annual 2013 -- Nursing Annual 2014
    $recertpolicy["5013"]= "5017";
// Nursing Annual 2014 -- none
    $recertpolicy["5017"]= "none";

// ---------------------------------------------
// Nutrition -- none
    $recertpolicy["4002"]= "none";

// ---------------------------------------------
// Orientation -- none
    $recertpolicy["1001"]= "none";

// ---------------------------------------------
// PBS -- none
    $recertpolicy["4003"]= "none";

// ---------------------------------------------
// PST -- none
    $recertpolicy["3001"]= "none";

// ---------------------------------------------
// Personal Safety 1 -- Personal Safety 2 2011
    $recertpolicy["5006"]= "6004";

// ---------------------------------------------
// Personal Safety 2 2011 -- none
    $recertpolicy["6003"]= "none";
// Personal Safety 2 2012 -- none
    $recertpolicy["6004"]= "none";
// Personal Safety 2 2013 -- none
    $recertpolicy["6024"]= "none";
// Personal Safety 2 2014 -- none
    $recertpolicy["6025"]= "none";

// ---------------------------------------------
// PT -- none
    $recertpolicy["4004"]= "none";

// ---------------------------------------------
// Sensory -- none
    $recertpolicy["4005"]= "none";

// ---------------------------------------------
// WBTraining -- none
    $recertpolicy["0000"]= "none";
    
/*
 *************************************************************************
 */

    // Connect to the Moodle database
    // WBLMS production platform
    @ $db = new mysqli('localhost', 'root', 'ever40green', 'moodle' );
    // Test system on www.augurynet.com
//    @ $db = new mysqli('localhost', 'auguryne_mdl2', 'N3div999', 'auguryne_mdl1');
    // Test system on plariv laptop
//  @ $db = new mysqli('localhost', 'prlroot', 'ever40green', 'moodle');

    if (mysqli_connect_errno ()) {
        printf("Connect failed: error %s\n", mysqli_connect_errno());
        exit;
    }

    /*
     * Select a list of employees and their completed courses.
     * Course completions are determined solely by a course completion certificate
     * award
     */
    $employRoster = $db->query("Select emplidnum, courseid from mdl_certificate_issues
        join
                (select mdl_user.idnumber as emplidnum,
                mdl_context.contextlevel,
                mdl_context.instanceid,
                mdl_course.fullname as fullname,
                mdl_course.idnumber as courseid
                from
                        mdl_user,
                        mdl_context,
                        mdl_role_assignments,
                        mdl_course
                 where mdl_user.id=mdl_role_assignments.userid
                 and mdl_role_assignments.roleid=5
                 and mdl_role_assignments.contextid=mdl_context.id
                 and mdl_context.contextlevel=50
                 and mdl_context.instanceid=mdl_course.id) as interim01
        on interim01.emplidnum=mdl_certificate_issues.userid
        and interim01.fullname=mdl_certificate_issues.classname");

    if ($employRoster) {
        $resultscount = $employRoster->num_rows;

        /*
        * Build an array of rows to submit for flat file enrollment processing.
        * There is one 'del' (for 'delete') row for each employee who has completed
        * the course and received a certificate. The row format is
        *       "del", student, userid, courseid
        * Immediately following the 'del' row, we may insert an 'add' row for the
        * next re-certification course for the same employee, iff the Recertification
        * Policy array indicates that there is a follow-on course. The row format is
        * nearly identical, as:
        *       "add", "student", userid, courseid
        *
        */
        $ixB = 0;
        $enrollist = array();
        for($ixA = 0; $ixA < $resultscount; $ixA++) {
            $row = $employRoster->fetch_row();
            $employee = $row[0];
            $course = $row[1];
            $enrollist[$ixB]= array("del", "student", $employee, $course);
            //printf ("%s,del,student,%s,%s, %s\n", $ixB, $employee, $course, $recertpolicy[$course]);          //debug
            $ixB++;
            if ((!($recertpolicy[$course]=="none"))&& $enable_recertpolicy) {
                $enrollist[$ixB]= array("add", "student", $employee, $recertpolicy[$course]);
                //printf ("%s,add,student,%s,%s\n", $ixB, $employee, $recertpolicy[$course]);   //debug
                $ixB++;
            }
        }

        /*
        * Append to an existing flat file, or create a new one
        */
        $fp = fopen($ffLocation, 'a');

        if($fp) {
            foreach($enrollist as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);
        } else {
            printf ("file open failed.");
            }

        $employRoster->close(); //free result set
    } else {
        printf("Query failed");
    }

/*
 * Close database connection
 */
$db->close();

?>
