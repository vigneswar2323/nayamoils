<!DOCTYPE html>
<head>
    <?php
    include_once './common/headerscripting.php';
    include_once("./connection/DBConnection.php");
    include './common/ApplicationConstant.php';
    session_start();
    $DBConnection = new DBConnection();
    $siteurl = constant('SITE_URL');
    $CPOMANYID = constant('CPOMANYID');

    $logoqry = "SELECT * FROM tbl_imagedetails WHERE image_flag='L' AND companyid='$CPOMANYID'";
    $tmp = mysqli_query($conn, $logoqry);
    while ($row = mysqli_fetch_assoc($tmp)) {
        $logopath = $row['image_path'];
    }

    $pagename = 'ourproducts';
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
                    <h2>Products </h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->
    <!-- Start Shop Page  -->
    <div class="products-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 column">
                    <div class="title-all text-center">
                        <h1>Our Products</h1>
                    </div>
                </div>
            </div>


            <div class="row" id="product-menu">
                <div class="col-xl-12 col-lg-12 col-sm-12 col-xs-12 shop-content-right column">
                    <div class="special-menu text-center">
                        <div class="button-group filter-button-group">
                            <?php
                            $sql_row = "SELECT * FROM cropmaster where status=1 order by cropname asc";
                            $result_row = $conn->query($sql_row);
                            while ($rows = mysqli_fetch_assoc($result_row)) {
                                echo '<button data-filter="*" class="menu-filter" value="' . $rows['id'] . '">' . $rows['cropname'] . '</button>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="no-result alert-danger hidden" style="text-align: center;">
                <p>Stock Unavailable !!</p>
            </div>
        </div> 

        <!-- Start Shop Page  -->
        <div class="shop-box-inner">
            <div class="container">
                <div class="row">
                    <div class="shop-content-right">
                        <div class="box-img-hover prod-block">
                            <div class="col-lg-3 col-md-6 col-sm-12 blog-box hidden animate__animated  animate__pulse card" id="blog-div">
                                <div class='products-single fix'>
                                    <div class="blog-img" id="blog">
                                    </div>
                                    <div class='why-text'>
                                        <div class="blog-content">
                                            <div class="postdiv">
                                                <h2 class="postdiv-title var-name" id="var_name" style="font-weight: 600;text-decoration: none;"></h2>
                                            </div>

                                            <div id="var_con" class="hidden">
                                                <a id="col_name" class="col-name"></a>
                                                <i id="colsdecription" class="col-des"></i>
                                            </div>

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
            $('#userInfo').click(function () {
                $('body').addClass('on-side');
                $('.side').addClass('on');
            });
            $(document).ready(function () {
                $('.special-menu').find('button:first').trigger('click');
                $('.special-menu').find('button:first').addClass('active');
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
            var crop_id;
            $(document).on('click', '.menu-filter', function () {
                crop_id = $(this).val();
                Contentload(crop_id);
            });


            function Contentload(crop_id) {
                console.log(crop_id);
                $.ajax({
                    url: "json/products_details_list.php",
                    type: "POST",
                    data: {
                        crop_id: crop_id
                    },
                    /*dataType:"json",  */
                    success: function (data) {
                        $('.no-result').addClass('hidden');
                        if (data.length != 0 && data.statusCode != 200) {
                            $('.blog-box:not(.hidden)').remove();
                            for (var i = 0; i < data.length; i++) {
                                var cloned_ul = $("#blog-div:not(.cloned-div)").clone();
                                $(cloned_ul).removeClass("hidden").addClass('cloned-div');

                                $(cloned_ul).find('.blog-img').html('<img class="img-fluid image_path" alt="" src=./' + data[i].image_path + '>');
                                $(cloned_ul).find('.var-name').text(data[i].variety_description);
                                $(cloned_ul).attr('id', 'crop_clone_' + i);
                                $(".box-img-hover").append($(cloned_ul));
                                for (j = 0; j < data[i].variety_details.length; j++) {
                                    var varirty_li = $("#crop_clone_" + i).find('#var_con:not(.variety-clone)').clone();
                                    $(varirty_li).removeClass("hidden").addClass('variety-clone');

                                    $(varirty_li).find('.col-name').html('&#9830; ' + data[i].variety_details[j].col_name);
                                    $(varirty_li).find('.col-des').text('- ' + data[i].variety_details[j].col_description);
                                    $("#crop_clone_" + i).append($(varirty_li));

                                }
                            }
                        } else {
                            console.log(data.statusCode);

                            if (data.statusCode == 200) {
                                crop_id = '';
                                $('#blog').html('');
                                $('.blog-box:not(.hidden)').remove();
                                //$('#var_con').removeClass('hidden');
                                $('.no-result').removeClass('hidden');


                                /*Contentload(crop_id);*/
                            }
                        }
                        //var myJSON = JSON.stringify(arr);


                        /*console.log(data);
                         if(data.length != 0){
                         $('#col_name').text(data.col_name);
                         $('#col_description').text(data.col_description);
                         }else {
                         $('#col_name').text('SORRY..!');
                         $('#col_description').text('NO RESULT FOUND');
                         }*/
                    }
                });
            }
        </script>
</body>
</html>