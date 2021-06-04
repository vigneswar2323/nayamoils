<html lang="en">
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

        

        $pagename = 'aboutus';
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
                        <h2>ABOUT US</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">About Us</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End All Title Box -->

        <!-- Start About Page  -->
        <div class="about-box-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 column767">
                        <div class="banner-frame"> <img class="img-fluid" src="images/bannerimg3.jpg" alt="" style="height: 400px;width: 100%"/>
                        </div>
                    </div>
                    <div class="col-lg-6 column767">
                        <h2 class="noo-sh-title-top">Who we are</h2>
                        <p>We heartly welcome you to the online portal which is very ease and awesomeness at the same time. Our family has been into the chekku oil business for 3 generations since 1971 and we have our own labs to help us test each batch of oils that are produced. </p>
                        <p>This ensures the purity & consistency of our oils that we produce and helps us be 100% confident in the product that we are selling. We are into the business of cold pressed oil with more than 5 decades (three generations).</p>
                        <p>We re-introduce a traditional theme to the oil market in a smarter way. Being a sustainable business, our focus is to enhance Human Health by allowing people to access a great range of quality oils and to bring and spread the traditional knowledge of chekku oils into the modern era. We specialize in bringing high quality and premium cooking oils in order to make our customers healthy, beauty and goodness.</p>
                        <!-- <a class="btn hvr-hover" href="#">Read More</a> -->
                    </div>
                </div>
                <div class="row my-5">
                    <div class="col-sm-6 col-lg-4">
                        <div class="service-block-inner">
                            <h3>We are Trusted</h3>
                            <p>Cold Pressed Pure Oil.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="service-block-inner">
                            <h3>We are Professional</h3>
                            <p>Cold Pressed Pure Oil</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="service-block-inner">
                            <h3>We are Expert</h3>
                            <p>Cold Pressed Pure Oil</p>
                        </div>
                    </div>
                </div>
                <!-- <div class="row my-4">
                    <div class="col-12">
                        <h2 class="noo-sh-title">Meet Our Team</h2>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="hover-team">
                            <div class="our-team"> <img src="images/img-1.jpg" alt="" />
                                <div class="team-content">
                                    <h3 class="title">Williamson</h3> <span class="post">Web Developer</span> </div>
                                <ul class="social">
                                    <li>
                                        <a href="#" class="fab fa-facebook"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-twitter"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-google-plus"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-youtube"></a>
                                    </li>
                                </ul>
                                <div class="icon"> <i class="fa fa-plus" aria-hidden="true"></i> </div>
                            </div>
                            <div class="team-description">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent urna diam, maximus ut ullamcorper quis, placerat id eros. Duis semper justo sed condimentum rutrum. Nunc tristique purus turpis. Maecenas vulputate. </p>
                            </div>
                            <hr class="my-0"> </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="hover-team">
                            <div class="our-team"> <img src="images/img-2.jpg" alt="" />
                                <div class="team-content">
                                    <h3 class="title">Kristiana</h3> <span class="post">Web Developer</span> </div>
                                <ul class="social">
                                    <li>
                                        <a href="#" class="fab fa-facebook"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-twitter"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-google-plus"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-youtube"></a>
                                    </li>
                                </ul>
                                <div class="icon"> <i class="fa fa-plus" aria-hidden="true"></i> </div>
                            </div>
                            <div class="team-description">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent urna diam, maximus ut ullamcorper quis, placerat id eros. Duis semper justo sed condimentum rutrum. Nunc tristique purus turpis. Maecenas vulputate. </p>
                            </div>
                            <hr class="my-0"> </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="hover-team">
                            <div class="our-team"> <img src="images/img-3.jpg" alt="" />
                                <div class="team-content">
                                    <h3 class="title">Steve Thomas</h3> <span class="post">Web Developer</span> </div>
                                <ul class="social">
                                    <li>
                                        <a href="#" class="fab fa-facebook"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-twitter"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-google-plus"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-youtube"></a>
                                    </li>
                                </ul>
                                <div class="icon"> <i class="fa fa-plus" aria-hidden="true"></i> </div>
                            </div>
                            <div class="team-description">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent urna diam, maximus ut ullamcorper quis, placerat id eros. Duis semper justo sed condimentum rutrum. Nunc tristique purus turpis. Maecenas vulputate. </p>
                            </div>
                            <hr class="my-0"> </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="hover-team">
                            <div class="our-team"> <img src="images/img-1.jpg" alt="" />
                                <div class="team-content">
                                    <h3 class="title">Williamson</h3> <span class="post">Web Developer</span> </div>
                                <ul class="social">
                                    <li>
                                        <a href="#" class="fab fa-facebook"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-twitter"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-google-plus"></a>
                                    </li>
                                    <li>
                                        <a href="#" class="fab fa-youtube"></a>
                                    </li>
                                </ul>
                                <div class="icon"> <i class="fa fa-plus" aria-hidden="true"></i> </div>
                            </div>
                            <div class="team-description">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent urna diam, maximus ut ullamcorper quis, placerat id eros. Duis semper justo sed condimentum rutrum. Nunc tristique purus turpis. Maecenas vulputate. </p>
                            </div>
                            <hr class="my-0"> </div>
                    </div>
                </div> -->
            </div>
        </div>
        <!-- End About Page -->

        <?php
        require("./common/footerscripting.php");
        ?>

        <script type="text/javascript">
            $('#userInfo').click(function () {
                $('body').addClass('on-side');
                $('.side').addClass('on');
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