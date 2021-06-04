<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';
$DBConnection = new DBConnection();
$cropid = $_POST['cropid'];
$rowperpage = 32;
$rows = isset($_POST['rows']) ? $_POST['rows'] : '0';

$sqlallcount = "SELECT count(*) as countvalue  FROM varietymaster  WHERE status=1 and cropid='$cropid'";
$resultcount = $conn->query($sqlallcount);
$row = mysqli_fetch_assoc($resultcount);
$allcount = $row['countvalue'];

$cropsql = "select * from cropmaster where id='$cropid'";
$result = mysqli_query($conn, $cropsql);
$row = mysqli_fetch_assoc($result);
$cropname = $row['cropname'];
?>
<h3 class="annocement" style="width: 100%;height:50px;margin-bottom: 10px;">
    <a href="#"><img src="images/animationdownarrow_200.gif" width="30" height="30" ><u><?= $cropname ?></u></a>
</h3>

<?php
$sql = "SELECT vm.id, vm.cropid, vm.variety_description, im.image_path, c.cropname, vm.column1, vm.column2, vm.column3, vm.column4, vm.column5, vm.column6,vm.column9 FROM varietymaster vm INNER JOIN cropmaster c ON vm.cropid= c.id INNER JOIN tbl_imagedetails im ON im.id= vm.imgid WHERE vm.cropid='$cropid' and vm.status=1 ORDER BY vm.id DESC limit $rows,$rowperpage";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row_prd = $result->fetch_assoc()) {
        $shortcontent = substr($row_prd['variety_description'], 0, 160) . "...";
        $id = $row_prd['id'];
        ?>      
        <div class='post col-sm-6 col-md-6 col-lg-3 col-xl-3 special-grid best-seller' id="post_<?= $id ?>">
            <div class='products-single fix card'>
                <div class='box-img-hover' >
                    <div class='type-lb'>
                        <p class='new'><?= $row_prd["column3"] ?></p>
                    </div>
                    <div class='flag blueflag'>SALE </div>
                    <img src='./<?= $row_prd['image_path']; ?>' class='img-fluid' alt='Image'>
                    <div class='mask-icon'>
                        <a class='cart view-variety' href='productDetails.php' name='submit'>View</a>
                        <p class='varietyid'><?= $row_prd['id']; ?></p> 
                    </div>
                </div>
                <div class='why-text'>
                    <div class="postdiv">
                        <h2 class="postdiv-title"><a style="cursor: pointer" onclick='getimg(<?= $processingid ?>)'><?= $shortcontent ?></a></h2>
                        <div class="mrpprice"><a>Rs.<?= $row_prd['column9'] ?></a></div>
                        <div class="dealprice dprice"><a>Rs.<?= $row_prd['column4'] ?></a></div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="rows" value="0">
        <input type="hidden" id="all" value="<?php echo $allcount; ?>">
        <input type="hidden" id="rowperpage" value="<?php echo $rowperpage; ?>">
        <?php
    }
} else {
    ?>
    <div class="no-result alert-danger" style="width: 100%;text-align: center;">
        <p>Stock Unavilable !!!</p>
    </div>
    <?php
}
mysqli_close($conn);
?>
