$(document).ready(function() {
    $('#roomhistory').submit(function() {
        var abort = false;
        $("div.error").remove();
        $(':input[required]').each(function() {
            if ($(this).val()==='') {
                $(this).after('<div class="error">This is a required field</div>');
                abort = true;
            }
        }); // go through each required value
        if (abort) { return false; } else { 
            postData = $('#roomhistory').serialize();
            $.post('process.php', postData+'&action=submit&ajaxrequest=1', function(msg) {
                if (msg) {
                    $('#roomhistory').before(msg);
                }
            });
            return false;
        }
    })//on submit
}); // ready

$('input[placeholder]').blur(function() {
    $("div.error").remove();
    var myPattern = $(this).attr('pattern');
    var myPlaceholder = $(this).attr('placeholder');
    var isValid = $(this).val().search(myPattern) >= 0;

    if (!isValid) {
        $(this).focus();
        $(this).after('<div class="error">Entry does not match expected pattern: ' + myPlaceholder + '</div>');
    } // isValid test
}); // onblur