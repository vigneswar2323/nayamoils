<?php

include_once ("./connection/DBConnection.php");
include './common/ApplicationConstant.php';
$DBConnection = new DBConnection();
/* $data = ''; */
$id = $_POST['id'];

$sql = "SELECT v.id, v.cropid, v.imgId, v.variety_description, i.id AS img_master_id, i.image_path, d.varietyid, d.col_name, d.col_description FROM varietymaster v LEFT JOIN tbl_imagedetails i ON i.id = v.imgId LEFT JOIN varietydetails d ON v.id = d.varietyid WHERE v.cropid = '$id'";

$result = $conn->query($sql);
/* if ($result->num_rows > 0) { */
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data = array(
        'id' => $row['id'],
        'image_path' => $row['image_path'],
        'variety_description' => $row['variety_description']
    );
}
echo json_encode($data);
/* }
  else {
  echo "0 results";
  }
  mysqli_close($conn); */
?>
