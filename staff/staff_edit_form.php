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

    $selectionmatrix = $this->_customdata['coursetable'];

    //--------------------------------------------------------------

    $mform->addElement( "html", "<div>" );
    $mform->addElement( "html", $selectionmatrix );

    // The scrollY setting in the DataTable plugin below sets the height
    // of the viewport for the selection table for specifying multiple 
    // enrolments.
    $mform->addElement( "html", "<script type=\"text/javascript\"> $(document).ready( function(){
      $('#enroltable').DataTable({\"scrollY\":\"500\", \"scrollX\":true, \"scrollCollapse\":true});}); </script>");

    //--------------------------------------------------------------

    //$mform->addElement("submit", "submitbutton", "Enroll");
    $this->add_action_buttons( $cancel=true, $submitlabel="Enroll staff");

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
