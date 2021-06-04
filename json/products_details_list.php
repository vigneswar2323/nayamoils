<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

include_once("../connection/DBConnection.php");
include '../common/ApplicationConstant.php';
$DBConnection = new DBConnection();

$scnList_array = array();
$main_array = array();
$details_array = array();
$details_array2 = array();
$id = $_POST['crop_id'];
$rowperpage = 32;

$rows = isset($_POST['rows']) ? $_POST['rows'] : '0';
//print_r($id);
$fetch_users = mysqli_query($conn, "select vm.*,cm.cropname,i.image_path from varietymaster vm left join tbl_imagedetails i on i.id=vm.imgId left join cropmaster cm on cm.id=vm.cropid where cm.id=$id AND vm.status=1 limit $rows,$rowperpage") or die(mysqli_error($conn));
 if ($fetch_users->num_rows > 0) {
while ($row_users = mysqli_fetch_assoc($fetch_users)) {

    //print_r($row_users);
	$varietyid = $row_users['id'];
    $main_array['varietyid'] = $row_users['id'];
    $main_array['cropname'] = $row_users['cropname'];
    $main_array['variety_description'] = $row_users['variety_description'];
    $main_array['image_path'] = $row_users['image_path'];
	
	
    $main_array['variety_details'] = array();
	$main_array['variety_packing_details'] = array();

    $fetch_notes = mysqli_query($conn, "select * from varietydetails where varietyid='$varietyid' limit 10") or die(mysqli_error($conn));


    while ($row_notes = mysqli_fetch_assoc($fetch_notes)) {

		$details_array['vdetailsid'] = $row_notes['vdetailsid'];
        $details_array['col_name'] = $row_notes['col_name'];
        $details_array['col_description'] = $row_notes['col_description'];
        
        array_push($main_array['variety_details'], $details_array);
    }
	
	$fetch_notes2 = mysqli_query($conn, "select * from varietypackingdetails where varietyid='$varietyid'") or die(mysqli_error($conn));
    while ($row_notes2 = mysqli_fetch_assoc($fetch_notes2)) {
		$details_array2['packingid'] = $row_notes2['packingid'];
        $details_array2['noofbags'] = $row_notes2['noofbags'];
        $details_array2['qtyperbag'] = $row_notes2['qtyperbag'];
		$details_array2['totalquantity'] = $row_notes2['totalquantity'];
        $details_array2['priceperbag'] = $row_notes2['priceperbag'];
		$details_array2['totalprice'] = $row_notes2['totalprice'];
        
        array_push($main_array['variety_packing_details'], $details_array2);
    }

    array_push($scnList_array, $main_array);
}
$jsonData = json_encode($scnList_array, JSON_UNESCAPED_SLASHES);

echo $jsonData;

}
else{
echo json_encode(array("statusCode"=>200));
}
?>