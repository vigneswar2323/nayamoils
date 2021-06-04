<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';

$DBConnection = new DBConnection();

$qty = "";

if (isset($_POST['qty']))
{

    $qty = stripslashes($_POST['qty']);
    $variety_id = stripslashes($_POST['variety_id']);

    if($_POST['session_id'] != ''){
        $user_id = stripslashes($_POST['session_id']);
        $select = "SELECT * FROM `registration` WHERE userid = '$user_id'";
        $check_select = mysqli_query($conn, $select); 
        $numrows=mysqli_num_rows($check_select);
        if($numrows > 0){
          $query = "insert into order_summary (variety_id, qty, user_id) values ('$variety_id', '$qty', '$user_id')";
            $result = mysqli_query($conn, $query);
            if ($result) {
              echo json_encode(array("statusCode"=>200));
            } 
            else {
              echo json_encode(array("statusCode"=>201));
            }
        }
        else if($numrows == 0){
            echo json_encode(array("statusCode"=>202));
        }

    }else{
        echo json_encode(array("statusCode"=>203));
    }
}
?>