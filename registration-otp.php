<!DOCTYPE html>
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '../Dharaniseeds/common/headerscripting.php'); ?>
    <?php
        include_once("./connection/DBConnection.php");
        include './common/ApplicationConstant.php';
        
        $DBConnection = new DBConnection();
        $siteurl = constant('SITE_URL');
        session_start();
        ?>
</head>
<body>
    <!-- Start Main Top -->
    <div class="main-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="right-phone-box">
                        <p>Call US :- <a href="#"> +11 900 800 100</a></p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="login-box">
                        <select id="basic" class="selectpicker show-tick form-control" data-placeholder="Sign In">
                            <option>Register Here</option>
                            <option>Sign In</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Top -->
    <!-- Start Main Top -->
    <header class="main-header">
        <!-- Start Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav">
            <div class="container">
                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="index.html"><img src="images/logo_img.png" class="logo" alt=""></a>
                </div>
                <!-- End Header Navigation -->
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav ml-auto" data-in="fadeInDown" data-out="fadeOutUp">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>
            <!-- Start Side Menu -->
            <!-- End Side Menu -->
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Main Top -->
    <!-- Start Top Search -->
    <div class="top-search">
        <div class="container">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search">
                <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
    <!-- End Top Search -->
    <!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Home / Register</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Register</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->
    <!-- Start Register Page  -->
    <div class="shop-box-inner">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-sm-12 col-xs-12 offset-md-2">
                    <div class="register-block">
                        <h4 class="reg-title">Verification code</h4>
                        <p class="reg-cont">Enter the code generated on your mobile device below to log in!</p>
                        <form class="form" method="post" name="validotp" id="validotp" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6 offset-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="OTP" name="OTP" placeholder="Enter OTP..." required data-error="Please enter OTP">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <p class="error-msg hidden">OTP Mismatch.Please Click Resend OTP to regenrate.</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="submit-button">
                                        <button class="btn hvr-hover" id="submit" type="button">Submit</button>
                                        <div id="msgSubmit" class="h3 text-center"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 offset-md-2">
                                <div class="submit-button text-center">
                                    <button class="btn" id="resend" type="button">Resend OTP</button>
                                    <div id="msgSubmit" class="h3 text-center hidden"></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                    </div>
                    </form>              
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="footer-copyright">
        <p class="footer-company">All Rights Reserved. &copy; 2020 <a href="#"> Dharani Seeds</a> 
    </div>
    <!-- End Register Page -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- ALL PLUGINS -->
    <script src="js/jquery.superslides.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/inewsticker.js"></script>
    <script src="js/bootsnav.js."></script>
    <script src="js/images-loded.min.js"></script>
    <script src="js/isotope.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/baguetteBox.min.js"></script>
    <script src="js/form-validator.min.js"></script>
    <script src="js/contact-form-script.js"></script>
    <script src="js/custom.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function() {
        $('#submit').on('click', function() {
            var otp = $("#OTP").val();
            var session_id=localStorage.getItem("session_id");
            var variety_id=localStorage.getItem("variety_id");
            var qty="1";
            if(otp!="" && session_id!=""){
                $.ajax({
                    url: "otp-insert.php",
                    type: "POST",
                    data: {
                        otp: otp,
                        session_id: session_id,
                        variety_id: variety_id,
                        qty: qty           
                    },
                    cache: false,
                    success: function(data){
                        var data = JSON.parse(data);
                        if(data.statusCode==200){
                            $('#validotp').find('input:text').val('');
                            window.location.href = "login.php";                  
                        }
                        else if(data.statusCode==202){
                           window.location.href = "registration-otp.php";
                        }
                    }
                });
            }       
            else{
                alert('Please fill all the field !');
            }
        });
        });
    </script>
</body>
</html>