<?php
require_once '../common/ApplicationConstant.php';
$loader_Path = constant("LOADER");
$locationurl = SITE_URL_ADMIN;
$adminname = ADMINNAME;
$message = "";

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
    <link rel="stylesheet" href="assets/css/tabcss.css">
    <body>
        <section id="container" >
            <!-- **********************************************************************************************************************************************************
            TOP BAR CONTENT & NOTIFICATIONS
            *********************************************************************************************************************************************************** -->
            <!--header start-->

            <!--header end-->

            <!-- **********************************************************************************************************************************************************
            MAIN SIDEBAR MENU
            *********************************************************************************************************************************************************** -->
            <!--sidebar start-->
            <?php include './common/leftnavigationbar.php'; ?>
            <!--sidebar end-->

            <!-- **********************************************************************************************************************************************************
            MAIN CONTENT
            *********************************************************************************************************************************************************** -->
            <!--main content start-->
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
                                <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> <a href="cropMaster.php"> Crop Master</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Add Crops </h4>

                                <div class="w3-container">
                                    <div class="w3-row">
                                        <a href="javascript:void(0)" onclick="openCity(event, 'crop');">
                                            <div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding w3-border-red">Add Crop</div>
                                        </a>
                                    </div>

                                    <div id="crop" class="w3-container city">
                                        <section id="unseen">
                                            <form class="form-style-9" enctype="multipart/form-data" method="post" name="Form" action="action/adminAction.php" onsubmit="return validateForm(this)">
                                                <ul> 
                                                    <li>                                                
                                                        <a>Name of the Crop</a>
                                                        <input type="text" class="field-style field-full" name="cropname" id="cropname"/>
                                                    </li>
                                                    <li>                                                
                                                        <a>Status</a>
                                                        <select class="field-style field-full" name="cropstatus" id="cropstatus">
                                                            <option value="1">Active</option>
                                                            <option value="0">Disable</option>
                                                        </select>
                                                    </li>

                                                    <li>                                                
                                                        <a>Upload Image</a>
                                                        <input type="file" id="myFile" name="filename">
                                                    </li>
                                                    <li>
                                                        <input type="hidden" value="getvendorslist" name="method"/>
        <!--                                                <input type="submit" value="Update"/>-->
                                                    </li>
                                                </ul>
                                                
                                                <center> <input type="button" class="btn-facebook" onclick="saveCrop();" value="Submit"/></center>
                                            </form> 
                                            
                                        </section>
                                    </div>
                                    <div id="confbtn" class="w3-container city" style="display:none">

                                    </div>
                                </div>
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


            }
            
            function saveCrop(){
                if (checkMandatoryFormFields(new Array("cropname~Crop Name","cropstatus~Crop Status"))) {
                     var img1 = $('#myFile').val();
                    if (img1 == '') {
                        alert("Upload Crop Image ! ");
                        return false;
                    }                    
                }
            }

            function openCity(evt, cityName) {

                if (checkMandatoryFormFields(new Array("cropname~Crop Name","cropstatus~Crop Status"))) {
                    var img1 = $('#myFile').val();
                    if (img1 == '') {
                        alert("Upload Crop Image ! ");
                        return false;
                    }
                    var i, x, tablinks;
                    x = document.getElementsByClassName("city");
                    for (i = 0; i < x.length; i++) {

                        x[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablink");

                    for (i = 0; i < x.length; i++) {

                        tablinks[i].className = tablinks[i].className.replace(" w3-border-red", "");
                    }
                    document.getElementById(cityName).style.display = "block";
                    evt.currentTarget.firstElementChild.className += " w3-border-red";
                }
            }

            //validations
            function validateForm()
            {
                var a = document.getElementById("categoryname").value;
                var b = document.getElementById("vendorname").value;
                var c = document.getElementById("productname").value;

                if (a == 0 || a == "", b == 0 || b == "", c == 0 || c == "")
                {
                    alert("Please Fill All Required Field");
                    return false;
                } else {
                    startloader();
                }
            }

            //load getvendors dropdown details
            function getvendors(Obj) {
                var catid = Obj.value;

                var list_target_id = 'vendorname';

                $('#' + list_target_id).empty();
                $.ajax({url: 'action/adminAction.php?method=getvendor&categoryid=' + catid,
                    success: function (output) {

                        $('#' + list_target_id).html(output);

                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + " " + thrownError);
                    }});

            }
           
          
        </script>

    </body>
</html>
