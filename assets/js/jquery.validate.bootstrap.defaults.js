/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery.validator.setDefaults({
    highlight: function(element, errorClass, validClass) {
        $(element).closest('.form-group').addClass(errorClass).removeClass(validClass);
        $(element).addClass('control-label');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).closest('.form-group').removeClass(errorClass).addClass(validClass);
    },
    errorPlacement: function(error, element) {
        if (element.attr('type') == 'file') {
            $(error).addClass('control-label');
            error.insertAfter(element.parent());
        } else {
            $(error).addClass('control-label');
            error.insertAfter(element);
        }
    }
});