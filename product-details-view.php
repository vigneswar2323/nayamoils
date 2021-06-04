<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';
$DBConnection = new DBConnection();
$id = $_POST['variety_id'];
$isOpen = constant("PLACE_ORDER_AFFILIATE_LINK");


//get productimage and name
$getproduct = "SELECT vm.id,cm.cropname,vm.variety_description,im.image_path,vm.column2 FROM varietymaster vm LEFT JOIN cropmaster cm on cm.id=vm.cropid LEFT JOIN tbl_imagedetails im ON im.id=vm.imgId WHERE vm.id='$id' AND vm.companyid=1";
$result1 = $conn->query($getproduct);
if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $cropname = isset($row['cropname']) ? $row['cropname'] : '';
        $variety = isset($row['variety_description']) ? $row['variety_description'] : '';
        $image_path = isset($row['image_path']) ? $row['image_path'] : '';
        $column2 = isset($row['column2']) ? $row['column2'] : '';
    }
} else {
    $cropname = '';
    $variety = '';
    $image_path = '/images/oppsimage.jpg';
    $column2 = '';
}
?>
<div class='col-lg-6 col-sm-12 column' style="background: #f2f2f2;">
    <div class='contact-form-right prod-detail'>
        <img height="400" width="400" src=./<?= $image_path; ?>>
    </div>
    <div class="modal-dialog alert-warning">
        <div class="modal-content" >
            <div class="modal-body">
                <?= $column2 ?>
            </div>
        </div>
    </div>
</div>
<div class='col-lg-6 col-sm-12 column'>
    <div class='detail-block'> 
        <h2 style="text-decoration: underline"><?= $cropname; ?></h2>
        <p style="text-decoration: underline"><?= $variety; ?></p>
        <table class='table table-bordered' id='prod-detail-table'>
            <thead><tr><th style="text-align: center;text-decoration: underline" colspan="2">ITEM SPECIFICATION</th></tr></thead>
            <?php
            $sql = "SELECT vm.id,vd.col_name,vd.col_description FROM varietymaster vm LEFT JOIN varietydetails vd ON vd.varietyid=vm.id WHERE vm.id='$id' AND vm.companyid=1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row_prd = $result->fetch_assoc()) {
                    ?>  

                    <tbody>
                        <tr>
                            <td><?= $row_prd['col_name']; ?></td><td><?= $row_prd['col_description']; ?></td>
                        </tr>

                    </tbody>

                    <?php
                }
            } else {
                echo "0 results";
            }
            ?>
        </table>

        <?php
        $sql2 = "SELECT vm.id,vd.qtyperbag,vd.priceperbag,rf.description as mesurementtype,vm.column1 FROM varietymaster vm LEFT JOIN varietypackingdetails vd ON vd.varietyid=vm.id LEFT JOIN referencecodes rf ON rf.parentcode=5 and rf.referencecode=vm.column6 WHERE vm.id='$id' AND vm.companyid=1 AND vm.column8='Y'";
        $result3 = $conn->query($sql2);
        if ($result3->num_rows > 0) {
            ?>
            <table class='table table-bordered' id='prod-detail-table'>
                <thead><tr><th style="text-align: center;text-decoration: underline" colspan="2">AVAILABLE PACKING</th></tr></thead>
                <?php
                while ($row_prd3 = $result3->fetch_assoc()) {
                    $affliatelink = $row_prd3['column1'];
                    ?>  
                    <tbody>
                        <tr>
                            <td><?= $row_prd3['qtyperbag'] . ' ' . $row_prd3['mesurementtype']; ?></td><td>Rs.<?= $row_prd3['priceperbag']; ?></td>
                        </tr>
                    </tbody>
                    <?php
                }
                ?>
            </table>
            <?php
        } 
        mysqli_close($conn);
        ?>

        <div class='row'>
            <?php if (strcmp($isOpen, 'Y') == 0) { ?>
                <a id='placeorder' class='cart' href='<?= $affliatelink ?>' target='_blank'>Place Order</a>
            <?php } else { ?>
                <a class='cart' name='submit' id='addCart'>Place Order</a>

            <?php } ?>
            <a id='placeorder' class='cart' href='index.php'>Back</a>            
        </div>

    </div>
</div>
