<html>
    <head>
        <?php
        include_once './common/headerscripting.php';
        include_once("./connection/DBConnection.php");
        include './common/ApplicationConstant.php';
        session_start();
        $DBConnection = new DBConnection();
        $siteurl = constant('SITE_URL');
        $CPOMANYID = constant('CPOMANYID');
        $SITE_TITLE = constant('SITE_TITLE');
        $APPNOTIFICATION = constant('APPNOTIFICATION');
        $isbloghide = 1;
        $cropid = '';

        $logoqry = "SELECT * FROM tbl_imagedetails WHERE image_flag='L' AND companyid='$CPOMANYID'";
        $tmp = mysqli_query($conn, $logoqry);
        while ($row = mysqli_fetch_assoc($tmp)) {
            $logopath = $row['image_path'];
        }

        //get banner image show or hide
        $bannerqry = "SELECT * FROM referencecodes WHERE parentcode='6'";
        $tmp = mysqli_query($conn, $bannerqry);
        while ($row = mysqli_fetch_assoc($tmp)) {
            $isbanner = $row['isuserdriven'];
        }

        //get app announcement show or hide
        $announcementqry = "SELECT * FROM referencecodes WHERE parentcode='7'";
        $tmp = mysqli_query($conn, $announcementqry);
        while ($row = mysqli_fetch_assoc($tmp)) {
            $isappannouncement = $row['isuserdriven'];
        }

        //get category names show or hide
        $announcementqry = "SELECT * FROM referencecodes WHERE parentcode='8'";
        $tmp = mysqli_query($conn, $announcementqry);
        while ($row = mysqli_fetch_assoc($tmp)) {
            $iscatename = $row['isuserdriven'];
        }
        $pagename = 'home';
        include_once './common/commonpage.php';

        if (isset($_GET['id'])) {
            $disable = true;
        } else {
            $disable = false;
        }
        ?>
    </head>
    <body>
        <?php if (strcmp($isbanner, 'Y') == 0) { ?>
            <!-- Start Slider -->
            <div id="slides-shop" class="cover-slides">
                <ul class="slides-container">
                    <?php
                    $hm_sec = "SELECT * FROM tbl_imagedetails WHERE image_flag='H' AND companyid='$CPOMANYID'";
                    $hm_sec_tmp = mysqli_query($conn, $hm_sec);
                    if (mysqli_num_rows($hm_sec_tmp) > 0) {
                        while ($hm_sec_row = mysqli_fetch_assoc($hm_sec_tmp)) {
                            $imagepath = $hm_sec_row["image_path"];
                            $imagepath = substr($imagepath, 1);
                            $subheader = $hm_sec_row["image_name"];
                            echo $ggg = "<li class='text-center'>
                        <img src='$imagepath' alt=''>
                        <div class='container'>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <h1 class='m-b-20'><strong>Welcome To <br>$SITE_TITLE </strong></h1>
                                    <p class='m-b-40'>$subheader </p>
                                    <p><a class='btn' href='#fff'>Shop Now</a></p>
                                </div>
                            </div>
                        </div>
                    </li>";
                        }
                    }
                    ?> 
                </ul>
                <div class="slides-navigation">
                    <a href="#" class="next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    <a href="#" class="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                </div>
            </div>
            <!-- End Slider -->
        <?php } ?>   

        <!-- Start Products  -->
        <div class="products-box" id="fff">
            <div class="shop-box-inner">
                <div class="container">
                    <?php if (strcmp($isappannouncement, 'Y') == 0) { ?>
                        <h3 class="annocement"><a href="apk/ClassyProd.apk" download><img src="images/rightfinger.png" width="30" height="30" ><u><?= $APPNOTIFICATION ?></u></a></h3>
                    <?php } ?>



                    <?php if (strcmp($iscatename, 'Y') == 0) { ?>
                        <div class="row titlecard" style="width: 100%;margin-left: 0px;margin-top: 10px;">
                            <div class="col-lg-12 column">
                                <div class="title-all text-center">
                                    <h1><?php echo $indexsubtitle ?></h1>
                                </div>
                            </div>
                            <div class="col-lg-12 column">
                                <div class="special-menu text-center">
                                    <div class="button-group filter-button-group">
                                        <?php
                                        $sql_row = "SELECT * FROM cropmaster where companyid=1 AND status=1 order by id asc";
                                        $result_row = $conn->query($sql_row);
                                        $sno = 0;
                                        while ($rows = mysqli_fetch_assoc($result_row)) {
                                            $sno++;
                                            if ($sno == 1) {
                                                $cropid = $rows['id'];
                                                echo '<button data-filter="*" class="active menu-filter" value="' . $rows['id'] . '">' . $rows['cropname'] . '</button>&nbsp;';
                                            } else {
                                                echo '<button data-filter="*" class="menu-filter" value="' . $rows['id'] . '">' . $rows['cropname'] . '</button>&nbsp;';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    } else {

                        $sql_row = "SELECT * FROM cropmaster where companyid=1 AND status=1 order by id asc";
                        $result_row = $conn->query($sql_row);
                        $sno = 0;
                        while ($rows = mysqli_fetch_assoc($result_row)) {
                            $sno++;
                            if ($sno == 1) {
                                $cropid = $rows['id'];
                            } else {
                                
                            }
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="shop-content-right">
                            <div class="right-product-box">
                                <div class="product-categorie-box">
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade show active" id="grid-view">
                                            <div class="row" id="draw"></div>
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
        </div>
    </div>
    <!-- End Products  -->

    <!-- Start Blog  -->
    <div class="latest-blog" id="blogdiv">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-all text-center">
                        <h1>latest blog</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class='main-instagram owl-carousel owl-theme'>
                    <?php
                    $hm_sec = "select b.blogid,b.blog_title,b.blog_desc,i.image_path from tbl_blog b LEFT JOIN tbl_imagedetails i ON i.id=b.imageid WHERE i.companyid='$CPOMANYID' AND b.status=1 order by b.blogid";
                    $hm_sec_tmp = mysqli_query($conn, $hm_sec);
                    if (mysqli_num_rows($hm_sec_tmp) > 0) {
                        while ($hm_sec_row = mysqli_fetch_assoc($hm_sec_tmp)) {
                            $processingid = json_encode($hm_sec_row["blogid"]);
                            echo $ggg = "
                            <div class='item'>
                                <div class='ins-inner-box'>
                                    <div class='blog-box' style='overflow-y: scroll;height:400px;'>
                                        <div class='blog-img'>
                                            <img src='./$hm_sec_row[image_path]' class='img-fluid' alt='Image' onclick='getimg($processingid)'>
                                        </div>                                    
                                        <div class='blog-content'>
                                            <div class='title-blog'>
                                                <h3>$hm_sec_row[blog_title]</h3>
                                                <p>$hm_sec_row[blog_desc]</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                        }
                        $isbloghide = 1;
                    } else {
                        $isbloghide = 0;
                    }
                    ?>   
                </div>
            </div>
        </div>
    </div>

    <!-- End Blog  -->
    <?php
    require("./common/footerscripting.php");
    ?>
    <script type="text/javascript">

        var isbloghide = '<?php echo $isbloghide ?>';
        if (isbloghide == 0) {
            $('#blogdiv').hide();
        } else {
            $('#blogdiv').show();
        }

        var crop_id = '<?= $cropid ?>';

        function getimg(obj) {
            window.open(obj, '_blank');
        }

        $("#basic").change(function () {
            var logType = $(this).val();
            if (logType == 'Register') {
                window.location.href = "register.php";
            } else if (logType == 'Sign In') {
                window.location.href = "login.php";
            }

        });
        $('.view-variety').click(function () {
            var variety_id = $(this).next().text();

            localStorage.setItem("variety_id", variety_id);

        });
        $('#userInfo').click(function () {
            $('body').addClass('on-side');
            $('.side').addClass('on');
        });

        var cropid;
        $(document).on('click', '.menu-filter', function () {
            cropid = $(this).val();
            crop_id = cropid;
            $.ajax({
                url: "index-view.php",
                type: "POST",
                data: {
                    cropid: cropid
                },
                success: function (data) {
                    $('#draw').html(data);
                }
            });
        });

        function worker() {
            $.ajax({
                url: "index-view.php",
                type: "POST",
                data: {
                    cropid: crop_id
                },
                success: function (data) {
                    $('#draw').html(data);
                    //setTimeout(worker, 30000);
                }
            });
        }
        $(document).ready(function () {
            $.ajax({
                url: "index-view.php",
                type: "POST",
                data: {
                    cropid: crop_id
                },
                success: function (data) {
                    $('#draw').html(data);
                    // setTimeout(worker, 30000);
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
                        url: 'index-view.php',
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
                        url: 'index-view.php',
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