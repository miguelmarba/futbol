$(function(){
    $('#current_password').keyup(function(e){
        var current_password = $(this).val();
        $.post(urlform, {
                current_password: current_password
            },function(data){
                if(data.response == true){
                    // print error message
                    $("#mimensaje").html("Correcto.");
                } else {
                    $("#mimensaje").html("Contraseña incorrecta");
                }
            }, 'json');
    });
    
    $('#password').keyup(validatePassword);
    $('#password_confirmation').keyup(confirmPassword);
});

function confirmPassword(){
    var options = null;
    var settings = $.extend({minlength: 8, maxlength: 16, onPasswordValidate: null, onPasswordMatch: null}, options);

    var cPassword = $('#password_confirmation');
    var labelConfirm = $('#label_confirm');
    labelConfirm.html('');
    
    var password = $('#password');
    var pstr = password.val().toString();
    
    var submit = $('#submitbutton');
    //cPassword.removeClass('no-match');
    if (cPassword.val().toString().length > 0) {
            if (pstr == cPassword.val().toString()) {
                labelConfirm.html('');
                submit.removeClass('disabled');
                submit.removeAttr('disabled');
                if (settings.onPasswordMatch != null)
                    settings.onPasswordMatch(true);
            }
            else {
                labelConfirm.html('Las contraseñas no coinciden');
                submit.addClass('disabled');
                submit.attr("disabled", "disabled");
                if (settings.onPasswordMatch != null)
                    settings.onPasswordMatch(false);
            }
        }
        else {
            //cPassword.addClass('no-match');
            labelConfirm.html('Las contraseñas no coinciden');
            submit.addClass('disabled');
            submit.attr("disabled", "disabled");
            if (settings.onPasswordMatch != null)
                settings.onPasswordMatch(false);
        }
}

function validatePassword() {
    var options = null;
    var settings = $.extend({
        minlength: 8, 
        maxlength: 16, 
        onPasswordValidate: null, 
        onPasswordMatch: null
    }, options);

    var password = $('#password');
    var pstr = password.val().toString();
    var meter = $('.meter');
    meter.html("");
    //fires password validate event if password meets the min length requirement
    if (settings.onPasswordValidate != null)
        settings.onPasswordValidate(pstr.length >= settings.minlength);

    if (pstr.length < settings.maxlength)
        meter.removeClass('strong').removeClass('medium').removeClass('week');
    if (pstr.length > 0) {
        var rx = new RegExp(/^(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{7,30}$/);
        if (rx.test(pstr)) {
            meter.addClass('strong');
            meter.html("Fuerte");
        }
        else {
            var alpha = containsAlpha(pstr);
            var number = containsNumeric(pstr);
            var upper = containsUpperCase(pstr);
            var special = containsSpecialCharacter(pstr);
            var result = alpha + number + upper + special;

            if (result > 2) {
                meter.addClass('medium');
                meter.html("Bien");
            }
            else {
                meter.addClass('week');
                meter.html("Débil");
            }
        }
    }
}


function containsAlpha(str) {
    var rx = new RegExp(/[a-z]/);
    if (rx.test(str)) return 1;
    return 0;
}

function containsNumeric(str) {
    var rx = new RegExp(/[0-9]/);
    if (rx.test(str)) return 1;
    return 0;
}

function containsUpperCase(str) {
    var rx = new RegExp(/[A-Z]/);
    if (rx.test(str)) return 1;
    return 0;
}
function containsSpecialCharacter(str) {

    var rx = new RegExp(/[\W]/);
    if (rx.test(str)) return 1;
    return 0;
}
