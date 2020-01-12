<?php



// moodleform is defined in formslib.php
require_once($CFG->libdir . "/formslib.php");

class bulkenroll_edit_form extends moodleform
{
  // Add elements to form
  public function definition()
  {
    global $CFG;

    $mform =& $this->_form;

    $mform->MoodleQuickForm("bulkenroll_form", "POST", "change.php");

    $tablestruct = $this->_customdata['coursetable'];

    //--------------------------------------------------------------

    $mform->addElement( "html", $tablestruct );

    //$this->add_action_buttons();
    $mform->addElement("submit", "submitbutton", "Enroll");

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
