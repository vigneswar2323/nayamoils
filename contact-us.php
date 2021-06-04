<html>
    <head>
        <?php
        include_once './common/headerscripting.php';
        include_once("./connection/DBConnection.php");
        include './common/ApplicationConstant.php';
        $DBConnection = new DBConnection();
        $siteurl = constant('SITE_URL');
        $CPOMANYID = constant('CPOMANYID');

        $logoqry = "SELECT * FROM tbl_imagedetails WHERE image_flag='L' AND companyid='$CPOMANYID'";
        $tmp = mysqli_query($conn, $logoqry);
        while ($row = mysqli_fetch_assoc($tmp)) {
            $logopath = $row['image_path'];
        }
        $pagename = 'contactus';
        include_once './common/commonpage.php';
        ?>
    </head>
    <body>
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
                    <div class="col-lg-12 column">
                        <h2>Contact Us</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"> Contact Us </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End All Title Box -->

        <!-- Start Contact Us  -->
        <div class="contact-box-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-sm-12 column">
                        <div class="contact-form-right">
                            <h2>GET IN TOUCH</h2>
                            <p>Cold Pressed Pure Oil </p>
                            <form id="contactForm" method="post">
                                <div class="row">
                                    <div class="col-md-12 column">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required data-error="Please enter your name">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 column">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Your Mobile" required data-error="Please enter your mobile no">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 column">
                                        <div class="form-group">
                                            <input type="text" class="form-control"  id="email" name="email" placeholder="Your Email" required data-error="Please enter your email">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 column">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required data-error="Please enter your Subject">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 column">
                                        <div class="form-group">
                                            <textarea class="form-control" id="message" name="message" placeholder="Your Message" rows="4" data-error="Write your message" required></textarea>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="submit-button text-center">
                                            <button class="btn" id="submit" type="submit">Send Message</button>
                                            <div id="msgSubmit" class="h3 text-center hidden"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12 column">
                        <div class="contact-info-left">
                            <h2>CONTACT INFO</h2>
                            <p>Cold Pressed Pure Oil</p>
                            <ul>
                                <li>
                                    <p><i class="fas fa-map-marker-alt"></i>Address: No.XX,<br> XXXXXXXX,
                                        <br>XXXXXXX, <br> XXXXXX-000000,
                                        <br>Tamilnadu. </p>
                                </li>
                                <li>
                                    <p><i class="fas fa-phone-square"></i>Phone: <a href="tel:+1-888705770"><?= $CONTACT_NO ?></a></p>
                                </li>
                                <li>
                                    <p><i class="fas fa-envelope"></i>Email: <a href="#"> <?= $E_MAILANDSITE ?></a></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 column">
                        <iframe class="iframestyle" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=1%20Grafton%20Street,%20Dublin,%20Ireland+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"  frameborder="0" style="border:2px solid #016C40;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Cart -->
        <?php
        require("./common/footerscripting.php");
        ?>
        <script type="text/javascript">
            $('#userInfo').click(function () {
                $('body').addClass('on-side');
                $('.side').addClass('on');
            });
            
            $(document).on('click', '#submit', function () {
                var name = $('#name').val();
                var mobile = $('#mobile').val();
                var email = $('#email').val();
                var subject = $('#subject').val();
                var message = $('#message').val();
                var method = 'saveContact';

                $.ajax({
                    url: "action/action.php",
                    type: "POST",
                    data: {
                        method: method,
                        name: name,
                        mobile: mobile,
                        email: email,
                        subject: subject,
                        message: message
                    },
                    success: function (data) {
                        alert(data)
                    }
                });

            });
            
            
            $(document).ready(function () {
                var session_id = localStorage.getItem("session_id");

                $.ajax({
                    url: "side-menu-view.php",
                    type: "POST",
                    data: {
                        session_id: session_id
                    },
                    success: function (data) {
                        $('#menudraw').html(data);
                    }
                });
            });
        </script>
    </body>

</html>