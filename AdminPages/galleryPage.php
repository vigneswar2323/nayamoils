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
    <body>
        <section id="container" >
            <?php include './common/leftnavigationbar.php'; ?>
            <section id="main-content">
                <section class="wrapper">
                    <div class="row mt">                       
                        <div class="col-md-12">
                            <div class="header_bg content-panel">
                                <div class="header_bg">
                                    <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Gallery Page <a href="#popup1" class="btn-facebook" style="float: right;margin-right: 10px;padding: 5px;"> Add New Image</a></h4>
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
                                
                                <fieldset class="fieldsetstyle2"><legend>Gallery Page Image Details</legend>
                                    <div id="galleryGrid"></div>
                                </fieldset>
<!--                                <center> <input type="button" class="btn-facebook" onclick="saveHomePage();" value="Submit"/></center>-->
                                <form  enctype="multipart/form-data" method="post" name="Form">
                                    <!-- Add Image details popup-->
                                    <div id="popup1" class="overlay">
                                        <div class="popup">
                                            <div class="popup_header_new">Add Gallery Page Image Details<a id="sss" class="closepopup" href="#">&times;</a></div>
                                            <section id="unseen">
                                                <div class="form-style-9" style="overflow-y: scroll;" enctype="multipart/form-data" method="post" name="Form">
                                                    <ul> 
                                                        <li>                                                
                                                            <a>Choose Type <span class="mandatory_red">*</span></a>
                                                            <select class="field-style field-full" name="gallerytype" id="gallerytype"></select>
                                                        </li>
                                                        <li>                                                
                                                            <a>Description <span class="mandatory_red">*</span></a>
                                                            <input type="text" class="field-style field-full" name="gallerydesc" id="gallerydesc"/>
                                                        </li>
                                                        <li>  
                                                            <fieldset class="form-style-9 field-style field-full">
                                                                <a>Upload Image</a>
                                                                <input type="file" id="myFile" name="upload_image" class="btn-facebook" value="Upload" />
                                                                <div id="imagePreview"> </div>
                                                            </fieldset>
                                                        </li>
                                                    </ul>
                                                    <center> <input type="button" class="btn-facebook" onclick="savePage();" value="Update"/></center>    
                                                </div> 
                                            </section>
                                        </div>
                                    </div>

                                    <!-- Edit Image details popup-->
                                    <div id="popup2" class="overlay">
                                        <div class="popup">
                                            <div class="popup_header_new">Edit Gallery Page Image Details<a id="sss" class="closepopup" href="#">&times;</a></div>
                                            <section id="unseen">
                                                <div class="form-style-9" style="overflow-y: scroll;height: 400px;" enctype="multipart/form-data" method="post" name="Form">
                                                    <ul> 
                                                        <li>                                                
                                                            <a>Choose Type <span class="mandatory_red">*</span></a>
                                                            <select class="field-style field-full" name="editgallerytype" id="editgallerytype"></select>
                                                        </li>
                                                        <li>                                                
                                                            <a>Description <span class="mandatory_red">*</span></a>
                                                            <input type="text" class="field-style field-full" name="editdescription" id="editdescription"/>
                                                        </li>
                                                        <li>                                                
                                                            <a>Image</a>
                                                            <img  height="100" width="100" id="imagepath" name="imagepath">
                                                        </li>
                                                        <li>                                                
                                                            <a>Upload Image</a>
                                                            <input type="file" id="myFileUpdate" name="upload_editimage" class="btn-facebook" value="Upload" />
                                                        </li>
                                                    </ul>
                                                    <center> <input type="button" class="btn-facebook" onclick="updatePage();" value="Update"/></center>  

                                                </div> 
                                            </section>
                                        </div>
                                    </div>
                                    <input type="hidden" name="method"/>
                                    <input type="hidden" name="galleryid" id="galleryid"/>
                                    <input type="hidden" name="imageid" id="imageid"/>
                                    <input type="hidden" name="imageflag" id="imageflag"/>
                                    <input type="hidden" name="filename"/>
                                    <input type="hidden" name="id"/>
                                </form>
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
            var app_load = "";
            bodyOnLoad();

            function bodyOnLoad() {
                getPageDetails();
                getReferencecode();

            }

            function getReferencecode() {
                startloader();
                var list_target_id = 'gallerytype'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=getReferencecodes&parentcode=4',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            //fetch crop master details
            function getPageDetails() {
                var list_target_id = 'galleryGrid'; //first select list ID
                startloader();
                $.ajax({url: 'action/adminAction.php?method=getGalleryPageDetails',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        $('#minitable').DataTable();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function elementToPassHome(elementsToPass) {

                var galleryid = elementsToPass[0].split('~');
                var imageid = elementsToPass[1].split('~');
                var galltype = elementsToPass[2].split('~');
                var galldesc = elementsToPass[3].split('~');
                var imagepath = elementsToPass[4].split('~');
                var imageflag = elementsToPass[5].split('~');
                var gallery_flagid = elementsToPass[6].split('~');

                $('#' + galleryid[0]).val(galleryid[1]);
                $('#' + imageid[0]).val(imageid[1]);
                $('#' + galltype[0]).val(galltype[1]);
                $('#' + galldesc[0]).val(galldesc[1]);
                document.getElementById(imagepath[0]).src = '..' + imagepath[1];
                $('#' + imageflag[0]).val(imageflag[1]);
                app_load = gallery_flagid[1];
                getDropdownid();
            }

            function savePage() {
                if (checkMandatoryFormFields(new Array("gallerytype~Gallery Type", "gallerydesc~Description"))) {
                    var img1 = $('#myFile').val();
                    if (img1 == '') {
                        alert("Upload Image ! ");
                        return false;
                    }
                    var r = confirm("Are you sure want to Confirm!");
                    if (r) {
                        startloader();
                        document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                        document.forms[0].method.value = 'saveGalleryPage';
                        document.forms[0].submit();
                    }
                }
            }

            function saveHomePage() {

                var img1 = $('#myFile').val();
                if (img1 == '') {
                    alert("Upload Crop Image ! ");
                    return false;
                }
                startloader();
                document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                document.forms[0].method.value = 'saveHomePage';
                document.forms[0].submit();

            }

            function  getDropdownid() {
                startloader();
                var list_target_id = 'editgallerytype'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=getReferencecodes&parentcode=4',
                    success: function (output) {
                        stoploader();
                        $('#' + list_target_id).html(output);
                        if (app_load) {
                            document.getElementById(list_target_id).value = app_load;
                            document.getElementById(list_target_id).onchange();
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function updatePage() {
                if (checkMandatoryFormFields(new Array("editgallerytype~Gallery Type", "editdescription~Description"))) {

                    var r = confirm("Are you sure want to Confirm!");
                    if (r) {
                        startloader();
                        document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                        document.forms[0].method.value = 'updateGalleryPage';
                        document.forms[0].submit();
                    }
                }
            }

            function delGallery(blogid, imageid) {
                var r = confirm("Are you sure want to Remove?");
                if (r) {
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].id.value = blogid;
                    document.forms[0].imageid.value = imageid;
                    document.forms[0].method.value = 'saveTrash';
                    document.forms[0].filename.value = 'galleryPage';
                    document.forms[0].submit();
                }
            }
        </script>
    </body>
</html>