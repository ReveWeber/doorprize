$(document).ready(function () {

    if (!($('#entry-name').length)) {
        setInterval(function () {
            $('#form-itself').load('entry-form.html');
        }, 2500);
    }

});