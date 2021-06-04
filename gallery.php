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
        $pagename = 'gallery';
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
                        <h2>Gallery</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Gallery</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End All Title Box -->

        <!-- Start Gallery  -->
        <div class="products-box">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 column">
                        <div class="title-all text-center">
                            <h1>Our Gallery</h1>
                            <p></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 column">
                        <div class="special-menu text-center">
                            <div class="button-group filter-button-group">
                                <?php
                                $sql_row = "SELECT * FROM referencecodes where parentcode=4 order by referencecode asc";
                                $result_row = $conn->query($sql_row);
                                while ($rows = mysqli_fetch_assoc($result_row)) {
                                    echo '<button data-filter="*" class="menu-filter" value="' . $rows['referencecode'] . '">' . $rows['description'] . '</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Start Shop Page  -->
                <div class="shop-box-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12 col-xs-12 shop-content-right column">
                                <div class="right-product-box">
                                    <div class="product-categorie-box">
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade show active" id="grid-view">
                                                <div class="row" id="draw">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- End Gallery  -->

            </div>
        </div>


        <?php
        require("./common/footerscripting.php");
        ?>
        <script type="text/javascript">
            $('#userInfo').click(function () {
                $('body').addClass('on-side');
                $('.side').addClass('on');
            });

            var galleryflag;
            $(document).on('click', '.menu-filter', function () {
                galleryflag = $(this).val();
                $.ajax({
                    url: "gallery-view.php",
                    type: "POST",
                    data: {
                        galleryflag: galleryflag
                    },
                    success: function (data) {
                        console.log(data);
                        $('#draw').html(data);
                    }
                });
            });

            $(document).ready(function () {
                $.ajax({
                    url: "gallery-view.php",
                    type: "POST",
                    data: {
                        galleryflag: 1
                    },
                    success: function (data) {
                        console.log(data);
                        $('#draw').html(data);
                    }
                });

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