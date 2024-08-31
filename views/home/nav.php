<?php
//function getBaseUrl() {
//    return 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
//}
//?>
<nav style="background: #333646" class="fixed top-0 left-0 w-full flex justify-between items-center bg-transparent py-4 px-6 z-20">
    <a href="#" class="text-teal-500 font-bold text-xl">
        <img class="w-20" src="<?php echo getBaseUrl(); ?>/public/assets/main-logo.png" alt="">
    </a>
    <div class="flex gap-10 items-center">
        <a href="index" class="text-teal-500 hover:text-teal-800">Home</a>
        <a href="patient/doctor-list" class="text-teal-500 hover:text-teal-800">Find a Doctor</a>
        <a href="doctor-registration" target="_blank" class="text-teal-500 hover:text-teal-800">Doctor Registration</a>
        <a href="doctor/index" target="_blank" class="text-teal-500 hover:text-teal-800">Doctor Access</a>
        <a href="admin/index" target="_blank" class="text-teal-500 hover:text-teal-800">Admin Access</a>

        <a href="patient/login"
           class="px-6 py-2 bg-gradient-to-r from-[#56E0E0] to-[#007299] text-white rounded-full">Sign in</a>
        <a href="patient/register"
           class="px-6 py-2 bg-gradient-to-r from-[#56E0E0] to-[#007299] text-white rounded-full">Sign up</a>
    </div>
</nav>
