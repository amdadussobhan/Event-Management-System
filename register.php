<?php
$pageTitle = 'Register | EMS';

// Include the header
include 'header.php';
?>

<div class="container my-5 text-center">
    <div class="card w-50 mx-auto">
        <div class="card-body">
            <h3 class="card-title py-3">Complete Your Sign up</h3>
            <form action="register_action.php" method="POST" class="">
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Full Name</span>
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Password</span>
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Confirm Password</span>
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>
                <button type="submit" required class="btn btn-success col-3 my-3">Submit</button>
            </form>
        </div>
    </div>

</div>

<!-- Include the footer -->
<?php include 'footer.php'; ?>