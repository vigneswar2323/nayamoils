<?php

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {

    $uri = 'https://';
} else {

    $uri = 'http://';
}

$uri .= $_SERVER['HTTP_HOST'];

$root = $uri;
define("LOADER", 'assets/img/loader.gif');
define('SITE_URL', $root . '/Nayamoils');
define('SITE_URL_ADMIN', $root . '/Nayamoils/AdminPages');
//define('SITE_URL', $root);
//define('SITE_URL_ADMIN', $root . '/AdminPages');
define('SITE_TITLE', 'Nayam Oils');
define('INDEX_SUB_TITLE', 'We Deal With Various Quality Organic Products!');
define("ADMINNAME", "Senthil");
define("CPOMANYID", "1"); //Nayam Oils
define("EMAILANDWEB", "nayamoils.com");
define("CONTACT_NO", "+91 999 999 9999");
define("PLACE_ORDER_AFFILIATE_LINK", "N"); //Y = Enable LINK, N= Enable Pop up
define("FOOTER_IMAGES_DISPLAY", "N"); //Y = Enable, N= Disable
define("FOOTER", "N"); //Y = Enable, N= Disable
define("APPNOTIFICATION", "For getting classyproduct.in Android App" ); //Y = Enable, N= Disable


//index page sub navigation red buttons
//reference table
// parent code 1 - activie , In activie status
// parent code 2 - IS new variety flag
// parent code 3 - Male,Female gender selection
// parent code 4 - Gallery type list
// parent code 5 - Measurement type list
// parent code 6 - Home page banner show/hide
// parent code 9 - company codes
//image flags definition
//G = Gallery type
//C = Category type
//V = Product type
//A = Affiliate Type
//B = Blog Type
//L = Logo Type
?>