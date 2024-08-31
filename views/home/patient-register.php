
<style>
    .text-red-500:{
        color: red !important;
        padding: 10px;
        margin: 10px;
    }
</style>

<div class="flex min-h-screen w-full">
    <div class="flex-1 gap-10 flex flex-col items-center justify-center bg-[url('../assets/bg-left.png')] bg-cover">
        <img  alt="logo" class="w-20 mx-auto md:mx-0 mb-4" src="<?php echo  getBaseUrl(); ?>/public/assets/main-logo.png" />
        <img class="w-80"
             alt="Illustration of two people discussing over a large screen, representing an online community for healthcare"
             class="mx-auto md:mx-0 mb-4" src="<?php echo  getBaseUrl(); ?>/public/assets/signup-Illustration.png" />
        <h1 class="text-4xl font-bold mb-2 text-white text-center w-full max-w-[400px]">
            Online Community For Healthcare
        </h1>
        <p class="mb-4 text-white  text-center w-full max-w-[400px]">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.
        </p>
    </div>
    <div class="flex-1 flex justify-center items-center">
        <div class="flex flex-col max-w-[400px]">
            <h2 class="text-2xl text-[#242424] font-bold mb-6 text-center">
                Join and connect with the rapidly expanding online patient treatment
            </h2>
            <form method="post" enctype="multipart/form-data" action="">
                <?php if (isset($errors['duplicate'])): ?>
                    <p class="text-red-500 text-sm" style="color:red;"><?= $errors['duplicate']; ?></p>
                <?php endif; ?>

                <?php if (isset($errors['general'])): ?>
                    <p class="text-red-500 text-sm"  style="color:red;"><?= $errors['general']; ?></p>
                <?php endif; ?>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm" for="username">
                        Name
                    </label>
                    <input
                            class="appearance-none focus:border-blue-500 border-b-2 focus:outline-none leading-tight py-4 text-gray-700 w-full"
                            id="name" placeholder="Enter name" name="name" required type="text" />
                    <?php if (isset($errors['name'])): ?>
                        <p class="text-red-500 text-sm"  style="color:red;"><?= $errors['name']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm" for="username">
                        Username
                    </label>
                    <input
                            class="appearance-none focus:border-blue-500 border-b-2 focus:outline-none leading-tight py-4 text-gray-700 w-full"
                            id="username" name="username" placeholder="Enter username" required type="text" />
                    <?php if (isset($errors['username'])): ?>
                        <p class="text-red-500 text-sm"  style="color:red;"><?= $errors['username']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm" for="username">
                        Phone
                    </label>
                    <input
                            class="appearance-none focus:border-blue-500 border-b-2 focus:outline-none leading-tight py-4 text-gray-700 w-full"
                            id="phone" name="phone" placeholder="Enter phone " required type="text" />
                    <?php if (isset($errors['phone'])): ?>
                        <p class="text-red-500 text-sm"  style="color:red;"><?= $errors['phone']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm " for="email">
                        Email
                    </label>
                    <input
                            class="appearance-none focus:border-blue-500 border-b-2 focus:outline-none leading-tight py-4 text-gray-700 w-full"
                            id="email" placeholder="johndoe@email.com" name="email" required type="email" />
                    <?php if (isset($errors['email'])): ?>
                        <p class="text-red-500 text-sm"  style="color:red;"><?= $errors['email']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm " for="Password">
                        Password
                    </label>
                    <input
                            class="appearance-none focus:border-blue-500 border-b-2 focus:outline-none leading-tight py-4 text-gray-700 w-full"
                            id="Password" placeholder="********" required name="password" type="Password" />
                    <?php if (isset($errors['password'])): ?>
                        <p class="text-red-500 text-sm"  style="color:red;"><?= $errors['password']; ?></p>
                    <?php endif; ?>
                </div>


            <div class="flex items-center justify-between mb-6">

                <input value="SIGN UP" name="registerPatient"
                    class="bg-cyan-400 hover:bg-cyan-500 text-white font-bold py-4 px-6 rounded-full focus:outline-none focus:shadow-outline"
                    type="submit"/>
            </div>
            </form>
            <div class="flex items-center mt-5 justify-center">
                <a class="inline-block align-baseline" href="login">
                    Own an Account?<span class="font-bold">Login here</span>
                </a>
            </div>
        </div>
    </div>
</div>