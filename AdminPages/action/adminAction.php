<?php

require_once '../dao/adminDAO.php';
require_once '../../connection/DBConnection.php';
require_once ('../../common/ApplicationConstant.php');

$locationurl = SITE_URL_ADMIN;
$dbconnection = new DBConnection();
$adminDao = new adminDAO();

if (isset($_POST['method'])) {
    $method = $_POST['method'];
} else {
    $method = $_GET['method'];
}

switch ($method) {
    case 'login':
        $adminDao->login($conn, $_POST, $locationurl);
        break;
    case 'bannerSliderStatus':
        $adminDao->bannerSliderStatus($conn, $_GET, $locationurl);
        break;
    case 'getReferencebyParent':
        $adminDao->getReferencebyParent($conn, $_GET, $locationurl);
        break;
    case 'getHomePageDetails':
        $adminDao->getHomePageDetails($conn, $_POST, $locationurl);
        break;
    case 'addHomePage':
        $adminDao->addHomePage($conn, $_POST, $locationurl);
        break;
    case 'updateHomePage':
        $adminDao->updateHomePage($conn, $_POST, $locationurl);
        break;
    case 'getBlogDetails':
        $adminDao->getBlogDetails($conn, $_POST, $locationurl);
        break;
    case 'saveBlog':
        $adminDao->saveBlog($conn, $_POST, $locationurl);
        break;
    case 'updateBlog':
        $adminDao->updateBlog($conn, $_POST, $locationurl);
        break;

    //2
    case 'getGalleryPageDetails':
        $adminDao->getGalleryPageDetails($conn, $_POST, $locationurl);
        break;
    case 'getReferencecodes':
        $adminDao->getReferencecodes($conn, $_GET, $locationurl);
        break;
    case 'saveReferenceCodes':
        $adminDao->saveReferenceCodes($conn, $_GET, $locationurl);
        break;
    case 'getReferencecodeswithname':
        $adminDao->getReferencecodeswithname($conn, $_GET, $locationurl);
        break;
    case 'saveGalleryPage':
        $adminDao->saveGalleryPage($conn, $_POST, $locationurl);
        break;
    case 'updateGalleryPage':
        $adminDao->updateGalleryPage($conn, $_POST, $locationurl);
        break;

    //user master
    case 'userGrid':
        $adminDao->userGrid($conn, $_POST, $locationurl);
        break;
    case 'getUserRole':
        $adminDao->getUserRole($conn);
        break;
    case 'addNewUser':
        $adminDao->addNewUser($conn, $_POST, $locationurl);
        break;
    case 'getUserDetails':
        $adminDao->getUserDetails($conn, $_GET, $locationurl);
        break;
    case 'editUser':
        $adminDao->editUser($conn, $_POST, $locationurl);
        break;

    //crop master
    case 'cropGrid':
        $adminDao->getCropGrid($conn, $_GET, $locationurl);
        break;
    case 'addNewCrop':
        $adminDao->addNewCrop($conn, $_POST, $locationurl);
        break;
    case 'getCropDetails':
        $adminDao->getCropDetails($conn, $_GET, $locationurl);
        break;
    case 'editCrop':
        $adminDao->editCrop($conn, $_POST, $locationurl);
        break;

    //variety master
    case 'varietyGrid':
        $adminDao->getVarietyGrid($conn, $_GET, $locationurl);
        break;
    case 'getCropList':
        $adminDao->getCropList($conn, $_POST, $locationurl);
        break;
    case 'getAffiliateLogo':
        $adminDao->getAffiliateLogo($conn, $_POST, $locationurl);
        break;
    case 'constructVarityArray':
        $adminDao->constructVarityArray($conn, $_GET, $locationurl);
        break;
    case 'modifyVarietyArray':
        $adminDao->modifyVarietyArray($conn, $_GET, $locationurl);
        break;
    case 'constructPackingArray':
        $adminDao->constructPackingArray($conn, $_GET, $locationurl);
        break;
    case 'constructModifyPackingArray':
        $adminDao->constructModifyPackingArray($conn, $_GET, $locationurl);
        break;
    case 'cancelArrayList':
        $adminDao->cancelArrayList($conn, $_GET, $locationurl);
        break;
    case 'addNewVariety':
        $adminDao->addNewVariety($conn, $_POST, $locationurl);
        break;
    case 'updateVarietyDetails':
        $adminDao->updateVarietyDetails($conn, $_GET, $locationurl);
        break;
    case 'updatePackingDetails':
        $adminDao->updatePackingDetails($conn, $_GET, $locationurl);
        break;
    case 'removeVarietyDetails':
        $adminDao->removeVarietyDetails($conn, $_GET, $locationurl);
        break;
    case 'getVarietyDetails':
        $adminDao->getVarietyDetails($conn, $_GET, $locationurl);
        break;
    case 'getVarietyDetailsList':
        $adminDao->getVarietyDetailsList($conn, $_GET, $locationurl);
        break;
    case 'getVarietyPackingList':
        $adminDao->getVarietyPackingList($conn, $_GET, $locationurl);
        break;
    case 'editVariety':
        $adminDao->editVariety($conn, $_POST, $locationurl);
        break;

    //affiliate icon 
    case 'affiliateGrid':
        $adminDao->getAffiliateGrid($conn, $_GET, $locationurl);
        break;
    case 'addNewAffLogo':
        $adminDao->addNewAffLogo($conn, $_POST, $locationurl);
        break;
    case 'getAffiliateDetails':
        $adminDao->getAffiliateDetails($conn, $_GET, $locationurl);
        break;
    case 'editAffiliate':
        $adminDao->editAffiliate($conn, $_POST, $locationurl);
        break;


    //reports
    case 'contactusGrid':
        $adminDao->getContactusGrid($conn, $_GET, $locationurl);
        break;

    //trash
    case 'trashGrid':
        $adminDao->getTrashGrid($conn, $_GET, $locationurl);
        break;
    case 'saveTrash':
        $adminDao->saveTrash($conn, $_POST, $locationurl);
        break;
    case 'delTrash':
        $adminDao->delTrash($conn, $_POST, $locationurl);
        break;

//reports

    default:
        break;
}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>