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

    $tablestruct = $this->_customdata['coursetable'];

    //--------------------------------------------------------------

    $mform->addElement("html", "<div>This is the bulkenroll form base</div>" );
    $mform->addElement( "html", $tablestruct );

    $this->add_action_buttons();

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
