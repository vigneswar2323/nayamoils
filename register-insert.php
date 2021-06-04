<?php

include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';

$DBConnection = new DBConnection();

$mobile = "";
$firstname = "";
$lastname = "";
$email = "";
$gender = "";
$password = "";

if (isset($_POST['mobile'])) {

    $firstname = stripslashes($_POST['firstname']);
    $lastname = stripslashes($_POST['lastname']);
    $mobile = stripslashes($_POST['mobile']);
    $email = stripslashes($_POST['email']);
    $password = md5(stripslashes($_POST['password']));
    $otp = stripslashes($_POST['OTP']);
    $select = "SELECT * FROM `registration` WHERE userid = '$mobile'";
    $check_select = mysqli_query($conn, $select);
    $numrows = mysqli_num_rows($check_select);

    if ($numrows == 0) {
        $query = "insert into registration (userid,username,firstname, lastname, mobile, email, password, OTP) values ('$mobile','$firstname','$firstname', '$lastname','$mobile', '$email', '$password', '$otp')";
        $result = mysqli_query($conn, $query);

        $this_id = $conn->insert_id;
        $select_id = "SELECT * FROM `registration` WHERE id = '$this_id'";
        $check_select_id = mysqli_query($conn, $select_id);
        $fetcher = mysqli_fetch_assoc($check_select_id);
        if ($result) {
            echo json_encode(array("statusCode" => 200, "otp_gen" => $fetcher['OTP']));
        } else {
            echo json_encode(array("statusCode" => 201));
        }
    } else if ($numrows > 0) {
        echo json_encode(array("statusCode" => 202));
    }
}
?>