<?php
require_once '../common/ApplicationConstant.php';
include_once("../connection/DBConnection.php");
$DBConnection = new DBConnection();
$locationurl = SITE_URL_ADMIN;
session_start();
$message = "";
$CPOMANYID = constant('CPOMANYID');

if (isset($_SESSION["message"]) ? $_SESSION["message"] : '') {
    $message = $_SESSION["message"];
}

if ($_SESSION['userdetails']) {
    $userdetails = $_SESSION["userdetails"];
    $adminname = $userdetails['username'];
    $firstname = $userdetails['firstname'];
    $lastname = $userdetails['lastname'];
    $welcometitle = $firstname . ' ' . $lastname;
} else {
    header("location:$locationurl/adminLogin.php");
}

$logoqry = "SELECT * FROM tbl_imagedetails WHERE image_flag='L' AND companyid='$CPOMANYID'";
$tmp = mysqli_query($conn, $logoqry);
while ($row = mysqli_fetch_assoc($tmp)) {
    $logopath = $row['image_path'];
}
?>
<!DOCTYPE html>
<?php include './common/header.php'; ?>
<html lang="en">
    <head>
        <title>Admin - Home</title>
    </head>

    <body>
        <section id="container" > 
            <!--sidebar start-->
            <?php include './common/leftnavigationbar.php'; ?>
            <!--sidebar end-->

            <!-- **********************************************************************************************************************************************************
            MAIN CONTENT
            *********************************************************************************************************************************************************** -->
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">

                    <div class="row">
                        <div class="col-lg-9 main-chart">
                            <div class="row mt">
                                <!-- SERVER STATUS PANELS -->
                                <div class="col-md-4 col-sm-4 mb">
                                    <div class="white-panel pn donut-chart">
                                        <div class="white-header">
                                            <h5>My Dashboard</h5>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6 goleft">
                                                <p><img src="../<?php echo $logopath ?>" width="100" height="100" class="logo" alt=""></p>
                                            </div>
                                        </div>
                                        <canvas id="serverstatus01" height="120" width="120"></canvas>
                                        <script>
                                            var doughnutData = [
                                                {
                                                    value: 70,
                                                    color: "#68dff0"
                                                },
                                                {
                                                    value: 30,
                                                    color: "#fdfdfd"
                                                }
                                            ];
                                            var myDoughnut = new Chart(document.getElementById("serverstatus01").getContext("2d")).Doughnut(doughnutData);
                                        </script>
                                    </div><! --/grey-panel -->
                                </div><!-- /col-md-4-->


                                <div class="col-md-4 col-sm-4 mb">
                                    <div class="white-panel pn">
                                        <div class="white-header">
                                            <h5></h5>
                                        </div>
                                        <div class="centered">
                                            <canvas id="canvas" width="200" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>  

                                <div class="col-md-4 mb">
                                    <canvas id="canvas" width="250" height="250"></canvas>
                                </div><!-- /col-md-4 -->


                            </div>                                       			
                            <div class="row">
                                <!-- TWITTER PANEL -->
                                <div class="col-md-4 mb">
                                </div>
                            </div>


                            <div class="row mt">


                            </div>

                        </div>


                        <!-- **********************************************************************************************************************************************************
                        RIGHT SIDEBAR CONTENT
                        *********************************************************************************************************************************************************** -->                  

                        <div class="col-lg-3 ds">
                            <!--COMPLETED ACTIONS DONUTS CHART-->


                            <!-- USERS ONLINE SECTION -->

                            <!-- First Member -->

                            <!-- Second Member -->

                            <!-- Third Member -->

                            <!-- Fourth Member -->

                            <!-- Fifth Member -->


                            <!-- CALENDAR-->
                            <div id="calendar" class="mb">
                                <div class="panel green-panel no-margin">
                                    <div class="panel-body">
                                        <div id="date-popover" class="popover top" style="cursor: pointer; disadding: block; margin-left: 33%; margin-top: -50px; width: 175px;">
                                            <div class="arrow"></div>
                                            <h3 class="popover-title" style="disadding: none;"></h3>
                                            <div id="date-popover-content" class="popover-content"></div>
                                        </div>
                                        <div id="my-calendar"></div>
                                    </div>
                                </div>
                            </div><!-- / calendar -->

                        </div><!-- /col-lg-3 -->
                    </div><! --/row -->
                </section>
            </section>

            <!--main content end-->
            <!--footer start-->

            <!--footer end-->
        </section>

        <?php include './common/footer2.php'; ?>
        <!--script for this page-->
        <script src="assets/js/sparkline-chart.js"></script>    
        <script src="assets/js/zabuto_calendar.js"></script>	

        <script type="text/javascript">
                                            $(document).ready(function () {
                                                var unique_id = $.gritter.add({
                                                    // (string | mandatory) the heading of the notification
                                                    title: 'Welcome to <?php echo $welcometitle ?>!',
                                                    // (string | mandatory) the text inside the notification
                                                    text: '<a>hi, <?php echo $adminname ?><a>',
                                                    // (string | optional) the image to display on the left
                                                    image: 'assets/img/adminicon.png',
                                                    // (bool | optional) if you want it to fade out on its own or just sit there
                                                    sticky: true,
                                                    // (int | optional) the time you want it to be alive for before fading out
                                                    time: '',
                                                    // (string | optional) the class name you want to apply to that specific message
                                                    class_name: 'my-sticky-class'
                                                });

                                                return false;
                                            });

                                            $(document).ready(function () {
                                                $("#date-popover").popover({html: true, trigger: "manual"});
                                                $("#date-popover").hide();
                                                $("#date-popover").click(function (e) {
                                                    $(this).hide();
                                                });

                                                $("#my-calendar").zabuto_calendar({
                                                    action: function () {
                                                        return myDateFunction(this.id, false);
                                                    },
                                                    action_nav: function () {
                                                        return myNavFunction(this.id);
                                                    },
                                                    ajax: {
                                                        url: "show_data.php?action=1",
                                                        modal: true
                                                    },
                                                    legend: [
                                                        {type: "text", label: "Special event", badge: "00"},
                                                        {type: "block", label: "Regular event", }
                                                    ]
                                                });
                                            });


                                            function myNavFunction(id) {
                                                $("#date-popover").hide();
                                                var nav = $("#" + id).data("navigation");
                                                var to = $("#" + id).data("to");
                                                console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
                                            }


                                            var canvas = document.getElementById("canvas");
                                            var ctx = canvas.getContext("2d");
                                            var radius = canvas.height / 2;
                                            ctx.translate(radius, radius);
                                            radius = radius * 0.90
                                            setInterval(drawClock, 1000);

                                            function drawClock() {
                                                drawFace(ctx, radius);
                                                drawNumbers(ctx, radius);
                                                drawTime(ctx, radius);
                                            }

                                            function drawFace(ctx, radius) {
                                                var grad;
                                                ctx.beginPath();
                                                ctx.arc(0, 0, radius, 0, 2 * Math.PI);
                                                ctx.fillStyle = 'white';
                                                ctx.fill();
                                                grad = ctx.createRadialGradient(0, 0, radius * 0.95, 0, 0, radius * 1.05);
                                                grad.addColorStop(0, '#333');
                                                grad.addColorStop(0.5, 'white');
                                                grad.addColorStop(1, '#333');
                                                ctx.strokeStyle = grad;
                                                ctx.lineWidth = radius * 0.1;
                                                ctx.stroke();
                                                ctx.beginPath();
                                                ctx.arc(0, 0, radius * 0.1, 0, 2 * Math.PI);
                                                ctx.fillStyle = '#333';
                                                ctx.fill();
                                            }

                                            function drawNumbers(ctx, radius) {
                                                var ang;
                                                var num;
                                                ctx.font = radius * 0.15 + "px arial";
                                                ctx.textBaseline = "middle";
                                                ctx.textAlign = "center";
                                                for (num = 1; num < 13; num++) {
                                                    ang = num * Math.PI / 6;
                                                    ctx.rotate(ang);
                                                    ctx.translate(0, -radius * 0.85);
                                                    ctx.rotate(-ang);
                                                    ctx.fillText(num.toString(), 0, 0);
                                                    ctx.rotate(ang);
                                                    ctx.translate(0, radius * 0.85);
                                                    ctx.rotate(-ang);
                                                }
                                            }

                                            function drawTime(ctx, radius) {
                                                var now = new Date();
                                                var hour = now.getHours();
                                                var minute = now.getMinutes();
                                                var second = now.getSeconds();
                                                //hour
                                                hour = hour % 12;
                                                hour = (hour * Math.PI / 6) +
                                                        (minute * Math.PI / (6 * 60)) +
                                                        (second * Math.PI / (360 * 60));
                                                drawHand(ctx, hour, radius * 0.5, radius * 0.07);
                                                //minute
                                                minute = (minute * Math.PI / 30) + (second * Math.PI / (30 * 60));
                                                drawHand(ctx, minute, radius * 0.8, radius * 0.07);
                                                // second
                                                second = (second * Math.PI / 30);
                                                drawHand(ctx, second, radius * 0.9, radius * 0.02);
                                            }

                                            function drawHand(ctx, pos, length, width) {
                                                ctx.beginPath();
                                                ctx.lineWidth = width;
                                                ctx.lineCap = "round";
                                                ctx.moveTo(0, 0);
                                                ctx.rotate(pos);
                                                ctx.lineTo(0, -length);
                                                ctx.stroke();
                                                ctx.rotate(-pos);
                                            }
        </script>
    </body>
</html>
