<?php
class DBConnection {
    function __construct() {
        
        //for development
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "nayamoils_db";

        
        //godaddy test environment
//        $servername = "50.62.209.73:3306";
//        $username = "nayamoil_test";
//        $password = "Nayamdb@2323";
//        $dbname = "nayamoils_db";
        
        //for production digital ocean cloud nayamoils.com
//        $servername = "localhost:3306";
//        $username = "admin_nayamoils";
//        $password = "P47rr6v~";
//        $dbname = "nayamoils_db";
        
        //for production digital ocean cloud classyproduct.in
//        $servername = "localhost:3306";
//        $username = "classyproduct_admin";
//        $password = "My123Root";
//        $dbname = "nayamoils_db";

        $conn = new mysqli($servername, $username, $password);
        // Check connection
        if ($conn->connect_error) {
            header("Location: defaultPage.php");
            die("Connection failed: " . $conn->connect_error);
        }else{
            $GLOBALS['conn'] = new mysqli($servername, $username, $password, $dbname);
        }        
    }
}
?>