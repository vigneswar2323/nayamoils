<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';

$DBConnection = new DBConnection();

$username = "1";
$firstname = "";
$lastname = "";
$mobile="";
$email = "";
$gender = "";
$password = "";

if (isset($_POST['username']))
{

	/*print_r($username);exit();*/
    $username = stripslashes($_POST['username']);
    $firstname = stripslashes($_POST['firstname']);
    $lastname = stripslashes($_POST['lastname']);
    $mobile = stripslashes($_POST['mobile']);
    $email = stripslashes($_POST['email']);
    $gender = stripslashes($_POST['gender']);
    $password = stripslashes($_POST['password']);

    $query = "insert into registration (username, firstname, lastname, mobile,email, gender, password) values ('$username', '$firstname', '$lastname','$mobile', '$email', '$gender', '$password')";
    /*print_r($query);exit();*/
    $result = mysqli_query($conn, $query);
    if($result)
     {

      echo 'Successfully Registered';
      header('Location: registration-otp.php');
     } 

}
?>
