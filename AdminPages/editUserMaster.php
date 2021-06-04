<?php
require_once '../common/ApplicationConstant.php';
$loader_Path = constant("LOADER");
$locationurl = SITE_URL_ADMIN;
$message = "";
$userid = $_POST['prevuserid'];
$prevpagename = 'userMaster.php';

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
                                <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> <a onclick="backbtn('<?php echo $prevpagename ?>')"> User Master</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Edit User </h4>
                                <div class="w3-container">
                                    <div class="w3-container city">
                                        <section id="unseen">
                                            <form class="form-style-9" enctype="multipart/form-data" method="post" name="Form">
                                                <ul> 
                                                    <li>                                                
                                                        <a>User Role</a>
                                                        <select class="field-style field-full" name="usertype" id="usertype"></select>
                                                    </li>
                                                    <li>                                                
                                                        <a>User Id</a>
                                                        <input type="text" class="field-style field-full" name="userid" id="userid" readonly/>
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
                                                            <option value="1">Active</option>
                                                            <option value="2">In-Active</option>
                                                        </select>
                                                    </li>
                                                </ul>

                                                <center> 
                                                    <input type="button" class="btn-cancel" onclick="backbtn('<?php echo $prevpagename ?>');" value="Cancel"/>
                                                    <input type="button" class="btn-facebook" onclick="updateUser();" value="Update"/>
                                                </center>
                                                <input type="hidden" name="method"/>
                                            </form> 
                                        </section>
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
                getUserDetails();
            }

            function updateUser() {
                if (checkMandatoryFormFields(new Array("usertype~User Role", "userid~User Id", "displayname~Display Name", "userid~User Id", "fname~First Name", "lname~Last Name", "mobile~Mobile No", "email~Email", "gender~Gender", "password~Password"))) {
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].method.value = 'editUser';
                    document.forms[0].submit();
                    startloader();
                }
            }


            //fetch crop master details
            function getUserDetails() {
                let userid = '<?php echo $userid ?>';
                startloader();
                $.ajax({url: 'action/adminAction.php?method=getUserDetails&userid=' + userid,
                    success: function (output) {
                        var splitdata = output.split('~');

                        var userid = splitdata[0];
                        var displayname = splitdata[1];
                        var fname = splitdata[2];
                        var lname = splitdata[3];
                        var mobile = splitdata[4];
                        var email = splitdata[5];
                        var gender = splitdata[6];
                        var userstatus = splitdata[7];
                        var usertype = splitdata[8];


                        $('#usertype').append(usertype);
                        $('#userid').val(userid);
                        $('#displayname').val(displayname);
                        $('#fname').val(fname);
                        $('#lname').val(lname);
                        $('#mobile').val(mobile);
                        $('#email').val(email);
                        $('#gender').val(gender);
                        $('#userstatus').val(userstatus);

                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
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
