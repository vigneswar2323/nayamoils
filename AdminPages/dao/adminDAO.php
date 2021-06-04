<?php

require_once '../../common/Dateparser.php';
require_once '../dao/commondao.php';
require_once('../common/NextRunningNumber.php');

class adminDAO {
    /*     * ****************************************************************Login*************************************************************************** */

    public function login($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();

            $userid = $parameters['mobile'];
            $password = $parameters['password'];

            $userdetails = array();
            $sql = "SELECT * FROM `registration` WHERE `userid`='" . $userid . "'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $userdetails['companyid'] = $row["companyid"];
                    $userdetails['userid'] = $row["userid"];
                    $userdetails['username'] = $row["username"];
                    $userdetails['firstname'] = $row["firstname"];
                    $userdetails['lastname'] = $row["lastname"];
                    $userdetails['roleid'] = $row["roleid"];
                    $userdetails['mobile'] = $row["mobile"];
                    $userdetails['email'] = $row["email"];
                    $userdetails['isloggedin'] = 1;
                    $dbpassword = $row["password"];

                    if (strcmp(md5($password), $dbpassword) == 0) {

                        $_SESSION["userdetails"] = $userdetails;
                        if (strcmp($userdetails['roleid'], "1") == 0) {
//admin
                            $location = "Location:../home.php";
                            $message = "Success Admin";
                        } else if (strcmp($userdetails['roleid'], "11") == 0) {
//admin
                            $location = "Location:../home.php";
                            $message = "Success User";
                        } else {
//users
                            $location = "Location:../adminLogin.php";
                            $message = "Failure!";
                        }
                    } else {

                        unset($_SESSION);
                        if (isset($_SERVER['HTTP_COOKIE'])) {
                            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                            foreach ($cookies as $cookie) {
                                $parts = explode('=', $cookie);
                                $name = trim($parts[0]);
                                setcookie($name, '', time() - 1000);
                                setcookie($name, '', time() - 1000, '/');
                            }
                        }
//session_destroy();
                        $message = "Invalid password";
                        $location = "Location:../adminLogin.php";
                    }
                }
            } else {
                unset($_SESSION);
                if (isset($_SERVER['HTTP_COOKIE'])) {
                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                    foreach ($cookies as $cookie) {
                        $parts = explode('=', $cookie);
                        $name = trim($parts[0]);
                        setcookie($name, '', time() - 1000);
                        setcookie($name, '', time() - 1000, '/');
                    }
                }
                session_destroy();
                $message = "Invalid user name and password!";
                $location = "Location:../adminLogin.php";
            }
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    /*     * ****************************************************************Category*************************************************************************** */

    public function getCropGrid($conn, $parameters, $locationurl) {
//get crop list
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $contentname = $parameters['contentname'];
            $imagurl = substr($locationurl, 0, -11);

            $sql = "SELECT c.id,c.cropname,c.imgId,rc.description as isnew,img.is_new,img.image_path,r.description,c.createdby,c.createddate,c.createdtime from cropmaster c left join tbl_imagedetails img on img.id=c.imgId left join referencecodes r on r.parentcode=1 and r.referencecode=c.status left join referencecodes rc on rc.parentcode=2 and rc.referencecode=img.is_new WHERE c.companyid='$companyid' AND c.status NOT IN(0)";
            $dataGrid = '';
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {

                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th>S.No</th>';
                $dataGrid = $dataGrid . '<th>Edit</th>';
                $dataGrid = $dataGrid . '<th>Remove</th>';
                $dataGrid = $dataGrid . "<th>$contentname Name</th>";
                $dataGrid = $dataGrid . "<th>$contentname Image</th>";
                $dataGrid = $dataGrid . '<th>Status</th>';
                $dataGrid = $dataGrid . '<th>Is New</th>';
                $dataGrid = $dataGrid . '<th>Created By</th>';
                $dataGrid = $dataGrid . '<th>Created Date</th>';
                $dataGrid = $dataGrid . '<th>Created Time</th>';

                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $processingid = "'" . $row["id"] . "','" . $row["imgId"] . "'";
                    $imagurll = $imagurl . $row["image_path"];
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td>' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td><img width=20 height=20 src="' . $locationurl . '/assets/img/editicon.png' . '" onclick="editCrop(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td><img width=20 height=20 src="' . $locationurl . '/assets/img/cross_img.png' . '" onclick="delCrop(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["cropname"] . '</td>';
                    $dataGrid = $dataGrid . '<td><img width=50 height=50 src="' . $imagurll . '" /></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["description"] . '</td>';
                    $dataGrid = $dataGrid . '<td><a class="btn-theme04">' . $row["isnew"] . '</a></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdby"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . Dateparser::datevalidation($row["createddate"]) . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdtime"] . '</td>';

                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</table></div>';
            } else {
                $dataGrid = $dataGrid . '<div class="no-result alert-danger" style="width: 100%;text-align: center;"><p>No Records Found !!!</p></div>';
            }
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function addNewCrop($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);
            $cropname = $parameters['cropname'];
            $isnew = $parameters['isnew'];
            $cropstatus = $parameters['cropstatus'];
            $contentname = $parameters['contentname'];

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

//upload image
            if (count($_FILES) > 0) {
                $_FILES["upload_image"];

                $target_dir = '../../uploads/crop/';
                $types = array("image/jpeg", "image/png", "image/gif");

// upload bill
                for ($i = 0; $i < count($_FILES['upload_image']['tmp_name']); $i++) {
                    $cropid = $NextRunningNumber->nextnumber('2', date("Y"), $conn); // id of the crop
                    $imageid = $NextRunningNumber->nextnumber('1', date("Y"), $conn); // id of the image
                    $imagetype = $_FILES["upload_image"]["type"][$i];
                    $file = $_FILES["upload_image"]["name"][$i];  // random name of the file
                    $image_content = pathinfo($file, PATHINFO_EXTENSION);
                    $fileinbytes = $_FILES["upload_image"]["size"][$i];
                    $filesize = $commondao->formatSizeUnits($fileinbytes);
                    $name = date('YmdHis', time()) . '_' . mt_rand();
                    $file = $name . $file;
                    $file = str_replace(" ", '_', $file);
                    $target_file = $file;
                    $img_path = "/uploads/crop/" . $file;
                    $flagtypes = "C"; // setting falgtype 
                    $imagequery = "INSERT INTO tbl_imagedetails (id,referenceid,companyid,image_path,image_name,image_content,image_size,is_new,image_flag,createddate,createdtime,createdby) VALUES ($imageid,$cropid,$companyid,'$img_path','$file', '$image_content','$filesize', '$isnew', '$flagtypes', '$createddate','$createdtime','$userid')";
                    $res = mysqli_query($conn, $imagequery);

                    if ($res) {
                        $insertcrop = "INSERT INTO cropmaster (id,companyid,cropname,imgId,status,createddate,createdtime,createdby) VALUES ('$cropid','$companyid','$cropname','$imageid','$cropstatus','$createddate','$createdtime','$userid')";
                        $res2 = mysqli_query($conn, $insertcrop);
                        if ($res2) {
                            if (move_uploaded_file($_FILES["upload_image"]["tmp_name"][$i], $target_dir . $target_file)) {
                                $message = "The file " . basename($_FILES["upload_image"]["name"][$i]) . " has been uploaded.";
                                $message = "$contentname ($cropname) has been Added Successfully!";
                            } else {
                                $message = "Sorry, there was an error uploading your file.";
                            }
                        } else {
                            $message = "crop table Error";
                        }
                    } else {
                        $message = "image table Error";
                    }
                }
            }

            echo $message;

            $_SESSION['message'] = $message;

            $location = "Location:../cropMaster.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getCropDetails($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $cropid = $parameters['cropid'];
            $sql = "SELECT c.id,c.cropname,c.imgId,img.image_path,r.description,c.status,c.createdby,c.createddate,c.createdtime from cropmaster c left join tbl_imagedetails img on img.id=c.imgId left join referencecodes r on r.parentcode=1 and r.referencecode=c.status WHERE c.id='$cropid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo $row['cropname'] . '~' . $row['image_path'] . '~' . $row['status'];
                }
            }
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function editCrop($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $imagurl = substr($locationurl, 0, -11);
            $cropname = $parameters['cropname'];
            $isnew = isset($parameters['isnew']) ? $parameters['isnew'] : '0';
            $cropstatus = $parameters['cropstatus'];
            $cropid = $parameters['cropid'];
            $imageid = $parameters['imageid'];
            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');



            $target_dir = "../../uploads/crop/";
            $target_file = $target_dir . basename($_FILES["upload_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
            if (isset($parameters['cropname'])) {
                $check = getimagesize($_FILES["upload_image"]["tmp_name"]);
                if ($check !== false) {
                    $message = "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $message = "Crop ($cropname) has been Updated Successfully!";
                    $uploadOk = 0;
                    $updatecrop = "UPDATE cropmaster set cropname='$cropname',status='$cropstatus',modifieddate='$createddate' WHERE id='$cropid' AND imgId='$imageid'";
                    mysqli_query($conn, $updatecrop);

                    $imagequery = "UPDATE tbl_imagedetails set is_new='$isnew',modifieddate='$createddate' WHERE id='$imageid'";
                    $res = mysqli_query($conn, $imagequery);
                }
            }

            if ($uploadOk == 1) {
// Check if file already exists
                if (file_exists($target_file)) {
                    $message = "Sorry, file already exists.";
                    $uploadOk = 0;
                }
// Check file size
                else if ($_FILES["upload_image"]["size"] > 500000) {//500kb
                    $message = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
// Allow certain file formats
                else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                } else {
                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $target_file)) {

                            $file = $_FILES["upload_image"]["name"];  // random name of the file
                            $file = str_replace(" ", '_', $file);
                            $img_path = "/uploads/crop/" . $file;
                            $fileinbytes = $_FILES["upload_image"]["size"];
                            $filesize = $commondao->formatSizeUnits($fileinbytes);
                            $flagtypes = "C"; // setting falgtype 

                            $imagequery = "UPDATE tbl_imagedetails set image_path='$img_path',image_name='$file',image_content='$imageFileType',image_size='$filesize',is_new='$isnew',image_flag='$flagtypes',modifieddate='$createddate' WHERE id='$imageid'";
                            $res = mysqli_query($conn, $imagequery);

                            if ($res) {
                                $updatecrop = "UPDATE cropmaster set cropname='$cropname',status='$cropstatus',modifieddate='$createddate' WHERE id='$cropid' AND imgId='$imageid'";
                                $res2 = mysqli_query($conn, $updatecrop);
                            }

                            $message = "The file " . htmlspecialchars(basename($_FILES["upload_image"]["name"])) . " has been uploaded.";
                            $message = "Crop ($cropname) has been Updated Successfully!";
                        } else {
                            $message = "Sorry, there was an error uploading your file.";
                        }
                    }
                }
            }

