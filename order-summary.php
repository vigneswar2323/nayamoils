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
    $pagename = 'mycart';
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
                <div class="col-lg-12">
                    <h2>Order Summary</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                        <li class="breadcrumb-item active">Summary</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->
    <!-- Start Contact Us  -->
    <div class="container">
        <div class="row">
            <button type="button" class="btn btn-primary hidden" data-toggle="modal" data-target="#myModal" id="popupBtn">
                Open modal
            </button>
            <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog alert-warning">
                    <div class="modal-content warning-modal">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <div class="alert alert-warning">
                                <br><p id="msg-lbl">This alert box could indicate a successful or positive action.<p>
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

            <div class='col-lg-12 col-sm-12'>
                <div class='cart-box1'>
                    <h4 class="order-head">Order Details</h4>
                    <div id="table">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Cart -->
    <?php require("./common/footerscripting.php"); ?>
    <script type="text/javascript">
        $(document).on('click', '.inc-btn', function () {

            var qtyCount = parseInt($(this).closest('.qty').find('.qty-inc').text());
            qtyCount = qtyCount + 1;
            $(this).closest('.qty').find('.qty-inc').text(qtyCount);
            var price = $(this).closest('.prod-li').find('.prod-price').text();
            var totQty = qtyCount * price;
            $(this).closest('.prod-li').find('.tot-price').text(totQty);

            var sum = 0;
            $(".tot-price").each(function (i, e) {
                sum += +$(e).text();
            });
            $(".total").text(sum);
        });

        $(document).on('click', '.dec-btn', function () {

            var qtyCount = parseInt($(this).closest('.qty').find('.qty-inc').text());
            if (qtyCount > 1) {
                qtyCount = qtyCount - 1;
            }
            $(this).closest('.qty').find('.qty-inc').text(qtyCount);
            var price = $(this).closest('.prod-li').find('.prod-price').text();
            var totQty = qtyCount * price;
            $(this).closest('.prod-li').find('.tot-price').text(totQty);

            var sum = 0;
            $(".tot-price").each(function (i, e) {
                sum += +$(e).text();
            });
            $(".total").text(sum);
        });


        $(document).on('click', '#order_summary_cancel', function () {

            var order_summary_id = $('#order_summary_id').text();
            $.ajax({
                url: "order-summary-delete.php",
                method: "POST",
                data: {order_summary_id: order_summary_id},
                success: function (dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        $('#msg-lbl').text('Item removed from your cart')
                        $('#popupBtn').click();
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                }
            })
        });



        $(document).ready(function () {
            var session_id = localStorage.getItem("session_id");
            $.ajax({
                url: "order-summary-view.php",
                type: "POST",
                data: {
                    session_id: session_id
                },
                success: function (data) {
                    console.log(data);
                    $('#table').html(data);
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


            var sum = 0;
            $(".price").each(function () {
                sum += +$(this).text();
            });
            $(".total").text(sum);

        });

        $('#userInfo').click(function () {
            $('body').addClass('on-side');
            $('.side').addClass('on');
        });
        $(document).on('click', '#Paymentsave', function () {
            $("#butsave").attr("disabled", "disabled");
            var data = $('#fupForm').searilize();
            console.log(data);
            return;
            /*var lastname = $('#lastname').val();
             var email = $('#email').val();
             var mobile = $('#mobile').val();
             var password = $('#password').val();
             var confirm_password = $('#confirm_password').val();
             var OTP = Math.floor(100000 + Math.random() * 900000);*/
            if (firstname != "" && lastname != "" && email != "" && mobile != "" && password != "" && confirm_password != "") {
                $.ajax({
                    url: "register-insert.php",
                    type: "POST",
                    data: {
                        firstname: firstname,
                        lastname: lastname,
                        email: email,
                        mobile: mobile,
                        password: password,
                        OTP: OTP
                    },
                    cache: false,
                    success: function (dataResult) {
                        var dataResult = JSON.parse(dataResult);
                        console.log(dataResult);
                        if (dataResult.statusCode == 200) {
                            $("#butsave").removeAttr("disabled");
                            $('#fupForm').find('input:text').val('');
                            alert(dataResult.otp_gen);
                            window.location.href = "registration-otp.php";
                        } else if (dataResult.statusCode == 201) {
                            alert("Error occured !");
                        } else if (dataResult.statusCode == 202) {
                            alert("Is Exist !");
                            location.reload();
                        }

                    }
                });
            } else {
                alert('Please fill all the field !');
            }
        });

    </script>
</body>
</html>