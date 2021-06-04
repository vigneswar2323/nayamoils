<html lang="en">
    <head>
        <?php
        include_once './common/headerscripting.php';
        include_once("./connection/DBConnection.php");
        include './common/ApplicationConstant.php';
        $DBConnection = new DBConnection();
        $siteurl = constant('SITE_URL');
        $CPOMANYID = constant('CPOMANYID');
        $variety_id = isset($_GET['id']) ? $_GET['id'] : '';

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
        <button type="button" class="btn btn-primary hidden" data-toggle="modal" data-target="#myModal" id="popupBtn">
            Open modal
        </button>

        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog alert-warning">
                <div class="modal-content warning-modal">                    
                    <div class="modal-body">
                        <div class="modal-header" style="text-align: center;">
                            <h2>HOW TO ORDER</h2>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <ul>
                            <li>* We are NOT an E-Commerce but a leading Manufacturer of Cold Press Oils.</li>
                            <li>* If you have any query or wish to order then simply call us on below phone no.</li>
                            <li>* Purchase Order : We accept PO from Office, Corporate, Institute & Hospitals.</li>
                        </ul>
                        <table class='table table-bordered' id='prod-detail-table' style="background: white;">
                            <thead><tr><th style="text-align: center;text-decoration: underline" colspan="2">We are available Between 10 AM & 8 PM Monday - Saturday except on Major Holidays</th></tr></thead>
                            <tbody>
                                <tr><td>CHENNAI</td><td>044-0000000</td></tr>
                                <tr><td>BANGALORE</td><td>044-0000000</td></tr>
                                <tr><td>MUMBAI</td><td>044-0000000</td></tr>
                                <tr><td>HYDERABAD</td><td>044-0000000</td></tr>
                            </tbody>
                            <tfoot>
                                <tr style="background: #ec4247;"><td style="color:#ffffff;">EMAIL US</td><td style="color:#ffffff;"><?= $E_MAILANDSITE ?></td></tr>
                            </tfoot>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
        <!-- Start All Title Box -->
        <div class="all-title-box">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 column">
                        <h2>Product Details</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                            <li class="breadcrumb-item"><a href="shop-detail.php">Products</a></li>
                            <li class="breadcrumb-item active"> Product Details  </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End All Title Box -->
        <!-- Start Contact Us  -->
        <div class="contact-box-main">
            <div class="container">
                <div class="row" id="table">
                    <!-- <div id="table"></div> -->
                </div>
            </div>
        </div>

    </div>
    <!-- End Cart -->
    <?php
    require("./common/footerscripting.php");
    ?>
    <script type="text/javascript">
        /*PRODUCT VIEW LOAD PAGE*/
        $(document).ready(function () {
            var variety_id = '<?= $variety_id ?>';
            if (variety_id == '') {
                variety_id = localStorage.getItem("variety_id");
            }


            $.ajax({
                url: "product-details-view.php",
                type: "POST",
                data: {
                    variety_id: variety_id
                },
                success: function (data) {
                    console.log(data);
                    $('#table').html(data);
                }
            });
        });

        $('#userInfo').click(function () {
            $('body').addClass('on-side');
            $('.side').addClass('on');
        });

        $(document).on('click', '#addCart', function () {

            //$('#msg-lbl').text('HOW TO ORDER')
            $('#popupBtn').click();

        });

    </script>
</body>
</html>