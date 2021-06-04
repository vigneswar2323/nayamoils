<?php 
require_once '../common/ApplicationConstant.php';
$locationurl = SITE_URL_ADMIN;
session_start();
$message=""; 


if(isset($_SESSION["message"]) ? $_SESSION["message"] : ''){
   $message = $_SESSION["message"];    
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include('./common/headerscripting.php'); ?>
  </head>

<body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

	  <div id="login-page">
	  	<div class="container">
	  	
                    <form class="form-login" action="action/adminAction.php" method="post">
                         <?php if($message){
                             echo '<h2 class="form-login-danger">' .$message. '</h2>'; 
                         }
                        
                         ?>
                         
		        <h2 class="form-login-heading">sign in now</h2>
		        <div class="login-wrap">
		            <input type="text" class="form-control" name="mobile"  value="" placeholder="User ID" autofocus>
		            <br class="form-login-danger">
		            <input type="password" class="form-control" name="password" value="" placeholder="Password">
		            <label class="checkbox">
		                <span class="pull-right">
<!--		                    <a data-toggle="modal" href="login.php#myModal"> Forgot Password?</a>-->
		                </span>
		            </label>
                            <input type="hidden" name="method" value="login">
                           <i class="fa fa-lock"></i>
<!--                           <a data-toggle="modal" class="btn btn-theme btn-block" href="login.php#myModal"> SIGN IN</a>-->
                           <input type="submit" class="btn btn-theme btn-block" value="SIGN IN">
		           
		            <hr>
<!--		            <div class="registration">
		                Don't have an account yet?<br/>
		                <a class="" href="#">
		                    Create an account
		                </a>
                                
		            </div>-->
		
		        </div>
                        
                        <!-- Modal -->
		          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">OTP Authentication Required?</h4>
		                      </div>
		                      <div class="modal-body">
		                          <p>Enter your otp.</p>
		                          <input type="text" name="email" placeholder="opt is here" autocomplete="off" class="form-control placeholder-no-fix">
		
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
		                          <button class="btn btn-theme" type="button">Submit</button>
		                      </div>
		                  </div>
		              </div>
		          </div>
		          <!-- modal -->
		
		          <!-- Modal -->
<!--		          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">Forgot Password ?</h4>
		                      </div>
		                      <div class="modal-body">
		                          <p>Enter your e-mail address below to reset your password.</p>
		                          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
		
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
		                          <button class="btn btn-theme" type="button">Submit</button>
		                      </div>
		                  </div>
		              </div>
		          </div>-->
		          <!-- modal -->
		
		      </form>	  	
	  	
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("assets/img/loginbg1.jpg", {speed: 500});
    </script>


  </body>
</html>
