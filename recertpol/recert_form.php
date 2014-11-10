<?php

// moodleform is defined in formslib.php
require_once($CFG->libdir . "/formslib.php");

class recertpol_edit_form extends moodleform
{
  // Add elements to form
  public function definition()
  {
    global $CFG;

    $mform =& $this->_form;

    //--------------------------------------------------------------

    $allPoliciesList = $this->_customdata['policylist'];
    $allCourseList = $this->_customdata['allcourselist'];

    // Define elements used in the recert_edit form
    $mform->addElement('html', '<div class="recertlist" >');
    foreach( $allPoliciesList as $entry => $polEntry )
    {
      $mform->addElement('html', "<div class=\"rcname\"> {$polEntry['curCourseName']} (Course ID: {$polEntry['curCourseId']}) </div>" );
      $select = $mform->addElement( 'select', 'courseNextUpd'.$entry, 'On completion, next enrollment will be in: ', $allCourseList );
      $select-> setSelected( $polEntry['nextCourseId'] );
      $mform->addElement( 'hidden', 'courseCur'.$entry, $polEntry['curCourseId'] );
      $mform->addElement( 'hidden', 'courseNextOri'.$entry, $polEntry['nextCourseId'] );

    }
    $mform->addElement('html', '</div>' );

    $this->add_action_buttons();

  }

  // Include custom validation here
  function validation( $data, $files)
  {
    return array();
  }
}

?>
