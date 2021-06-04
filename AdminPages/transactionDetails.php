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
    $adminname = $userdetails['username'];
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
                                <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Transaction Details</h4>


                                <fieldset class="fieldsetstyle2"><div id="transGrid"></div></fieldset>

                            </div><!-- /content-panel -->
                        </div><!-- /col-md-12 -->
                    </div><!-- /row -->
                </section><! --/wrapper -->

                <!--           new popup alert-->
                <div id="popup1" class="overlay">
                    <div class="popup" style="width: 80%;">
                        <div class="popup_header_new">Add New Variety<a class="closepopup" href="#">&times;</a></div>
                        <section id="unseen">
                            <form class="form-style-9" style="overflow-y: scroll;height: 500px;" enctype="multipart/form-data" method="post" name="Form" action="action/adminAction.php" onsubmit="return validateForm(this)">
                                <ul> 
                                    <li>                                                
                                        <a>Choose Crop <span class="mandatory_red">*</span></a>
                                        <select class="field-style field-full" name="cropid" id="cropid"></select>
                                    </li>
                                    <li>                                                
                                        <a>Name of the Variety <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="varietyname" id="varietyname"/>
                                    </li>
                                    <li>                                                
                                        <a>No of Days (Nursery) <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="column1" id="column1"/>
                                    </li>
                                    <li>                                                
                                        <a>No of Days (Main Field) <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="column2" id="column2"/>
                                    </li>
                                    <li>                                                
                                        <a>Characters <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="column3" id="column3"/>
                                    </li>
                                    <li>                                                
                                        <a>Price (INR) <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="column4" id="column4"/>
                                    </li>
                                    <li>                                                
                                        <a>Quantity (Kg) <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="column5" id="column5"/>
                                    </li>
                                    <li>                                                
                                        <a>Status <span class="mandatory_red">*</span></a>
                                        <select class="field-style field-full" name="varietystatus" id="varietystatus">
                                            <option value="1">Active</option>
                                            <option value="2">In-Active</option>
                                        </select>
                                    </li>
                                    <li>                                                
                                        <a>IS New Variety?</a>
                                        <input type="checkbox" name="isnew" id="isnew" value="1"/>
                                    </li>

                                    <li>                                                
                                        <a>Upload Image <span class="mandatory_red">*</span></a>
                                        <input type="file" id="myFile" name="upload_image" class="btn-facebook" value="Upload" multiple="multiple" />
                                    </li>
                                </ul>

                                <center> <input type="button" class="btn-facebook" onclick="saveVariety();" value="Submit"/></center>
                                <input type="hidden" name="crpid"/>
                                <input type="hidden" name="varietyid"/>
                                <input type="hidden" name="imageid"/>
                                <input type="hidden" name="method"/>
                            </form> 
                        </section>
                    </div>
                </div>
            </section><!-- /MAIN CONTENT -->
            <!--main content end-->
            <!--footer start-->
            <?php include './common/footer2.php'; ?>
            <!--footer end-->
        </section>
        <script>

            bodyOnLoad();

            function bodyOnLoad() {
                 getTransGrid();
            }

           

            //fetch crop master details
            function getTransGrid() {
                var list_target_id = 'transGrid'; //first select list ID
                startloader();
                $.ajax({url: 'action/adminAction.php?method=transGrid',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        $('#minitable').DataTable();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function saveVariety() {
                if (checkMandatoryFormFields(new Array("cropid~Name of the Crop","varietyname~Name of the Variety","column1~No of Days (Nursery)","column2~No of Days (Main Field)","column3~Characters","column4~Price","column5~Quantity", "varietystatus~Variety Status"))) {
                    var img1 = $('#myFile').val();
                    if (img1 == '') {
                        alert("Upload Variety Image ! ");
                        return false;
                    }

                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].method.value = 'addNewVariety';
                    document.forms[0].submit();
                }
            }

            function editVariety(varietyid,cropid, imageid) {
                document.forms[0].action = "<?php echo $locationurl; ?>/editVarietyMaster.php";
                document.forms[0].varietyid.value = varietyid;
                document.forms[0].crpid.value = cropid;
                document.forms[0].imageid.value = imageid;
                document.forms[0].submit();
            }

        </script>

    </body>
</html>
