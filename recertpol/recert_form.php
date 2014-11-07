<?php

// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class recert_form extends moodleform
{
  // Add elements to form
  public function definition()
  {
    global $CFG;

    $mform = $this->_form;

    $mform->addElement('button', 'intro', 'MyButton');
    
  }
}

?>
