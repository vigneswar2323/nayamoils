<?php
// configuration
include './connection/DBConnection.php';
$cropid = '220210045';
$row = $_POST['row'];
$rowperpage = 3;
$DBConnection = new DBConnection();
// selecting posts
$sql = "SELECT vm.id, vm.cropid, vm.variety_description, c.cropname, vm.column1, vm.column2, vm.column3, vm.column4, vm.column5, vm.column6,vm.column9,imd.image_path as affiliatelogo, im.is_new, im.image_path, im.image_flag,vm.createddate,vm.createdtimestamp FROM cropmaster c LEFT JOIN varietymaster vm ON c.id = vm.cropid LEFT JOIN tbl_imagedetails im ON im.id= vm.imgId LEFT JOIN tbl_imagedetails imd on imd.id=vm.column7 WHERE 
                        im.is_new = '1' AND im.image_flag!='H' and vm.status=1 and vm.cropid='$cropid' ORDER BY vm.id DESC limit $row,$rowperpage";
$result = mysqli_query($conn, $sql);

while ($hm_sec_row = mysqli_fetch_array($result)) {
    $id = $hm_sec_row['id'];
    $createddate = $hm_sec_row['createddate'];
    $timestamp = $hm_sec_row['createdtimestamp'];

    $lastseen = '';
    $processingid = json_encode($hm_sec_row["column1"]);
    ?>     

    <div class='post col-lg-3 col-md-6 special-grid best-seller' id="post_<?= $id ?>">
        <div class='products-single fix card'>
            <div class='box-img-hover'>                    
                <div class='flag rowflag'><?= $hm_sec_row['cropname'] ?> </div>
                <img src='<?= './' . $hm_sec_row['image_path'] ?>' class='img-fluid' alt='Image' onclick='getimg(<?= $processingid ?>)'>
            </div>

            <div class='view-details-btn'>
    <!--                    <a class='cart btn hvr-hover view-variety' href='productDetails.php?id=<?= $hm_sec_row['id'] ?>' name='submit' style='width:100%;color:white;'>View Details</a>-->
                <p id='varietyid' style='display:none;'><?= $hm_sec_row['id'] ?></p>
            </div>
            <div class='why-text'>

                <div class="postdiv">
                    <div class='storename'>
                        <img src='<?= './' . $hm_sec_row['affiliatelogo'] ?>'>
                    </div>
                    <div class="getdeal"> <i class="fa fa-shopping-cart">Get Deal</i></div>
                    <h2 class="postdiv-title pdetail"><a href='productDetails.php?id=<?= $hm_sec_row['id'] ?>' target='_blank'><?= $hm_sec_row['variety_description'] ?></a></h2>
                    <div class="mrpprice">Rs.<?= $hm_sec_row['column9'] ?></div>
                    <div class="dealprice dprice">Rs.<?= $hm_sec_row['column4'] ?></div>
                </div>
                <div class="grid-footer">
                    <div class="pcomments"><i class="fa fa-comment"><span>0</span></i></div>
                    <div class="ptime"><a href="#"><i class="fa fa-clock"><span><?= $lastseen; ?></span></i></a></div>
                    <div class="gdiscount"><i class="fa fa-hand-o-right"><span class="per"><?= $hm_sec_row['column3']; ?>%</span></i></div>
                </div>
            </div>
        </div>
    </div> 
    <?php
}
?>