//// Check if $uploadOk is set to 0 by an error
//        if ($uploadOk == 0) {
//            $message = "Sorry, your file was not uploaded.";
//// if everything is ok, try to upload file
//        } else {
//            if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $target_file)) {
//                $message =  "The file " . htmlspecialchars(basename($_FILES["upload_image"]["name"])) . " has been uploaded.";
//            } else {
//                $message =  "Sorry, there was an error uploading your file.";
//            }
//        }
//upload image
//        if (count($_FILES) > 0) {
//            //update with image
//            $_FILES["upload_image"];
//
//            $target_dir = '../../uploads/crop/';
//            $types = array("image/jpeg", "image/png", "image/gif");
//
//            // upload bill
//            for ($i = 0; $i < count($_FILES['upload_image']['tmp_name']); $i++) {
//                $imagetype = $_FILES["upload_image"]["type"][$i];
//                $file = $_FILES["upload_image"]["name"][$i];  // random name of the file
//                $image_content = pathinfo($file, PATHINFO_EXTENSION);
//                $fileinbytes = $_FILES["upload_image"]["size"][$i];
//                $filesize = $commondao->formatSizeUnits($fileinbytes);
//                $name = date('YmdHis', time()) . '_' . mt_rand();
//                $file = $name . $file;
//                $file = str_replace(" ", '_', $file);
//                $target_file = $file;
//                $img_path = "/uploads/crop/" . $file;
//                $flagtypes = "C"; // setting falgtype 
//                $imagequery = "UPDATE tbl_imagedetails set image_path='$img_path',image_name='$file',image_content='$image_content',image_size='$filesize',is_new='$isnew',image_flag='$flagtypes',modifieddate='$createddate' WHERE id='$imageid'";
//                $res = mysqli_query($conn, $imagequery);
//
//                if ($res) {
//                    $updatecrop = "UPDATE cropmaster set cropname='$cropname',status='$cropstatus',modifieddate='$createddate' WHERE id='$cropid' AND imgId='$imageid'";
//                    $res2 = mysqli_query($conn, $updatecrop);
//                    if ($res2) {
//                        if (move_uploaded_file($_FILES["upload_image"]["tmp_name"][$i], $target_dir . $target_file)) {
//                            $message = "The file " . basename($_FILES["upload_image"]["name"][$i]) . " has been uploaded.";
//                        } else {
//                            $message = "Sorry, there was an error uploading your file.";
//                        }
//                    } else {
//                        $message = "crop table Error";
//                    }
//                } else {
//                    $message = "image table Error";
//                }
//            }
//        } else {
//            //update cropmaster
//            $updatecrop = "UPDATE cropmaster set cropname='$cropname',status='$cropstatus',modifieddate='$createddate' WHERE id='$cropid' AND imgId='$imageid'";
//            $res2 = mysqli_query($conn, $updatecrop);
//            $message = "Crop Updated Successfully!";
//        }

            $_SESSION['message'] = $message;

            $location = "Location:../cropMaster.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    /*     * ****************************************************************Products*************************************************************************** */

    public function getVarietyGrid($conn, $parameters, $locationurl) {
//get variety grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);
            $contentname = $parameters['contentname'];
            $dataGrid = '';

            $sql = "SELECT v.id,v.cropid,v.imgId,rc.description as isnew,rc2.description as varietystatus,c.cropname,v.variety_description,img.image_path,img.is_new,v.column1,v.column2,v.column3,v.column4,v.column5,v.column6,v.status,v.createddate,v.createdtime,v.createdby from varietymaster v left join cropmaster c on c.id=v.cropid left join tbl_imagedetails img on img.id=v.imgId left join referencecodes rc on rc.parentcode=2 and rc.referencecode=img.is_new left join referencecodes rc2 on rc2.parentcode=1 and rc2.referencecode=v.status WHERE c.companyid='$companyid' and v.status NOT IN(0) ORDER BY v.id DESC";
            $result = mysqli_query($conn, $sql);
            $totalcount = mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0) {
                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th>Edit</th>';
                $dataGrid = $dataGrid . '<th>Remove All <br><input type="checkbox" id="ckbCheckAll" onclick="SelectandDeselectalltags(this)"/></th>';
                $dataGrid = $dataGrid . '<th>S.No</th>';
                $dataGrid = $dataGrid . "<th>$contentname ID</th>";
                $dataGrid = $dataGrid . '<th>Category Name</th>';
                $dataGrid = $dataGrid . "<th>$contentname Image</th>";
                $dataGrid = $dataGrid . "<th>$contentname Name</th>";
                $dataGrid = $dataGrid . '<th>Status</th>';
                $dataGrid = $dataGrid . '<th>Is New</th>';
                $dataGrid = $dataGrid . '<th>Created By</th>';
                $dataGrid = $dataGrid . '<th>Created Date</th>';
                $dataGrid = $dataGrid . '<th>Created Time</th>';

                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $processingid = "'" . $row["id"] . "','" . $row["cropid"] . "','" . $row["imgId"] . "'";
                    $idd = $row["id"];
                    $imagurll = $imagurl . $row["image_path"];
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td><img width=20 height=20 src="' . $locationurl . '/assets/img/editicon.png' . '" onclick="editVariety(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td style="text-align:center"><input type="checkbox" class="checkBoxClass" id="Checkbox' . $sno . '" value="' . $idd . '" onclick="getCheckboxpage(this,' . $processingid . ')" /></td>';
                    $dataGrid = $dataGrid . '<td>' . $sno . '</td>';
                    //$dataGrid = $dataGrid . '<td><img width=20 height=20 src="' . $locationurl . '/assets/img/cross_img.png' . '" onclick="delVariety(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["id"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["cropname"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><img width=50 height=50 src="' . $imagurll . '" /></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["variety_description"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><a class="btn-theme04">' . $row["varietystatus"] . '</a></td>';
                    $dataGrid = $dataGrid . '<td><a class="btn-theme04">' . $row["isnew"] . '</a></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdby"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . Dateparser::datevalidation($row["createddate"]) . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdtime"] . '</td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</table></div>';
            } else {
                $dataGrid = $dataGrid . '<div class="no-result alert-danger" style="width: 100%;text-align: center;"><p>No Records Found !!!</p></div>';
            }
            $dataGrid = $dataGrid . '<input type="hidden" name="totalcount" id="totalcount" value="' . $totalcount . '"/>';

            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    function getCropList($conn) {
//crop list
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $companyid = $userdetails['companyid'];


            $sql = "SELECT * FROM cropmaster WHERE companyid='$companyid' AND status=1";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo '<option value="0">-- Select Category --</option>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value=' . $row["id"] . '> ' . $row["cropname"] . ' </option>';
                }
            }

            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    function getAffiliateLogo($conn) {
        //affiliate logo list
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $companyid = $userdetails['companyid'];

            $sql = "SELECT id,image_path,image_name FROM tbl_imagedetails where image_flag='A' AND is_new=1";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo '<option value="0" data-description="in case logo not found in this list, please add new logo">Please choose your logo before add/edit product</option>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $image_path = '..' . $row['image_path'];
                    $imagename = $row['image_name'];
                    $id = $row['id'];
                    echo "<option value='$id' data-image='$image_path'>$imagename</option>";
                }
            }

            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function constructVarityArray($conn, $parameters, $locationurl) {
        $varietydetails = array();
        $varietydetails['colname'] = $parameters['colname'];
        $varietydetails['coldescription'] = $parameters['coldescription'];

        $TotalVarietyArraylist = array();
        session_start();
        if (isset($_SESSION['TotalVarietyArraylist'])) {
            $TotalVarietyArraylist = $_SESSION['TotalVarietyArraylist'];
        }
        $totalLength = count($TotalVarietyArraylist);
        $totalLength = $totalLength + 1;
        $varietydetails['indexvariety'] = $totalLength;

        $TotalVarietyArraylist[$totalLength] = $varietydetails;
        $_SESSION['TotalVarietyArraylist'] = $TotalVarietyArraylist;

        $this->constructionVarietyArrayGrid($TotalVarietyArraylist);
    }

    public function modifyVarietyArray($conn, $parameters, $locationurl) {
        $TotalModifyVarietyArraylist = array();
        session_start();
        if (isset($_SESSION['TotalVarietyArraylist'])) {
            $TotalModifyVarietyArraylist = $_SESSION['TotalVarietyArraylist'];
        }

        $indexvariety = $parameters['indexvariety'];
        $rowArray = array();
        $rowArray = $TotalModifyVarietyArraylist[$indexvariety];
        $rowArray['colname'] = $parameters['colname'];
        $rowArray['coldescription'] = $parameters['coldescription'];
        $TotalModifyVarietyArraylist[$indexvariety] = $rowArray;

        $this->constructionVarietyArrayGrid($TotalModifyVarietyArraylist);
        $_SESSION['TotalVarietyArraylist'] = $TotalModifyVarietyArraylist;
    }

    public function constructionVarietyArrayGrid($TotalVarietyArraylist) {
        $dataGrid = '';
        $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
        $dataGrid = $dataGrid . '<thead>';
        $dataGrid = $dataGrid . '<tr class="gridmenu">';
        $dataGrid = $dataGrid . '<th width="5%" align="center">s.no</th>';
        $dataGrid = $dataGrid . '<th width="15%" align="center"> Column Name</th>';
        $dataGrid = $dataGrid . '<th width="15%" align="center">Column Description</th>';
        $dataGrid = $dataGrid . '<th width="5%" align="center">Edit</th>';
        $dataGrid = $dataGrid . '</tr>';
        $dataGrid = $dataGrid . '</thead>';
        $dataGrid = $dataGrid . '<tbody id="tab_data">';
        $slCount = 0;
        $values = "";

        foreach ($TotalVarietyArraylist as &$value) {
            $slCount++;
            $values = "'line','" . $slCount . "','" . $value['colname'] . "','" . $value['coldescription'] . "'";
            $dataGrid = $dataGrid . '<tr id="line' . $slCount . '">';
            $dataGrid = $dataGrid . '<td align="center">' . $slCount . '</td>';
            $dataGrid = $dataGrid . '<td align="center">' . $value['colname'] . '</td>';
            $dataGrid = $dataGrid . '<td align="center">' . $value['coldescription'] . '</td>';
            $dataGrid = $dataGrid . '<td align="center"> <img  name="one" src="assets/img/editicon.png" height="20" width="20" onclick="mofifyVarietyValues(' . $values . ')"/></td>';
            $dataGrid = $dataGrid . '</tr>';
        }
        $dataGrid = $dataGrid . '</table></div>';
        echo $dataGrid;
    }

    public function constructPackingArray($conn, $parameters, $locationurl) {
        $packing = array();
        $packing['noofbags'] = $parameters['noofbags'];
        $packing['qtyperbag'] = $parameters['qtyperbag'];
        $packing['totalquantity'] = (int) $parameters['noofbags'] * (float) $parameters['qtyperbag'];
        $packing['priceperbag'] = $parameters['priceperbag'];
        $packing['totalprice'] = (int) $parameters['noofbags'] * (float) $parameters['priceperbag'];
        $mesdesc = $parameters['mesdesc']; //eg kg/ltr

        $TotalPackingArraylist = array();
        session_start();
        if (isset($_SESSION['TotalPackingArraylist'])) {
            $TotalPackingArraylist = $_SESSION['TotalPackingArraylist'];
        }
        $totalLength = count($TotalPackingArraylist);
        $totalLength = $totalLength + 1;
        $packing['index'] = $totalLength;

        $TotalPackingArraylist[$totalLength] = $packing;
        $_SESSION['TotalPackingArraylist'] = $TotalPackingArraylist;

        $this->contructionPackingGrid($TotalPackingArraylist, $mesdesc);
    }

    public function constructModifyPackingArray($conn, $parameters, $locationurl) {
        $TotalModifyPackingArraylist = array();
        session_start();
        if (isset($_SESSION['TotalPackingArraylist'])) {
            $TotalModifyPackingArraylist = $_SESSION['TotalPackingArraylist'];
        }

        $index = $parameters['index'];
        $rowArray = array();
        $rowArray = $TotalModifyPackingArraylist[$index];
        $rowArray['noofbags'] = $parameters['noofbags'];
        $rowArray['qtyperbag'] = $parameters['qtyperbag'];
        $rowArray['totalquantity'] = (int) $parameters['noofbags'] * (float) $parameters['qtyperbag'];
        $rowArray['priceperbag'] = $parameters['priceperbag'];
        $rowArray['totalprice'] = (int) $parameters['noofbags'] * (float) $parameters['priceperbag'];
        $mesdesc = $parameters['mesdesc']; //eg kg/ltr
        $TotalModifyPackingArraylist[$index] = $rowArray;

        $this->contructionPackingGrid($TotalModifyPackingArraylist, $mesdesc);
        $_SESSION['TotalPackingArraylist'] = $TotalModifyPackingArraylist;
    }

    public function contructionPackingGrid($TotalPackingArraylist, $mesdesc) {
        $dataGrid = '';
        $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
        $dataGrid = $dataGrid . '<thead>';
        $dataGrid = $dataGrid . '<tr class="gridmenu">';
        $dataGrid = $dataGrid . '<th width="5%" align="center">s.no</th>';
        $dataGrid = $dataGrid . '<th width="15%" align="center">Quantity per Bag (' . $mesdesc . ')</th>';
        $dataGrid = $dataGrid . '<th width="15%" align="center">No of Bags/Container</th>';
        $dataGrid = $dataGrid . '<th width="15%" align="center">Total Quantity (' . $mesdesc . ')</th>';
        $dataGrid = $dataGrid . '<th width="15%" align="center">Price per Bag/Container (INR)</th>';
        $dataGrid = $dataGrid . '<th width="15%" align="center">Total Bags/Container (INR)</th>';
        $dataGrid = $dataGrid . '<th width="5%" align="center">Edit</th>';
        $dataGrid = $dataGrid . '</tr>';
        $dataGrid = $dataGrid . '</thead>';
        $dataGrid = $dataGrid . '<tbody id="tab_data">';
        $slCount = 0;
        $values = "";

        $quantityperbagsinkgs = "";
        $totalprbag = "";
        $totalquantity = "";
        $totpriceperbag = "";
        $grandtotalprice = "";

        foreach ($TotalPackingArraylist as &$value) {
            $slCount++;
            //$values = "'" . $value['colname'] . "','" . $value['coldescription'] . "','" . $slCount . "'";
            $values = "'packline','" . $slCount . "','" . $value['noofbags'] . "','" . $value['qtyperbag'] . "','" . $value['priceperbag'] . "'";
            $dataGrid = $dataGrid . '<tr id="packline' . $slCount . '">';
            $dataGrid = $dataGrid . '<td align="center">' . $slCount . '</td>';
            $dataGrid = $dataGrid . '<td align="center">' . $value['qtyperbag'] . '</td>';
            $dataGrid = $dataGrid . '<td align="center">' . $value['noofbags'] . '</td>';
            $dataGrid = $dataGrid . '<td align="center">' . $value['totalquantity'] . '</td>';
            $dataGrid = $dataGrid . '<td align="center">' . $value['priceperbag'] . '</td>';
            $dataGrid = $dataGrid . '<td align="center">' . $value['totalprice'] . '</td>';
            $dataGrid = $dataGrid . '<td align="center"> <img  name="one" src="assets/img/editicon.png" height="20" width="20" onclick="modifyValues(' . $values . ')"/></td>';
            $dataGrid = $dataGrid . '</tr>';

            $quantityperbagsinkgs = (float) $quantityperbagsinkgs + (float) $value['qtyperbag'];
            $totalprbag = (float) $totalprbag + (float) $value['noofbags'];
            $totalquantity = (float) $totalquantity + (float) $value['totalquantity'];
            $totpriceperbag = (float) $totpriceperbag + (float) $value['priceperbag'];
            $grandtotalprice = (float) $grandtotalprice + (float) $value['totalprice'];

            if ($slCount == (sizeof($TotalPackingArraylist))) {
                $dataGrid = $dataGrid . '<tr id="linetot">';
                $dataGrid = $dataGrid . '<td align="center">Total</td>';
                $dataGrid = $dataGrid . '<td align="center"></td>';
                $dataGrid = $dataGrid . '<td align="center">' . $totalprbag . '</td>';
                $dataGrid = $dataGrid . '<td align="center"> ' . $totalquantity . '</td>';
                $dataGrid = $dataGrid . '<td align="center">' . $totpriceperbag . '</td>';
                $dataGrid = $dataGrid . '<td align="center"> ' . $grandtotalprice . '</td>';
                $dataGrid = $dataGrid . '<td align="center"></td>';
                $dataGrid = $dataGrid . '</tr>';

                //$_SESSION['totalprqty'] = $totalprqty;
            }
        }
        $dataGrid = $dataGrid . '</table></div>';
        echo $dataGrid;
    }

    public function cancelArrayList($conn, $parameters, $locationurl) {
        session_start();
        $arrName = $parameters['arrName'];
        unset($_SESSION[$arrName]);
    }

    public function updateVarietyDetails($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $message = '';
            $varietydetailid = $parameters['varietydetailid'];
            $varietyid = $parameters['varietyid'];
            $editcolname = $parameters['editcolname'];
            $editcoldescription = $parameters['editcoldescription'];

            $imagequery = "UPDATE varietydetails set col_name='$editcolname',col_description='$editcoldescription' WHERE vdetailsid='$varietydetailid'";
            $res = mysqli_query($conn, $imagequery);

            if ($res) {
                $message = "Updated Successfully!";
            } else {
                $message = "Error While Update";
            }
            $_SESSION['message'] = $message;
            $this->getVarietyDetailsList($conn, $parameters, $locationurl);
            //mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function updatePackingDetails($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $message = '';
            $packingid = $parameters['packingid'];
            $varietyid = $parameters['varietyid'];
            $editqtyperbag = $parameters['editqtyperbag'];
            $editnoofbags = $parameters['editnoofbags'];
            $totalquantity = (float) $editqtyperbag * $editnoofbags;
            $editpriceperbag = $parameters['editpriceperbag'];
            $totalprice = (float) $editpriceperbag * $editnoofbags;

            $updateqry = "UPDATE varietypackingdetails set qtyperbag='$editqtyperbag',noofbags='$editnoofbags',totalquantity='$totalquantity',priceperbag='$editpriceperbag',totalprice='$totalprice' WHERE packingid='$packingid'";
            $res = mysqli_query($conn, $updateqry);

            if ($res) {
                $message = "Updated Successfully!";
            } else {
                $message = "Error While Update";
            }
            $_SESSION['message'] = $message;
            $this->getVarietyPackingList($conn, $parameters, $locationurl);
            //mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function removeVarietyDetails($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $message = '';
            $varflag = $parameters['varflag'];
            $varietydetailid = $parameters['varietydetailid'];
            $varietyid = $parameters['varietyid'];
            if ($varflag == '1') {
                //variety details
                $delquery = "DELETE FROM varietydetails WHERE vdetailsid='$varietydetailid' AND varietyid='$varietyid'";
            } else {
                //packing
                $delquery = "DELETE FROM varietypackingdetails WHERE packingid='$varietydetailid' AND varietyid='$varietyid'";
            }
            $res = mysqli_query($conn, $delquery);
            if ($res) {
                $message = "Removed Successfully!";
            } else {
                $message = "Error While Remove";
            }
            $_SESSION['message'] = $message;
            if ($varflag == '1') {
                $this->getVarietyDetailsList($conn, $parameters, $locationurl);
            } else if ($varflag == '2') {
                $this->getVarietyPackingList($conn, $parameters, $locationurl);
            }
            //mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function addNewVariety($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);
            $cropid = isset($parameters['cropid']) ? $parameters['cropid'] : '';
            $varietyname = isset($parameters['varietyname']) ? $parameters['varietyname'] : '';
            $offers = isset($parameters['offers']) ? $parameters['offers'] : '';
            $displayprice = isset($parameters['displayprice']) ? $parameters['displayprice'] : '';
            $actualprice = isset($parameters['actualprice']) ? $parameters['actualprice'] : '';
            $displayvolume = isset($parameters['displayvolume']) ? $parameters['displayvolume'] : '';
            $measurementtype = isset($parameters['measurementtype']) ? $parameters['measurementtype'] : '';
            $affiliatelogo = isset($parameters['affiliatelogo']) ? $parameters['affiliatelogo'] : '';
            $hyperlink = isset($parameters['hyperlink']) ? $parameters['hyperlink'] : '';
            $productdescription = isset($parameters['productdescription']) ? $parameters['productdescription'] : '';
            $varietystatus = isset($parameters['varietystatus']) ? $parameters['varietystatus'] : '';
            $ispackingvalue = isset($parameters['ispackingvalue']) ? $parameters['ispackingvalue'] : ''; //tick = Y, untick = N
            $isnew = isset($parameters['isnew']) ? $parameters['isnew'] : '1';
            $contentname = isset($parameters['contentname']) ? $parameters['contentname'] : '';

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

            $TotalPackingArraylist = "";

            //check upload image 
            $target_dir = "../../uploads/variety/";
            $target_file = $target_dir . basename($_FILES["upload_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

//upload image
            if (count($_FILES) > 0) {
                $_FILES["upload_image"];
                if ($uploadOk == 1) {
// Check if file already exists
                    if (file_exists($target_file)) {
                        $message = "Sorry, file already exists.";
                        $uploadOk = 0;
                    }
// Check file size
                    else if ($_FILES["upload_image"]["size"] > 500000) {//500kb
                        $message = "Sorry, your file is too large.";
                        $uploadOk = 0;
                    }
// Allow certain file formats
                    else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                        $uploadOk = 0;
                    } else {
                        if ($uploadOk == 1) {
                            $types = array("image/jpeg", "image/png", "image/gif");
                            $varietyid = $NextRunningNumber->nextnumber('3', date("Y"), $conn); // id of the crop
                            $imageid = $NextRunningNumber->nextnumber('1', date("Y"), $conn); // id of the image
                            $imagetype = $_FILES["upload_image"]["type"];
                            $file = $_FILES["upload_image"]["name"];  // random name of the file
                            $image_content = pathinfo($file, PATHINFO_EXTENSION);
                            $fileinbytes = $_FILES["upload_image"]["size"];
                            $filesize = $commondao->formatSizeUnits($fileinbytes);
                            //$name = date('YmdHis', time()) . '_' . mt_rand();
                            $file = str_replace(" ", '_', $file);

                            $img_path = "/uploads/variety/" . $file;
                            $flagtypes = "V"; // setting falgtype 

                            $imagequery = "INSERT INTO tbl_imagedetails (id,referenceid,companyid,image_path,image_name,image_content,image_size,is_new,image_flag,createddate,createdtime,createdby) VALUES ($imageid,$varietyid,$companyid,'$img_path','$file', '$image_content','$filesize', '$isnew', '$flagtypes', '$createddate','$createdtime','$userid')";
                            $res = mysqli_query($conn, $imagequery);

                            if ($res) {
                                $insertcrop = "INSERT INTO varietymaster (id,companyid,cropid,imgId,variety_description,column1,column2,column3,column4,column5,column6,column7,column8,column9,status,createddate,createdtime,createdby) VALUES ('$varietyid','$companyid','$cropid','$imageid','$varietyname','$hyperlink','$productdescription','$offers',$displayprice,'$displayvolume','$measurementtype','$affiliatelogo','$ispackingvalue',$actualprice,'$varietystatus','$createddate','$createdtime','$userid')";
                                $res2 = mysqli_query($conn, $insertcrop);
                                if ($res2) {
                                    if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $target_dir . $file)) {
                                        $message = "The file " . basename($_FILES["upload_image"]["name"]) . " has been uploaded.";
                                        $message = "$contentname ($varietyname) has been Added Successfully!";
                                        $uploadok = 1;
                                    } else {
                                        $message = "Sorry, there was an error uploading your file.";
                                        $uploadok = 0;
                                    }
                                } else {
                                    $message = "variety table Error";
                                    $uploadok = 0;
                                }
                            } else {
                                $message = "image table Error";
                                $uploadok = 0;
                            }
                        }
                    }
                }

// upload for multiple image
//                for ($i = 0; $i < count($_FILES['upload_image']['tmp_name']); $i++) {
//                    $cropid = $NextRunningNumber->nextnumber('2', date("Y"), $conn); // id of the crop
//                    $imageid = $NextRunningNumber->nextnumber('1', date("Y"), $conn); // id of the image
//                    $imagetype = $_FILES["upload_image"]["type"][$i];
//                    $file = $_FILES["upload_image"]["name"][$i];  // random name of the file
//                    $image_content = pathinfo($file, PATHINFO_EXTENSION);
//                    $fileinbytes = $_FILES["upload_image"]["size"][$i];
//                    $filesize = $commondao->formatSizeUnits($fileinbytes);
//                    $name = date('YmdHis', time()) . '_' . mt_rand();
//                    $file = $name . $file;
//                    $file = str_replace(" ", '_', $file);
//                    $target_file = $file;
//                    $img_path = "/uploads/crop/" . $file;
//                    $flagtypes = "C"; // setting falgtype 
//                    $imagequery = "INSERT INTO tbl_imagedetails (id,image_path,image_name,image_content,image_size,is_new,image_flag,createddate,createdtime,createdby) VALUES ($imageid,'$img_path','$file', '$image_content','$filesize', '$isnew', '$flagtypes', '$createddate','$createdtime','$userid')";
//                    $res = mysqli_query($conn, $imagequery);
//
//                    if ($res) {
//                        $insertcrop = "INSERT INTO cropmaster (id,companyid,cropname,imgId,status,createddate,createdtime,createdby) VALUES ('$cropid','$companyid','$cropname','$imageid','$cropstatus','$createddate','$createdtime','$userid')";
//                        $res2 = mysqli_query($conn, $insertcrop);
//                        if ($res2) {
//                            if (move_uploaded_file($_FILES["upload_image"]["tmp_name"][$i], $target_dir . $target_file)) {
//                                $message = "The file " . basename($_FILES["upload_image"]["name"][$i]) . " has been uploaded.";
//                            } else {
//                                $message = "Sorry, there was an error uploading your file.";
//                            }
//                        } else {
//                            $message = "crop table Error";
//                        }
//                    } else {
//                        $message = "image table Error";
//                    }
//                }
//            }
                //store into variety details table
                $insertvardetails = " INSERT INTO varietydetails (varietyid, col_name, col_description) VALUES ";
                $valuespairvar = '';
                if (isset($_SESSION['TotalVarietyArraylist'])) {
                    $TotalVarietyArraylist = $_SESSION['TotalVarietyArraylist'];
                }
                foreach ($TotalVarietyArraylist as &$value) {
                    $colname = $value['colname'];
                    $coldescription = $value['coldescription'];

                    $valuespairvar .= "('$varietyid', '$colname', '$coldescription'),";
                }

                $valuespairvar = substr($valuespairvar, 0, -1);
                $insertvartable = $insertvardetails . $valuespairvar . ';';
                if ($uploadok == 1) {
                    $conn->query($insertvartable);
                }

                //store into variety packing details table
                if (strcmp($ispackingvalue, 'Y') == 0) {
                    $insertdetails = " INSERT INTO varietypackingdetails (varietyid, noofbags, qtyperbag, totalquantity, priceperbag, totalprice) VALUES ";
                    $valuespair = '';
                    if (isset($_SESSION['TotalPackingArraylist'])) {
                        $TotalPackingArraylist = $_SESSION['TotalPackingArraylist'];
                    }
                    foreach ($TotalPackingArraylist as &$value) {
                        $qtyperbag = $value['qtyperbag'];
                        $noofbags = $value['noofbags'];
                        $totalquantity = $value['totalquantity'];
                        $priceperbag = $value['priceperbag'];
                        $totalprice = $value['totalprice'];

                        $valuespair .= "('$varietyid', $noofbags, $qtyperbag, $totalquantity, $priceperbag,$totalprice),";
                    }

                    $valuespair = substr($valuespair, 0, -1);
                    $inserttable = $insertdetails . $valuespair . ';';
                    if ($uploadok == 1) {
                        $conn->query($inserttable);
                    }
                }

                $_SESSION['message'] = $message;

                $location = "Location:../varietyMaster.php";
                header($location);
                mysqli_close($conn);
            }
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getVarietyDetails($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $companyid = $userdetails['companyid'];
            $varietyid = $parameters['varietyid'];

            $sql = "SELECT v.id,v.cropid,v.imgId,c.cropname,img.image_path,v.imgId,v.variety_description,v.column1,v.column2,v.column3,v.column4,v.column5,v.column6,v.column7,v.column8,rf.description,img.is_new,v.status,v.createddate,v.createdtime,v.createdby from varietymaster v left join cropmaster c on c.id=v.cropid left join tbl_imagedetails img on img.id=v.imgId left join referencecodes rf on rf.parentcode=5 and rf.referencecode=v.column6 WHERE v.id='$varietyid' AND c.companyid='$companyid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo $row['id'] . '~' . $row['cropid'] . '~' . $row['imgId'] . '~' . $row['variety_description'] . '~' . $row['column3'] . '~' . $row['status'] . '~' . $row['image_path'] . '~' . '<option value=' . $row["cropid"] . '> ' . $row["cropname"] . ' </option>' . '~' . $row["column6"] . '~' . $row["description"] . '~' . $row["column1"] . '~' . $row["column2"] . '~' . $row["column4"] . '~' . $row["column5"] . '~' . $row["column7"] . '~' . $row["column8"] . '~' . $row["is_new"];
                }
            }
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getVarietyDetailsList($conn, $parameters, $locationurl) {
        //get variety details grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $varietyid = $parameters['varietyid'];

            $sql = "SELECT * FROM varietydetails where varietyid='$varietyid'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $dataGrid = '';
                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th style="width:10px;">S.No</th>';
                $dataGrid = $dataGrid . '<th style="width:10px;">Edit</th>';
                $dataGrid = $dataGrid . '<th style="width:10px;">Remove</th>';
                $dataGrid = $dataGrid . '<th> Name</th>';
                $dataGrid = $dataGrid . '<th>Description</th>';

                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $vdetailsid = $row["vdetailsid"];
                    $colname = $row["col_name"];
                    $coldescription = $row["col_description"];
                    $arrvalues = "new Array('varietydetailid~$vdetailsid','editcolname~$colname','editcoldescription~$coldescription')";
                    $processingid = "'" . $vdetailsid . "','1','columnGrid'";
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td style="width:10px;">' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td style="width:10px;"><a href="#popup1"><img width="20" height="20" src="' . $locationurl . '/assets/img/editicon.png' . '" onclick="elementToPassParameters(' . $arrvalues . ')"/></a></td>';
                    $dataGrid = $dataGrid . '<td style="width:10px;"><img width="20" height="20" src="' . $locationurl . '/assets/img/delete.png' . '" onclick="removeItem(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $colname . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $coldescription . '</b></td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</table></div>';
            } else {
                $dataGrid = '';
                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th style="width:10px;">No Records Found</th>';
                $dataGrid = $dataGrid . '<tr></table></div>';
            }
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getVarietyPackingList($conn, $parameters, $locationurl) {
        //get variety packing grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $varietyid = $parameters['varietyid'];
            $mesdesc = $parameters['mesdesc']; //eg kg/ltr

            $sql = "SELECT * FROM varietypackingdetails where varietyid='$varietyid'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $dataGrid = '';
                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th style="width:10px;">S.No</th>';
                $dataGrid = $dataGrid . '<th style="width:10px;">Edit</th>';
                $dataGrid = $dataGrid . '<th style="width:10px;">Remove</th>';
                $dataGrid = $dataGrid . "<th>Quantity per Bag ($mesdesc)</th>";
                $dataGrid = $dataGrid . '<th>No of Bags/Container</th>';
                $dataGrid = $dataGrid . "<th>Total Quantity ($mesdesc)</th>";
                $dataGrid = $dataGrid . '<th>Price per Bag/Container (INR)</th>';
                $dataGrid = $dataGrid . '<th>Total Price (INR)</th>';

                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $packingid = $row["packingid"];
                    $qtyperbag = $row["qtyperbag"];
                    $noofbags = $row["noofbags"];
                    $priceperbag = $row["priceperbag"];
                    $arrvalues = "new Array('packingid~$packingid','editqtyperbag~$qtyperbag','editnoofbags~$noofbags','editpriceperbag~$priceperbag')";
                    $processingid = "'" . $packingid . "','2','packingGrid'";
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td style="width:10px;">' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td style="width:10px;"><a href="#popup2"><img width="20" height="20" src="' . $locationurl . '/assets/img/editicon.png' . '" onclick="elementToPassParameters(' . $arrvalues . ')"/></a></td>';
                    $dataGrid = $dataGrid . '<td style="width:10px;"><img width="20" height="20" src="' . $locationurl . '/assets/img/delete.png' . '" onclick="removeItem(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $qtyperbag . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $noofbags . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["totalquantity"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $priceperbag . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["totalprice"] . '</b></td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</table></div>';
            } else {
                $dataGrid = '';
                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th style="width:10px;">No Records Found</th>';
                $dataGrid = $dataGrid . '<tr></table></div>';
            }
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function editVariety($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $imagurl = substr($locationurl, 0, -11);

            $varietyid = isset($parameters['varietyid']) ? $parameters['varietyid'] : '';
            $cropid = isset($parameters['cropid']) ? $parameters['cropid'] : '';
            $varietyname = isset($parameters['varietyname']) ? $parameters['varietyname'] : '';
            $offers = isset($parameters['offers']) ? $parameters['offers'] : '';
            $displayvolume = isset($parameters['displayvolume']) ? $parameters['displayvolume'] : '';
            $displayprice = isset($parameters['displayprice']) ? $parameters['displayprice'] : '';
            $affiliatelogo = isset($parameters['affiliatelogo']) ? $parameters['affiliatelogo'] : '';
            $hyperlink = isset($parameters['hyperlink']) ? $parameters['hyperlink'] : '';
            $productdescription = isset($parameters['productdescription']) ? $parameters['productdescription'] : '';
            $isnew = isset($parameters['isnewvalue']) ? $parameters['isnewvalue'] : '0';
            $varietystatus = isset($parameters['varietystatus']) ? $parameters['varietystatus'] : '';
            $ispackingvalue = isset($parameters['ispackingvalue']) ? $parameters['ispackingvalue'] : ''; //Y - tick, N- untick
            $imageid = isset($parameters['imageid']) ? $parameters['imageid'] : '';
            $contentname = isset($parameters['contentname']) ? $parameters['contentname'] : '';

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

            //check upload image 
            $target_dir = "../../uploads/variety/";
            $target_file = $target_dir . basename($_FILES["upload_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
            if (isset($parameters['cropid'])) {
                $check = getimagesize($_FILES["upload_image"]["tmp_name"]);
                if ($check !== false) {
                    $message = "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $message = "$contentname ($varietyname) has been updated Successfully!";
                    $uploadOk = 0;
                    $uploadyes = 1;
                    $updatecrop = "UPDATE varietymaster set cropid='$cropid',variety_description='$varietyname',column1='$hyperlink',column2='$productdescription',column3='$offers',column4='$displayprice',column5='$displayvolume',column7='$affiliatelogo',column8='$ispackingvalue',status='$varietystatus',modifieddate='$createddate' WHERE id='$varietyid'";
                    mysqli_query($conn, $updatecrop);

                    $imagequery = "UPDATE tbl_imagedetails set is_new='$isnew',modifieddate='$createddate' WHERE id='$imageid'";
                    mysqli_query($conn, $imagequery);
                }
            }

            if ($uploadOk == 1) {
// Check if file already exists
                if (file_exists($target_file)) {
                    $message = "Sorry, file already exists.";
                    $uploadOk = 0;
                }
// Check file size
                else if ($_FILES["upload_image"]["size"] > 500000) {//500kb
                    $message = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
// Allow certain file formats
                else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                } else {
                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $target_file)) {

                            $file = $_FILES["upload_image"]["name"];  // random name of the file
                            $file = str_replace(" ", '_', $file);
                            $img_path = "/uploads/variety/" . $file;
                            $fileinbytes = $_FILES["upload_image"]["size"];
                            $filesize = $commondao->formatSizeUnits($fileinbytes);
                            $flagtypes = "V"; // setting falgtype 

                            $imagequery = "UPDATE tbl_imagedetails set image_path='$img_path',image_name='$file',image_content='$imageFileType',image_size='$filesize',is_new='$isnew',image_flag='$flagtypes',modifieddate='$createddate' WHERE id='$imageid'";
                            $res = mysqli_query($conn, $imagequery);

                            if ($res) {
                                $updatecrop = "UPDATE varietymaster set cropid='$cropid',variety_description='$varietyname',column1='$hyperlink',column2='$productdescription',column3='$offers',column4='$displayprice',column5='$displayvolume',status='$varietystatus',modifieddate='$createddate' WHERE id='$varietyid'";
                                mysqli_query($conn, $updatecrop);
                            }

                            $message = "The file " . htmlspecialchars(basename($_FILES["upload_image"]["name"])) . " has been uploaded.";
                            $message = "$contentname ($varietyname) has been updated Successfully!";
                        } else {
                            $message = "Sorry, there was an error uploading your file.";
                        }
                    }
                }
            }


            //store into variety details table
            $insertvardetails = " INSERT INTO varietydetails (varietyid, col_name, col_description) VALUES ";
            $valuespairvar = '';
            if (isset($_SESSION['TotalVarietyArraylist'])) {
                $TotalVarietyArraylist = $_SESSION['TotalVarietyArraylist'];
            }
            foreach ($TotalVarietyArraylist as &$value) {
                $colname = $value['colname'];
                $coldescription = $value['coldescription'];

                $valuespairvar .= "('$varietyid', '$colname', '$coldescription'),";
            }

            $valuespairvar = substr($valuespairvar, 0, -1);
            $insertvartable = $insertvardetails . $valuespairvar . ';';
            if ($uploadok == 1 || $uploadyes = 1) {
                $conn->query($insertvartable);
            }

            //store into variety packing details table
            if (strcmp($ispackingvalue, 'Y') == 0) {
                $insertdetails = " INSERT INTO varietypackingdetails (varietyid, noofbags, qtyperbag, totalquantity, priceperbag, totalprice) VALUES ";
                $valuespair = '';
                if (isset($_SESSION['TotalPackingArraylist'])) {
                    $TotalPackingArraylist = $_SESSION['TotalPackingArraylist'];
                }
                foreach ($TotalPackingArraylist as &$value) {
                    $qtyperbag = $value['qtyperbag'];
                    $noofbags = $value['noofbags'];
                    $totalquantity = $value['totalquantity'];
                    $priceperbag = $value['priceperbag'];
                    $totalprice = $value['totalprice'];

                    $valuespair .= "('$varietyid', $noofbags, $qtyperbag, $totalquantity, $priceperbag,$totalprice),";
                }

                $valuespair = substr($valuespair, 0, -1);
                $inserttable = $insertdetails . $valuespair . ';';
                if ($uploadok == 1 || $uploadyes = 1) {
                    $conn->query($inserttable);
                }
            }


            $_SESSION['message'] = $message;

            $location = "Location:../varietyMaster.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    //**************************************************************Affiliate Page******************************************************************
    public function getAffiliateGrid($conn, $parameters, $locationurl) {
        //get affiliate grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);
            $contentname = $parameters['contentname'];

            $sql = "SELECT * from tbl_imagedetails where image_flag='A' AND is_new=1";
            $dataGrid = '';
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th>S.No</th>';
                $dataGrid = $dataGrid . '<th>Edit</th>';
                $dataGrid = $dataGrid . '<th>Remove</th>';
                $dataGrid = $dataGrid . "<th>$contentname ID</th>";
                $dataGrid = $dataGrid . "<th>$contentname Name</th>";
                $dataGrid = $dataGrid . "<th>$contentname Image</th>";
                $dataGrid = $dataGrid . '<th>Created By</th>';
                $dataGrid = $dataGrid . '<th>Created Date</th>';
                $dataGrid = $dataGrid . '<th>Created Time</th>';

                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $processingid = "'" . $row["id"] . "'";
                    $imagurll = $imagurl . $row["image_path"];
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td>' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td><img width=20 height=20 src="' . $locationurl . '/assets/img/editicon.png' . '" onclick="editItem(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td><img width=20 height=20 src="' . $locationurl . '/assets/img/cross_img.png' . '" onclick="delItem(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["id"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["image_name"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><img width=50 height=50 src="' . $imagurll . '" /></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdby"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . Dateparser::datevalidation($row["createddate"]) . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdtime"] . '</td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</tbody></table><div>';
            } else {
                $dataGrid = $dataGrid . '<div class="no-result alert-danger" style="width: 100%;text-align: center;"><p>No Records Found !!!</p></div>';
            }
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function addNewAffLogo($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $affiliatename = $parameters['affiliatename'];
            $imagurl = substr($locationurl, 0, -11);
            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

//upload image
            if (count($_FILES) > 0) {
                $_FILES["upload_image"];

                $target_dir = '../../uploads/affiliatelogo/';
                $types = array("image/jpeg", "image/png", "image/gif");

// upload bill
                for ($i = 0; $i < count($_FILES['upload_image']['tmp_name']); $i++) {
                    $cropid = $NextRunningNumber->nextnumber('2', date("Y"), $conn); // id of the crop
                    $imageid = $NextRunningNumber->nextnumber('1', date("Y"), $conn); // id of the image
                    $imagetype = $_FILES["upload_image"]["type"][$i];
                    $file = $_FILES["upload_image"]["name"][$i];  // random name of the file
                    $image_content = pathinfo($file, PATHINFO_EXTENSION);
                    $fileinbytes = $_FILES["upload_image"]["size"][$i];
                    $filesize = $commondao->formatSizeUnits($fileinbytes);
                    $name = date('YmdHis', time()) . '_' . mt_rand();
                    $file = $name . $file;
                    $file = str_replace(" ", '_', $file);
                    $target_file = $file;
                    $img_path = "/uploads/affiliatelogo/" . $file;
                    $flagtypes = "A"; // setting falgtype 
                    $imagequery = "INSERT INTO tbl_imagedetails (id,referenceid,companyid,image_path,image_name,image_content,image_size,is_new,image_flag,createddate,createdtime,createdby) VALUES ($imageid,$imageid,$companyid,'$img_path','$affiliatename', '$image_content','$filesize', '1', '$flagtypes', '$createddate','$createdtime','$userid')";
                    $res = mysqli_query($conn, $imagequery);
                    if ($res) {
                        if (move_uploaded_file($_FILES["upload_image"]["tmp_name"][$i], $target_dir . $target_file)) {
                            $message = "The file " . basename($_FILES["upload_image"]["name"][$i]) . " has been uploaded.";
                        } else {
                            $message = "Sorry, there was an error uploading your file.";
                        }
                    } else {
                        $message = "image table Error";
                    }
                }
            }
            echo $message;

            $_SESSION['message'] = $message;

            $location = "Location:../affiliateIconMaster.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    function getAffiliateDetails($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $itemid = $parameters['itemid'];
            $sql = "SELECT * FROM tbl_imagedetails where id='$itemid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo $row['image_name'] . '~' . $row['image_path'] . '~' . $row['is_new'];
                }
            }
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    function editAffiliate($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $imagurl = substr($locationurl, 0, -11);
            $itemname = $parameters['itemname'];
            $itemstatus = $parameters['itemstatus'];
            $itemid = $parameters['itemid'];
            $contentname = $parameters['contentname'];
            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

            $target_dir = "../../uploads/affiliatelogo/";
            $target_file = $target_dir . basename($_FILES["upload_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
            if (isset($parameters['itemname'])) {
                $check = getimagesize($_FILES["upload_image"]["tmp_name"]);
                if ($check !== false) {
                    $message = "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $message = "$contentname ($itemname) has been Updated Successfully!";
                    $uploadOk = 0;

                    $imagequery = "UPDATE tbl_imagedetails set is_new='$itemstatus',image_name='$itemname',modifieddate='$createddate' WHERE id='$itemid'";
                    $res = mysqli_query($conn, $imagequery);
                }
            }

            if ($uploadOk == 1) {
// Check if file already exists
                if (file_exists($target_file)) {
                    $message = "Sorry, file already exists.";
                    $uploadOk = 0;
                }
// Check file size
                else if ($_FILES["upload_image"]["size"] > 500000) {//500kb
                    $message = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
// Allow certain file formats
                else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                } else {
                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $target_file)) {

                            $file = $_FILES["upload_image"]["name"];  // random name of the file
                            $file = str_replace(" ", '_', $file);
                            $img_path = "/uploads/affiliatelogo/" . $file;
                            $fileinbytes = $_FILES["upload_image"]["size"];
                            $filesize = $commondao->formatSizeUnits($fileinbytes);
                            $flagtypes = "A"; // setting falgtype 

                            $imagequery = "UPDATE tbl_imagedetails set image_path='$img_path',image_name='$itemname',image_content='$imageFileType',image_size='$filesize',is_new='$itemstatus',image_flag='$flagtypes',modifieddate='$createddate' WHERE id='$itemid'";
                            $res = mysqli_query($conn, $imagequery);

                            $message = "The file " . htmlspecialchars(basename($_FILES["upload_image"]["name"])) . " has been uploaded.";
                        } else {
                            $message = "Sorry, there was an error uploading your file.";
                        }
                    }
                }
            }

            $_SESSION['message'] = $message;

            $location = "Location:../affiliateIconMaster.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    //**************************************************************Home Page******************************************************************
    public function bannerSliderStatus($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $parentcode = $parameters['parentcode'];
            $isuserdriven = $parameters['isuserdriven'];

            $update = "UPDATE referencecodes set isuserdriven='$isuserdriven' WHERE parentcode=$parentcode";
            $conn->query($update);
            $_SESSION['message'] = 'Your Settings updated successfully!';
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getReferencebyParent($conn, $parameters, $locationurl) {
        //reference code list
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $companyid = $userdetails['companyid'];
            $parentcode = $parameters['parentcode'];

            $sql = "SELECT * FROM referencecodes WHERE parentcode='$parentcode'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if (strcmp($row["isuserdriven"], 'Y') == 0) {
                        $status = 'Shown';
                    } else {
                        $status = 'Hide';
                    }
                }
            }
            echo $status;

            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getHomePageDetails($conn, $parameters, $locationurl) {
        //get home page grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);

            $sql = "SELECT * FROM tbl_imagedetails WHERE image_flag IN('H','L') AND companyid='$companyid' ORDER BY image_flag DESC";
            $dataGrid = '';
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th>S.No</th>';
                $dataGrid = $dataGrid . '<th>Edit</th>';
                $dataGrid = $dataGrid . '<th>Description</th>';
                $dataGrid = $dataGrid . '<th>Image</th>';
                $dataGrid = $dataGrid . '<th>Status</th>';
                $dataGrid = $dataGrid . '<th>Created By</th>';
                $dataGrid = $dataGrid . '<th>Created Date</th>';
                $dataGrid = $dataGrid . '<th>Created Time</th>';
                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $imageflag = $row["image_flag"];
                    if (strcmp($imageflag, 'L') == 0) {
                        $description = "Company Logo";
                    } else {
                        $description = "Banner Slider";
                    }
                    $imageid = $row["id"];
                    $imagepath = $row["image_path"];
                    $arrvalues = "new Array('imageid~$imageid','editimageflag~$description','editimagepath~$imagepath','imageflag~$imageflag')";
                    $processingid = "'" . $imageid . "'";
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td>' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td><a href="#popup1"><img width=20 height=20 src="' . $locationurl . '/assets/img/editicon.png' . '" onclick="elementToPassHome(' . $arrvalues . ')"/></a></td>';
                    $dataGrid = $dataGrid . '<td>' . $description . '</td>';
                    $dataGrid = $dataGrid . '<td><b><img width="60" height="60" src="' . $imagurl . $imagepath . '"></b></td>';
                    $dataGrid = $dataGrid . '<td><a class="btn-theme04">' . $row["image_flag"] . '</a></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdby"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . Dateparser::datevalidation($row["createddate"]) . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdtime"] . '</td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</table></div>';
            } else {
                $dataGrid = $dataGrid . '<div class="no-result alert-danger" style="width: 100%;text-align: center;"><p>No Records Found !!!</p></div>';
            }
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function addHomePage($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);

            $flagtypes = $parameters['homeimagetype']; //S-Slider image, L- Logo
            $homeimagedesc = $parameters['homeimagedesc'];

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

