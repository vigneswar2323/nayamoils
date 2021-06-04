<?php

include_once '../connection/DBConnection.php';
include_once '../dao/dao.php';
$DBConnection = new DBConnection();
$dao = new dao();
session_start();

if (isset($_POST["method"])) {
    $method = $_POST["method"];
    $pagename = isset($_POST['pagename']) ? $_POST['pagename'] : 'No Titile';
} else {
    $method = $_GET["method"];
    $pagename = isset($_GET['pagename']) ? $_GET['pagename'] : 'No Titile';
}

switch ($method) {
    //save contact
    case 'saveContact':
        $dao->saveContact($conn, $_POST);
        break;
    
    default:
        break;
}
?>