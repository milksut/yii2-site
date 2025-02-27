$(document).on('click', '#toggleAccessToken', function () {
    var input = $('#accessTokenInput');
    var icon = $('#eyeIcon');

    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    }
});


