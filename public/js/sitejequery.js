var arabicPattern = /[\u0600-\u06FF]/;

$('#post_titre').bind('input propertychange', function(ev) {

    var text = ev.target.value;

    if (arabicPattern.test(text)) {
        // arabic;
        $('#post_titre').css('direction', 'rtl')

    }else{
        $('#post_titre').css('direction', 'ltr')
    }

});

$('#post_content').bind('input propertychange', function(ev) {

    var text = ev.target.value;

    if (arabicPattern.test(text)) {
        // arabic;
        $('#post_content').toggle('direction', 'rtl')

    }

});
