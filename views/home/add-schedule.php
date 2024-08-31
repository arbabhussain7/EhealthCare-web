<?php include "admin-nav.php"; ?>
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h4 class="page-title">Add Schedule</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <form method="post" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group <?php echo isset($errors['doctor_id']) ? 'has-error' : ''; ?>">
                                <label>Doctor Name</label>
                                <select class="form-control" name="doctor_id">
                                    <option value="">Select</option>
                                    <?php foreach ($doctors as $doctor): ?>
                                        <option value="<?php echo htmlspecialchars($doctor['id']); ?>" <?php echo isset($_POST['doctor_id']) && $_POST['doctor_id'] == $doctor['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($doctor['full_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['doctor_id'])): ?>
                                    <span class="text-danger"><?php echo htmlspecialchars($errors['doctor_id']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?php echo isset($errors['available_days']) ? 'has-error' : ''; ?>">
                                <label>Available Days</label>
                                <select class="form-control select" name="available_days[]" multiple>
                                    <option value="Sunday" <?php echo isset($_POST['available_days']) && in_array('Sunday', $_POST['available_days']) ? 'selected' : ''; ?>>Sunday</option>
                                    <option value="Monday" <?php echo isset($_POST['available_days']) && in_array('Monday', $_POST['available_days']) ? 'selected' : ''; ?>>Monday</option>
                                    <option value="Tuesday" <?php echo isset($_POST['available_days']) && in_array('Tuesday', $_POST['available_days']) ? 'selected' : ''; ?>>Tuesday</option>
                                    <option value="Wednesday" <?php echo isset($_POST['available_days']) && in_array('Wednesday', $_POST['available_days']) ? 'selected' : ''; ?>>Wednesday</option>
                                    <option value="Thursday" <?php echo isset($_POST['available_days']) && in_array('Thursday', $_POST['available_days']) ? 'selected' : ''; ?>>Thursday</option>
                                    <option value="Friday" <?php echo isset($_POST['available_days']) && in_array('Friday', $_POST['available_days']) ? 'selected' : ''; ?>>Friday</option>
                                    <option value="Saturday" <?php echo isset($_POST['available_days']) && in_array('Saturday', $_POST['available_days']) ? 'selected' : ''; ?>>Saturday</option>
                                </select>
                                <?php if (isset($errors['available_days'])): ?>
                                    <span class="text-danger"><?php echo htmlspecialchars($errors['available_days']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php echo isset($errors['hospital_name']) ? 'has-error' : ''; ?>">
                        <label>Hospital Name</label>
                        <input type="text" class="form-control" name="hospital_name" value="<?php echo isset($_POST['hospital_name']) ? htmlspecialchars($_POST['hospital_name']) : ''; ?>">
                        <?php if (isset($errors['hospital_name'])): ?>
                            <span class="text-danger"><?php echo htmlspecialchars($errors['hospital_name']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group <?php echo isset($errors['fee']) ? 'has-error' : ''; ?>">
                        <label>Doctor Fees</label>
                        <input type="number" class="form-control" name="fee" value="<?php echo isset($_POST['fee']) ? htmlspecialchars($_POST['fee']) : ''; ?>">
                        <?php if (isset($errors['fee'])): ?>
                            <span class="text-danger"><?php echo htmlspecialchars($errors['fee']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group <?php echo isset($errors['start_time']) ? 'has-error' : ''; ?>">
                                <label>Start Time</label>
                                <input type="time" class="form-control" name="start_time" value="<?php echo isset($_POST['start_time']) ? htmlspecialchars($_POST['start_time']) : ''; ?>">
                                <?php if (isset($errors['start_time'])): ?>
                                    <span class="text-danger"><?php echo htmlspecialchars($errors['start_time']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?php echo isset($errors['end_time']) ? 'has-error' : ''; ?>">
                                <label>End Time</label>
                                <input type="time" class="form-control" name="end_time" value="<?php echo isset($_POST['end_time']) ? htmlspecialchars($_POST['end_time']) : ''; ?>">
                                <?php if (isset($errors['end_time'])): ?>
                                    <span class="text-danger"><?php echo htmlspecialchars($errors['end_time']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea cols="30" rows="4" class="form-control" name="message"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    <div class="form-group <?php echo isset($errors['status']) ? 'has-error' : ''; ?>">
                        <label class="display-block">Schedule Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="product_active" value="active" <?php echo isset($_POST['status']) && $_POST['status'] === 'active' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="product_active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="product_inactive" value="inactive" <?php echo isset($_POST['status']) && $_POST['status'] === 'inactive' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="product_inactive">Inactive</label>
                        </div>
                        <?php if (isset($errors['status'])): ?>
                            <span class="text-danger"><?php echo htmlspecialchars($errors['status']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="m-t-20 text-center">
                        <input type="submit" name="createSchedule" class="btn btn-primary submit-btn" value="Create Schedule"/>
                        <?php if (isset($errors['general'])): ?>
                            <div class="text-danger"><?php echo htmlspecialchars($errors['general']); ?></div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
