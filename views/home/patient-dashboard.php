<?php
include "patient-nav.php";

?>
<section class="min-h-screen swiper mySwiper w-full overflow-hidden">
    <div class="swiper-wrapper !h-screen w-full select-none">
        <div class="w-full h-full swiper-slide bg-[url('../assets/home-slider-1.jpeg')] bg-cover">
            <div class="w-full max-w-[650px] h-full flex flex-col p-20 justify-center">
                <h3
                    class="text-5xl font-bold bg-gradient-to-b from-[#15C0B6] to-[#147278] inline-block text-transparent bg-clip-text">
                    We care</h3>
                <h3 class="text-5xl font-bold text-white">about your health</h3>
                <p class="text-[#142585] my-8">Good health is the state of mental, physical and social well being
                    and it does not just mean
                    absence of diseases.</p>
                <div class="">
                    <a href="doctor-list" class="p-5 px-14 rounded-lg text-white bg-[#238573]">Book an appointment</a>
                </div>
            </div>

        </div>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</section>

<section class="flex items-stretch my-8">
    <div class="w-2/5 flex justify-center items-center">
        <img src="<?php echo getBaseUrl();?>/public/assets/Group-529.png"
             alt="A smiling female doctor wearing a white coat and stethoscope, representing healthcare professionals."
             class="w-80">
    </div>
    <div class="flex-1 bg-[#EBF4FC] p-10">
        <h2 class="text-4xl font-bold mb-4">From Concern to Healthcare</h2>
        <div class="p-10 flex flex-col gap-5 items-center">
            <h3
                class="text-4xl w-full bg-gradient-to-r from-[#15C0B6] to-[#147278] inline-block text-transparent bg-clip-text">
                Find the Right Doctor for You.</h3>
            <p class="text-slate-400">We'll connect your health concerns with the right doctor, ensuring you receive
                the best possible care for your
                well-being. Trust us to find the perfect healthcare professional tailored to your needs.</p>
            <div class="grid grid-cols-2 gap-3">
                <div class="flex flex-col items-center">
                    <img src="<?php echo getBaseUrl();?>/public/assets/right-doctor-1.png" alt="">
                    <h2 class="text-[#172048]">Dentist</h2>
                    <p class="text-slate-400 text-xs text-center">A dentist specializes in oral health, treating
                        issues such as
                        tooth decay, gum disease, and dental emergencies, and providing services like cleanings,
                        fillings, and extractions.</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="<?php echo getBaseUrl();?>/public/assets/right-doctor-2.png" alt="">
                    <h2 class="text-[#172048]">Cardiologist</h2>
                    <p class="text-slate-400 text-xs text-center">A dentist specializes in oral health, treating
                        issues such as
                        tooth decay, gum disease, and dental emergencies, and providing services like cleanings,
                        fillings, and extractions.</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="<?php echo getBaseUrl();?>/public/assets/right-doctor-3.png" alt="">
                    <h2 class="text-[#172048]">Dialectology</h2>
                    <p class="text-slate-400 text-xs text-center">A dentist specializes in oral health, treating
                        issues such as
                        tooth decay, gum disease, and dental emergencies, and providing services like cleanings,
                        fillings, and extractions.</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="<?php echo getBaseUrl();?>/public/assets/right-doctor-4.png" alt="">
                    <h2 class="text-[#172048]">Child Specialist</h2>
                    <p class="text-slate-400 text-xs text-center">A dentist specializes in oral health, treating
                        issues such as
                        tooth decay, gum disease, and dental emergencies, and providing services like cleanings,
                        fillings, and extractions.</p>
                </div>
            </div>
            <button class="border-2 border-[#53E8F1] text-[#53E8F1] px-6 py-3 rounded-2xl">View All
                Socialist</button>
        </div>
    </div>
</section>

<section class="p-10 bg-[#EBF4FC]">
    <h2 class="my-10 text-4xl font-bold text-[#15C0B6] w-[560px] m-auto text-center">What domain are you inclined to
        become a part of ?</h2>
    <div class="grid grid-cols-4 items-center justify-items-center">
       <a href="doctor-list"> <img class="row-span-2" src="<?php echo getBaseUrl();?>/public/assets/become-a-part-of-1.png" alt="">
       </a> <img class="col-span-2" src="<?php echo getBaseUrl();?>/public/assets/become-a-part-of-2.png" alt="">
        <img class="row-span-2" src="<?php echo getBaseUrl();?>/public/assets/become-a-part-of-3.png" alt="">
        <img class="col-span-2" src="<?php echo getBaseUrl();?>/public/assets/become-a-part-of-4.png" alt="">
    </div>
</section>

<section class="p-10 bg-[url('../assets/OUR-SERVICES-BG.png')] bg-cover">
    <h2 class="my-10 text-4xl font-bold text-white w-[360px] m-auto text-center border-b-[10px] pb-4 border-white">
        OUR SERVICES</h2>
    <div class="grid grid-cols-2 gap-5 justify-items-center">
        <div class="flex flex-col items-center w-[400px]">
            <img src="<?php echo getBaseUrl();?>/public/assets/our-services-1.png" alt="">
            <h2 class="text-white">ONLINE DOCTOR APPOINTMENT</h2>
            <p class="text-slate-400 text-xs text-center">E-Healthcare is the website where you can find and get
                appointment from highly professional doctors in any city.</p>
        </div>
        <div class="flex flex-col items-center w-[400px]">
            <img src="<?php echo getBaseUrl();?>/public/assets/our-services-2.png" alt="">
            <h2 class="text-white">SENOIR DOCTORS HEALTH TIPS</h2>
            <p class="text-slate-400 text-xs text-center">E-Healthcare is the website where you can see Health tips
                from highly professional doctors.</p>
        </div>
        <div class="flex flex-col items-center w-[400px]">
            <img src="<?php echo getBaseUrl();?>/public/assets/our-services-3.png" alt="">
            <h2 class="text-white">BUY MEDINCES</h2>
            <p class="text-slate-400 text-xs text-center">E-Healthcare is the website where you can buy any Medince
                on discount price from any city in pakistan.</p>
        </div>
        <div class="flex flex-col items-center w-[400px]">
            <img src="<?php echo getBaseUrl();?>/public/assets/our-services-4.png" alt="">
            <h2 class="text-white">SHIPMENT</h2>
            <p class="text-slate-400 text-xs text-center">E-Healthcare e will provide you Free Shipment.</p>
        </div>
    </div>
    </div>
</section>
