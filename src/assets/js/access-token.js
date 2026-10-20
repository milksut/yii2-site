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

$(document).on('click', '#copyAccessToken', function () {
    var value = $('#accessTokenInput').val();
    var icon = $('#copyIcon');
    var done = function () {
        icon.removeClass('fa-copy').addClass('fa-check');
        setTimeout(function () {
            icon.removeClass('fa-check').addClass('fa-copy');
        }, 1500);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(value).then(done);
        return;
    }
    var temp = $('<textarea>').val(value).appendTo('body').select();
    document.execCommand('copy');
    temp.remove();
    done();
});
