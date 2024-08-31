<div class="main-wrapper account-wrapper">
    <div class="account-page">
        <div class="account-center">
            <div class="account-box">

                <form action="" method="post" class="form-signin">
                    <div class="account-logo">
                        <a href="index"><img src="public/img/logo-dark.png" alt=""></a>
                    </div>

                    <?php if (isset($errors['general'])): ?>

                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errors['general']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($errors['adminEmail'])): ?>
                        <div class="text-danger">
                            <?php echo htmlspecialchars($errors['adminEmail']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($errors['adminPassword'])): ?>
                        <div class="text-danger">
                            <?php echo htmlspecialchars($errors['adminPassword']); ?>
                        </div>
                    <?php endif; ?>


                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" autofocus name="adminEmail" class="form-control" placeholder="Enter admin email address" required>
                        <?php if (isset($errors['adminEmail'])): ?>
                            <div class="text-danger">
                                <?php echo htmlspecialchars($errors['adminEmail']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="adminPassword" placeholder="Enter admin password" required class="form-control">
                        <?php if (isset($errors['adminPassword'])): ?>
                            <div class="text-danger">
                                <?php echo htmlspecialchars($errors['adminPassword']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group text-center">
                        <input value="Login" name="adminLogin" type="submit" class="btn btn-primary account-btn"/>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
