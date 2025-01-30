<?php
$pageTitle = 'Event Details | EMS';
include '../layout/header.php';
?>

<h4 id="title">Title: </h4>
<div class="overflow-hidden">
    <div class="mx-2">
        <h5 id="date" class='pe-5 pt-2' style='float: left;'> Date:</h5>
        <h5 id="max_capacity" class='pt-2' style='float: left;'> Max Capacity:</h5>
        <a id="register" class='btn px-5' style='float: right;'>Register This Event</a>
    </div>
    <div class="shadow m-2">
        <img id="image" alt='Cover Photo' style='height:250px; width:100%'>
    </div>
    <p id="description" class='text-start py-2'>Description:</p>
</div>

<!-- Modal for Event Registration -->
<div class="modal modal-lg fade" id="registerEventModal" tabindex="-1" aria-labelledby="registerEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerEventModalLabel">Complete Event Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventRegisterForm">
                    <div class="col input-group my-3">
                        <span class="input-group-text">Full Name</span>
                        <input type="text" class="form-control" name="name" <?php echo isset($_SESSION['name']) ? 'value="'.htmlspecialchars($_SESSION['name']).'" readonly' : '';?> required>
                    </div>
                    <span class="error text-danger" id="nameError"></span>

                    <div class="col input-group my-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                        <input type="email" class="form-control" name="email" <?php echo isset($_SESSION['email']) ? 'value="'.htmlspecialchars($_SESSION['email']).'" readonly' : '';?> required>
                    </div>
                    <span class="error text-danger" id="emailError"></span>

                    <div class="col input-group my-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Password</span>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <span class="error text-danger" id="passwordError"></span>

                    <!-- Hidden input for the event ID -->
                    <input id="event_id" type="hidden" name="event_id" value="<?php echo htmlspecialchars($_GET['event_id']); ?>">
                    <button type="submit" class="btn btn-success col-3 my-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>

<script>
    $(document).ready(function() {
        const eventId = <?php echo json_encode($_GET['event_id']); ?>;

        // Fetch event details via AJAX
        $.ajax({
            url: '/ems/events/event_details_api.php',
            method: 'GET',
            data: {
                event_id: eventId
            },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    const event = response.event;

                    // Display event details
                    $('#title').text('Title: ' + event.title);
                    $('#date').text('Date: ' + event.date);
                    $('#max_capacity').text('Max Capacity: ' + event.max_capacity + ' Person');
                    if (event.cover_photo)
                        $('#image').attr('src', '/ems/' + event.cover_photo);
                    
                    if (event.description)
                        $('#description').html('Description: ' + event.description);
                    

                    // Handle registration button
                    if (event.registered) {
                        $('#register').text('Already Registered').addClass('btn-success').prop('disabled', true);
                    } else if (event.participants >= event.max_capacity) {
                        $('#register').text('Capacity Reached').addClass('btn-info').prop('disabled', true);
                    } else {
                        $('#register').text('Register This Event').addClass('btn-warning').attr('data-bs-toggle', 'modal').attr('data-bs-target', '#registerEventModal');
                    }
                }
            },
            error: function() {
                window.location.href = 'event_list_page.php';
            }
        });

        // Handle the registration form submission via AJAX
        $('#eventRegisterForm').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const formData = $(this).serialize(); // Serialize form data

            // Clear previous error messages
            $('.error').text('');

            // Send AJAX request
            $.ajax({
                url: 'event_register_api.php', // PHP script to handle registration
                method: 'POST',
                data: formData,
                dataType: 'json', // Expecting JSON response
                success: function(response) {
                    if (response.errors) {
                        // Display validation errors
                        if (response.errors.name)
                            $('#nameError').text(response.errors.name);

                        if (response.errors.email)
                            $('#emailError').text(response.errors.email);

                        if (response.errors.password)
                            $('#passwordError').text(response.errors.password);

                    } else if (response.success) {
                        $('#registerEventModal').modal('hide');
                        $('#register').text('Registered Successfully').removeClass('btn-warning').addClass('btn-success').prop('disabled', true);
                        alert(response.success);
                    }
                },
                error: function(xhr, status, error) {
                    $('#registerEventModal').modal('hide');
                    alert("Event Registration Connection failed ! Please try again.");
                    console.error('AJAX request failed:', error);
                }
            });
        });
    });
</script>