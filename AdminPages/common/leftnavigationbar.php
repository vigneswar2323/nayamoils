<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">

            <p class="centered"><a href="#"><img src="assets/img/adminicon.png" class="img-circle" width="60"></a></p>
            <h5 class="centered"><?php echo $adminname ?></h5>

            <li class="mt">
                <a href="home.php">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="javacript:;" >
                    <i class="fa fa-users"></i>
                    <span>Users Account</span>
                </a>
                <ul class="sub">
                    <li><a  href="userMaster.php">User Master</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-expand"></i>
                    <span>Home/Gallery</span>
                </a>
                <ul class="sub">
                    <li><a  href="homePage.php">Home Page</a></li>
                    <li><a  href="galleryPage.php">Gallery</a></li>
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-leaf"></i>
                    <span>Masters</span>
                </a>
                <ul class="sub">
                    <!--                    <li><a  href="categoryMaster.php">Category Master</a></li>-->
                    <li><a  href="cropMaster.php">Category Master</a></li>
                    <li><a  href="varietyMaster.php">Product Master</a></li>
                    <li><a  href="affiliateIconMaster.php">Affiliate Icon Master</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="#" >
                    <i class="fa fa-download"></i>
                    <span>Reports</span>
                </a>
                <ul class="sub">
                    
                    <li><a  href="contactDetails.php">Contact Us Details</a></li>                                     
                </ul>
            </li>
            <li class="sub-menu">
                <a href="Trash.php" >
                    <i class="fa fa-trash-o"></i>
                    <span>Trash</span>
                </a>

            </li>
            <!--
          <li class="sub-menu">
              <a href="#" >
                  <i class="fa fa-trash-o"></i>
                  <span>Trash</span>
              </a>
              <ul class="sub">
                  <li><a  href="#">Delete Records</a></li>                                     
              </ul>
          </li>           -->
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>