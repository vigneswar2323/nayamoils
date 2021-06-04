<?php

    include_once("./connection/DBConnection.php");
    include './common/ApplicationConstant.php';
    $DBConnection = new DBConnection();
    $id = $_POST['session_id'];
    $sql = "SELECT * FROM registration WHERE userid='$id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
        while($row_prd = $result->fetch_assoc()) {
        ?>      
    
    <li class="cart-box">
     <ul class="cart-list">
    <li>
    <a href="#" class="photo"><img src="images/user.png" class="cart-thumb" alt="" /></a>
    <h6><a href="#"><?=$row_prd['firstname']." ".$row_prd['lastname'];?></a></h6>
    <p><span><?=$row_prd['mobile'];?></span></p>
    </li>
   <li>
    <a href="#" class="photo"><img src="images/myorders.png" class="cart-thumb" alt="" /></a>
    <h6><a href="#">My Orders</a></h6>
      <p><span>View Order History</span></p>                      
    </li>
    <li class="total">
        <a href="#" class="btn btn-default logout"><img src="images/logout.png">Log out</a>
    
    </li>
</ul>
</li>
           
        <?php   
        }
    }
    else {
    echo "0 results";
    }
    mysqli_close($conn);
    ?>
