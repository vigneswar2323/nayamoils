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
                            <div class="header_bg content-panel">
                                <div class="header_bg">
                                    <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i><a> Masters </a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> <?= $contentname ?> Master <a href="#popup1" class="btn-facebook" style="float: right;margin-right: 10px;padding: 5px;"> Add New <?= $contentname ?></a><a id="delBtn" class="btn-cancel" style="float: right;margin-right: 10px;padding: 5px;display: none;" onclick="delVariety();">Trash</a></h4>                                    
                                </div>
                            </div>
                            <div class="content-panel">
                                <?php
                                if ($message) {
                                    echo '<div class="success">' . $message . '</div>';
                                }
                                ?>
                                <div class="modalloder" id="loader" style="display: none">
                                    <div class="center">
                                        <img width="100px" height="100px" alt="" src="<?php echo $loader_Path ?>" />
                                    </div>
                                </div>
                                <fieldset class="fieldsetstyle2"><div id="cropGrid"></div></fieldset>
                            </div><!-- /content-panel -->
                        </div><!-- /col-md-12 -->
                    </div><!-- /row -->
                </section><! --/wrapper -->

                <!--           new popup alert-->
                <div id="popup1" class="overlay">
                    <div class="popup">
                        <div class="popup_header_new">Add New <?= $contentname ?><a class="closepopup" href="#">&times;</a></div>
                        <section id="unseen">
                            <form class="form-style-9" enctype="multipart/form-data" method="post" name="Form" action="action/adminAction.php" onsubmit="return validateForm(this)">
                                <ul> 
                                    <li>                                                
                                        <a>Name of the <?= $contentname ?> <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="cropname" id="cropname"/>
                                    </li>
                                    <li>                                                
                                        <a>Status <span class="mandatory_red">*</span></a>
                                        <select class="field-style field-full" name="cropstatus" id="cropstatus">
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </li>
                                    <li>                                                
                                        <a>IS New <?= $contentname ?>?</a>
                                        <input type="checkbox" name="isnew" id="isnew" value="1"/>
                                    </li>

                                    <li>   
                                        <fieldset class="form-style-9 field-style field-full">
                                            <a>Upload Image <span class="mandatory_red">*</span></a>
                                            <input type="file" id="myFile" name="upload_image[]" class="btn-facebook" value="Upload" multiple="multiple" />
                                            <div id="imagePreview"> </div>
                                        </fieldset>
                                    </li>
                                </ul>

                                <center> <input type="button" class="btn-facebook" onclick="saveCrop();" value="Submit"/></center>
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
                getCropGrid();
            }
            //fetch crop master details
            function getCropGrid() {
                var contentname = $('#contentname').val();
                var list_target_id = 'cropGrid'; //first select list ID
                startloader();
                $.ajax({url: 'action/adminAction.php?method=cropGrid&contentname=' + contentname,
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        $('#minitable').DataTable();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function saveCrop() {
                if (checkMandatoryFormFields(new Array("cropname~Crop Name", "cropstatus~Crop Status"))) {
                    var img1 = $('#myFile').val();
                    if (img1 == '') {
                        alert("Upload Crop Image ! ");
                        return false;
                    }
                    startloader();
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].method.value = 'addNewCrop';
                    document.forms[0].submit();
                }
            }

            function editCrop(cropid, imageid) {
                document.forms[0].action = "<?php echo $locationurl; ?>/editCropMaster.php";
                document.forms[0].cropid.value = cropid;
                document.forms[0].imageid.value = imageid;
                document.forms[0].submit();
            }
            
            function delCrop(cropid, imageid) {
                var r = confirm("Are you sure want to Remove?");
                if (r) {
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].iddArray.value = cropid;
                    document.forms[0].imageid.value = imageid;
                    document.forms[0].method.value = 'saveTrash';
                    document.forms[0].filename.value = 'cropMaster';
                    document.forms[0].submit();
                }
            }
        </script>
    </body>
</html>