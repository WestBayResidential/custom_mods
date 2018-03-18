    $(document).ready( function(){
        var table = $('#enroltable').DataTable({
            "scrollY":"500",
            "scrollX":true,
            "scrollCollapse":true,
            "columnDefs": [
                {
                "targets":"th-checkit",
                "checkboxes":
                    {
                      "selectAll":true
                    }
                }
            ]
        });
    });

