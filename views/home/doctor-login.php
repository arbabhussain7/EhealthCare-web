<div class="flex min-h-screen w-full">
    <div class="flex-1 gap-20 flex flex-col items-center justify-center bg-[url('../assets/bg-left.png')] bg-cover">
        <img class="w-20 mx-auto md:mx-0 mb-4" alt="logo" src="../public/assets/main-logo.png" />
        <img class="w-80 mx-auto md:mx-0 mb-4" alt="Illustration of two people discussing over a large screen, representing an online community for healthcare" src="../public/assets/signup-Illustration.png" />
        <h1 class="text-4xl font-bold mb-2 text-white text-center w-full max-w-[400px]">
            Online Community For Healthcare
        </h1>
    </div>
    <div class="flex-1 flex justify-center items-center">
        <div class="flex flex-col max-w-[400px] w-full">
            <h2 class="text-2xl text-[#242424] font-bold mb-12 text-center">
                Welcome back to the
                <br>
                E-Healthcare
            </h2>

            <?php if (isset($errors) && !empty($errors)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        swal("Oops!", "<?php echo htmlspecialchars($errors); ?>", "error");

                    });
                </script>
            <?php endif; ?>
            <form action="" method="post" autocomplete="off">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm" for="email">
                        Email
                    </label>
                    <input class="appearance-none focus:border-blue-500 border-b-2 focus:outline-none leading-tight py-4 text-gray-700 w-full" id="email" placeholder="johndoe@email.com" autocomplete="off" required name="doctorEmail" type="email" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm" for="password">
                        Password
                    </label>
                    <input class="appearance-none focus:border-blue-500 border-b-2 focus:outline-none leading-tight py-4 text-gray-700 w-full" id="password" name="doctorPassword" required placeholder="********" autocomplete="new-password" type="password" />
                </div>
                <div class="flex items-center justify-between mb-6">
                    <input class="bg-cyan-400 hover:bg-cyan-500 text-white font-bold py-4 px-10 rounded-full focus:outline-none focus:shadow-outline" type="submit" value="Login" name="doctorLogin" />
                </div>
            </form>
            <div class="flex items-center mt-5 justify-center">
                <a class="inline-block align-baseline" href="../doctor-registration">
                    No Account yet? <span class="font-bold">SIGN UP</span>
                </a>
            </div>
        </div>
    </div>
</div>
