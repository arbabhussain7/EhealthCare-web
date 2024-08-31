<?php include "patient-nav.php"; ?>

<br><br><br>
<style>
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
</style>
<section class="flex flex-col gap-7 mx-20 my-10" >
    <div class="bg-white p-10 flex flex-col items-start gap-7">
        <!-- Doctor Information Section -->
        <div class="flex justify-between w-full">
            <div class="flex gap-10">
                <img width="222" height="222" src="../<?php echo htmlspecialchars(!empty($doctor['avatar']) ? $doctor['avatar'] : 'public/img/placeholder.jpg'); ?>" alt="">
                <div class="flex flex-col gap-5">
                    <div class="flex gap-10 text-2xl font-bold">
                        <h2 class="text-[#089FB4]"><?php echo htmlspecialchars($doctor['full_name']); ?></h2>
                    </div>
                    <h2 class="flex gap-2">
                        <b>Speciality:</b> <?php echo htmlspecialchars($doctor['specialty']); ?>
                    </h2>
                    <?php if (isset($education[0]['degree'])): ?>
                        <h2>Degree: <?php echo htmlspecialchars($education[0]['degree']); ?></h2>
                    <?php else: ?>
                        <h2>Degree not available</h2>
                    <?php endif; ?>
                    <h2 class="text-slate-500">Experience: <?php echo htmlspecialchars($totalExperience ? $totalExperience : 'No Experience'); ?> Years</h2>
                    <h2 class="text-[#34AC39]">PMC No: <?php echo htmlspecialchars($doctor['pmc_no'] ? $doctor['pmc_no'] : 'Not PMC No Available'); ?></h2>
                    <h4 class="flex gap-2 text-[#90EF18]">
                        <span class="w-5 h-5 rounded-full block bg-[#90EF18]"></span>
                        <?php
                        if($doctor['status'] ==1){
                            echo "Active";
                        } else {
                            echo "<span class='' style='color:red;'>Inactive</span>";
                        }
                        ?>
                    </h4>
                </div>
            </div>
            <div class="flex flex-col gap-5 items-center">

            </div>
        </div>
    </div>
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="error-message bg-red-100 text-red-700 p-3 rounded-xl mb-5">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <!-- Schedule Section -->
    <div class="bg-white p-10 flex flex-col items-start gap-7">
        <h2 class="px-7 py-3 text-white bg-[#35DDBF] rounded-2xl">Doctor Schedule</h2>
        <h3 class="text-slate-600 text-2xl">Select date and time slot</h3>
        <div class="flex flex-wrap gap-5">
            <?php if (!empty($schedule)): ?>
                <?php
                // Group by available days
                $scheduleByDay = [];
                foreach ($schedule as $slot) {
                    $days = explode(',', $slot['available_days']);
                    foreach ($days as $day) {
                        $day = trim($day); // Remove extra spaces
                        if (!isset($scheduleByDay[$day])) {
                            $scheduleByDay[$day] = [];
                        }
                        $scheduleByDay[$day][] = $slot;
                    }
                }
                ?>
                <?php foreach ($scheduleByDay as $day => $slots): ?>
                    <div class="w-full">
                        <h4 class="text-xl font-bold mb-3 text-center"><?php echo htmlspecialchars($day); ?></h4>
                        <div class="flex flex-wrap gap-5">
                            <?php foreach ($slots as $slot): ?>
                                <div class="border-2 border-black rounded-3xl py-7 px-10 hover:text-white hover:bg-[#35DDBF] w-full md:w-1/2 lg:w-1/3">
                                    <h4><b>Hospital Name:</b> <?php echo htmlspecialchars($slot['hospital_name']); ?></h4>
                                    <h4><b>Time Slot:</b> <?php echo htmlspecialchars($slot['start_time']); ?> - <?php echo htmlspecialchars($slot['end_time']); ?></h4>
                                    <p><b>Fees:</b> <?php echo htmlspecialchars($slot['fees']); ?></p>
                                    <a href="#"
                                       class="bg-[#43E4DB] py-3 rounded-xl text-white text-center inline-block"
                                       onclick="openConfirmationModal(
                                               '<?php echo htmlspecialchars($doctor['full_name']); ?>',
                                               '<?php echo htmlspecialchars($day); ?>',
                                               '<?php echo htmlspecialchars($slot['hospital_name']); ?>',
                                               '<?php echo htmlspecialchars($slot['start_time']); ?>',
                                               '<?php echo htmlspecialchars($slot['end_time']); ?>',
                                               '<?php echo htmlspecialchars($slot['fees']); ?>'
                                               )">
                                        Book Appointment
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No schedule available for this doctor.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="Patient-reconfirm" class="fixed h-screen w-screen top-0 left-0 bg-black bg-opacity-25 flex justify-center items-center z-50" style="display: none;">
    <div class="bg-[#FAFAFA] p-4 flex w-full max-w-md flex-col gap-0">
        <div class="flex justify-end">
            <a href="#" class="w-7 h-7 flex justify-center items-center border border-gray-800 rounded-xl" onclick="closeModal()">X</a>
        </div>
        <div class="bg-white p-5 flex flex-col gap-3 items-center rounded-xl">
            <h2 class="text-xl">Doctor: <span id="modalDoctorName"></span></h2>
            <p class="text-xl">Time: <span id="modalStartTime"></span> - <span id="modalEndTime"></span></p>
        </div>



        <form action="" method="post" class="flex flex-col gap-3" enctype="multipart/form-data">
            <div class="flex flex-col gap-3">
                <label for="patientName" class="text-[#757575]">Patient Name</label>
                <input type="text" id="patientName" name="patientName" value="<?php echo htmlspecialchars($_SESSION["patient_name"]); ?>" class="border p-2 bg-transparent rounded-xl" placeholder="Johnson Doe" required>
            </div>
            <div class="flex flex-col gap-3">
                <label for="patientPhone" class="text-[#757575]">Patient Phone No.</label>
                <input type="tel" id="patientPhone" name="patientPhone" value="<?php echo htmlspecialchars($_SESSION["patient_phone"]); ?>" class="border p-2 bg-transparent rounded-xl" placeholder="0321-XXXXXXX" required >
            </div>
            <div class="flex flex-col gap-3">
                <label for="patientProblem" class="text-[#757575]">Patient Problem detail (optional)</label>
                <textarea id="patientProblem" name="patientProblem" class="border p-2 bg-transparent rounded-xl" placeholder=""></textarea>
            </div>
            <div class="flex flex-col gap-3">
                <label for="paymentProof" class="text-[#757575]">Payment Proof (screenshot/image)</label>
                <input type="file" id="paymentProof" name="paymentProof" class="border p-2 bg-transparent rounded-xl" accept="image/*" required>
            </div>
            <input type="hidden" name="doctorId" id="doctorId">
            <input type="hidden" name="startTime" id="startTime">
            <input type="hidden" name="endTime" id="endTime">
            <input type="hidden" name="fees" id="fees">
            <input type="hidden" name="hospitalName" id="hospitalName">
            <input type="hidden" name="availableDays" id="availableDays">
            <input type="hidden" name="availableTime" id="availableTime">
            <input type="submit" name="bookAppt" class="bg-[#43E4DB] py-3 rounded-xl text-white text-center" value="Confirm"/>
        </form>
    </div>
</section>

<script>
    function openConfirmationModal(doctorName, day, hospitalName, startTime, endTime, fees) {
        document.getElementById('modalDoctorName').innerText = doctorName;
        document.getElementById('modalStartTime').innerText = startTime;
        document.getElementById('modalEndTime').innerText = endTime;
        document.getElementById('doctorId').value = doctorId; // Set the doctor ID
        document.getElementById('startTime').value = startTime;
        document.getElementById('endTime').value = endTime;
        document.getElementById('fees').value = fees;
        document.getElementById('hospitalName').value = hospitalName;
        document.getElementById('availableDays').value = day;
        document.getElementById('availableTime').value = startTime + ' - ' + endTime;
        document.getElementById('Patient-reconfirm').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('Patient-reconfirm').style.display = 'none';
    }
</script>
