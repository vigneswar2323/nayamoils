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
                            <?php
                            if ($message) {
                                echo '<div class="success">' . $message . '</div>';
                            }
                            ?>
                            <form enctype="multipart/form-data" method="post" name="Form">
                                <div class="content-panel">
                                    <div class="modalloder" id="loader" style="display: none">
                                        <div class="center">
                                            <img width="100px" height="100px" alt="" src="<?php echo $loader_Path ?>" />
                                        </div>
                                    </div>
                                    <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Home Page </h4>         
                                    <fieldset class="fieldsetstyle2"><legend>A) Home Page Image Details <a href="#popup4" class="btn-facebook" style="float: right;margin-right: 10px;padding: 5px;"> Add Slider Image</a></legend>
                                        <div id="bannerSlideGrid"></div>
                                    </fieldset>
                                    <fieldset class="fieldsetstyle3"><legend>B) Other Operations</legend>
                                        <table id="minitable" style="width: 70%">
                                            <tr>
                                                <td>1</td>
                                                <td>Is the Banner slider shown on home page?</td>
                                                <td><input type="radio" name="isbanner" value="Y" onclick="getBanner(this, '6')"/> Show</td>
                                                <td><input type="radio" name="isbanner" value="N" onclick="getBanner(this, '6')"/> Hide</td>
                                                <td><input type="button" class="btn-facebook" name="bannerstatus" id="bannerstatus"/></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Is the App Announcement shown on home page?</td>
                                                <td><input type="radio" name="isbanner" value="Y" onclick="getBanner(this, '7')"/> Show</td>
                                                <td><input type="radio" name="isbanner" value="N" onclick="getBanner(this, '7')"/> Hide</td>
                                                <td> <input type="button" class="btn-facebook" name="announcementstatus" id="announcementstatus"/></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Is the Category name (menus) shown on home page?</td>
                                                <td><input type="radio" name="isbanner" value="Y" onclick="getBanner(this, '8')"/> Show</td>
                                                <td><input type="radio" name="isbanner" value="N" onclick="getBanner(this, '8')"/> Hide</td>
                                                <td><input type="button" class="btn-facebook" name="catstatus" id="catstatus"/></td>
                                            </tr>
                                        </table>                                        
                                    </fieldset>

                                    <fieldset class="fieldsetstyle2"><legend>C) Blog Details <a href="#popup2" class="btn-facebook" style="float: right;margin-right: 10px;padding: 5px;"> Add New Blog</a></legend>

                                        <div id="blogGriddiv"></div>
                                    </fieldset>

                                    <!-- add slider image details popup-->
                                    <div id="popup4" class="overlay">
                                        <div class="popup">
                                            <div class="popup_header_new">Home Page Image Details<a id="sss" class="closepopup" href="#">&times;</a></div>
                                            <section id="unseen">
                                                <div class="form-style-9" style="overflow-y: scroll;">
                                                    <ul> 
                                                        <li>                                                
                                                            <a>Image Description <span class="mandatory_red">*</span></a>
                                                            <textarea  class="field-style field-full" name="homeimagedesc" id="homeimagedesc" placeholder="write upto 200 characters..."></textarea>
                                                        </li>
                                                        <li>   
                                                            <a>Image type <span class="mandatory_red">*</span></a>
                                                            <select id="homeimagetype" name="homeimagetype">
                                                                <option value="0">--select--</option>
                                                                <option value="L">Logo</option>
                                                                <option value="H">Slider</option>
                                                            </select>
                                                        </li>
                                                        <li> 
                                                            <fieldset class="fieldsetstyle2">
                                                                <a>Upload Image <span class="mandatory_red">*</span></a>
                                                                <input type="file" id="myFile" name="slider_image" class="btn-facebook" value="Upload" />
                                                                <br><span class="mandatory_red">Note : Image should be in 1920x1080 pixel size</span>
                                                            </fieldset>
                                                        </li>
                                                    </ul>
                                                    <center> <input type="button" class="btn-facebook" onclick="saveHomePage();" value="Submit"/></center>    

                                                </div> 
                                            </section>
                                        </div>
                                    </div>

                                    <!-- Edit variety details popup-->
                                    <div id="popup1" class="overlay">
                                        <div class="popup">
                                            <div class="popup_header_new">Edit Home Page Image Details<a id="sss" class="closepopup" href="#">&times;</a></div>
                                            <section id="unseen">
                                                <div class="form-style-9" style="overflow-y: scroll;height: 300px;">
                                                    <ul> 
                                                        <li>                                                
                                                            <a>Description </a>
                                                            <a class="field-style field-full" name="editimageflag" id="editimageflag"></a>
                                                        </li>
                                                        <li>                                                
                                                            <a>Image</a>
                                                            <img  height="100" width="100" id="editimagepath" name="editimagepath">
                                                        </li>
                                                        <li>
                                                            <fieldset class="fieldsetstyle2">
                                                                <a>Upload Image</a>
                                                                <input type="file" id="myFile2" name="upload_image" class="btn-facebook" value="Upload" />
                                                            </fieldset>
                                                        </li>
                                                    </ul>
                                                    <center> <input type="button" class="btn-facebook" onclick="updateHomePage();" value="Update"/></center>    

                                                </div> 
                                            </section>
                                        </div>
                                    </div>

                                    <!-- add new blog details popup-->
                                    <div id="popup2" class="overlay">
                                        <div class="popup">
                                            <div class="popup_header_new">Add New Blog Details<a id="sss" class="closepopup" href="#">&times;</a></div>
                                            <section id="unseen">
                                                <div class="form-style-9" style="overflow-y: scroll;height: 400px;">
                                                    <ul> 
                                                        <li>                                                
                                                            <a>Blog Title </a>
                                                            <input type="text" class="field-style field-full" name="blogtitle" id="blogtitle"/>
                                                        </li>
                                                        <li>                                                
                                                            <a>Blog Description </a>
                                                            <textarea  class="field-style field-full" name="blogdesc" id="blogdesc" placeholder="write upto 400 characters..."></textarea>
                                                        </li>
                                                        <li>  
                                                            <fieldset class="fieldsetstyle2">
                                                                <a>Upload Image</a>
                                                                <input type="file" id="myBlog" name="blog_image" class="btn-facebook" value="Upload" />
                                                            </fieldset>
                                                        </li>
                                                    </ul>
                                                    <center> <input type="button" class="btn-facebook" onclick="saveBlog();" value="Submit"/></center>    
                                                </div> 
                                            </section>
                                        </div>
                                    </div>

                                    <!-- edit blog details popup-->
                                    <div id="popup3" class="overlay">
                                        <div class="popup">
                                            <div class="popup_header_new">Edit Blog Details<a id="sss" class="closepopup" href="#">&times;</a></div>
                                            <section id="unseen">
                                                <div class="form-style-9" style="overflow-y: scroll;height: 450px;">
                                                    <ul> 
                                                        <li>                                                
                                                            <a>Blog Title </a>
                                                            <input type="text" class="field-style field-full" name="editblogtitle" id="editblogtitle"/>
                                                        </li>
                                                        <li>                                                
                                                            <a>Blog Description </a>
                                                            <textarea  class="field-style field-full" name="editblogdesc" id="editblogdesc" placeholder="write upto 400 characters..."></textarea>
                                                        </li>
                                                        <li>                                                
                                                            <a>Image</a>
                                                            <img  height="100" width="100" id="editblogimage" name="editblogimage">
                                                        </li>
                                                        <li>   
                                                            <fieldset class="fieldsetstyle2">
                                                                <a>Upload Image</a>
                                                                <input type="file" id="myEditBlog" name="editblog_image" class="btn-facebook" value="Upload" />
                                                            </fieldset>
                                                        </li>
                                                        <li>                                                
                                                            <a>Status <span class="mandatory_red">*</span></a>
                                                            <select class="field-style field-split" name="blogstatus" id="blogstatus">
                                                                <option value="1">Active</option>
                                                                <option value="2">In-Active</option>
                                                            </select>
                                                        </li>
                                                    </ul>

                                                    <center> <input type="button" class="btn-facebook" onclick="updateBlog();" value="Update"/></center>    
                                                </div> 
                                            </section>
                                        </div>
                                    </div>
                                </div><!-- /content-panel -->
                                <input type="hidden" name="method"/>
                                <input type="hidden" name="blogid" id="blogid"/>
                                <input type="hidden" name="imageid" id="imageid"/>
                                <input type="hidden" name="imageflag" id="imageflag"/>
                                <input type="hidden" name="filename"/>
                                <input type="hidden" name="id"/>
                            </form>
                        </div><!-- /col-md-12 -->
                    </div><!-- /row -->
                </section>
            </section><!-- /MAIN CONTENT -->
            <!--main content end-->
            <!--footer start-->
            <?php include './common/footer2.php'; ?>
            <!--footer end-->
        </section>
        <script>

            bodyOnLoad();

            function bodyOnLoad() {
                getHomePageDetails();
                getBannerReferencebyParent();
                getAnnouncementReferencebyParent();
                getReferencebyParent();
                blogGrid();

            }

            function getBanner(obj, parentcode) {
                $.ajax({url: 'action/adminAction.php?method=bannerSliderStatus&parentcode=' + parentcode + '&isuserdriven=' + obj.value,
                    success: function (output) {
                        location.reload();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function getBannerReferencebyParent() {
                $.ajax({url: 'action/adminAction.php?method=getReferencebyParent&parentcode=6',
                    success: function (output) {
                        $('#bannerstatus').val(output);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function getAnnouncementReferencebyParent() {
                $.ajax({url: 'action/adminAction.php?method=getReferencebyParent&parentcode=7',
                    success: function (output) {
                        $('#announcementstatus').val(output);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }
            function getReferencebyParent() {
                $.ajax({url: 'action/adminAction.php?method=getReferencebyParent&parentcode=8',
                    success: function (output) {
                        $('#catstatus').val(output);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            //fetch crop master details
            function getHomePageDetails() {
                var list_target_id = 'bannerSlideGrid'; //first select list ID
                startloader();
                $.ajax({url: 'action/adminAction.php?method=getHomePageDetails',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        $('#minitable').DataTable();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function blogGrid() {
                var list_target_id = 'blogGriddiv'; //first select list ID
                startloader();
                $.ajax({url: 'action/adminAction.php?method=getBlogDetails',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        $('.display').DataTable();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function saveBlog() {
                if (checkMandatoryFormFields(new Array("blogtitle~Blog Title", "blogdesc~Description"))) {
                    var img1 = $('#myBlog').val();
                    if (img1 == '') {
                        alert("Upload Image ! ");
                        return false;
                    }
                    var r = confirm("Are you sure want to Confirm!");
                    if (r) {
                        startloader();
                        document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                        document.forms[0].method.value = 'saveBlog';
                        document.forms[0].submit();
                    }
                }
            }

            function elementToPassHome(elementsToPass) {

                var imageid = elementsToPass[0].split('~');
                var imagedesc = elementsToPass[1].split('~');
                var imagepath = elementsToPass[2].split('~');
                var imageflag = elementsToPass[3].split('~');

                $('#' + imageid[0]).val(imageid[1]);
                $('#' + imagedesc[0]).html(imagedesc[1]);
                document.getElementById(imagepath[0]).src = '..' + imagepath[1];
                $('#' + imageflag[0]).val(imageflag[1]);

            }

            function elementToPassBlog(elementsToPass) {
                var blogid = elementsToPass[0].split('~');
                var imageid = elementsToPass[1].split('~');
                var blogtitle = elementsToPass[2].split('~');
                var blogdesc = elementsToPass[3].split('~');
                var imagepath = elementsToPass[4].split('~');
                var imageflag = elementsToPass[5].split('~');

                $('#' + blogid[0]).val(blogid[1]);
                $('#' + imageid[0]).val(imageid[1]);
                $('#' + blogtitle[0]).val(blogtitle[1]);
                $('#' + blogdesc[0]).val(blogdesc[1]);
                document.getElementById(imagepath[0]).src = '..' + imagepath[1];
                $('#' + imageflag[0]).val(imageflag[1]);

            }

            function saveHomePage() {
                if (checkMandatoryFormFields(new Array("homeimagedesc~Image Description", "homeimagetype~Image Type"))) {
                    var img1 = $('#myFile').val();
                    if (img1 == '') {
                        alert("Upload Image ! ");
                        return false;
                    }
                    startloader();
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].method.value = 'addHomePage';
                    document.forms[0].submit();
                }
            }

            function updateHomePage() {
                var img1 = $('#myFile2').val();
                if (img1 == '') {
                    alert("Upload Image ! ");
                    return false;
                }
                startloader();
                document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                document.forms[0].method.value = 'updateHomePage';
                document.forms[0].submit();
            }

            function updateBlog() {
                if (checkMandatoryFormFields(new Array("editblogtitle~Blog Title", "editblogdesc~Description", "blogstatus~Status"))) {
                    var r = confirm("Are you sure want to Confirm!");
                    if (r) {
                        startloader();
                        document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                        document.forms[0].method.value = 'updateBlog';
                        document.forms[0].submit();
                    }
                }
            }

            function delBlog(blogid, imageid) {
                var r = confirm("Are you sure want to Remove?");
                if (r) {
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].id.value = blogid;
                    document.forms[0].imageid.value = imageid;
                    document.forms[0].method.value = 'saveTrash';
                    document.forms[0].filename.value = 'homePage';
                    document.forms[0].submit();
                }
            }

        </script>
    </body>
</html>
