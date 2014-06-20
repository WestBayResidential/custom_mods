<?php

// by Kaalgat
// This page lists all the issued certificates for a specific user, or all the certificates for the admin user.

require_once('../../config.php');
require_once('lib.php');
global $USER;

if (isguestuser()){
 error ( get_string('loggedinasguest', 'moodle').'<br>'.get_string('loginsite'));
} elseif (isloggedin()) {

 if (has_capability('mod/certificate:manage', get_context_instance(CONTEXT_SYSTEM))) {

  // Show all user certificates
  print_header_simple(get_string('modulenameplural', 'certificate'));

  $strto = get_string('awardedto', 'certificate');
  $strdate = get_string('receiveddate', 'certificate');
  $strgrade = get_string('grade','certificate');
  $strcode = get_string('code', 'certificate');
  $strclass = get_string('coursename', 'certificate');

  print_heading(get_string('modulenameplural', 'certificate'));

  $table->head  = array ($strto, $strclass, $strgrade, $strdate, $strcode);
  $table->align = array ("LEFT", "LEFT", "LEFT", "CENTER", "CENTER");

  $sort = "s.studentname";
  $certificates = get_records_sql("SELECT s.code, u.id, u.picture, s.timecreated, s.certdate, s.studentname, s.reportgrade, s.classname
                                   FROM {$CFG->prefix}certificate_issues s,
                                        {$CFG->prefix}user u
                                   WHERE s.userid = u.id
                                   AND s.certdate > 0
                                   ORDER BY {$sort}");
  if (!$certificates) error(get_string('notissuedyet','certificate'));
  foreach ($certificates as $user) {
   $name = print_user_picture($user->id, $course->id, $user->picture, false, true).' '.$user->studentname;
   $date = userdate($user->certdate).certificate_print_user_files($user->id);
   if ($user->reportgrade != null) {
    $grade = str_replace(" ","",$user->reportgrade);
   } else {
    $grade = get_string('notapplicable','certificate');
   }
   $code = $user->code;
   $class = $user->classname;

   if ($last_user == $user->studentname) {
    $table->data[] = array (' ', $class, $grade, $date, $code);
   } else {
    $table->data[] = array ('<br>', ' ', ' ', ' ', ' ');
    $table->data[] = array ($name, $class, $grade, $date, $code);
    $last_user = $user->studentname;
   }
  }
  print_table($table);
 } else {

 // Show specific user certificates
  print_header_simple(get_string('modulenameplural', 'certificate'));

  $strto = get_string('awardedto', 'certificate');
  $strdate = get_string('receiveddate', 'certificate');
  $strgrade = get_string('grade','certificate');
  $strcode = get_string('code', 'certificate');
  $strclassname = get_string('coursename', 'certificate');

  print_heading(get_string('modulenameplural', 'certificate'));

  $table->head  = array ($strto, $strclassname, $strgrade, $strdate, $strcode);
  $table->align = array ("LEFT", "LEFT", "LEFT", "CENTER", "CENTER");

  $sort = "s.studentname";
  $certificates = get_records_sql("SELECT s.code, u.id, u.picture, s.timecreated, s.certdate, s.studentname, s.reportgrade, s.classname
                            FROM {$CFG->prefix}certificate_issues s,
                                 {$CFG->prefix}user u
                            WHERE s.userid = u.id
                            AND u.id = {$USER->id}
                            AND s.certdate > 0
                            ORDER BY $sort");
  if (!$certificates) error(get_string('notissuedyet','certificate'));
  foreach ($certificates as $user) {
   $name = print_user_picture($user->id, $course->id, $user->picture, false, true).' '.$user->studentname;
   $date = userdate($user->certdate).certificate_print_user_files($user->id);
   if ($user->reportgrade != null) {
    $grade = $user->reportgrade;
   } else {
    $grade = get_string('notapplicable','certificate');
   }
   $code      = $user->code;
   $classname = $user->classname;

   if ($last_user == $user->studentname) {
    $table->data[] = array (' ', $classname, $grade, $date, $code);
   } else {
    $table->data[] = array ('<br>', ' ', ' ', ' ', ' ');
    $table->data[] = array ($name, $classname, $grade, $date, $code);
    $last_user = $user->studentname;
   }
  }
  print_table($table);
 }
} else {
 error (get_string('loginsite'));
}
print_footer($course);
?>