//upload image
            if (count($_FILES) > 0) {
                $_FILES["slider_image"];
                $target_dir = '../../uploads/home/';
                $types = array("image/jpeg", "image/png", "image/gif");
                $imagetype = $_FILES["slider_image"]["type"];
                $file = $_FILES["slider_image"]["name"];  // random name of the file
                $image_content = pathinfo($file, PATHINFO_EXTENSION);
                $fileinbytes = $_FILES["slider_image"]["size"];
                $filesize = $commondao->formatSizeUnits($fileinbytes);
                $name = date('YmdHis', time()) . '_' . mt_rand();
                $file = $name . $file;
                $file = str_replace(" ", '_', $file);
                $target_file = $file;
                $img_path = "/uploads/home/" . $file;


                if (move_uploaded_file($_FILES["slider_image"]["tmp_name"], $target_dir . $target_file)) {
                    $imageid = $NextRunningNumber->nextnumber('1', date("Y"), $conn); // id of the image

                    $imagequery = "INSERT INTO tbl_imagedetails (id,referenceid,companyid,image_path,image_name,image_content,image_size,image_flag,createddate,createdtime,createdby) VALUES ($imageid,$imageid,$companyid,'$img_path','$homeimagedesc', '$image_content','$filesize', '$flagtypes', '$createddate','$createdtime','$userid')";
                    mysqli_query($conn, $imagequery);

                    $message = "The file " . basename($_FILES["slider_image"]["name"]) . " has been uploaded.";
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            }

            $_SESSION['message'] = $message;
            $location = "Location:../homePage.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function updateHomePage($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $imagurl = substr($locationurl, 0, -11);
            $imageid = $parameters['imageid'];
            $imageflag = $parameters['imageflag'];

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

            $target_dir = "../../uploads/home/";
            $target_file = $target_dir . basename($_FILES["upload_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
            if (isset($parameters['imageid'])) {
                $check = getimagesize($_FILES["upload_image"]["tmp_name"]);
                if ($check !== false) {
                    $message = "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }
            }

            if ($uploadOk == 1) {
// Check if file already exists
                if (file_exists($target_file)) {
                    $message = "Sorry, file already exists.";
                    $uploadOk = 0;
                }
// Check file size
                else if ($_FILES["upload_image"]["size"] > 500000) {//500kb
                    $message = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
// Allow certain file formats
                else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                } else {
                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $target_file)) {

                            $file = $_FILES["upload_image"]["name"];  // random name of the file
                            $file = str_replace(" ", '_', $file);
                            $img_path = "/uploads/home/" . $file;
                            $fileinbytes = $_FILES["upload_image"]["size"];
                            $filesize = $commondao->formatSizeUnits($fileinbytes);
                            $flagtypes = $imageflag; // setting falgtype 

                            $imagequery = "UPDATE tbl_imagedetails set image_path='$img_path',image_name='$file',image_content='$imageFileType',image_size='$filesize',image_flag='$flagtypes',modifieddate='$createddate' WHERE id='$imageid'";
                            mysqli_query($conn, $imagequery);

                            $message = "The file " . htmlspecialchars(basename($_FILES["upload_image"]["name"])) . " has been uploaded.";
                        } else {
                            $message = "Sorry, there was an error uploading your file.";
                        }
                    }
                }
            }

            $_SESSION['message'] = $message;

            $location = "Location:../homePage.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    /*     * ****************************************************************Blog*************************************************************************** */

    public function getBlogDetails($conn, $parameters, $locationurl) {
        //get blog grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);

            $sql = "SELECT b.*,i.id,i.image_path,i.image_flag,r.description FROM tbl_blog b left join tbl_imagedetails i on i.id=b.imageid LEFT JOIN referencecodes r on r.parentcode=1 and r.referencecode=b.status WHERE image_flag IN('B') AND i.companyid='$companyid' AND b.status NOT IN(0)";
            $dataGrid = '';
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th>S.No</th>';
                $dataGrid = $dataGrid . '<th>Edit</th>';
                $dataGrid = $dataGrid . '<th>Remove</th>';
                $dataGrid = $dataGrid . '<th>Blog title</th>';
                $dataGrid = $dataGrid . '<th>Blog Description</th>';
                $dataGrid = $dataGrid . '<th>Image</th>';
                $dataGrid = $dataGrid . '<th>Status</th>';
                $dataGrid = $dataGrid . '<th>Created By</th>';
                $dataGrid = $dataGrid . '<th>Created Date</th>';
                $dataGrid = $dataGrid . '<th>Created Time</th>';
                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $blogid = $row["blogid"];
                    $imageid = $row["id"];
                    $blogtitle = $row["blog_title"];
                    $blogdesc = $row["blog_desc"];
                    $imagepath = $row["image_path"];
                    $imageflag = $row["image_flag"];
                    $arrvalues = "new Array('blogid~$blogid','imageid~$imageid','editblogtitle~$blogtitle','editblogdesc~$blogdesc','editblogimage~$imagepath','imageflag~$imageflag')";
                    $processingid = "'" . $blogid . "','" . $imageid . "'";
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td>' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td><a href="#popup3"><img width=20 height=20 src="' . $locationurl . '/assets/img/editicon.png' . '" onclick="elementToPassBlog(' . $arrvalues . ')"/></a></td>';
                    $dataGrid = $dataGrid . '<td><img width=20 height=20 src="' . $locationurl . '/assets/img/cross_img.png' . '" onclick="delBlog(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td>' . $blogtitle . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $blogdesc . '</td>';
                    $dataGrid = $dataGrid . '<td><b><img width="60" height="60" src="' . $imagurl . $imagepath . '"></b></td>';
                    $dataGrid = $dataGrid . '<td><a class="btn-theme04">' . $row["description"] . '</a></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdby"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . Dateparser::datevalidation($row["createddate"]) . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdtime"] . '</td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</table></div>';
            } else {
                $dataGrid = $dataGrid . '<div class="no-result alert-danger" style="width: 100%;text-align: center;"><p>No Records Found !!!</p></div>';
            }
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function saveBlog($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);

            $blogtitle = $parameters['blogtitle'];
            $blogdesc = $parameters['blogdesc'];

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

//upload image
            if (count($_FILES) > 0) {
                $_FILES["blog_image"];
                $target_dir = '../../uploads/blog/';
                $types = array("image/jpeg", "image/png", "image/gif");
                $imagetype = $_FILES["blog_image"]["type"];
                $file = $_FILES["blog_image"]["name"];  // random name of the file
                $image_content = pathinfo($file, PATHINFO_EXTENSION);
                $fileinbytes = $_FILES["blog_image"]["size"];
                $filesize = $commondao->formatSizeUnits($fileinbytes);
                $name = date('YmdHis', time()) . '_' . mt_rand();
                $file = $name . $file;
                $file = str_replace(" ", '_', $file);
                $target_file = $file;
                $img_path = "/uploads/blog/" . $file;
                $flagtypes = "B"; // setting falgtype 

                if (move_uploaded_file($_FILES["blog_image"]["tmp_name"], $target_dir . $target_file)) {
                    $blogid = $NextRunningNumber->nextnumber('4', date("Y"), $conn); // id of the blog
                    $imageid = $NextRunningNumber->nextnumber('1', date("Y"), $conn); // id of the image

                    $imagequery = "INSERT INTO tbl_imagedetails (id,referenceid,companyid,image_path,image_name,image_content,image_size,image_flag,createddate,createdtime,createdby) VALUES ($imageid,$blogid,$companyid,'$img_path','$file', '$image_content','$filesize', '$flagtypes', '$createddate','$createdtime','$userid')";
                    mysqli_query($conn, $imagequery);

                    $insertqry = "INSERT INTO tbl_blog (blogid,imageid,blog_title,blog_desc,status,createddate,createdtime,createdby) VALUES ($blogid,$imageid,'$blogtitle','$blogdesc','1','$createddate','$createdtime','$userid')";
                    mysqli_query($conn, $insertqry);



                    $message = "The file " . basename($_FILES["blog_image"]["name"]) . " has been uploaded.";
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            }

            $_SESSION['message'] = $message;
            $location = "Location:../homePage.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function updateBlog($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $imagurl = substr($locationurl, 0, -11);

            $blogid = $parameters['blogid'];
            $imageid = $parameters['imageid'];
            $editblogtitle = $parameters['editblogtitle'];
            $editblogdesc = $parameters['editblogdesc'];
            $imageflag = $parameters['imageflag'];
            $blogstatus = $parameters['blogstatus'];

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

            $target_dir = "../../uploads/blog/";
            $target_file = $target_dir . basename($_FILES["editblog_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
            if (isset($parameters['imageid'])) {
                $check = getimagesize($_FILES["editblog_image"]["tmp_name"]);
                if ($check !== false) {
                    $message = "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $updateqry = "UPDATE tbl_blog set blog_title='$editblogtitle',blog_desc='$editblogdesc',status='$blogstatus',modifieddate='$createddate' WHERE blogid='$blogid' AND imageid='$imageid'";
                    mysqli_query($conn, $updateqry);
                    $message = "Blog has been Updated Successfully!";
                    $uploadOk = 0;
                }
            }

            if ($uploadOk == 1) {
// Check if file already exists
                if (file_exists($target_file)) {
                    $message = "Sorry, file already exists.";
                    $uploadOk = 0;
                }
// Check file size
                else if ($_FILES["editblog_image"]["size"] > 500000) {//500kb
                    $message = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
// Allow certain file formats
                else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                } else {
                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["editblog_image"]["tmp_name"], $target_file)) {

                            $file = $_FILES["editblog_image"]["name"];  // random name of the file
                            $file = str_replace(" ", '_', $file);
                            $img_path = "/uploads/blog/" . $file;
                            $fileinbytes = $_FILES["editblog_image"]["size"];
                            $filesize = $commondao->formatSizeUnits($fileinbytes);
                            $flagtypes = $imageflag; // setting falgtype 

                            $imagequery = "UPDATE tbl_imagedetails set image_path='$img_path',image_name='$file',image_content='$imageFileType',image_size='$filesize',image_flag='$flagtypes',modifieddate='$createddate' WHERE id='$imageid'";
                            $res = mysqli_query($conn, $imagequery);

                            if ($res) {
                                $updateqry = "UPDATE tbl_blog set blog_title='$editblogtitle',blog_desc='$editblogdesc',status='$blogstatus',modifieddate='$createddate' WHERE blogid='$blogid' AND imageid='$imageid'";
                                mysqli_query($conn, $updateqry);
                            }

                            $message = "The file " . htmlspecialchars(basename($_FILES["editblog_image"]["name"])) . " has been uploaded.";
                        } else {
                            $message = "Sorry, there was an error uploading your file.";
                        }
                    }
                }
            }

            $_SESSION['message'] = $message;

            $location = "Location:../homePage.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    /*     * ****************************************************************Gallery*************************************************************************** */

    public function getGalleryPageDetails($conn, $parameters, $locationurl) {
        //get gallery page grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);

            $sql = "SELECT g.galleryid,g.description,g.gallery_flag,rf.description as gallerytype,im.id,im.image_path,im.image_flag,im.createdby,im.createddate,im.createdtime FROM tbl_gallery g left join tbl_imagedetails im on im.id=g.imageid LEFT JOIN referencecodes rf on rf.parentcode=4 AND rf.referencecode=g.gallery_flag WHERE im.image_flag IN('G') AND g.companyid='$companyid' AND g.status NOT IN(0) ORDER BY im.image_flag DESC";
            $dataGrid = '';
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {

                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th>S.No</th>';
                $dataGrid = $dataGrid . '<th>Edit</th>';
                $dataGrid = $dataGrid . '<th>Remove</th>';
                $dataGrid = $dataGrid . '<th>Gallery Type</th>';
                $dataGrid = $dataGrid . '<th>Description</th>';
                $dataGrid = $dataGrid . '<th>Image</th>';
                $dataGrid = $dataGrid . '<th>Status</th>';
                $dataGrid = $dataGrid . '<th>Created By</th>';
                $dataGrid = $dataGrid . '<th>Created Date</th>';
                $dataGrid = $dataGrid . '<th>Created Time</th>';

                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $galleryid = $row["galleryid"];
                    $gallerytype = $row["gallerytype"];
                    $description = $row["description"];
                    $gallery_flagid = $row["gallery_flag"];

                    $imageid = $row["id"];
                    $imagepath = $row["image_path"];
                    $imageflag = $row["image_flag"];

                    $arrvalues = "new Array('galleryid~$galleryid','imageid~$imageid','editigallerytype~$gallerytype','editdescription~$description','imagepath~$imagepath','imageflag~$imageflag','gallery_flagid~$gallery_flagid')";
                    $processingid = "'" . $galleryid . "','" . $imageid . "'";
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td>' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td><a href="#popup2"><img width=20 height=20 src="' . $locationurl . '/assets/img/editicon.png' . '" onclick="elementToPassHome(' . $arrvalues . ')"/></a></td>';
                    $dataGrid = $dataGrid . '<td><img width=20 height=20 src="' . $locationurl . '/assets/img/cross_img.png' . '" onclick="delGallery(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td>' . $gallerytype . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $description . '</td>';
                    $dataGrid = $dataGrid . '<td><b><img width="60" height="60" src="' . $imagurl . $imagepath . '"></b></td>';
                    $dataGrid = $dataGrid . '<td><a class="btn-theme04">' . $row["image_flag"] . '</a></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdby"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . Dateparser::datevalidation($row["createddate"]) . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdtime"] . '</td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</table></div>';
            } else {
                $dataGrid = $dataGrid . '<div class="no-result alert-danger" style="width: 100%;text-align: center;"><p>No Records Found !!!</p></div>';
            }
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getReferencecodes($conn, $parameters, $locationurl) {
        //reference code list
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $companyid = $userdetails['companyid'];
            $parentcode = $parameters['parentcode'];

            $sql = "SELECT * FROM referencecodes WHERE parentcode='$parentcode' order by referencecode";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo '<option value="0">-- Select One --</option>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value=' . $row["referencecode"] . '> ' . $row["description"] . ' </option>';
                }
            }

            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function saveReferenceCodes($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $parentcode = $parameters['parentcode'];
            $description = $parameters['description'];

            $sql = "SELECT * FROM referencecodes WHERE parentcode='$parentcode' AND referencecode NOT IN(100)";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $referencecode = $row["referencecode"] + 1;
                }
            }

            $imagequery = "INSERT INTO referencecodes (parentcode,referencecode,description,isuserdriven) VALUES ('$parentcode','$referencecode','$description','Y')";
            mysqli_query($conn, $imagequery);



            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getReferencecodeswithname($conn, $parameters, $locationurl) {
        //reference code list
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $companyid = $userdetails['companyid'];
            $parentcode = $parameters['parentcode'];
            $referencecode = $parameters['referencecode'];

            $sql = "SELECT * FROM referencecodes WHERE parentcode='$parentcode' AND referencecode='$referencecode'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo $row["description"];
                }
            }

            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function saveGalleryPage($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);

            $gallerytype = $parameters['gallerytype'];
            $gallerydesc = $parameters['gallerydesc'];

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

