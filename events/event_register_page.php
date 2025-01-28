<?php
$pageTitle = 'Register Event | EMS';

// Include the header and message
include '../layout/header.php'; ?>

<div>
    <div class="card w-50 mx-auto shadow px-3">
        <div class="card-body">
            <h3 class="card-title py-3">Complete Event Registration</h3>
            <form id="eventRegisterForm">
                <div class="col input-group my-3">
                    <span class="input-group-text">Full Name</span>
                    <input type="text" class="form-control" name="name" value="<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>" required>
                </div>
                <span class="error text-danger" id="nameError"></span>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                    <input type="email" class="form-control" name="email" value="<?php echo (isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''); ?>" required>
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

<?php include '../layout/footer.php'; ?> <!-- Include the footer -->

<script>
    $(document).ready(function() {
        $('#eventRegisterForm').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const formData = $(this).serialize(); // Serialize form data

            // Clear previous error messages
            $('#success').text('');
            $('#info').text('');
            $('#error').text('');
            
            // Send AJAX request
            $.ajax({
                url: 'event_register_api.php',  // PHP script to handle registration
                method: 'POST',
                data: formData,
                dataType: 'json',  // Expecting JSON response
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
                        window.location.href = 'event_details_page.php?event_id=' + $('#event_id').val();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', error);
                }
            });
        });
    });
</script>