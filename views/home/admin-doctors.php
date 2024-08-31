<?php
include "admin-nav.php";
?>
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Doctors</h4>
            </div>
<!--            <div class="col-sm-8 col-9 text-right m-b-20">-->
<!--                <a href="add-doctor" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Doctor</a>-->
<!--            </div>-->
        </div>
        <div class="row doctor-grid">

          <?php  foreach ($doctors as $doctor):
            ?>
            <div class="col-md-4 col-sm-4  col-lg-3">
                <div class="profile-widget">
                    <div class="doctor-img">
                        <a class="avatar" href="doctor-profile.php?id=<?php echo $doctor['id']; ?>">
                            <img alt="Doctor Picture" src="../<?php echo htmlspecialchars(!empty($doctor['avatar']) ? $doctor['avatar'] : 'public/img/placeholder.jpg'); ?>" />

                        </a>
                    </div>
                    <div class="dropdown profile-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="edit-doctor-profile.php?id=<?php echo $doctor['id']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="doctors.php?doctor_id=<?php echo $doctor['id']; ?>"  ><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                        </div>
                    </div>
                    <h4 class="doctor-name text-ellipsis"><a href="doctor-profile.php?id=<?php echo $doctor['id']; ?>"><?php echo htmlspecialchars($doctor['full_name']); ?></a></h4>
                    <div class="doc-prof"><?php echo htmlspecialchars($doctor['specialty']); ?></div>
                    <div class="user-country">
                        <i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($doctor['city']); ?>
                    </div>
                </div>
            </div>
            <?php endforeach ; ?>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="see-all">
                    <a class="see-all-btn" href="javascript:void(0);">Load More</a>
                </div>
            </div>
        </div>
    </div>

</div>
