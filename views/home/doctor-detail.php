<?php
include "patient-nav.php";

//function getBaseUrl() {
//    return 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
//}
?>
<style>
    .error_color{
        color: white;
        background: red;
        padding: 15px;
        border-radius: 15px;
        font-weight: bold;
        text-align: center;
    }
</style>

<br><br><br>
<form method="get" action="">
    <section class="bg-[#2E717A] rounded-2xl m-10 p-10 flex items-center">
        <div class="bg-[#D9D9D9] flex items-center flex-1">
            <label for="" class="p-2">
                <img src="<?php echo getBaseUrl(); ?>/public/assets/search-icon.png" alt="">
            </label>
            <input class="flex-1 py-2 bg-transparent" name="search" type="text" placeholder="Search Doctor Name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <!-- Include specialty if it's part of the query string -->
            <input type="hidden" name="specialty" value="<?php echo isset($_GET['specialty']) ? htmlspecialchars($_GET['specialty']) : ''; ?>">
        </div>
        <button type="submit" class="p-2 px-8 rounded-lg text-white bg-[#3DB8F5]">Search</button>
    </section>
</form>

<section class="bg-white m-10 p-5">
    <p>Best Doctors in Pakistan | EHealthcare.com</p>
    <b class="text-2xl "><?php echo htmlspecialchars($specialty); ?></b>
</section>

<section class="flex flex-col gap-7 mx-20 my-10">
    <?php if (isset($error)) { ?>
        <div class="bg-red-500 error_color p-5 rounded">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php } else if (!empty($doctors)) { ?>
        <?php foreach ($doctors as $doctor) { ?>
            <div class="bg-white p-10 flex flex-col items-start gap-7">
                <div class="flex justify-between w-full">
                    <div class="flex gap-10">
                        <img width="222" height="222" src="<?php echo getBaseUrl(); ?>/<?php echo htmlspecialchars($doctor['avatar']); ?>" alt="">
                        <div class="flex flex-col gap-5">
                            <div class="flex gap-10 text-2xl font-bold">
                                <h2 class="text-[#089FB4]"><?php echo htmlspecialchars($doctor['full_name']); ?></h2>
                            </div>
                            <h2 class="flex gap-2">
                                <b>Specialty:</b> <?php echo htmlspecialchars($doctor['specialty']); ?>
                            </h2>
                            <h2 class="flex gap-2">
                                <b>Degree:</b> <?php echo htmlspecialchars($doctor['education'][0]['degree']); ?>
                            </h2>
                            <h2 class="text-slate-500"><?php echo htmlspecialchars($doctor['total_experience']); ?> year(s) Experience</h2>
                            <h2 class="text-[#34AC39]">PMC No: <?php echo htmlspecialchars($doctor['pmc_no']); ?></h2>
                            <h2 class="">Status <?php if ($doctor['status'] == 1) {
                                    echo "<span class='text-[#34AC39]'>Active</span>";
                                } else {
                                    echo "<span class='text-red-600'>Inactive</span>";
                                } ?></h2>
                        </div>
                    </div>
                    <div class="flex flex-col gap-5 items-center">
                        <h4 class="flex gap-2 text-[#90EF18]">
                            <span class="w-5 h-5 rounded-full block bg-[#90EF18]"></span>
                            <?php if ($doctor['status'] == 1) {
                                echo "<span class='text-[#34AC39]'>Active</span>";
                            } else {
                                echo "<span class='text-red-600'>Inactive</span>";
                            } ?>
                        </h4>
                        <a href="doctor-confirmation.php?id=<?php echo htmlspecialchars($doctor['id']); ?>" class="border-2 py-3 text-center w-80 bg-[#18CCE4] text-white">Book Appointment</a>
                    </div>
                </div>
                <div class="border-4 px-32 py-5 flex flex-col gap-5">
                    <?php if (!empty($doctor['schedule'])) { ?>
                        <h2>Hospital: <b><?php echo htmlspecialchars($doctor['schedule'][0]['hospital_name']); ?></b></h2>
                        <h2><b>Location :</b> <?php echo htmlspecialchars($doctor['schedule'][0]['location']) ?: 'Location not available'; ?></h2>
                        <h2><b>Fee :</b> <b><?php echo htmlspecialchars($doctor['schedule'][0]['fees']); ?></b></h2>
                        <h2><b>Days :</b> <b><?php echo htmlspecialchars($doctor['schedule'][0]['available_days']); ?></b></h2>
                        <h2><b>Timing :</b> <b><?php echo htmlspecialchars($doctor['schedule'][0]['start_time']); ?>-<?php echo htmlspecialchars($doctor['schedule'][0]['end_time']); ?></b></h2>
                        <a href="doctor-confirmation.php?id=<?php echo htmlspecialchars($doctor['id']); ?>" class="w-full p-2 border-4 text-[#38EADF] border-[#38EADF]">Book Appointment</a>
                    <?php } else { ?>
                        <h2 class="text-red-500">No schedule information available.</h2>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <section class="error_color">
            No doctors found for the search term
        </section>
    <?php } ?>
</section>