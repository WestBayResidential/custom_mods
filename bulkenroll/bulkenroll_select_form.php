<?php

// moodleform is defined in formslib.php
require_once($CFG->libdir . "/formslib.php");

class bulkenroll_select_form extends moodleform
{
  // Add elements to form
  public function definition()
  {
    global $CFG;

    $mform =& $this->_form;

    //--------------------------------------------------------------

    $resList = $this->_customdata['residencelist'];

    $mform->addElement("html", "<div>" );
    $select = $mform->addElement( "select" , "residence", "Select residence ", $resList );

    $this->add_action_buttons();

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
