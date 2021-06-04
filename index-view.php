<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';
include_once './dao/CommonDao.php';

$CommonDao = new CommonDao();
$DBConnection = new DBConnection();

date_default_timezone_set("Asia/Kolkata");
$cropid = $_POST['cropid'];
$rowperpage = 32;
$rows = isset($_POST['rows']) ? $_POST['rows'] : '0';

$sqlallcount = "SELECT vm.id, vm.cropid, vm.variety_description, c.cropname, vm.column1, vm.column2, vm.column3, vm.column4, vm.column5, vm.column6,vm.column9,imd.image_path as affiliatelogo, im.is_new, im.image_path, im.image_flag,vm.createddate,vm.createdtimestamp FROM cropmaster c LEFT JOIN varietymaster vm ON c.id = vm.cropid LEFT JOIN tbl_imagedetails im ON im.id= vm.imgId LEFT JOIN tbl_imagedetails imd on imd.id=vm.column7 WHERE 
                        im.is_new = '1' AND im.image_flag!='H' and vm.status=1 and vm.cropid='$cropid'";

$resultcount = $conn->query($sqlallcount);
$allcount = $resultcount->num_rows;

$sql = "SELECT vm.id, vm.cropid, vm.variety_description, c.cropname, vm.column1, vm.column2, vm.column3, vm.column4, vm.column5, vm.column6,vm.column9,imd.image_path as affiliatelogo, im.is_new, im.image_path, im.image_flag,vm.createddate,vm.createdtimestamp FROM cropmaster c LEFT JOIN varietymaster vm ON c.id = vm.cropid LEFT JOIN tbl_imagedetails im ON im.id= vm.imgId LEFT JOIN tbl_imagedetails imd on imd.id=vm.column7 WHERE 
                        im.is_new = '1' AND im.image_flag!='H' and vm.status=1 and vm.cropid='$cropid' ORDER BY vm.id DESC limit $rows,$rowperpage";
$result = $conn->query($sql);
if ($result->num_rows > 0) {

    while ($hm_sec_row = $result->fetch_assoc()) {
        $id = $hm_sec_row['id'];
        $createddate = $hm_sec_row['createddate'];
        $timestamp = $hm_sec_row['createdtimestamp'];
        $shortcontent = substr($hm_sec_row['variety_description'], 0, 160) . "...";

        $lastseen = $CommonDao->getDateDiff($timestamp);
        $processingid = json_encode($hm_sec_row["column1"]);
        $afflogo = $hm_sec_row['affiliatelogo'];
        $afflogo = $CommonDao->defaultimage($afflogo);
             
        ?>     
        <div class='post col-lg-3 col-md-6 special-grid best-seller' id="post_<?= $id ?>">
            <div class='products-single fix card'>
                <div class='box-img-hover'>   
                    <div class='type-lb'>
                        <p class='new'><?= $hm_sec_row["column3"] ?></p>
                    </div>
                    <div class='flag rowflag'><?= $hm_sec_row['cropname'] ?> </div>
                   <img style="cursor: pointer" src='<?= './' . $hm_sec_row['image_path'] ?>' class='img-fluid' alt='Image' onclick='getimg(<?= $processingid ?>)'>
                </div>

                <div class='view-details-btn'>
        <!--                    <a class='cart btn hvr-hover view-variety' href='productDetails.php?id=<?= $hm_sec_row['id'] ?>' name='submit' style='width:100%;color:white;'>View Details</a>-->
                    <p id='varietyid' style='display:none;'><?= $hm_sec_row['id'] ?></p>
                </div>
                <div class='why-text'>
                    <div class="postdiv">
                        <div class='storename' style="cursor: pointer">
                            <img src='<?= './' . $afflogo ?>' onclick='getimg(<?= $processingid ?>)'>
                        </div>
                        <div class="getdeal"> <i class="fa fa-eye"> <a class="pdetail" href='productDetails.php?id=<?= $hm_sec_row['id'] ?>'>Details</a></i></div>
                        <h2 class="postdiv-title"><a style="cursor: pointer" onclick='getimg(<?= $processingid ?>)'><?= $shortcontent ?></a></h2>
                        <div class="mrpprice"><a>Rs.<?= $hm_sec_row['column9'] ?></a></div>
                        <div class="dealprice dprice"><a>Rs.<?= $hm_sec_row['column4'] ?></a></div>
                    </div>
<!--                    <div class="grid-footer">
                                <div class="pcomments"><i class="fa fa-comment"> <span>0</span></i></div>
                        <div class="ptime"><a href="#"><i class="fa fa-clock"> <span><?= $lastseen; ?></span></i></a></div>
                        <div class="gdiscount"><i class="fa fa-hand-o-right"> <span class="per"><?= $hm_sec_row['column3']; ?></span></i></div>
                    </div>-->
                </div>
            </div>
        </div>
        <input type="hidden" id="rows" value="0">
        <input type="hidden" id="all" value="<?php echo $allcount; ?>">
        <input type="hidden" id="rowperpage" value="<?php echo $rowperpage; ?>">
        <?php
    }
} else {
    echo '<div class="no-result alert-danger" style="width:100%;text-align:center;">
                <p>No Records Found !!</p>
            </div>';
}
mysqli_close($conn);
?>


