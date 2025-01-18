// Wait for the DOM to be ready
$(document).ready(function () {
    // Attach an event listener to the form submission
    $('#loginForm').submit(function (event) {
        // Prevent the default form submission
        event.preventDefault();

        // Get form data
        var username = $('#username').val();
        var password = $('#password').val();

        $.ajax({
            url: 'validate.php',
            method: 'POST',
            data: { username: username, password: password },
            success: function(response) {
                handleLoginResponse(response);
            },
            error: function(error) {
                console.log('Error:', error);
                // Handle the error if needed
            }
        });
    });
});

// Example function to handle the login response
function handleLoginResponse(response) {
    // Check the response and perform actions accordingly
    if (response.trim().toLowerCase() === 'login successful') {
        // Redirect to adminmenu.php for successful login
        window.location.href = 'adminmenu.php';
    } else {
        // Show Bootstrap modal for login failure
        showLoginFailedModal();
    }
}

// Function to show a Bootstrap modal for login failure
function showLoginFailedModal() {
    // Create a Bootstrap modal dynamically
    var modalHtml = '<div class="modal fade" id="loginFailedModal" tabindex="-1" role="dialog" aria-labelledby="loginFailedModalLabel" aria-hidden="true">' +
                        '<div class="modal-dialog" role="document">' +
                            '<div class="modal-content">' +
                                '<div class="modal-header">' +
                                    '<h5 class="modal-title" id="loginFailedModalLabel">Login Failed</h5>' +
                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                        '<span aria-hidden="true">&times;</span>' +
                                    '</button>' +
                                '</div>' +
                                '<div class="modal-body">' +
                                    '<p>Your login attempt was unsuccessful. Please check your username and password and try again.</p>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';

    // Append the modal to the body
    $('body').append(modalHtml);

    // Show the modal
    $('#loginFailedModal').modal('show');

    // Hide the modal after 3 seconds
    setTimeout(function() {
        $('#loginFailedModal').modal('hide');
    }, 3000);
}