//upload image
            if (count($_FILES) > 0) {
                $_FILES["upload_image"];

                $target_dir = '../../uploads/gallery/';
                $types = array("image/jpeg", "image/png", "image/gif");

                $galleryid = $NextRunningNumber->nextnumber('5', date("Y"), $conn); // id of the gallery
                $imageid = $NextRunningNumber->nextnumber('1', date("Y"), $conn); // id of the image
                $imagetype = $_FILES["upload_image"]["type"];
                $file = $_FILES["upload_image"]["name"];  // random name of the file
                $image_content = pathinfo($file, PATHINFO_EXTENSION);
                $fileinbytes = $_FILES["upload_image"]["size"];
                $filesize = $commondao->formatSizeUnits($fileinbytes);
                $name = date('YmdHis', time()) . '_' . mt_rand();
                $file = $name . $file;
                $file = str_replace(" ", '_', $file);
                $target_file = $file;
                $img_path = "/uploads/gallery/" . $file;
                $flagtypes = "G"; // setting falgtype 

                if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $target_dir . $target_file)) {
                    $imagequery = "INSERT INTO tbl_imagedetails (id,referenceid,companyid,image_path,image_name,image_content,image_size,image_flag,createddate,createdtime,createdby) VALUES ($imageid,$galleryid,$companyid,'$img_path','$file', '$image_content','$filesize', '$flagtypes', '$createddate','$createdtime','$userid')";
                    mysqli_query($conn, $imagequery);

                    $insertqry = "INSERT INTO tbl_gallery (galleryid,imageid,companyid,description,gallery_flag,status,createddate,createdtime,createdby) VALUES ($galleryid,$imageid,$companyid,'$gallerydesc','$gallerytype',1,'$createddate','$createdtime','$userid')";
                    mysqli_query($conn, $insertqry);
                    $message = "The file " . basename($_FILES["upload_image"]["name"]) . " has been uploaded.";
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            }

            $_SESSION['message'] = $message;
            $location = "Location:../galleryPage.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function updateGalleryPage($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $imagurl = substr($locationurl, 0, -11);

            $galleryid = $parameters['galleryid'];
            $imageid = $parameters['imageid'];
            $imageflag = $parameters['imageflag'];
            $editdescription = $parameters['editdescription'];
            $editgallerytype = $parameters['editgallerytype'];

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

            $target_dir = "../../uploads/gallery/";
            $target_file = $target_dir . basename($_FILES["upload_editimage"]["name"]);
            $uploadOk = 0;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
            if (isset($parameters['galleryid'])) {
                $check = getimagesize($_FILES["upload_editimage"]["tmp_name"]);
                if ($check !== false) {
                    $message = "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                    $updateqry = "UPDATE tbl_gallery set description='$editdescription',gallery_flag='$editgallerytype',modifieddate='$createddate' WHERE galleryid='$galleryid' AND imageid='$imageid'";
                    mysqli_query($conn, $updateqry);
                    $message = "Gallery has been Updated Successfully!";
                }
            }

            if ($uploadOk == 1) {
// Check if file already exists
                if (file_exists($target_file)) {
                    $message = "Sorry, file already exists.";
                    $uploadOk = 0;
                }
// Check file size
                else if ($_FILES["upload_editimage"]["size"] > 500000) {//500kb
                    $message = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
// Allow certain file formats
                else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                } else {
                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["upload_editimage"]["tmp_name"], $target_file)) {

                            $file = $_FILES["upload_editimage"]["name"];  // random name of the file
                            $file = str_replace(" ", '_', $file);
                            $img_path = "/uploads/gallery/" . $file;
                            $fileinbytes = $_FILES["upload_editimage"]["size"];
                            $filesize = $commondao->formatSizeUnits($fileinbytes);
                            $flagtypes = $imageflag; // setting falgtype 

                            $imagequery = "UPDATE tbl_imagedetails set image_path='$img_path',image_name='$file',image_content='$imageFileType',image_size='$filesize',image_flag='$flagtypes',modifieddate='$createddate' WHERE id='$imageid'";
                            $res = mysqli_query($conn, $imagequery);

                            if ($res) {
                                $updateqry = "UPDATE tbl_gallery set description='$editdescription',gallery_flag='$editgallerytype',modifieddate='$createddate' WHERE galleryid='$galleryid' AND imageid='$imageid'";
                                $res2 = mysqli_query($conn, $updateqry);
                            }

                            $message = "Details updated and The file " . htmlspecialchars(basename($_FILES["upload_editimage"]["name"])) . " has been uploaded.";
                        } else {
                            $message = "Sorry, there was an error uploading your file.";
                        }
                    }
                }
            }

            $_SESSION['message'] = $message;

            $location = "Location:../galleryPage.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    //**************************************************************User Master******************************************************************
    public function userGrid($conn, $parameters, $locationurl) {
        //get user grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);

            $sql = "SELECT r.*,rf.description as userstatus,rf2.description as sex FROM registration r LEFT JOIN referencecodes rf on rf.parentcode=1 and rf.referencecode=r.status LEFT JOIN referencecodes rf2 on rf2.parentcode=3 and rf2.referencecode=r.gender WHERE companyid='$companyid'";
            $dataGrid = '';
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {

                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th>S.No</th>';
                $dataGrid = $dataGrid . '<th>Edit</th>';
                $dataGrid = $dataGrid . '<th>User Id</th>';
                $dataGrid = $dataGrid . '<th>User Name</th>';
                $dataGrid = $dataGrid . '<th>First Name</th>';
                $dataGrid = $dataGrid . '<th>Last Name</th>';
                $dataGrid = $dataGrid . '<th>Mobile</th>';
                $dataGrid = $dataGrid . '<th>Email</th>';
                $dataGrid = $dataGrid . '<th>Gender</th>';
                $dataGrid = $dataGrid . '<th>Status</th>';
                $dataGrid = $dataGrid . '<th>Created By</th>';
                $dataGrid = $dataGrid . '<th>Created Date</th>';
                $dataGrid = $dataGrid . '<th>Created Time</th>';

                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $processingid = "'" . $row["userid"] . "'";
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td>' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td><img width=20 height=20 src="' . $locationurl . '/assets/img/editicon.png' . '" onclick="editUser(' . $processingid . ')"/></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["userid"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["username"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["firstname"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["lastname"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["mobile"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["email"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["sex"] . '</td>';
                    $dataGrid = $dataGrid . '<td><a class="btn-theme04">' . $row["userstatus"] . '</a></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdby"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . Dateparser::datevalidation($row["createddate"]) . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdtime"] . '</td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</table></div>';
            } else {
                $dataGrid = $dataGrid . '<div class="no-result alert-danger" style="width: 100%;text-align: center;"><p>No Records Found !!!</p></div>';
            }
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getUserRole($conn) {
        //user role list
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $companyid = $userdetails['companyid'];

            $sql = "SELECT * FROM tbl_rolemaster WHERE companyid='$companyid'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo '<option value="0">-- Select Role --</option>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value=' . $row["roleid"] . '> ' . $row["description"] . ' </option>';
                }
            }
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function addNewUser($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $createdby = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);

            $usertype = $parameters['usertype'];
            $userid = $parameters['userid'];
            $displayname = $parameters['displayname'];
            $fname = isset($parameters['fname']) ? $parameters['fname'] : '';
            $lname = isset($parameters['lname']) ? $parameters['lname'] : '';
            $mobile = isset($parameters['mobile']) ? $parameters['mobile'] : '';
            $email = isset($parameters['email']) ? $parameters['email'] : '';
            $gender = isset($parameters['gender']) ? $parameters['gender'] : '';
            $password = md5($parameters['password']);
            $userstatus = $parameters['userstatus'];

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');

            $insertuser = "INSERT INTO registration (companyid,roleid,userid,username,firstname,lastname,mobile,email,gender,password,status,createddate,createdtime,createdby) VALUES ('$companyid','$usertype','$userid','$displayname','$fname','$lname','$mobile','$email','$gender','$password','$userstatus','$createddate','$createdtime','$createdby')";
            $res = mysqli_query($conn, $insertuser);

            if ($res) {
                $message = 'New User Created Successfully!';
            } else {
                $message = 'Something went wrong! Please try again!';
            }
            $_SESSION['message'] = $message;

            $location = "Location:../userMaster.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function getUserDetails($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $companyid = $userdetails['companyid'];
            $userid = $parameters['userid'];

            $sql = "SELECT r.userid,r.roleid,r.username,r.firstname,r.lastname,r.mobile,r.email,r.gender,rm.description,r.status from registration r LEFT JOIN tbl_rolemaster rm on rm.roleid=r.roleid  WHERE r.userid='$userid' AND r.companyid='$companyid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo $row['userid'] . '~' . $row['username'] . '~' . $row['firstname'] . '~' . $row['lastname'] . '~' . $row['mobile'] . '~' . $row['email'] . '~' . $row['gender'] . '~' . $row['status'] . '~' . '<option value=' . $row["roleid"] . '> ' . $row["description"] . ' </option>';
                }
            }
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function editUser($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $commondao = new commondao();
            $NextRunningNumber = new NextRunningNumber();
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $createdby = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $usertype = $parameters['usertype'];
            $userid = $parameters['userid'];
            $displayname = $parameters['displayname'];
            $fname = isset($parameters['fname']) ? $parameters['fname'] : '';
            $lname = isset($parameters['lname']) ? $parameters['lname'] : '';
            $mobile = isset($parameters['mobile']) ? $parameters['mobile'] : '';
            $email = isset($parameters['email']) ? $parameters['email'] : '';
            $gender = isset($parameters['gender']) ? $parameters['gender'] : '';
            $password = md5($parameters['password']);
            $userstatus = $parameters['userstatus'];

            $createddate = date('Y-m-d');
            date_default_timezone_set('Asia/Kolkata');
            $createdtime = date('H:i:s');


            $updateuser = "UPDATE registration set username='$displayname',firstname='$fname',lastname='$lname',mobile='$mobile',email='$email',gender='$gender',password='$password',status='$userstatus',roleid='$usertype' WHERE userid='$userid' AND companyid='$companyid'";
            mysqli_query($conn, $updateuser);


            $message = "User Details Updated Successfully!";
            $_SESSION['message'] = $message;

            $location = "Location:../userMaster.php";
            header($location);
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    /*     * ****************************************************************Reports*************************************************************************** */

    public function getContactusGrid($conn, $parameters, $locationurl) {
        //get grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);


            $sql = "select * from tbl_contactdetails order by createddate";
            $dataGrid = '';
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {

                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th>S.No</th>';
                $dataGrid = $dataGrid . "<th>Contact Name</th>";
                $dataGrid = $dataGrid . "<th>Contact No</th>";
                $dataGrid = $dataGrid . "<th>Contact Email</th>";
                $dataGrid = $dataGrid . "<th>Contact Subject</th>";
                $dataGrid = $dataGrid . "<th>Contact Message</th>";
                $dataGrid = $dataGrid . '<th>Created By</th>';
                $dataGrid = $dataGrid . '<th>Created Date</th>';
                $dataGrid = $dataGrid . '<th>Created Time</th>';
                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td>' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["contact_name"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["contact_no"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["contact_email"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["contact_subject"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["contact_message"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdby"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . Dateparser::datevalidation($row["createddate"]) . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdtime"] . '</td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</tbody></table></div>';
            } else {
                $dataGrid = $dataGrid . '<div class="no-result alert-danger" style="width: 100%;text-align: center;"><p>No Records Found !!!</p></div>';
            }
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    /*     * ****************************************************************Trash*************************************************************************** */

    public function getTrashGrid($conn, $parameters, $locationurl) {
        //get grid
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);
            $trashid = $parameters['trashid'];
            $contentname = $parameters['contentname'];
            $delstatus = '0';

            switch ($trashid) {
                case '1':
                    //category
                    $sql = "select cm.id,cm.cropname as maincolumn,cm.createdby,cm.createddate,cm.createdtime,i.image_path from cropmaster cm left JOIN tbl_imagedetails i on i.id=cm.imgId where cm.status=$delstatus";
                    $tableheader = "Category Master Trash";
                    $tablecolumn = 'Name of the Category';
                    $filename = 'cropMaster';
                    break;
                case '2':
                    //product
                    $sql = "select vm.id,vm.variety_description as maincolumn,cm.cropname,vm.createdby,vm.createddate,vm.createdtime,i.image_path from varietymaster vm left join cropmaster cm on cm.id=vm.cropid left JOIN tbl_imagedetails i on i.id=vm.imgId where vm.status=$delstatus";
                    $tableheader = "Product Master Trash";
                    $tablecolumn = 'Name of the Product';
                    $filename = 'varietyMaster';
                    break;
                case '3':
                    //blog
                    $sql = "select b.blogid as id,b.blog_title as maincolumn,b.createdby,b.createddate,b.createdtime,i.image_path from tbl_blog b left JOIN tbl_imagedetails i on i.id=b.imageid where b.status=$delstatus";
                    $tableheader = "Blog Trash";
                    $tablecolumn = 'Name of the Blog';
                    $filename = 'homePage';
                    break;
                case '4':
                    //Gallery
                    $sql = "select g.galleryid as id,g.description as maincolumn,g.createdby,g.createddate,g.createdtime,i.image_path from tbl_gallery g left JOIN tbl_imagedetails i on i.id=g.imageid where g.status=$delstatus";
                    $tableheader = "Gallery Trash";
                    $tablecolumn = 'Name of the Gallery';
                    $filename = 'galleryPage';
                    break;
                case '5':
                    //Affiliate
                    $sql = "select i.id,i.image_name as maincolumn,i.createdby,i.createddate,i.createdtime,i.image_path from tbl_imagedetails i where i.image_flag='A' and is_new=$delstatus";
                    $tableheader = "Gallery Trash";
                    $tablecolumn = 'Name of the Gallery';
                    $filename = 'affiliateIconMaster';
                    break;
                default:
                    break;
            }


            $dataGrid = '';
            $result = mysqli_query($conn, $sql);
            $totalcount = mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0) {

                $dataGrid = $dataGrid . '<div class="divscrolltable"><table id="minitable" class="display" width="98%" align="center" border="0" cellpadding="1" cellspacing="1">';
                $dataGrid = $dataGrid . '<thead>';
                $dataGrid = $dataGrid . "<tr><th colspan='10'>$tableheader</th></tr>";
                $dataGrid = $dataGrid . '<tr class="gridmenu">';
                $dataGrid = $dataGrid . '<th>Remove All <br><input type="checkbox" id="ckbCheckAll" onclick="SelectandDeselectalltags(this)"/></th>';
                $dataGrid = $dataGrid . '<th>S.No</th>';
                $dataGrid = $dataGrid . '<th>Id</th>';
                $dataGrid = $dataGrid . "<th>Image</th>";
                if ($trashid == 2) {
                    $dataGrid = $dataGrid . "<th>Category</th>";
                }
                $dataGrid = $dataGrid . "<th>$tablecolumn</th>";
                $dataGrid = $dataGrid . '<th>Created By</th>';
                $dataGrid = $dataGrid . '<th>Created Date</th>';
                $dataGrid = $dataGrid . '<th>Created Time</th>';


                $dataGrid = $dataGrid . '</tr>';
                $dataGrid = $dataGrid . '</thead>';
                $dataGrid = $dataGrid . '<tbody id="tab_data">';
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $imagepath = $row['image_path'];
                    $processingid = "'" . $row["id"] . "','" . $filename . "'";
                    $idd = $row["id"];
                    $sno++;
                    $dataGrid = $dataGrid . '<tr>';
                    $dataGrid = $dataGrid . '<td style="text-align:center"><input type="checkbox" class="checkBoxClass" id="Checkbox' . $sno . '" value="' . $idd . '" onclick="getCheckboxpage(this,' . $processingid . ')" /></td>';
                    $dataGrid = $dataGrid . '<td>' . $sno . '</td>';
                    $dataGrid = $dataGrid . '<td><b>' . $row["id"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td><img width="50" height="50" src="' . $imagurl . $imagepath . '"></td>';
                    if ($trashid == 2) {
                        $dataGrid = $dataGrid . '<td><b>' . $row["cropname"] . '</b></td>';
                    }
                    $dataGrid = $dataGrid . '<td><b>' . $row["maincolumn"] . '</b></td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdby"] . '</td>';
                    $dataGrid = $dataGrid . '<td>' . Dateparser::datevalidation($row["createddate"]) . '</td>';
                    $dataGrid = $dataGrid . '<td>' . $row["createdtime"] . '</td>';
                    $dataGrid = $dataGrid . '</tr>';
                }
                $dataGrid = $dataGrid . '</tbody></table></div>';
            } else {
                $dataGrid = $dataGrid . '<div class="no-result alert-danger" style="width: 100%;text-align: center;"><p>No Records Found !!!</p></div>';
            }
            $dataGrid = $dataGrid . '<input type="hidden" name="totalcount" id="totalcount" value="' . $totalcount . '"/>';
            $dataGrid = $dataGrid . '<input type="hidden" name="filename" id="filename" value="' . $filename . '"/>';
            echo $dataGrid;
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function saveTrash($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);
            $id = isset($parameters['iddArray']) ? $parameters['iddArray'] : '';
            $filename = isset($parameters['filename']) ? $parameters['filename'] : '';
            $method = isset($parameters['method']) ? $parameters['method'] : '';
            $flag = isset($parameters['flag']) ? $parameters['flag'] : '';

            $statuscode = 0;

            switch ($filename) {
                case 'cropMaster':
                    $updatetrash = "UPDATE cropmaster set status='$statuscode' WHERE id='$id'";
                    $message = "Category ($id) Removed Successfully!";
                    break;
                case 'varietyMaster':
                    if (strcmp($flag, 'A') == 0) {
                        echo $updatetrash = "UPDATE varietymaster set status='$statuscode'";
                        $message = "All Products Removed Successfully!";
                    } else {
                        $updatetrash = "UPDATE varietymaster set status='$statuscode' WHERE id IN($id)";
                        $message = "Product ($id) Removed Successfully!";
                    }
                    break;
                case 'homePage':
                    $updatetrash = "UPDATE tbl_blog set status='$statuscode' WHERE blogid='$id'";
                    $message = "Blog ($id) Removed Successfully!";
                    break;
                case 'galleryPage':
                    $updatetrash = "UPDATE tbl_gallery set status='$statuscode' WHERE galleryid='$id'";
                    $message = "Gallery ($id) Removed Successfully!";
                    break;
                case 'affiliateIconMaster':
                    $updatetrash = "UPDATE tbl_imagedetails set is_new='$statuscode' WHERE id='$id'";
                    $message = "Affiliate Logo ($id) Removed Successfully!";
                    break;
                default:
                    break;
            }


             mysqli_query($conn, $updatetrash);

            $_SESSION['message'] = $message;
            $location = "Location:../$filename.php";
            header($location);

            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    public function delTrash($conn, $parameters, $locationurl) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            session_start();
            $userdetails = $_SESSION["userdetails"];
            $userid = $userdetails['userid'];
            $companyid = $userdetails['companyid'];
            $imagurl = substr($locationurl, 0, -11);
            $id = isset($parameters['iddArray']) ? $parameters['iddArray'] : '';
            $filename = isset($parameters['filename']) ? $parameters['filename'] : '';
            $method = isset($parameters['method']) ? $parameters['method'] : '';
            $statuscode = 0;
            $deleteok = 0;

            //get image paths
            $sql = "select * from tbl_imagedetails where referenceid IN($id)";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $imagepath = $row['image_path'];
                    unlink('../..' . $imagepath) or die("Failed to <strong class='highlight'>delete</strong> file");
                    $deleteok = 1;
                }
            }

            $BEGIN = 'BEGIN; ';
            $COMMIT = ' COMMIT;';
            $delvd = '';
            $delvpd = '';

            switch ($filename) {
                case 'cropMaster':
                    $deletetrash = " DELETE from cropmaster WHERE id IN($id);";
                    $message = "Category ($id) Removed Permenantly!";
                    break;
                case 'varietyMaster':
                    $delvd = " DELETE from varietydetails where varietyid IN($id);";
                    $delvpd = " DELETE from varietypackingdetails where varietyid IN($id);";
                    $deletetrash = " DELETE from varietymaster WHERE id IN($id);";
                    $message = "Product ($id) Removed Permenantly!";
                    break;
                case 'homePage':
                    $deletetrash = " DELETE from tbl_blog WHERE blogid IN($id);";
                    $message = "Blog ($id) Removed Permenantly!";
                    break;
                case 'galleryPage':
                    $deletetrash = " DELETE from tbl_gallery WHERE galleryid IN($id);";
                    $message = "Gallery ($id) Removed Permenantly!";
                    break;
                case 'affiliateIconMaster':
                    $deletetrash = " DELETE from tbl_imagedetails WHERE id IN($id);";
                    $message = "Affiliate Logo ($id) Removed Permenantly!";
                    break;
                default:
                    break;
            }

            $delimage = " DELETE from tbl_imagedetails where referenceid IN($id);";
            $concatenate = $BEGIN . $delvd . $delvpd . $deletetrash . $delimage . $COMMIT;

            if ($deleteok == 1) {
                $res = mysqli_multi_query($conn, $concatenate);
                if ($res) {
                    
                } else {
                    $message = mysqli_error($conn);
                }
            } else {
                $message = 'Something went wrong! Please try again';
            }
            $_SESSION['message'] = $message;
            $location = "Location:../Trash.php";
            header($location);

            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

}

?>