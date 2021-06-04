<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';
$DBConnection = new DBConnection();
/* $id = $_POST['session_id']; */
$sql = "SELECT c.id, c.cropname, c.imgId, im.image_path FROM cropmaster c INNER JOIN tbl_imagedetails im ON im.id = c.imgId WHERE c.status=1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row_prd = $result->fetch_assoc()) {
        ?>      
        <div class='col-sm-6 col-md-6 col-lg-3 col-xl-3 special-grid best-seller'>
            <div class='products-single fix card'>
                <div class='box-img-hover'>
                    <div class='type-lb'>
                        <p class='new'>Sale</p>
                    </div>
                    <img src='./<?= $row_prd['image_path']; ?>' class='img-fluid' alt='Image'>
                    <div class='mask-icon'>
                        <a class='cart view-variety' href='shop-detail.php' name='submit'>View</a>
                        <p class='cropid'><?= $row_prd['id']; ?></p>
                    </div>
                </div>
                <div class='why-text'>
                    <div class="postdiv">
                        <h2 class="postdiv-title"><img src="images/rightfinger.png" width="30" height="30" ><a style="cursor: pointer" onclick='getimg(<?= $processingid ?>)'><?= $row_prd['cropname']; ?></a></h2>
                    </div>
                    <!-- <h5> $9.79</h5> -->
                </div>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="no-result alert-danger" style="width:100%;text-align:center;">
        <p>Stock Unavilable !!!</p>
    </div>
<?php
}
mysqli_close($conn);
?>


