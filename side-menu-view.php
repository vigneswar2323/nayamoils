<?php
include_once("./connection/DBConnection.php");
include './common/ApplicationConstant.php';
$DBConnection = new DBConnection();
$id = $_POST['session_id'];
$sql = "SELECT COUNT(user_id) as countuserid FROM `order_summary` WHERE user_id='$id' and status='A'";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row_prd = $result->fetch_assoc()) {
        ?>      
        <!-- Start Main Top -->
<!--        <ul>
            <li class="my-cart">
                <a href="order-summary.php">
                    <i class="fa fa-shopping-bag"></i>
                    <span class="badge"><?= $row_prd['countuserid']; ?></span>
                    <p>My Cart</p>
                </a>
            </li>              

        </ul>-->
        <!-- End Main Top -->
        <?php
    }
} else {
    echo "0 results";
}
mysqli_close($conn);
?>