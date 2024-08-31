<?php
$base_url = "http://localhost/care";
?>
<!DOCTYPE html>
<html lang="en">

<!-- login23:11-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $base_url; ?>/public/img/favicon.ico">
    <title><?php echo isset($title) ? $title : 'Health Care Admin'; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/public/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/public/css/adminStyle.css">
    <!--[if lt IE 9]>
    <script src="<?php echo $base_url; ?>/public/js/html5shiv.min.js"></script>
    <script src="<?php echo $base_url; ?>/public/js/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="https://common.olemiss.edu/_js/sweet-alert/sweet-alert.css">

</head>

<body>

<main>
    <div class="main-wrapper">
    <?php echo $content; ?>
    </div>
</main>


</div>
<div class="sidebar-overlay" data-reff=""></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="<?php echo $base_url; ?>/public/js/popper.min.js"></script>
<script src="<?php echo $base_url; ?>/public/js/bootstrap.min.js"></script>
<script src="<?php echo $base_url; ?>/public/js/jquery.slimscroll.js"></script>
<script src="<?php echo $base_url; ?>/public/js/Chart.bundle.js"></script>
<script src="<?php echo $base_url; ?>/public/js/chart.js"></script>
<script src="<?php echo $base_url; ?>/public/js/app.js"></script>
<script src="https://common.olemiss.edu/_js/sweet-alert/sweet-alert.min.js"></script>

</body>


</html>