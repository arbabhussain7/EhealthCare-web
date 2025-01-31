<div class="header">
    <div class="header-left">
        <a href="index" class="logo">
            <img src="../public/img/logo.png" width="35" height="35" alt=""> <span>Preclinic</span>
        </a>
    </div>
    <a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
    <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
    <ul class="nav user-menu float-right">


        <li class="nav-item dropdown has-arrow">
            <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
							<img class="rounded-circle" src="../public/img/user.jpg" width="24" alt="Admin">
							<span class="status online"></span>
						</span>
                <span><?php echo $_SESSION["admin_email"];?></span>
            </a>
            <div class="dropdown-menu">

                <a class="dropdown-item" href="logout">Logout</a>
            </div>
        </li>
    </ul>
    <div class="dropdown mobile-user-menu float-right">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="logout">Logout</a>

        </div>
    </div>
</div>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">Main</li>
                <li class="active">
                    <a href="dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <li>
                    <a href="doctors"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
                </li>
                <li>
                    <a href="patients"><i class="fa fa-wheelchair"></i> <span>Patients</span></a>
                </li>
                <li>
                    <a href="appointments"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                </li>
                <li>
                    <a href="add-schedule"><i class="fa fa-calendar-check-o"></i> <span>Create Doctor Schedule</span></a>
                </li>
                <li>
                    <a href="schedule"><i class="fa fa-calendar-check-o"></i> <span>Doctor Schedule</span></a>
                </li>
                <li>
                    <a href="payment"><i class="fa fa-money"></i> <span>Payments </span></a>
                </li>
                <li>
                    <a href="logout"><i class="fa fa-calendar-check-o"></i> <span>Logout</span></a>
                </li>

            </ul>
        </div>
    </div>
</div>