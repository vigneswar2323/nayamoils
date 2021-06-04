<?php
$SITE_TITLE = constant('SITE_TITLE');
$indexsubtitle = constant('INDEX_SUB_TITLE');
$E_MAILANDSITE = constant('EMAILANDWEB');
$CONTACT_NO = constant('CONTACT_NO');
$FOOTER_IMAGES_DISPLAY = constant('FOOTER_IMAGES_DISPLAY');
$FOOTER = constant('FOOTER');
$subheader = "Nayam Oils - Pure Cold Pressed Cooking Oils";
$finyear = ( date('m') > 6) ? date('Y') + 1 : date('Y');
switch ($pagename) {
    case 'home':
        $activehome = 'active';
        break;
    case 'aboutus':
        $activeaboutus = 'active';
        break;
    case 'ourproducts':
        $activeproducts = 'active';
        break;
    case 'shop':
        $activeshop = 'active';
        break;
    case 'mycart':
        $activemycart = 'active';
        break;
    case 'gallery':
        $activegallery = 'active';
        break;
    case 'contactus':
        $activecontactas = 'active';
        break;
    default:
        $activehome = '';
        break;
}
?>
<div class="main-top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="right-phone-box">
                    <p>Call US :- <a href="#"><?= $CONTACT_NO ?></a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Main Top -->

<!-- Start Main Top -->
<!-- Start Main Top -->
<header class="main-header" >
    <!-- Start Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav">
        <div class="container">
            <!-- Start Header Navigation -->
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="index.php"><img src="./<?php echo $logopath ?>" width="60" height="60" class="logo" alt=""></a>
            </div>
            <!-- End Header Navigation -->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="nav navbar-nav ml-auto" data-in="fadeInDown" data-out="fadeOutUp">
                    <li class="nav-item"><a class="nav-link" href="index.php"><img src="images/home_64p.png" width="25" height="25"></a></li>
<!--                    <li class="nav-item <?php echo $activehome ?>"><a class="nav-link" href="index.php">Home</a></li>-->
                    <li class="nav-item <?php echo $activeaboutus ?>"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="nav-item <?php echo $activeproducts ?>"><a class="nav-link" href="ourproducts.php">Our Products</a></li>
                    <li class="nav-item <?php echo $activeshop ?>"><a class="nav-link" href="shop.php">SHOP</a></li>
                    <li class="nav-item <?php echo $activegallery ?>"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item <?php echo $activecontactas ?>"><a class="nav-link" href="contact-us.php">Contact Us</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->

            <!-- Start Atribute Navigation -->
            <div class="attr-nav" id="menudraw">
            </div>
            <!-- End Atribute Navigation -->

            <!-- Start Side Menu -->
            <div class="side">
                <a href="#" class="close-side"><i class="fa fa-times"></i></a>
                <div id="user_view"></div>
            </div>
            <!-- End Side Menu -->
        </div>
    </nav>
    <!-- End Navigation -->
</header>