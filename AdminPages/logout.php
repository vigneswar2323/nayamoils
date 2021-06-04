<?php
require_once '../common/ApplicationConstant.php';
$locationurl = SITE_URL_ADMIN;
session_start();
unset($_SESSION["userdetails"]);
session_destroy();
header("Location:$locationurl/adminLogin.php");
?>