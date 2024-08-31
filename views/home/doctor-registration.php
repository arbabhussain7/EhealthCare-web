
<div class="bg-[url('../assets/patient-signup-bg.png')] bg-cover">
    <h1 class="text-slate-800 text-2xl font-bold text-center my-20">DOCTOR REGISTRATION REQUEST FORM</h1>
    <form action="" enctype="multipart/form-data" method="post">
        <div class="grid grid-cols-2 mx-40 mb-10">
            <div class="p-10 bg-white rounded-l-3xl flex flex-col items-start gap-7">
                <div class="flex flex-col gap-3 w-full mt-20">
                    <label class="text-slate-400" for="full_name">Enter your full name</label>
                    <input class="outline-none border-b-2 w-full border-slate-400 py-2 max-w-96"
                           id="full_name" name="full_name" placeholder="John Doe" type="text"
                           value="<?= htmlspecialchars($data['full_name'] ?? '') ?>">
                    <?php if (isset($errors['full_name'])): ?>
                        <span class="text-red-500" style="color:red;"><?= htmlspecialchars($errors['full_name']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col gap-3 w-full">
                    <label class="text-slate-400" for="phone_no">Enter your phone no</label>
                    <input class="outline-none border-b-2 w-full border-slate-400 py-2 max-w-96"
                           id="phone_no" name="phone_no" placeholder="+92 345678901" type="text"
                           value="<?= htmlspecialchars($data['phone_no'] ?? '') ?>">
                    <?php if (isset($errors['phone_no'])): ?>
                        <span class="text-red-500" style="color:red;"><?= htmlspecialchars($errors['phone_no']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col gap-3 w-full">
                    <label class="text-slate-400" for="email">Email</label>
                    <input class="outline-none border-b-2 w-full border-slate-400 py-2 max-w-96"
                           id="email" name="email" placeholder="johndoe@email.com" type="email"
                           value="<?= htmlspecialchars($data['email'] ?? '') ?>">
                    <?php if (isset($errors['email'])): ?>
                        <span class="text-red-500" style="color:red;"><?= htmlspecialchars($errors['email']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col gap-3 w-full">
                    <label class="text-slate-400" for="city">Enter your city</label>
                    <input class="outline-none border-b-2 w-full border-slate-400 py-2 max-w-96"
                           id="city" name="city" placeholder="Islamabad" type="text"
                           value="<?= htmlspecialchars($data['city'] ?? '') ?>">
                    <?php if (isset($errors['city'])): ?>
                        <span class="text-red-500" style="color:red;"><?= htmlspecialchars($errors['city']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col gap-3 w-full">
                    <label class="text-slate-400" for="pmc_no">PMC No.</label>
                    <input class="outline-none border-b-2 w-full border-slate-400 py-2 max-w-96"
                           id="pmc_no" name="pmc_no" placeholder="79329123" type="text"
                           value="<?= htmlspecialchars($data['pmc_no'] ?? '') ?>">
                    <?php if (isset($errors['pmc_no'])): ?>
                        <span class="text-red-500" style="color:red;"><?= htmlspecialchars($errors['pmc_no']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col gap-3 w-full">
                    <label class="text-slate-400" for="specialty">Enter your specialty</label>
                    <input class="outline-none border-b-2 w-full border-slate-400 py-2 max-w-96"
                           id="specialty" name="specialty" placeholder="child specialist" type="text"
                           value="<?= htmlspecialchars($data['specialty'] ?? '') ?>">
                    <?php if (isset($errors['specialty'])): ?>
                        <span class="text-red-500" style="color:red;"><?= htmlspecialchars($errors['specialty']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col gap-3 w-full">
                    <h5 class="text-slate-400">Please select your gender identity</h5>
                    <div class="flex items-center gap-2">
                        <input type="radio" name="gender" id="Male" value="Male" <?= isset($data['gender']) && $data['gender'] == 'Male' ? 'checked' : '' ?>>
                        <label for="Male">Male</label>
                        <input type="radio" name="gender" id="Female" value="Female" <?= isset($data['gender']) && $data['gender'] == 'Female' ? 'checked' : '' ?>>
                        <label for="Female">Female</label>
                    </div>
                    <?php if (isset($errors['gender'])): ?>
                        <span class="text-red-500" style="color:red;"><?= htmlspecialchars($errors['gender']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="accept" id="accept">
                    <label for="accept">I agree to the <a href="#" class="underline">Terms and Conditions</a></label>
                </div>
                <button class="p-3 py-4 my-4 rounded-full text-white px-10 bg-[#2DCFDA]" type="submit">Request submit</button>
            </div>
        </div>
    </form>

</div><br><br>

<?php if (!empty($errors)): ?>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            <?php foreach ($errors as $field => $message): ?>
            alert('<?= addslashes($message) ?>');
            <?php endforeach; ?>
        });
    </script>
<?php endif; ?>