<?php



// moodleform is defined in formslib.php
require_once($CFG->libdir . "/formslib.php");

class staff_edit_form extends moodleform
{
  // Add elements to form
  public function definition()
  {
    global $CFG;

    $mform =& $this->_form;

    $mform->MoodleQuickForm("enrol_staff_form", "POST", "change.php");

    $tablestruct = $this->_customdata['coursetable'];

    //--------------------------------------------------------------

    $mform->addElement( "html", "<div class=\"container\">" );
    $mform->addElement( "html", $tablestruct );

    $mform->addElement( "html", "<script type=\"text/javascript\"> $(document).ready( function(){
    $('#enroltable').DataTable({\"scrollY\":200, \"scrollX\":true});}); </script>");

    $mform->addElement("submit", "submitbutton", "Enroll");

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
