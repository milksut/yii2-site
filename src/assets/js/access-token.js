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
                    console.log('Response received:', response);
                    if (response.success) {
                        $('#accessTokenInput').val(response.token);
                        $('input[name="ProfileForm[access_token]"]').val(response.token);
                        alert(response.message);
                    } else {
                        alert('Hata: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    console.log('Response text:', xhr.responseText);
                    alert('An error occurred: ' + status + ' - ' + error);
                }
            });
        }
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
});