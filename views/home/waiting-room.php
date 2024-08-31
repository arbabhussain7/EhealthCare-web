<?php
include "patient-nav.php";
?>
<style>
    body, html {
        height: 100%;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f0f0f0;
    }
    .card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        padding: 20px;
        text-align: center;
        max-width: 300px;
        width: 100%;
    }
    .card h2 {
        margin-top: 0;
    }
    .card p {
        margin: 10px 0;
    }
    .card button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .card button:hover {
        background-color: #0056b3;
    }
    .btn {
        padding: 10px 20px;
        background-color: #ff003b;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>
<div class="card">
    <?php if (isset($data['error'])): ?>
        <p style="color:red;"><?php echo htmlspecialchars($data['error']); ?></p>
    <?php elseif (isset($data['appointment'])): ?>
        <?php $appointment = $data['appointment']; ?>
        <h2>Your Appointment Schedule</h2>
        <?php if ($appointment['status'] == 1): ?>
            <p>Your appointment is now active.</p>
            <p>Join the meeting:<br><hr> <a class="btn" href="<?php echo htmlspecialchars($data['meet_link']); ?>" target="_blank">Google Meet Link</a></p>
            <!-- Display ongoing meeting ID -->
            <p>Your current ongoing meeting ID is: <?php echo htmlspecialchars($appointment['id']); ?></p>
        <?php else: ?>
            <p style="color:red;">Your appointment is not yet active. Please wait until your number is called.</p>
            <p>Your appointment ID is: <?php echo htmlspecialchars($appointment['id']); ?></p>
        <?php endif; ?>
    <?php endif; ?>
    <button onclick="location.href='index.php'">Go Back</button>
</div>

<script>
    var appointmentId = <?php echo json_encode(isset($appointment['id']) ? $appointment['id'] : 0); ?>;
    console.log('Current appointment ID:', appointmentId); // Check the ID here

    // Function to check appointment status
    function checkAppointmentStatus() {
        console.log('Checking status for appointment ID:', appointmentId);
        if (appointmentId) {
            fetch('check_appointment_status.php?appointment_id=' + appointmentId)
                .then(response => {
                    console.log('Response received:', response); // Log the response object
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data); // Log the received data
                    if (typeof data.status !== 'undefined') {
                        var status = Number(data.status);
                        console.log('Converted status:', status); // Log the converted status
                        // If status is 1, reload the page to update the information
                        if (status === 1) {
                            console.log('Status is 1, reloading page.'); // Log status change
                            window.location.reload();
                        } else {
                            console.log('Status is still 0, will check again.'); // Log status check
                        }
                    } else {
                        console.error('Status field is undefined in the response:', data); // Log missing status field
                    }
                })
                .catch(error => console.error('Error:', error)); // Log any errors
        } else {
            console.log('No valid appointment ID found.'); // Log invalid ID
        }
    }

    // Set interval to check the status every 5 seconds if status is 0
    var appointmentStatus = <?php echo json_encode(isset($appointment['status']) ? $appointment['status'] : 0); ?>;
    console.log('Current appointment status:', appointmentStatus); // Log initial status
    if (appointmentStatus === '0') {
        console.log('Appointment status is 0, starting status checks.'); // Log interval start
        setInterval(checkAppointmentStatus, 5000);
    } else {
        console.log('Appointment status is not 0, no need to check further.'); // Log that checking is not necessary
    }
</script>