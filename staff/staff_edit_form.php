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

    $mform->addElement( "html", "<div class=\"horiz_container\">" );
    $mform->addElement( "html", "<div class=\"container\">" );
    $mform->addElement( "html", "<div class=\"fixed-th-table-wrapper\">" );
    $mform->addElement( "html", $tablestruct );
    $mform->addElement( "html", "</div>");
    $mform->addElement( "html", "<div class=\"scrolled-td-table-wrapper\">" );
    $mform->addElement( "html", $tablestruct );
    $mform->addElement( "html", "</div>");
    $mform->addElement( "html", "</div>");

    $mform->addElement("submit", "submitbutton", "Enroll");

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
