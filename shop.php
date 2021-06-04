<!DOCTYPE html>
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
    $pagename = 'shop';
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
                    <h2>Shop / Categories</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Shop</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->
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
    <!-- End Shop Page -->
    <?php
    require("./common/footerscripting.php");
    ?>
    <script type="text/javascript">
        $(document).on('click', '.view-variety', function () {
            var cropid = $(this).next().text();
            localStorage.setItem("cropid", cropid);
        });

        $(document).ready(function () {
            $.ajax({
                url: "shop-view.php",
                type: "POST",
                data: {},
                success: function (data) {
                    console.log(data);
                    $('#draw').html(data);
                }
            });
            $('#userInfo').click(function () {
                $('body').addClass('on-side');
                $('.side').addClass('on');
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