/* 
 * Jquery section
 */

/*
 * The follow selectors support multi-checkbox checking of either
 * entire course columns or entire employee rows to select enrollments.
 */

$(document).ready(function(){
    $('thead input:checkbox').click(function() {
        var cgroup = '.' + $(this).attr('id');
        $(cgroup).attr('checked', $(this).attr('checked'));
    }
    )
    $('tbody div.allrow input:checkbox').click(function(){
        var rgroup = '.' + $(this).attr('id');
        $(rgroup).attr('checked', $(this).attr('checked'));
    }
    )
    $('#red').treeview({
        collapsed: true,
        animated: "fast"
    }
    )
    $(window).load(function() {
        openDialog("#writedone");
    }
    )
    $('#writedone')
        .find('.continue, .finished')
        .live('click', function() {
            closeDialog(this);
        })
        .end()
        .find('.continue')
        .live('click', function() {
            //clicked continue
        })
        .end()
        .find('.finished')
        .live('click', function() {
            //clicked finished
        });
});



function openDialog(selector) {
    $(selector)
        .clone()
        .show()
        .appendTo('#overlay')
        .parent()
        .fadeIn('fast');
}

function closeDialog(selector) {
    $(selector)
        .parents("#overlay")
        .fadeOut('fast', function() {
            $(this)
            .find(".dialog")
            .remove();
        });
}
