
<?php
include "patient-nav.php";
//function getBaseUrl() {
//    return 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
//}
?>

<?php include "patient-nav.php"; ?>

<br><br><br>
<section class="bg-[#2E717A] rounded-2xl m-10 p-10 flex items-center mt-10">
    <div class="bg-[#D9D9D9] flex items-center flex-1">
        <label for="" class="p-2 ">
            <img src="<?php echo getBaseUrl(); ?>/assets/search-icon.png" alt="">
        </label>
        <input class="flex-1 py-2 bg-transparent" type="text" placeholder="Search Doctor Specialty">
    </div>
    <button class="p-2 px-8 rounded-lg text-white bg-[#3DB8F5]">Search</button>
</section>

<section class="bg-white m-10 p-5">
    <p>Best Doctors in Pakistan | EHealthcare.com</p>
    <p>All Specialist Doctors</p>
</section>

<div class="m-10 p-5">
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php } else { ?>
        <div class="grid grid-cols-4 gap-10">
            <?php foreach ($specialties as $specialty) { ?>
                <div class="bg-white flex items-center flex-col gap-2 p-3">
                    <a href="doctor-detail.php?specialty=<?php echo htmlspecialchars($specialty['specialty']); ?>">                    <img class="h-40 w-40" src="<?php echo getBaseUrl(); ?>/<?php echo htmlspecialchars($specialty['avatar']); ?>" alt="">
                    </a>
                    <h4 class="text-2xl"><?php echo htmlspecialchars($specialty['specialty']); ?></h4>
                    <h5><?php echo htmlspecialchars($specialty['doctor_count']); ?> Doctors</h5>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
