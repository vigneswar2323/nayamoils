<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';

$DBConnection = new DBConnection();
session_start();
$otp = "";
$session_id = "";

if (isset($_POST['otp']))
{

    $otp = stripslashes($_POST['otp']);
    $session_id = stripslashes($_POST['session_id']);

    $select = "SELECT * FROM `registration` WHERE otp = '$otp'";
    $check_select = mysqli_query($conn, $select); 
    $numrows=mysqli_num_rows($check_select);
    if($numrows > 0){
        $query = "UPDATE registration SET is_success = 'S' WHERE otp = '$otp'";  
        $query_insert = mysqli_query($conn, $query); 
        echo json_encode(array("statusCode"=>200));
    }
    else if($numrows == 0){
        $query = "UPDATE registration SET is_success = 'N' WHERE otp = '$otp'"; 
        $query_insert = mysqli_query($conn, $query); 
        echo json_encode(array("statusCode"=>202));
    }
}
?>