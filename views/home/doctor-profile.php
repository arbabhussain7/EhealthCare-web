<?php
include "admin-nav.php";
?>

<div class="page-wrapper">
    <div class="content">
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php else: ?>
            <div class="row">
                <div class="col-sm-7 col-6">
                    <h4 class="page-title">My Profile</h4>
                </div>

                <div class="col-sm-5 col-6 text-right m-b-30">
                    <a href="edit-doctor-profile.php?id=<?php echo htmlspecialchars($doctor['id']); ?>" class="btn btn-primary btn-rounded">
                        <i class="fa fa-plus"></i> Edit Profile
                    </a>
                </div>
            </div>
            <div class="card-box profile-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <?php
                                    // Check if the avatar URL is set and not empty
                                    $avatarSrc = !empty($doctor['avatar']) ? htmlspecialchars($doctor['avatar']) : 'public/img/placeholder.jpg';
                                    ?>
                                    <a href="#"><img class="avatar" src="../<?php echo $avatarSrc; ?>" alt="Profile Image"></a>
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-0"><?php echo htmlspecialchars($doctor['full_name']); ?></h3>
                                            <small class="text-muted"><b>Specialty: </b><?php echo htmlspecialchars($doctor['specialty']); ?></small>
                                            <div class="staff-id"><b>PMC ID :</b> <?php echo htmlspecialchars($doctor['pmc_no']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <li>
                                                <span class="title">Phone:</span>
                                                <span class="text"><a href="tel:<?php echo htmlspecialchars($doctor['phone_no']); ?>"><?php echo htmlspecialchars($doctor['phone_no']); ?></a></span>
                                            </li>
                                            <li>
                                                <span class="title">Email:</span>
                                                <span class="text"><a href="mailto:<?php echo htmlspecialchars($doctor['email']); ?>"><?php echo htmlspecialchars($doctor['email']); ?></a></span>
                                            </li>
                                            <li>
                                                <span class="title">Birthday:</span>
                                                <span class="text"><?php echo htmlspecialchars($doctor['date_of_birth']) ?: 'Not available'; ?></span>
                                            </li>
                                            <li>
                                                <span class="title">Address:</span>
                                                <span class="text"><?php echo htmlspecialchars($doctor['address']) ?: 'Not available'; ?></span>
                                            </li>
                                            <li>
                                                <span class="title">Gender:</span>
                                                <span class="text"><?php echo htmlspecialchars($doctor['gender']) ?: 'Not available'; ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-tabs">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item"><a class="nav-link active" href="#about-cont" data-toggle="tab">About</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane show active" id="about-cont">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box">
                                    <h3 class="card-title">Education Information</h3>
                                    <?php if ($education): ?>
                                        <div class="experience-box">
                                            <ul class="experience-list">
                                                <li>
                                                    <div class="experience-user">
                                                        <div class="before-circle"></div>
                                                    </div>
                                                    <div class="experience-content">
                                                        <div class="timeline-content">
                                                            <a href="#/" class="name"><?php echo htmlspecialchars($education['institution']) ?: 'Not available'; ?></a>
                                                            <div><?php echo htmlspecialchars($education['degree']) ?: 'Not available'; ?></div>
                                                            <span class="time"><?php echo htmlspecialchars($education['starting_date']) ?: 'Not available'; ?> - <?php echo htmlspecialchars($education['complete_date']) ?: 'Not available'; ?></span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <p>No education information available.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-box mb-0">
                                    <h3 class="card-title">Experience</h3>
                                    <?php if ($experience): ?>
                                        <div class="experience-box">
                                            <ul class="experience-list">
                                                <li>
                                                    <div class="experience-user">
                                                        <div class="before-circle"></div>
                                                    </div>
                                                    <div class="experience-content">
                                                        <div class="timeline-content">
                                                            <a href="#/" class="name"><?php echo htmlspecialchars($experience['company_name']) ?: 'Not available'; ?></a>
                                                            <span class="time"><?php echo htmlspecialchars($experience['period_from']) ?: 'Not available'; ?> - <?php echo htmlspecialchars($experience['period_to']) ?: 'Not available'; ?> </span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <p>No experience information available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
