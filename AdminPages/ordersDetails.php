<?php
require_once '../common/ApplicationConstant.php';
$loader_Path = constant("LOADER");
$locationurl = SITE_URL_ADMIN;
$siteurl = SITE_URL;
$adminname = ADMINNAME;
$message = "";
$contentname = 'Crop';

session_start();
if (isset($_SESSION['message'])) {
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
    unset($_SESSION['message']);
}

if ($_SESSION['userdetails']) {
    $userdetails = $_SESSION["userdetails"];
} else {
    header("location:$locationurl/adminLogin.php");
}
?>
<!DOCTYPE html>

<html lang="en">
    <?php include './common/header.php'; ?>
    <body>
        <section id="container" >
            <?php include './common/leftnavigationbar.php'; ?>
            <section id="main-content">
                <section class="wrapper">
                    <div class="row mt">                       
                        <div class="col-md-12">
                            <?php
                            if ($message) {
                                echo '<div class="success">' . $message . '</div>';
                            }
                            ?>

                            <div class="content-panel">
                                <div class="modalloder" id="loader" style="display: none">
                                    <div class="center">
                                        <img width="100px" height="100px" alt="" src="<?php echo $loader_Path ?>" />
                                    </div>
                                </div>

                                <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Reports &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Orders Details <a  style="float: right;margin-right: 10px;padding: 5px;"></a></h4>  

                                <fieldset class="fieldsetstyle2"><div id="contactusGrid"></div></fieldset>
                                <a id="ddd" href="#popup1"></a>
                                <!--           new popup alert-->
                                <div id="popup1" class="overlay">
                                    <div class="popup" style="width: 75%;height: 650px;margin-top: 10px;">
                                        <div class="popup_header_new">Order Slip<a class="closepopup" href="#">&times;</a></div>
                                        <iframe id="cframe" src="" width=100% height=100%></iframe>    
                                    </div>
                                </div>

                                <!----------------  View------------------>
                                <!--                                <div id="viewWhilesave" class="popup_box2">
                                                                    <div class="closeBtn">
                                                                        <a href = "javascript:void(0)" onclick = "popupclose()">
                                                                            <img src="default/images/closeIcon.png" style="width: 25px;height: 25px;margin-top: -20px;"/></a><br />
                                                                        <iframe id="cframe" src="" width=100% height=100%></iframe>
                                                                    </div>
                                                                </div>-->

                            </div><!-- /content-panel -->
                        </div><!-- /col-md-12 -->
                    </div><!-- /row -->
                </section><! --/wrapper -->        
            </section><!-- /MAIN CONTENT -->
            <!--main content end-->
            <!--footer start-->
            <?php include './common/footer2.php'; ?>
            <!--footer end-->
        </section>
        <script>

            bodyOnLoad();

            function bodyOnLoad() {
                getGrid();

            }

            //fetch crop master details
            function getGrid() {
                var contentname = $('#contentname').val();
                var list_target_id = 'contactusGrid'; //first select list ID
                startloader();
                $.ajax({url: 'action/adminAction.php?method=ordersGrid&contentname=' + contentname,
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        $('#minitable').DataTable();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});

            }

            function openReport(id, userid) {

                $.ajax({url: 'action/adminAction.php?method=getOrderReport&oid=' + id + '&id=' + userid,
                    success: function (output) {
                        $('#ddd')[0].click();

                        var url = "<?php echo $siteurl ?>/order-confirmation.php";
                        document.getElementById("cframe").src = url//url

                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + " " + thrownError);
                    }});
            }
        </script>
    </body>
</html>
