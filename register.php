<!DOCTYPE html>
<head>
    <?php
    include_once("./common/headerscripting.php");
    include_once("./connection/DBConnection.php");
    include './common/ApplicationConstant.php';

    $DBConnection = new DBConnection();
    $siteurl = constant('SITE_URL');
    $CPOMANYID = constant('CPOMANYID');


    $logoqry = "SELECT * FROM tbl_imagedetails WHERE image_flag='L' AND companyid='$CPOMANYID'";
    $tmp = mysqli_query($conn, $logoqry);
    while ($row = mysqli_fetch_assoc($tmp)) {
        $logopath = $row['image_path'];
    }
    $pagename = '';
    include_once './common/commonpage.php';
    ?>
</head>
<body>
    
    <!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Home / Register</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Register</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->
    <!-- Start Register Page  -->
    <div class="shop-box-inner">
        <div class="container">
            <div class="row">
                <button type="button" class="btn btn-primary hidden" data-toggle="modal" data-target="#myModal" id="popupBtn">
                    Open modal
                </button>
                <!-- The Modal -->
                <div class="modal" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content alert-warning">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <div class="alert">
                                    <strong>Warning!</strong> <br><p id="msg-lbl">This alert box could indicate a successful or positive action.<p>
                                </div>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <!--  <div class="modal-body">
                               Modal body..
                             </div> -->

                            <!-- Modal footer -->


                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-sm-12 col-xs-12 offset-md-2">
                    <div class="register-block">
                        <h4 class="reg-title">Register Here</h4>
                        <form  method='post' autocomplete="off" id="fupForm">
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">First Name</label>
                                        <input type="text" placeholder="First Name" id="firstname" class="form-control" name="firstname" required data-error="Please enter your firstname">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Last Name</label>
                                        <input type="text" class="form-control" id="lastname" name=" lastname" placeholder="Last Name" required data-error="Please enter your  lastname">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required data-error="Please enter your email">
                                        <span class="check-mail hidden">*Password maximum have 8 characters*  </span>
                                        <div class="help-block with-errors">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1"> Mobile Number</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile number" required data-error="Please enter your mobile number">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required  autocomplete="off" data-error="Please enter your Subject" />
                                        <span class="check-len">*Password maximum have 8 characters*  </span><span class="hidden spec-char">*Alteast have one speacial character*</span>
                                        </span>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" placeholder="Confirm Password" required data-error="Please enter your Subject">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-8 offset-md-2">
                                    <div class="submit-button text-center">
                                        <input type="button" name="save" class="btn hvr-hover" value="Register"id="butsave">
                                        <div id="msgSubmit" class="h3 text-center hidden"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Register Page -->
    <?php
    require("./common/footerscripting.php");
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
        eval(function (p, a, c, k, e, d) { e = function (c) { return c }; if (!''.replace(/^/, String)) { while (c--) { d[c] = k[c] || c } k = [function (e) { return d[e] } ]; e = function () { return '\\w+' }; c = 1 }; while (c--) { if (k[c]) { p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c]) } } return p } ('(6($){$.58.5=6(2){3 31={5:10,13:33,9:30};3 2=$.54(31,2);20 14.42(6(){3 4=$(14);12(2.13){12(2.9==30||2.9.11==0){3 32=\'<27 41 = "66:\'+4[0].49+\'48;47-50:51"></27>\';4.53(32)}}4.7(4.7().28(0,2.5));18(14,2.5,2.9,2.13);4.25("46",6(19){3 23=19.23?19.23:19.44;3 29=36 35(8,37,38,39,40);55(3 21=0;21<29.11;21++){12(23==29[21])20 33}20 18(14,2.5,2.9,2.13)});4.25("67",6(19){18(14,2.5,2.9,2.13)});4.25(\'62 57 56\',6(){3 24=$(14);59(6(){24.7(24.7().28(0,2.5));18(24,2.5,2.9,2.13)},63)})})}})(68);6 18(16,22,15,34){3 11=$(16).7().11;3 17=22-11;12(17<0){$(16).7($(16).7().28(0,22));17=0}12(34){12(15==30||15.11==0){15=$(16).52()}3 26=15[0].45.65();12(26=="61"||26=="27"){15.64(17+" 69"+(17>1?"60":"")+" 43.")}}20 11<=22-1}', 10, 70, '||options|var|textBox|MaxLength|function|val||CharacterCountControl||length|if|DisplayCharacterCount|this|control|t|characters|SetCharacterCount|e|return|i|maxLength|keyCode|textarea|bind|tagName|div|substring|codes|null|defaults|counter|true|isVisible|Array|new|||||style|each|left|which|nodeName|keypress|text|px|offsetWidth|align|right|next|after|extend|for|blur|drop|fn|setTimeout|s|span|paste|100|html|toLowerCase|width|keyup|jQuery|character'.split('|'), 0, {}))

                $('#butsave').on('click', function() {
        $("#butsave").attr("disabled", "disabled");
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var mobile = $('#mobile').val();
        var password = $('#password').val();
        var confirm_password = $('#confirm_password').val();
        var OTP = Math.floor(100000 + Math.random() * 900000);
        if (firstname != "" && lastname != "" && email != "" && mobile != "" && password != "" && confirm_password != ""){
        $.ajax({
        url: "register-insert.php",
                type: "POST",
                data: {
                firstname: firstname,
                        lastname: lastname,
                        email: email,
                        mobile: mobile,
                        password: password,
                        OTP: OTP
                },
                cache: false,
                success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                console.log(dataResult);
                if (dataResult.statusCode == 200){
                $("#butsave").removeAttr("disabled");
                $('#fupForm').find('input:text').val('');
                $('#msg-lbl').text(dataResult.otp_gen)
                        $('#popupBtn').click();
                setTimeout(function(){
                window.location.href = "registration-otp.php";
                }, 600);
                }
                else if (dataResult.statusCode == 201){
                $('#msg-lbl').text('Error occured !')
                        $('#popupBtn').click();
                }
                else if (dataResult.statusCode == 202){
                $('#msg-lbl').text('The user already exist!!')
                        $('#popupBtn').click();
                setTimeout(function(){
                location.reload();
                }, 600);
                }
                }
        });
        }
        else{
        $('#msg-lbl').text('Please fill all the field !!')
                $('#popupBtn').click();
        }
        });
        $("#password").MaxLength({
        MaxLength: 8,
                DisplayCharacterCount: false
        });
        $("#mobile").MaxLength({
        MaxLength: 10,
                DisplayCharacterCount: false
        });
        });
        $('#password').blur(function(){
        var str = $('#password').val();
        if (str.length > 3){
        if (/^[a-zA-Z0-9- ]*$/.test(str) == false) {
        $('.check-len').addClass('hidden');
        $('.spec-char').addClass('hidden');
        $('#confirm_password').focus();
        }
        else{
        $('#password').focus();
        $('.check-len').addClass('hidden');
        $('.spec-char').removeClass('hidden')

        }
        }

        });
        $('#email').blur(function(){
        if ($('#email').val() != ''){
        ValidateEmail($('#email').val());
        }

        });
        $('#userInfo').click(function(){
        $('body').addClass('on-side');
        $('.side').addClass('on');
        });
        function ValidateEmail(inputText)
        {
        var atposition = inputText.indexOf("@");
        var dotposition = inputText.lastIndexOf(".");
        if (atposition < 1 || dotposition < atposition + 2 || dotposition + 2 >= inputText.length){

        $('#email').focus();
        $('.check-mail').removeClass('hidden');
        $('.check-mail').text("**You have entered an invalid email address!**");
        return false;
        }
        else{
        $('.check-mail').addClass('hidden');
        $('#mobile').focus();
        return true;
        }

        }







    </script>
</body>
</html>