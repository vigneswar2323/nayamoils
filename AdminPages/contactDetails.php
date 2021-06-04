<?php
require_once '../common/ApplicationConstant.php';
$loader_Path = constant("LOADER");
$locationurl = SITE_URL_ADMIN;
$adminname = ADMINNAME;
$message = "";
$contentname = 'Category';

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

                                <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Reports &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Contact Us <a  style="float: right;margin-right: 10px;padding: 5px;"></a></h4>  

                                <fieldset class="fieldsetstyle2"><div id="contactusGrid"></div></fieldset>
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
                getContactusGrid();

            }

            //fetch crop master details
            function getContactusGrid() {
                var contentname = $('#contentname').val();
                var list_target_id = 'contactusGrid'; //first select list ID
                startloader();
                $.ajax({url: 'action/adminAction.php?method=contactusGrid&contentname=' + contentname,
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        $('#minitable').DataTable();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});

            }





        </script>

    </body>
</html>
