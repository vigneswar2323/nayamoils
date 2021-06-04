<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';

$DBConnection = new DBConnection();

$mobile = "";
$password = "";
$OTP = "";
$variety_id = "";
$user_id = "";
session_start();
if (isset($_POST['mobile'] )){
    $mobile = stripslashes($_POST['mobile']);
    $select = "SELECT * FROM `registration` WHERE userid = '$mobile' and status='1' AND companyid='1'";
    $check_select = mysqli_query($conn, $select); 
    $numrows=mysqli_num_rows($check_select);
    if($numrows > 0){
        $mobile = stripslashes($_REQUEST['mobile']);
        $mobile = mysqli_real_escape_string($conn,$mobile);
        $password = stripslashes(md5($_REQUEST['password']));
        $password = mysqli_real_escape_string($conn,$password);

        $variety_id = stripslashes($_POST['variety_id']);
        $user_id = stripslashes($_POST['session_id']);
        $query = "SELECT * FROM `registration` WHERE userid='$mobile' and password='$password' and status='1' and is_success = 'S' AND companyid='1'";
        
        $result = mysqli_query($conn, $query) or die(mysql_error());
        if(isset($_POST['otp'])){
            $OTP = stripslashes($_POST['otp']);
            $update_query = "UPDATE registration SET OTP='$OTP' WHERE userid='$mobile'";
            $update_result = mysqli_query($conn, $update_query) or die(mysql_error());
        }
        $rows = mysqli_num_rows($result);
        $fetcher=mysqli_fetch_assoc($result);
        if ($rows == 1) {
            $_SESSION['mobile'] = $mobile;
            $_SESSION['id'] = $fetcher['userid'];
            $_SESSION['firstname'] = $fetcher['firstname'];
            $_SESSION['lastname'] = $fetcher['lastname'];
            $_SESSION['email'] = $fetcher['email'];
            echo json_encode(array("statusCode"=>200, "sessionid"=>$_SESSION['id']));
            $query = "insert into order_summary (variety_id, user_id) values ('$variety_id', '$_SESSION[id]')";
            $query_insert = mysqli_query($conn, $query);
        }
        else{
          echo json_encode(array("statusCode"=>201));
        }
    } else if($numrows == 0){
        echo json_encode(array("statusCode"=>202));
    }


}
?>