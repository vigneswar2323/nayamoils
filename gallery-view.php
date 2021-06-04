<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';
$DBConnection = new DBConnection();
$galleryflag = $_POST['galleryflag'];
if ($galleryflag != 1) {
    $sql = "SELECT g.description,im.image_path FROM tbl_gallery g left join tbl_imagedetails im on im.id=g.imageid WHERE image_flag='G' and g.gallery_flag='$galleryflag' and g.status=1";
} else {
    $sql = "SELECT g.description,im.image_path FROM tbl_gallery g left join tbl_imagedetails im on im.id=g.imageid WHERE image_flag='G' and g.status=1";
}

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row_prd = $result->fetch_assoc()) {
        ?>      
        <div class='col-sm-6 col-md-6 col-lg-3 col-xl-3'>
            <div class='products-single fix'>
                <div class='box-img-hover'>
                    <div class='type-lb'>
                        <p class='sale'></p>
                    </div>
                    <img src='./<?= $row_prd['image_path']; ?>' class='img-fluid' alt='Image'>
                    <div class='mask-icon'>
                        <a class='cart view-variety' href='#' name='submit'>View</a>
                        <p class='cropid'></p>
                    </div>
                </div>
                <div class='why-text'>
                    <h4><?= $row_prd['description']; ?></h4>
                    <!-- <h5> $9.79</h5> -->
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<div class="no-result alert-danger" style="width:100%;text-align:center;">
                <p>No Records Found !!</p>
            </div>';
}
mysqli_close($conn);
?>


