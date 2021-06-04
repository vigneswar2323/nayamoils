<?php
require_once '../common/ApplicationConstant.php';
$loader_Path = constant("LOADER");
$locationurl = SITE_URL_ADMIN;
$adminname = ADMINNAME;
$message = "";
$cropid = $_POST['cropid'];
$imageid = $_POST['imageid'];
$prevpagename = 'cropMaster.php';
$contentname = "Category";

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
                                <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> <a onclick="backbtn('<?php echo $prevpagename ?>')"> <?= $contentname ?> Master</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Edit <?= $contentname ?> </h4>

                                <div class="w3-container">
                                    <div id="crop" class="w3-container city">
                                        <section id="unseen">
                                            <form class="form-style-9" enctype="multipart/form-data" method="post" name="Form">
                                                <ul> 
                                                    <li>                                                
                                                        <a>Name of the <?= $contentname ?> <span class="mandatory_red">*</span></a>
                                                        <input type="text" class="field-style field-full" name="cropname" id="cropname"/>
                                                    </li>
                                                    <li>                                                
                                                        <a>Status <span class="mandatory_red">*</span></a>
                                                        <select class="field-style field-full" name="cropstatus" id="cropstatus">
                                                            <option value="1">Active</option>
                                                            <option value="2">In-Active</option>
                                                        </select>
                                                    </li>
                                                    <li>                                                
                                                        <a>Is New <?= $contentname ?>?</a>
                                                        <input type="checkbox" name="isnew" id="isnew" value="1"/>
                                                    </li>
                                                    <li>                                                
                                                        <a><?= $contentname ?> Image</a>
                                                        <img src="" height="100" width="100" id="cropimage" name="cropimage">
                                                    </li>
                                                    <li>                                                
                                                        <a>Upload Image</a>
                                                        <input type="file" id="myFile" name="upload_image" class="btn-facebook" value="Upload" />
                                                    </li>
                                                </ul>

                                                <center> 
                                                    <input type="button" class="btn-cancel" onclick="backbtn('<?php echo $prevpagename ?>');" value="Cancel"/>
                                                    <input type="button" class="btn-facebook" onclick="updateCrop();" value="Update"/>
                                                </center>
                                                <input type="hidden" name="method"/>
                                                <input type="hidden" name="cropid" value="<?php echo $cropid ?>"/>
                                                <input type="hidden" name="imageid" value="<?php echo $imageid ?>"/>
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
                getCropDetails();
                document.getElementById('myFile').value = "";

            }

            function updateCrop() {
                if (checkMandatoryFormFields(new Array("cropname~Crop Name", "cropstatus~Crop Status"))) {
                    startloader();
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].method.value = 'editCrop';
                    document.forms[0].submit();
                }
            }

            //fetch crop master details
            function getCropDetails() {
                var list_target_id = 'cropGrid'; //first select list ID
                let cropid = '<?php echo $cropid ?>';
                startloader();
                $.ajax({url: 'action/adminAction.php?method=getCropDetails&cropid=' + cropid,
                    success: function (output) {
                        var splitdata = output.split('~');
                        var crop = splitdata[0];
                        var imagepath = splitdata[1];
                        var cropsatatus = splitdata[2];

                        $('#cropname').val(crop);
                        document.getElementById("cropimage").src = '..' + imagepath;
                        // document.getElementById("upload_image").src = '..' + imagepath;
                        $('#cropstatus').val(cropsatatus);


                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

        </script>

    </body>
</html>
