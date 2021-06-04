<?php
require_once '../common/ApplicationConstant.php';
$title = SITE_TITLE;
?>
<header class="header black-bg">
    <title>Admin - Nayam Oils</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" />   



    <!-- Bootstrap core CSS -->

    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">    

    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">


<!--<script src="assets/js/jquery.js"></script>
<script src="assets/js/jquery-1.8.3.min.js"></script>-->
    <script src="assets/js/common-scripts.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="assets/js/jquery.sparkline.js"></script>
    <script type="text/javascript" src="<?php echo $locationurl; ?>/assets/js/common-scripts.js"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="assets/js/gritter-conf.js"></script>

    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">-->


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="assets/css/tabcss.css">

    
    <!-- <msdropdown> -->
    <link rel="stylesheet" type="text/css" href="assets/css/msdropdown/dd.css" />
    <script src="assets/js/msdropdown/jquery.dd.js"></script>
    <!-- </msdropdown> -->

    <link rel="stylesheet" type="text/css" href="assets/css/msdropdown/skin2.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/msdropdown/flags.css" />


    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    <!--logo start-->
    <a href="index.php" class="logo"><b><?php echo $title ?></b></a>
    <div class="top-menu">
        <ul class="nav pull-right top-menu">
            <li><a class="logout" href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>
<script>

    function backbtn(pagename) {
        document.forms[0].action = "<?php echo $locationurl; ?>/" + pagename;
        document.forms[0].submit();
    }
    //progress function
    function startloader() {
        document.getElementById("loader").style.display = "";
    }
    function stoploader() {
        document.getElementById("loader").style.display = "none";
    }
</script>