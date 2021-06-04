<!DOCTYPE html>
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
        $pagename = 'shop';
        include_once './common/commonpage.php';
        ?>
    </head>
    <body>
        <div class="top-search">
            <div class="container">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search">
                    <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
                </div>
            </div>
        </div>
        <div class="all-title-box">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 column">
                        <h2>Shop / Products</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                            <li class="breadcrumb-item active">Products</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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
                <h1 class="load-more">Load More </h1>
                <h4 style="padding: 10px;text-align: right">Showing <a id="rowww"></a> off <a id="totalpages"></a> entries</h4>
            </div>
        </div>
        <!-- End Shop Page -->
        <?php
        require("./common/footerscripting.php");
        ?>

        <script type="text/javascript">
            var crop_id;

            $('#userInfo').click(function () {
                $('body').addClass('on-side');
                $('.side').addClass('on');
            });
            $(document).on('click', '.view-variety', function () {
                var variety_id = $(this).next().text();
                localStorage.setItem("variety_id", variety_id);
            });

            $(document).ready(function () {
                var cropid = localStorage.getItem("cropid");
                crop_id = cropid;
                $.ajax({
                    url: "shop-detail-view.php",
                    type: "POST",
                    data: {
                        cropid: cropid
                    },
                    success: function (data) {
                        console.log(data);
                        $('#draw').html(data);
                    }
                });
            });

            $(window).scroll(function () {
                
                if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                    // ajax call get data from server and append to the div
                    var row = Number($('#rows').val());
                    var allcount = Number($('#all').val());
                    var rowperpage = Number($('#rowperpage').val());
                    row = row + rowperpage;
                    $('#rowww').html(row);
                    $('#totalpages').html(allcount);
                    if (row <= allcount) {
                        $("#rows").val(row);
                        $.ajax({
                            url: 'shop-detail-view.php',
                            type: 'post',
                            data: {rows: row, cropid: crop_id},
                            beforeSend: function () {
                                $(".load-more").text("Loading...");
                            },
                            success: function (response) {
                                // Setting little delay while displaying new content
                                setTimeout(function () {
                                    // appending posts after last post with class="post"
                                    $(".post:last").after(response).show().fadeIn("slow");
                                    var rowno = row + rowperpage;
                                    // checking row value is greater than allcount or not
                                    if (rowno > allcount) {
                                        
                                        // Change the text and background
                                        //$('.load-more').text("Hide");
                                        $(".load-more").text("Load more");
                                        //                                        $('.load-more').css("background", "darkorchid");
                                    } else {
                                        $(".load-more").text("Load more");
                                    }
                                }, 2000);
                            }
                        });
                    } else {

                        $('.load-more').text("Loading...");
                        var balrow = parseInt(row) - parseInt(allcount);
                        var lastpagerows = (parseInt(row) - parseInt(balrow));
                        $('#rowww').html(lastpagerows);
                        // Setting little delay while removing contents
                        setTimeout(function () {
                            // When row is greater than allcount then remove all class='post' element after 3 element
                            $('.post:nth-child(' + allcount + ')').nextAll('.post').remove();
                            // Reset the value of row
                            $("#row").val(0);
                            // Change the text and background
                            $('.load-more').text("Load more");
                            //                            $('.load-more').css("background", "#15a9ce");
                        }, 2000);
                    }
                }
            });
            // Load more data
            $(document).ready(function () {
                $('.load-more').click(function () {
                    var row = Number($('#rows').val());
                    var allcount = Number($('#all').val());
                    var rowperpage = Number($('#rowperpage').val());
                    row = row + rowperpage;
                    $('#rowww').html(row);
                    $('#totalpages').html(allcount);
                    if (row <= allcount) {
                        $("#rows").val(row);
                        $.ajax({
                            url: 'shop-detail-view.php',
                            type: 'post',
                            data: {rows: row, cropid: crop_id},
                            beforeSend: function () {
                                $(".load-more").text("Loading...");
                            },
                            success: function (response) {
                                // Setting little delay while displaying new content
                                setTimeout(function () {
                                    // appending posts after last post with class="post"
                                    $(".post:last").after(response).show().fadeIn("slow");
                                    var rowno = row + rowperpage;
                                    // checking row value is greater than allcount or not
                                    if (rowno > allcount) {
                                        // Change the text and background
                                        //$('.load-more').text("Hide");
                                        $(".load-more").text("Load more");
                                        //                                        $('.load-more').css("background", "darkorchid");
                                    } else {
                                        $(".load-more").text("Load more");
                                    }
                                }, 2000);
                            }
                        });
                    } else {
                        var balrow = parseInt(row) - parseInt(allcount);
                        var lastpagerows = (parseInt(row) - parseInt(balrow));
                        $('#rowww').html(lastpagerows);
                        $('.load-more').text("Loading...");
                        // Setting little delay while removing contents
                        setTimeout(function () {
                            // When row is greater than allcount then remove all class='post' element after 3 element
                            $('.post:nth-child(' + allcount + ')').nextAll('.post').remove();
                            // Reset the value of row
                            $("#row").val(0);
                            // Change the text and background
                            $('.load-more').text("Load more");
                            //                            $('.load-more').css("background", "#15a9ce");
                        }, 2000);
                    }
                });
            });

        </script>
    </body>
</html>