<?php
$pageTitle = 'Event Details | EMS';

// Include the header
include '../layout/header.php'; ?>

<h4 id="title"> Title: </h4>
<div class="overflow-hidden">
    <h5 id="date" class='pe-5 pt-2' style='float: left;'> Date:</h5>
    <h5 id="max_capacity" class='pt-2' style='float: left;'> Max Capacity:</h5>
    <a id="register" class='btn px-5' style='float: right;'> Register This Event </a>
    <div class="shadow"><img id="image" alt='Cover Photo' style='height:250px; width:100%'></div>
    <p id="description" class='text-start py-2'>Description:</p>
</div>

<?php include '../layout/footer.php'; // Include the footer ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    if (event.cover_photo) {
                        $('#image').attr('src', '/ems/' + event.cover_photo);
                    }
                    if (event.description) {
                        $('#description').html('Description: ' + event.description);
                    }

                    // Handle registration button
                    if (event.registered) {                        
                        $('#register').text('Registered Successfully').addClass('btn-success').prop('disabled', true);
                    } else if (event.participants >= event.max_capacity) {
                        $('#register').text('Capacity Reached').addClass('btn-info').prop('disabled', true);
                    } else {
                        $('#register').text('Register This Event').attr('href', 'event_register_page.php?event_id=' + event.id).addClass('btn-warning');
                    }
                }
            },
            error: function() {
                alert('Failed to fetch event details.');
            }
        });
    });
</script>