<?php

// moodleform is defined in formslib.php
require_once($CFG->libdir . "/formslib.php");

class staff_completion_form extends moodleform
{
  // Add elements to form
  public function definition()
  {
    global $CFG;

    $mform =& $this->_form;

    //--------------------------------------------------------------

    $staffenrolledList = $this->_customdata['staffenrolled'];

    $mform->addElement("html", "<div><p>The following staff members have been enrolled...</p>" );
    $mform->addElement('html', $staffenrolledList);

    $this->add_action_buttons( $cancel=true, $submitlabel='Submit');

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
