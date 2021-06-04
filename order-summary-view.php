<?php
    include_once("./connection/DBConnection.php");
    include './common/ApplicationConstant.php';
    $DBConnection = new DBConnection();
    $id = $_POST['session_id'];//user id
    $sql = "SELECT o.id, o.variety_id, o.qty, o.user_id, r.userid as regid, r.firstname, vm.id as varid, vm.variety_description, vm.column1, vm.column2, vm.column3, vm.column4, vm.column5, vm.column6, c.id as cropid, c.cropname, im.id as imgid, im.image_path, im.image_content, im.is_new, im.image_flag FROM order_summary o INNER JOIN registration r ON o.user_id = r.userid INNER JOIN varietymaster vm ON o.variety_id = vm.id INNER JOIN cropmaster c ON vm.cropid = c.id INNER JOIN tbl_imagedetails im ON vm.imgId = im.id WHERE o.user_id ='$id' AND o.status = 'A'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
        while($row_prd = $result->fetch_assoc()) {
        ?>
        <form  method='post' autocomplete="off" id="fupForm">
        <ul class='cart-list'>
            <li class='d-flex justify-content-start align-items-start prod-li'>
                <a href='#' class='photo'><img src='./<?=$row_prd['image_path'];?>' class='cart-thumb' alt='' /></a>
                <div class='col-md-4 qty-price'>
                    <h6><a href='#'><?=$row_prd['variety_description'];?></a></h6>
                    <p>1x - <span class='prod-price'><?=$row_prd['column4'];?></span></p>
                </div>
                <div class='qty'><span class='qty-inc'>1</span>
                    <button type='button' class='inc-btn'><span>&#43;</span></button>
                    <button type='button' class='dec-btn'><span>-</span></button>
                </div>
                <div class='col-md-4'>
                    <div class='tot-div'>
                        <p class='tot-price'><?=$row_prd['column4'];?></p>
                    </div>
                </div>
                <div class='col-md-2'>
                    <div class='cancel-order'>
                        <img src='images/cancel-order.png' id='order_summary_cancel'></img>
                        <p class='hidden' id='order_summary_id' ><?=$row_prd['id'];?></p>
                    </div>
                </div>
                </div>
            </li>
        </ul>
        <from>
    <?php   
        }
    }
    else {
    echo "0 results";
    }
    $sumsql = "SELECT order_summary.id, order_summary.variety_id, order_summary.qty, order_summary.user_id, registration.userid as regid, registration.firstname, varietymaster.id as varid, varietymaster.variety_description, varietymaster.column1, varietymaster.column2, varietymaster.column3, SUM(varietymaster.column4) as column4, varietymaster.column5, varietymaster.column6, cropmaster.id as cropid, cropmaster.cropname, tbl_imagedetails.id as imgid, tbl_imagedetails.image_path, tbl_imagedetails.image_content, tbl_imagedetails.is_new, tbl_imagedetails.image_flag FROM order_summary INNER JOIN registration ON order_summary.user_id = registration.userid INNER JOIN varietymaster ON order_summary.variety_id = varietymaster.id INNER JOIN cropmaster ON varietymaster.cropid = cropmaster.id INNER JOIN tbl_imagedetails ON varietymaster.imgId = tbl_imagedetails.id WHERE order_summary.user_id ='9095872323' AND order_summary.status = 'A'";
        $sumresult = $conn->query($sumsql);
        if ($sumresult->num_rows > 0) {
        while($sum_row_prd = $sumresult->fetch_assoc()) {
        ?>      
        <li class='d-flex justify-content-start align-items-start'>
            <div class='col-md-4'>
            </div>
            <div class='col-md-8'>
                <div class="float-right">
                <span><strong>Total Order Amount</strong>:</span><span class='total float-right'><?=$sum_row_prd['column4'];?></span>
            </div>
            </div>
            <div class='col-md-6'>
            </div>
        </li>
        <li class='d-flex justify-content-start align-items-start total-li'>
            <div class='col-md-6'>
                <!-- <a href='#' class='btn btn-default hvr-hover btn-cart'>VIEW CART</a> -->
            </div>
            <div class='col-md-6'>
                <button class='btn btn-default btn-cart float-right payment' id="Paymentsave"
                name="Payment">Confirm Order</button>
            </div>
        </li>
    <?php   
        }


    }
    else {
    echo "0 results";
    }
    mysqli_close($conn);
    ?>
