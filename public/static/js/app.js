jQuery(document).ready(function($) {
    'use strict';
    
    // ============================================================== 
    // tooltip
    // ============================================================== 
    if ($('[data-toggle="tooltip"]').length) {
        $('[data-toggle="tooltip"]').tooltip();
    }

     // ============================================================== 
    // popover
    // ============================================================== 
    if ($('[data-toggle="popover"]').length) {
        $('[data-toggle="popover"]').popover();
    }
     
    /**
     * select/deselect all the checkbox for batch actions
     */
    $(".list-actions-checkbox").change(function(e){
        e.preventDefault();
        var checked = $(this).prop('checked');
        $(".list-action:checkbox").prop('checked', checked);
        
    });
    
    /**
     For select2 search
    */
    $(".select2js").select2({
        allowClear: false
    });
    
    $(".select2js-100").select2({
        allowClear: false,
        width: '100%'
    });
    
    $(".select2js-auto").select2({
        allowClear: false,
        width: 'auto'
    });
    
    // For confirm message
    $('[data-text-confirm]').bind('click', function(e) {
        const message = $(this).attr('data-text-confirm');
        return confirm(message);
    });
    
    // For back button
    $('a.btn-back').click(function(e) {
        e.preventDefault();
        history.back();
    });
    
    /**
     * for permissions dependencies handle checked
     */
    $('.role-permission').change(function(){
        var checked = $(this).prop('checked');
        if(checked){
            var depend = $(this).attr('data-depend');
            if(depend){
               $('input#' + depend).prop('checked', true);
            }
       }
       else{
           var id = $(this).attr('id');
           $('input[data-depend=' + id + ']').prop('checked', false);
       }
    });
}); // AND OF JQUERY