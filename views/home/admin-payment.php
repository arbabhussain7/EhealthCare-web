<?php
include "admin-nav.php";
?>
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Payments</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable mb-0">
                        <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Appointment ID</th>
                            <th>Patient Name</th>
                            <th>View Receipt</th>
                            <th>Paid Amount</th>
                            <th>Paid Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($payments)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No payments available</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td>#INV-<?= htmlspecialchars($payment['id']) ?></td>
                                    <td><?= htmlspecialchars($payment['appointment_id']) ?></td>
                                    <td><?= htmlspecialchars($payment['patient_name']) ?></td>
                                    <td><a href="../<?= htmlspecialchars($payment['payment_screenshot']) ?>" target="_blank">View Receipt</a></td>
                                    <td>$<?= htmlspecialchars($payment['amount']) ?></td>
                                    <td><?= htmlspecialchars($payment['created_at']) ?></td>
                                    <td><span class="custom-badge <?= $payment['status'] == 1 ? 'status-green' : 'status-red' ?>">Paid</span></td>
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
