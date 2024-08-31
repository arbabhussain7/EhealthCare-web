
<nav style="background: #333646" class=" fixed top-0 left-0 w-full flex justify-between items-center py-4 px-6 z-20 bg-[#043330] bg-opacity-40">
    <a href="#" class="text-teal-500 font-bold text-xl">
        <img class="w-20" src="<?php echo getBaseUrl();?>/public/assets/main-logo.png" alt="">
    </a>
    <div class="flex gap-10 items-center">
        <a href="index" class="text-teal-500 hover:text-teal-800">Home</a>
        <a href="doctor-list" class="text-teal-500 hover:text-teal-800">Find a Doctor</a>

        <?php
        if(isset($_SESSION['patient_email'])){
            ?>
            <a href="home" class="text-teal-500 hover:text-teal-800 ">Dashboard</a>
            <a href="../doctor-registration" class="text-teal-500 hover:text-teal-800 ">Doctor registration</a>
            <a href="logout" class="text-teal-500 hover:text-teal-800">Logout</a>

            <?php
        }
        ?>
    </div>
    <a href="home" class="flex flex-col justify-center items-center">
        <img class="w-9" src="<?php echo getBaseUrl();?>/public/assets/user.png" alt="User image">


            <h5 style="color:#fff;" class="text-[#fffff] "><?php echo $_SESSION["patient_name"];?></h5>
    </a>
</nav>