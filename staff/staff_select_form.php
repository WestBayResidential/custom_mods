<?php

// moodleform is defined in formslib.php
require_once($CFG->libdir . "/formslib.php");

class staff_select_form extends moodleform
{
  // Add elements to form
  public function definition()
  {
    global $CFG;

    $mform =& $this->_form;

    //--------------------------------------------------------------

    $catList = $this->_customdata['categorylist'];
    $resList = $this->_customdata['residencelist'];

    $mform->addElement("html", "<div>" );
    $select = $mform->addElement( "select" , "cat", "Select course category ", $catList );
    $mform->addElement("html", "<div>" );
    $select = $mform->addElement( "select" , "res", "Select residence ", $resList );

    $this->add_action_buttons( $cancel=true, $submitlabel='Submit');

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
