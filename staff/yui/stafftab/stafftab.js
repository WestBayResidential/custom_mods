.add('moodle-block_fruit-fruitbowl', function(Y) {

  // Customized datatable code goes here.

//  $( document ).ready( function () {
//    $( '#enroltable' ).DataTable( {
//      "scrollY": 200,
//      "scrollX": true
//    });
//  } );

  // Define a name space to call

  M.enrol_staff = M.enrol_staff || { };
  M.enrol_staff.stafftab = {
    init: function() {
      //Y.one('#example').set('innerHTML', M.util.get_string('example', 'enrol_staff'));
    }
  };
}, '@VERSION@', {
  requires: ['node']
});
