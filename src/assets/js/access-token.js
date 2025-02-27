$(document).ready(function() {
    $('#regenerateTokenButton').on('click', function() {
        if (confirm('The token will be refreshed. This may affect existing API connections. Do you approve?')) {
            var tokenUrl = '/site/profile/regenerate-token';
            $.ajax({
                url: tokenUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-Token': yii.getCsrfToken()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#accessTokenInput').val(response.token);
                        $('input[name="ProfileForm[access_token]"]').val(response.token);
                        location.reload();
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    location.reload();
                }
            });
        }
    });
});
    $(document).on('click', '#toggleAccessToken', function() {
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


