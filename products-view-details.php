<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';

$DBConnection = new DBConnection();
$id = $_POST['id'];
$sql = "SELECT v.id, v.cropid, v.variety_description, d.vdetailsid, d.varietyid, d.col_name, d.col_description FROM varietymaster v LEFT JOIN varietydetails d ON v.id = d.varietyid WHERE v.cropid = '$id' ORDER BY v.variety_description ASC";


    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row_prd = $result->fetch_assoc()) {
?> 
                <li>&#9830;&nbsp;<?=$row_prd['col_name']."-".$row_prd['col_description'];?></li>
        <?php   
        }
    }
    else {
        echo "0 results";
    }
    mysqli_close($conn);
?>