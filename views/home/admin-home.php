<?php
include "admin-nav.php";
?>
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg1"><i class="fa fa-stethoscope" aria-hidden="true"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?= $data['totalDoctors'] ?></h3>
                        <span class="widget-title1">Doctors <i class="fa fa-check" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg2"><i class="fa fa-user-o"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?= $data['totalPatients'] ?></h3>
                        <span class="widget-title2">Patients <i class="fa fa-check" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg3"><i class="fa fa-user-md" aria-hidden="true"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?= $data['totalAppointments'] ?></h3>
                        <span class="widget-title3">Appointments <i class="fa fa-check" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title d-inline-block">Appointments</h4>
                        <a href="appointments.php" class="btn btn-primary float-right">View all</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="d-none">
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Doctor Name</th>
                                    <th>Timing</th>
                                    <th class="text-right">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($data['appointments'])): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No appointments available</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['appointments'] as $appointment): ?>
                                        <tr>
                                            <td style="min-width: 200px;">
                                                <p class="avatar" ><?= substr($appointment['patient_name'], 0, 1) ?></p>
                                                <h2><?= $appointment['patient_name']?>  <span><?= $appointment['hospital_name']?></span></h2>
                                            </td>
                                            <td>
                                                <h5 class="time-title p-0">Appointment With</h5>
                                                <p><?= $appointment['doctor_name'] ?></p>
                                            </td>
                                            <td>
                                                <h5 class="time-title p-0">Timing</h5>
                                                <p><?= $appointment['start_time'] ?></p>
                                            </td>
                                            <td>
                                                <h5 class="time-title p-0">Status</h5>
                                                <p><?= $appointment['appointment_status'] ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        </div>



