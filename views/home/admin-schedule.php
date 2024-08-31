<?php
include "admin-nav.php";
?>
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Schedule</h4>
            </div>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="add-schedule.php" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Schedule</a>
            </div>
        </div>
        <?php if (isset($error) && !empty($error)): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    swal("Oops!", "<?php echo htmlspecialchars($error); ?>", "error");

                });
            </script>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-border table-striped custom-table mb-0">
                        <thead>
                        <tr>
                            <th>Doctor Name</th>
                            <th>Hospital Name</th>
                            <th>Available Days</th>
                            <th>Available Time</th>
                            <th>Fees</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No schedules available</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data as $schedule): ?>
                                <tr>
                                    <td>
                                        <img width="28" height="28" src="../public/assets/placeholder.png" class="rounded-circle m-r-5" alt="">
                                        <?= htmlspecialchars($schedule['doctor_name']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($schedule['hospital_name']) ?></td>
                                    <td><?= htmlspecialchars($schedule['available_days']) ?></td>
                                    <td><?= htmlspecialchars($schedule['start_time']) ?> - <?= htmlspecialchars($schedule['end_time']) ?></td>
                                    <td><?= htmlspecialchars($schedule['fees']) ?></td>
                                    <td><span class="custom-badge <?= $schedule['status'] == 'Active' ? 'status-green' : 'status-red' ?>"><?= htmlspecialchars($schedule['status']) ?></span></td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_schedule_<?= htmlspecialchars($schedule['id']) ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Delete Modal -->
                                <div id="delete_schedule_<?= htmlspecialchars($schedule['id']) ?>" class="modal fade delete-modal" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body text-center">
                                                <img src="../public/assets/delete.png" alt="" width="50" height="46">
                                                <h3>Are you sure you want to delete this schedule?</h3>
                                                <div class="m-t-20">
                                                    <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                                                    <a href="schedule.php?id=<?= htmlspecialchars($schedule['id']) ?>" class="btn btn-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
