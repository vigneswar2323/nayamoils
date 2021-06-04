<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/responsive.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <?php
        include_once("./connection/DBConnection.php");
        include './common/ApplicationConstant.php';

        $DBConnection = new DBConnection();
        $siteurl = constant('SITE_URL');
        session_start();
        ?>
    </head>
    <body>
        <div class="col-xl-12 col-lg-12 col-sm-12 col-xs-12 bg">
            <button type="button" class="btn btn-primary hidden" data-toggle="modal" data-target="#myModal" id="popupBtn">
                Open modal
            </button>
            <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content alert-warning">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <div class="alert">
                                <strong>Warning!</strong> <br><p id="msg-lbl">This alert box could indicate a successful or positive action.<p>
                            </div>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <!--  <div class="modal-body">
                           Modal body..
                         </div> -->

                        <!-- Modal footer -->


                    </div>
                </div>
            </div>
            <div class="login-page">
                <div class="col-xl-4 col-lg-4 col-sm-12 col-xs-12 offset-md-4">
                    <div class= "text-center">
                        <img src="images/logo_img.png" class="logo" alt="">
                    </div>
                    <div class="register-block">
                        <form class="form" method="post" name="login" id="autologin" autocomplete="off">
                            <div class="col-md-8 offset-md-2">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mobile Number</label>
                                    <input type="text" id="mobile" class="form-control" name="mobile" required data-error="Please enter your mobile number">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-8 offset-md-2">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" placeholder=""id="password" class="form-control" name="password" required data-error="Please enter your password">
                                    <div class="help-block with-errors"></div>
                                    <div>
                                        <a href="register.php">Create Account</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="submit-button text-center">
                                        <input type="button" name="save" class="btn hvr-hover text-center" value="Login" id="butlogin"> 
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ALL JS FILES -->
<!-- <script src="https://kit.fontawesome.com/f5c60aa700.js" crossorigin="anonymous"></script> -->
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">

            $(document).ready(function () {
                $('#butlogin').on('click', function () {
                    var mobile = $('#mobile').val();
                    var password = $('#password').val();
                    var session_id = localStorage.getItem("session_id");
                    var variety_id = localStorage.getItem("variety_id");
                    if (mobile != "" && password != "") {
                        $.ajax({
                            url: "login-insert.php",
                            type: "POST",
                            data: {
                                mobile: mobile,
                                password: password,
                                session_id: session_id,
                                variety_id: variety_id
                            },
                            cache: false, success: function (data) {
                                var data = JSON.parse(data);
                                
                                if (data.statusCode == 200) {
                                    var session_id = data.sessionid;
                                    localStorage.setItem("session_id", session_id);
                                   window.location.href = "index.php";
                                } else if (data.statusCode == 201) {
                                    $('#msg-lbl').text("Invalid Credentials!");
                                    $('#popupBtn').click();

                                } else if (data.statusCode == 202) {
                                    window.location.href = "register.php";
                                }
                            }
                        });
                    } else {
                        $('#msg-lbl').text('Please fill all the field !');
                        $('#popupBtn').click();

                    }
                });
            });
        </script>
    </body>
</html>