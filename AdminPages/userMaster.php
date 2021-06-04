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
                                <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> User Master <a href="#popup1" class="btn-facebook" style="float: right;margin-right: 10px;padding: 5px;"> Add New User</a></h4>

                                <fieldset class="fieldsetstyle2"><div id="userGrid"></div></fieldset>
                            </div><!-- /content-panel -->
                        </div><!-- /col-md-12 -->
                    </div><!-- /row -->
                </section><! --/wrapper -->

                <!--           new popup alert-->
                <div id="popup1" class="overlay">
                    <div class="popup">
                        <div class="popup_header_new">Add New User<a class="closepopup" href="#">&times;</a></div>
                        <section id="unseen">
                            <form class="form-style-9" style="overflow-y: scroll;height: 500px;" enctype="multipart/form-data" method="post" name="Form">
                                <ul> 
                                    <li>                                                
                                        <a>User Role <span class="mandatory_red">*</span></a>
                                        <select class="field-style field-full" name="usertype" id="usertype"></select>
                                    </li>
                                    <li>                                                
                                        <a>User Id <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="userid" id="userid" onblur="checkUserIdAvailability(this)"/>
                                    </li>
                                    <li>                                                
                                        <a>Display Name <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="displayname" id="displayname"/>
                                    </li>
                                    <li>                                                
                                        <a>First Name</a>
                                        <input type="text" class="field-style field-full" name="fname" id="fname"/>
                                    </li>
                                    <li>                                                
                                        <a>Last Name</a>
                                        <input type="text" class="field-style field-full" name="lname" id="lname"/>
                                    </li>
                                    <li>                                                
                                        <a>Mobile Number <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="mobile" id="mobile" onBlur="checkNumberAvailability(this);
                                                isValidateMobileNumber(this, mobile);"/>
                                    </li>
                                    <li>                                                
                                        <a>Email <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="email" id="email" onBlur="checkEmailAvailability(this);validateEmailid(this);"/>
                                    </li>
                                    <li>                                                
                                        <a>Gender</a>
                                        <select class="field-style field-full" name="gender" id="gender">
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                    </li>
                                    <li>                                                
                                        <a>Password <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="password" id="password"/>
                                    </li>
                                    <li>                                                
                                        <a>Status <span class="mandatory_red">*</span></a>
                                        <select class="field-style field-full" name="userstatus" id="userstatus">
                                            <option value="A">Active</option>
                                            <option value="U">In-Active</option>
                                        </select>
                                    </li>
                                </ul>

                                <center> <input type="button" class="btn-facebook" onclick="saveUser();" value="Submit"/></center>
                                <input type="hidden" name="method"/>
                                <input type="hidden" name="prevuserid" />
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
                getUserGrid();
                getUserRole();

            }

            //fetch crop master details
            function getUserGrid() {
                var list_target_id = 'userGrid'; //first select list ID
                startloader();
                $.ajax({url: 'action/adminAction.php?method=userGrid',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        $('#minitable').DataTable();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function  getUserRole() {
                startloader();
                var list_target_id = 'usertype'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=getUserRole',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function saveUser() {
                if (checkMandatoryFormFields(new Array("usertype~User Role", "userid~User Id", "displayname~Display Name", "userid~User Id", "fname~First Name", "lname~Last Name", "mobile~Mobile No", "email~Email", "gender~Gender", "password~Password"))) {
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].method.value = 'addNewUser';
                    document.forms[0].submit();
                    startloader();
                }
            }

            function editUser(userid) {
                document.forms[0].action = "<?php echo $locationurl; ?>/editUserMaster.php";
                document.forms[0].prevuserid.value = userid;
                document.forms[0].submit();
            }

            function checkUserIdAvailability(obj) {
                var userid = obj.value;
                if (userid == "NULL" || userid == "") {
                    return false;
                } else {
                    $.ajax({url: 'dao/commondao.php?dataType=checkUserIdAvailability&userid=' + userid,
                        beforeSend: function () {
                        },
                        success: function (data) {
                            if (data == '1') {
                                alert("User Id already exists");
                                obj.value = "";
                            } else {
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + " " + thrownError);
                        }});
                }
            }

            function checkNumberAvailability(obj) {
                var number = obj.value;
                if (number == "NULL" || number == "") {
                    return false;
                } else {
                    $.ajax({url: 'dao/commondao.php?dataType=checknumberavailability&number=' + number,
                        beforeSend: function () {
                        },
                        success: function (data) {
                            if (data == '1') {
                                alert("Mobile number already exists");
                                obj.value = "";
                            } else {
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + " " + thrownError);
                        }});
                }
            }

            function checkEmailAvailability(obj) {
                var emailid = obj.value;
                if (emailid == "NULL" || emailid == "") {
                    return false;
                } else {
                    $.ajax({url: 'dao/commondao.php?dataType=checkEmailavailability&emailid=' + emailid,
                        beforeSend: function () {
                        },
                        success: function (data) {
                            if (data == '1') {
                                alert("Email id already exists");
                                obj.value = "";

                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + " " + thrownError);
                        }});
                }
            }

            function isValidateMobileNumber(obj, mobileno) {
                var mobileno = obj.value;
                //var pattern = /^[\s()+-]*([0-9][\s()+-]*){10,10}$/;
                //var pattern = /^([0-9]{10,11})$/i;
                var pattern = /^([0-9]{10,10})$/i;
                if (mobileno.length > 0) {
                    if (!pattern.test(mobileno)) {
                        alert("Please enter valid mobilenumber,mobile number should be 10 digits.");
                        obj.value = "";
                        return false;
                    }
                }
                return true;
            }
            function validateEmailid(txtBoxObj) {
                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                var address = txtBoxObj.value;
                if (address == "NULL" || address == "") {
                    return false;
                } else {

                    if (reg.test(address) == false) {
                        alert('Invalid email id');
                        txtBoxObj.focus();
                        txtBoxObj.value = "";
                        return false;
                    }
                    return true;
                }
            }



        </script>

    </body>
</html>
