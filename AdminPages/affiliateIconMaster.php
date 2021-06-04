<?php
require_once '../common/ApplicationConstant.php';
$loader_Path = constant("LOADER");
$locationurl = SITE_URL_ADMIN;
$adminname = ADMINNAME;
$message = "";
$contentname = 'Affiliate ';

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
                                <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i><a> Masters </a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> <?= $contentname ?> Icons Master <a href="#popup1" class="btn-facebook" style="float: right;margin-right: 10px;padding: 5px;"> Add New <?= $contentname ?> Icon</a></h4>                               
                                <fieldset class="fieldsetstyle2"><div id="affiliateGrid"></div></fieldset>
                            </div><!-- /content-panel -->
                        </div><!-- /col-md-12 -->
                    </div><!-- /row -->
                </section><! --/wrapper -->

                <!--           new popup alert-->
                <div id="popup1" class="overlay">
                    <div class="popup">
                        <div class="popup_header_new">Add New <?= $contentname ?> Icon<a class="closepopup" href="#">&times;</a></div>
                        <section id="unseen">
                            <form class="form-style-9" enctype="multipart/form-data" method="post" name="Form" action="action/adminAction.php" onsubmit="return validateForm(this)">
                                <ul> 
                                    <li>                                                
                                        <a>Name<span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="affiliatename" id="affiliatename" placeholder="eg: Amazon"/>
                                    </li>
                                    <li>  
                                        <fieldset class="fieldsetstyle2">
                                            <a>Upload Image <span class="mandatory_red">*</span></a>
                                            <input type="file" id="myFile" name="upload_image[]" class="btn-facebook" value="Upload" multiple="multiple" />
                                            <br><span class="mandatory_red">Note : Image should be in 48x48 pixel size</span>
                                        </fieldset>
                                    </li>
                                </ul>

                                <center> <input type="button" class="btn-facebook" onclick="saveAffiliate();" value="Submit"/></center>
                                <input type="hidden" name="method"/>
                                <input type="hidden" name="cropid"/>
                                <input type="hidden" name="imageid"/>
                                <input type="hidden" name="filename"/>
                                <input type="hidden" name="iddArray"/>
                                <input type='hidden' name="contentname" id="contentname" value='<?= $contentname ?>'/>
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
                getAffiliateGrid();

            }

            //fetch crop master details
            function getAffiliateGrid() {
                var contentname = $('#contentname').val();
                var list_target_id = 'affiliateGrid'; //first select list ID
                startloader();

                $.ajax({url: 'action/adminAction.php?method=affiliateGrid&contentname=' + contentname,
                    success: function (output) {

                        $('#' + list_target_id).html(output);
                        $('#minitable').DataTable({
//                            "scrollX": "1000px",
//                            "scrollCollapse": true
                        });
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function saveAffiliate() {
                if (checkMandatoryFormFields(new Array("affiliatename~Affiliate Link Name"))) {
                    var img1 = $('#myFile').val();
                    if (img1 == '') {
                        alert("Upload Crop Image ! ");
                        return false;
                    }


                    var r = confirm("Are you sure want to confirm?");
                    if (r) {
                        startloader();
                        document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                        document.forms[0].method.value = 'addNewAffLogo';
                        document.forms[0].submit();
                    }
                }

            }

            function editItem(itemid) {
                document.forms[0].action = "<?php echo $locationurl; ?>/editAffiliateMaster.php";
                document.forms[0].id.value = itemid;
                document.forms[0].submit();
            }
            function delItem(imageid) {
                var r = confirm("Are you sure want to Remove?");
                if (r) {
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].iddArray.value = imageid;
                    document.forms[0].method.value = 'saveTrash';
                    document.forms[0].filename.value = 'affiliateIconMaster';
                    document.forms[0].submit();
                }
            }

        </script>
    </body>
</html>