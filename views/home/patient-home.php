
<style>
    .container {
        display: flex;
    }
</style>
<div class="container min-h-screen">
    <nav class="flex flex-col w-[340px] p-14 min-h-screen bg-white shadow-lg">
        <img width="250" src="../public/assets/main-logo.png" alt="logo">
        <menu class="text-[#35DDBF] mt-20 flex flex-col gap-5">
            <a href="home" class="flex gap-2">
                <img src="../public/assets/Dashboard-icon.png" alt="">
                Dashboard
            </a>
            <a href="logout" class="flex gap-2">
                <img src="../public/assets/Log-icon.png" alt="">
                Log out
            </a>
        </menu>
    </nav>

    <div class="flex-1 bg-[#F5F5F5] rounded-l-3xl shadow-xl">
        <header class="flex justify-between my-10 mx-7 items-center">
            <h3 class="text-2xl font-bold">
                <span class="text-[#4E4E4E]">Welcome,</span>
                <span class="text-[#24306E]"><span style="color:orange;">Patient</span>: <?php echo htmlspecialchars($_SESSION["patient_name"]); ?>,</span>
            </h3>
        </header>
        <?php if (isset($error) && !empty($error)): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    swal("Oops!", "<?php echo htmlspecialchars($error); ?>", "error");
                });
            </script>
        <?php endif; ?>
        <div class="flex mt-5 justify-around">
            <table class="w-[670px] bg-white shadow-lg rounded-lg p-4">
                <thead>
                <tr class="bg-[#35DDBF] text-white">
                    <th class="py-4 px-6">ID</th>
                    <th class="py-4 px-6">Doctor Name</th>
                    <th class="py-4 px-6">Time</th>
                    <th class="py-4 px-6">Meeting Link</th>
                    <th class="py-4 px-6">Meeting Status</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="7" class="py-4 px-6 text-center">No appointments available</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td class="py-4 px-6"><?= htmlspecialchars($appointment['id']) ?></td>
                            <td class="py-4 px-6"><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                            <td class="py-4 px-6"><?= htmlspecialchars($appointment['start_time']). '-'. htmlspecialchars($appointment['end_time']) ?></td>
                            <td class="py-4 px-6">
                                <?php if($appointment['appointment_status'] == "completed"){  ?>
                                    <span style="color:red;">Meeting Link expired</span>
                                <?php } else { ?>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars($appointment['meeting_link']) ?>">Join Meeting</a>
                                <?php } ?>
                            </td>
                            <td class="py-4 px-6">
                                <?php if(htmlspecialchars($appointment['appointment_status']) == "waiting"){
                                    echo "<span class='text-[#34A853]'>Waiting</span>";
                                } else {
                                    echo htmlspecialchars($appointment['appointment_status']);
                                } ?>
                            </td>
                            <td class="py-4 px-6">
                                <?php if(htmlspecialchars($appointment['status']) == 1 && ($appointment["appointment_status"] == "waiting" || $appointment["appointment_status"] == "started")){
                                    echo "active";
                                } else {
                                    echo "inactive";
                                } ?>
                            </td>
                            <td class="py-4 px-6">
                                <a class="btn btn-danger" style="color:blue;" href="home.php?id=<?= htmlspecialchars($appointment['id']) ?>">Delete</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>