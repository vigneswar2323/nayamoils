<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';

    $DBConnection = new DBConnection();
    $order_summary_id=$_POST['order_summary_id'];

    $sql = "UPDATE `order_summary` SET `status`='U' WHERE id=$order_summary_id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(array("statusCode"=>200));
    } 
    else {
        echo json_encode(array("statusCode"=>201));
    }
    mysqli_close($conn);


?>