<?php
include "admin-nav.php";
?>
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Edit Doctor Profile</h4>
            </div>
        </div>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="card-box">
                <h3 class="card-title">Basic Information</h3>

                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-img-wrap">
                            <?php
                            $avatarSrc = !empty($doctor['avatar']) ? htmlspecialchars($doctor['avatar']) : 'public/img/placeholder.jpg';
                            ?>
                            <img class="inline-block" src="../<?php echo $avatarSrc; ?>" alt="user">
                            <div class="fileupload btn">
                                <span class="btn-text">Edit</span>
                                <input class="upload" type="file" name="avatar">
                            </div>
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group <?php echo !empty($errors['full_name']) ? 'has-error' : ''; ?>">
                                        <label class="focus-label">Full Name</label>
                                        <input type="text" class="form-control floating" name="full_name" value="<?php echo htmlspecialchars($doctor['full_name'] ?? ''); ?>">
                                        <?php if (!empty($errors['full_name'])): ?>
                                            <span class="help-block text-danger"><?php echo htmlspecialchars($errors['full_name']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group <?php echo !empty($errors['date_of_birth']) ? 'has-error' : ''; ?>">
                                        <label class="focus-label">Birth Date</label>
                                        <div class="">
                                            <input class="form-control floating " type="date" name="date_of_birth" value="<?php echo htmlspecialchars($doctor['date_of_birth'] ?? ''); ?>">
                                            <?php if (!empty($errors['date_of_birth'])): ?>
                                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['date_of_birth']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group select-focus <?php echo !empty($errors['gender']) ? 'has-error' : ''; ?>">
                                        <label class="focus-label">Gender</label>
                                        <select class="select form-control floating" name="gender">
                                            <option value="male" <?php echo ($doctor['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                                            <option value="female" <?php echo ($doctor['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                                        </select>
                                        <?php if (!empty($errors['gender'])): ?>
                                            <span class="help-block text-danger"><?php echo htmlspecialchars($errors['gender']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group <?php echo !empty($errors['password']) ? 'has-error' : ''; ?>">
                                        <label class="focus-label">Password</label>
                                        <input type="password" class="form-control floating" required name="password" placeholder="********">
                                        <?php if (!empty($errors['password'])): ?>
                                            <span class="help-block text-danger"><?php echo htmlspecialchars($errors['password']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <h3 class="card-title">Contact Information</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group <?php echo !empty($errors['address']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Address</label>
                            <input type="text" class="form-control floating" name="address" value="<?php echo htmlspecialchars($doctor['address'] ?? ''); ?>">
                            <?php if (!empty($errors['address'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['address']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['state']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">State</label>
                            <input type="text" class="form-control floating" name="state" value="<?php echo htmlspecialchars($doctor['state'] ?? ''); ?>">
                            <?php if (!empty($errors['state'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['state']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['country']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Country</label>
                            <input type="text" class="form-control floating" name="country" value="<?php echo htmlspecialchars($doctor['country'] ?? ''); ?>">
                            <?php if (!empty($errors['country'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['country']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['postal_code']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Postal Code</label>
                            <input type="text" class="form-control floating" name="postal_code" value="<?php echo htmlspecialchars($doctor['postal_code'] ?? ''); ?>">
                            <?php if (!empty($errors['postal_code'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['postal_code']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['phone_no']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Phone Number</label>
                            <input type="text" class="form-control floating" name="phone_no" value="<?php echo htmlspecialchars($doctor['phone_no'] ?? ''); ?>">
                            <?php if (!empty($errors['phone_no'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['phone_no']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <h3 class="card-title">Education Information</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['institution']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Institution</label>
                            <input type="text" class="form-control floating" name="institution" value="<?php echo htmlspecialchars($education['institution'] ?? ''); ?>">
                            <?php if (!empty($errors['institution'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['institution']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['subject']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Subject</label>
                            <input type="text" class="form-control floating" name="subject" value="<?php echo htmlspecialchars($education['subject'] ?? ''); ?>">
                            <?php if (!empty($errors['subject'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['subject']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['starting_date']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Starting Date</label>
                            <input type="date" class="form-control floating" name="starting_date" value="<?php echo htmlspecialchars($education['starting_date'] ?? ''); ?>">
                            <?php if (!empty($errors['starting_date'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['starting_date']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['complete_date']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Complete Date</label>
                            <input type="date" class="form-control floating" name="complete_date" value="<?php echo htmlspecialchars($education['complete_date'] ?? ''); ?>">
                            <?php if (!empty($errors['complete_date'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['complete_date']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['degree']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Degree</label>
                            <input type="text" class="form-control floating" name="degree" value="<?php echo htmlspecialchars($education['degree'] ?? ''); ?>">
                            <?php if (!empty($errors['degree'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['degree']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['grade']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Grade</label>
                            <input type="text" class="form-control floating" name="grade" value="<?php echo htmlspecialchars($education['grade'] ?? ''); ?>">
                            <?php if (!empty($errors['grade'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['grade']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <h3 class="card-title">Experience Information</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['company_name']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Company Name</label>
                            <input type="text" class="form-control floating" name="company_name" value="<?php echo htmlspecialchars($experience['company_name'] ?? ''); ?>">
                            <?php if (!empty($errors['company_name'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['company_name']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['company_location']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Company Location</label>
                            <input type="text" class="form-control floating" name="company_location" value="<?php echo htmlspecialchars($experience['location'] ?? ''); ?>">
                            <?php if (!empty($errors['company_location'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['company_location']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['job_position']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Job Position</label>
                            <input type="text" class="form-control floating" name="job_position" value="<?php echo htmlspecialchars($experience['job_position'] ?? ''); ?>">
                            <?php if (!empty($errors['job_position'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['job_position']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['period_from']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Period From</label>
                            <input type="date" class="form-control floating" name="period_from" value="<?php echo htmlspecialchars($experience['period_from'] ?? ''); ?>">
                            <?php if (!empty($errors['period_from'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['period_from']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo !empty($errors['period_to']) ? 'has-error' : ''; ?>">
                            <label class="focus-label">Period To</label>
                            <input type="date" class="form-control floating" name="period_to" value="<?php echo htmlspecialchars($experience['period_to'] ?? ''); ?>">
                            <?php if (!empty($errors['period_to'])): ?>
                                <span class="help-block text-danger"><?php echo htmlspecialchars($errors['period_to']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-primary submit-btn" type="submit" name="updateDoctor">Save Changes</button>
            </div>
        </form>
    </div>
</div>
