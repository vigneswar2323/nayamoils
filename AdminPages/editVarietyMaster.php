<?php
require_once '../common/ApplicationConstant.php';
require_once '../connection/DBConnection.php';
$DBConnection = new DBConnection();
$loader_Path = constant("LOADER");
$locationurl = SITE_URL_ADMIN;
$adminname = ADMINNAME;
$message = "";
$varietyid = $_POST['varietyid'];
$cropid = $_POST['crpid'];
$imageid = $_POST['imageid'];
$contentname = $_POST['contentname'];
$prevpagename = 'varietyMaster.php';

session_start();
if (isset($_SESSION['message'])) {
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
    unset($_SESSION['message']);
}

if ($_SESSION['userdetails']) {
    $userdetails = $_SESSION["userdetails"];
    $adminname = $userdetails['username'];
} else {
    header("location:$locationurl/adminLogin.php");
}

if (isset($_SESSION['TotalVarietyArraylist'])) {
    unset($_SESSION['TotalVarietyArraylist']);
}

if (isset($_SESSION['TotalPackingArraylist'])) {
    unset($_SESSION['TotalPackingArraylist']);
}

if (isset($_SESSION['TotalModifyPackingArraylist'])) {
    unset($_SESSION['TotalModifyPackingArraylist']);
}
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php include './common/header.php'; ?>
    </head>
    <style>

        input[type=text],[type=number], select, textarea {
            width: 90%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
    </style>
    <body>
        <section id="container" >
            <?php include './common/leftnavigationbar.php'; ?>
            <section id="main-content">
                <section class="wrapper">
                    <div class="row mt">                       
                        <div class="col-md-12">
                            <div class="header_bg content-panel">
                                <div class="header_bg">
                                    <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> <a onclick="backbtn('<?php echo $prevpagename ?>')"> <?= $contentname ?> Master</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Edit <?= $contentname ?> <input style="float: right;margin-right: 10px;padding: 5px;" type="button" class="btn-cancel" onclick="backbtn('<?php echo $prevpagename ?>');" value="Cancel"/><input style="float: right;margin-right: 10px;padding: 5px;" type="button" class="btn-facebook" onclick="updateVariety();" value="Update"/></h4>
                                </div>
                            </div>
                            <?php
                            if ($message) {
                                echo '<div class="success">' . $message . '</div>';
                            }
                            ?>
                            <form enctype="multipart/form-data" method="post" name="Form">
                                <div class="content-panel">
                                    <div class="modalloder" id="loader" style="display: none">
                                        <div class="center">
                                            <img width="100px" height="100px" alt="" src="<?php echo $loader_Path ?>" />
                                        </div>
                                    </div>

                                    <div class="w3-container">
                                        <div id="crop" class="w3-container city">
                                            <section id="unseen">
                                                <div class="form-style-9">
                                                    <ul> 
                                                        <li>                                                
                                                            <a><?= $contentname ?> ID </a>
                                                            <input type="text" class="field-style field-full" name="varietyid" id="varietyid" readonly/>
                                                        </li>
                                                        <li>                                                
                                                            <a>Name of the Category <span class="mandatory_red">*</span></a>
                                                            <select class="field-style field-full" name="cropid" id="cropid"></select>
                                                        </li>
                                                        <li>                                                
                                                            <a>Name of the <?= $contentname ?> <span class="mandatory_red">*</span></a>
                                                            <input type="text" class="field-style field-full" name="varietyname" id="varietyname"/>
                                                        </li>
                                                        <li>                                                
                                                            <a><?= $contentname ?> Offers <span class="mandatory_red"></span></a>
                                                            <input type="text" class="field-style field-full" name="offers" id="offers"/>
                                                        </li>
                                                        <li>                                                
                                                            <a>Display Volume per unit <span class="mandatory_blue" id="mesid"></span><span class="mandatory_red">*</span></a>
                                                            <input type="text" class="field-style field-full" name="displayvolume" id="displayvolume"/>
                                                        </li>
                                                        <li>                                                
                                                            <a>Display Price per unit <span class="mandatory_blue">(INR)</span><span class="mandatory_red">*</span></a>
                                                            <input type="text" class="field-style field-full"  name="displayprice" id="displayprice"/>
                                                        </li>
                                                        <fieldset class="fieldsetstyle2" style="padding: 15px;"><legend><?= $contentname ?> Details <span title="Add New"><img src="assets/img/plusicon.jpg" height="25" width="25" onclick="opennew('minitable2')"></span></legend>
                                                            <div id="columnGrid"></div>
                                                            <table id="minitable2" class="minitable" style="display: none;">
                                                                <tr class="gridmenu"><th>Name <span class="mandatory_red">*</span></th><th>Description <span class="mandatory_red">*</span></th><th colspan="2"></th></tr>
                                                                <tr><td><input type="text"  name="colname" id="colname"/></td>
                                                                    <td><input type="text"  name="coldescription" id="coldescription"/></td>
                                                                    <td>
                                                                        <input id="addVarBtn" type="button" class="btn-facebook" onclick="constructArray();" value="Add"/>
                                                                        <input id="addVarBtnDis" type="button"  value="Add" style="display: none;"/>
                                                                        <input id="updateVarBtn" type="button" class="btn-facebook" onclick="modifyVarietyArray();" value="Update" style="display: none;"/>
                                                                        <input id="updateVarBtnDis" type="button"  value="Update" style="display: none;"/>
                                                                    </td>
                                                                    <td><input type="button" class="btn-cancel" value="Cancel" onclick="cancelArray('TotalVarietyArraylist', 'minitable2');"/></td>
                                                                </tr>
                                                            </table>
                                                            <div id="addColumnGrid"></div>
                                                        </fieldset>

                                                        <fieldset class="fieldsetstyle2" style="padding: 15px;"><legend>Packing Details <span id="packingPlusBtn" title="Add Unit of Packing"><img src="assets/img/plusicon.jpg" height="25" width="25" onclick="opennew('minitable3')"></span> <input id="packingckbox" type="checkbox" onclick="ispacking(this)"></legend>
                                                            <div id="packingdiv">
                                                                <div id="packingGrid"></div>
                                                                <table id="minitable3" class="minitable" style="display: none;">
                                                                    <tr class="gridmenu"><th>Quantity per Bag <span class="mandatory_blue" id="mesurementid"></span><span class="mandatory_red">*</span></th><th>No of Bags <span class="mandatory_red">*</span></th><th>Price per Bag (INR) <span class="mandatory_red">*</span></th><th colspan="2"></th></tr>
                                                                    <tr>
                                                                        <td><input type="number"  name="qtyperbag" id="qtyperbag"/></td>
                                                                        <td><input type="number"  name="noofbags" id="noofbags"/></td>
                                                                        <td><input type="number"  name="priceperbag" id="priceperbag"/></td>
                                                                        <td>
                                                                            <input id="addPackBtn" type="button" class="btn-facebook" onclick="constructPackingArray();" value="Add"/>
                                                                            <input id="addPackBtnDis" type="button"  value="Add" style="display: none;"/>
                                                                            <input id="updatePackBtn" type="button" class="btn-facebook" onclick="constructModifyPackingArray();" value="Update" style="display: none;"/>
                                                                            <input id="updatePackBtnDis" type="button"  value="Update" style="display: none;"/>
                                                                        </td>
                                                                        <td><input type="button" class="btn-cancel" value="Cancel" onclick="cancelArray('TotalPackingArraylist', 'minitable3');"/></td>
                                                                    </tr>
                                                                </table>
                                                                <div id="addPackingGrid"></div>
                                                            </div>
                                                        </fieldset>
                                                        <table width="100%" border="0" cellspacing="1" cellpadding="5" class="tblWhite">
                                                            <tr>
                                                                <td valign="top">                                              
                                                                    <a>Affiliate Logo <span class="mandatory_red"></span></a>
                                                                    <select style="width: 50%;" name="affiliatelogo" id="affiliatelogo"></select>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                        <li>
                                                            <a>Affiliate Marketing Link </a>
                                                            <textarea class="field-style field-full" id="hyperlink" name="hyperlink" placeholder="write upto 500 chars maximum..." maxlength="500"></textarea>
                                                        </li>
                                                        <li>
                                                            <a>About Product </a>
                                                            <textarea class="field-style field-full" id="productdescription" name="productdescription" placeholder="write upto 500 chars maximum..." maxlength="500"></textarea>
                                                        </li>

                                                        <li>                                                
                                                            <a>Status <span class="mandatory_red">*</span></a>
                                                            <select class="field-style field-full" name="varietystatus" id="varietystatus">
                                                                <option value="1">Active</option>
                                                                <option value="2">In-Active</option>
                                                            </select>
                                                        </li>
                                                        <li>                                                
                                                            <a>Is Home Page?</a>
                                                            <input type="checkbox" name="isnew" id="isnew" onchange="gethomepage(this)" value="1"/>
                                                        </li>
                                                        <li>                                                
                                                            <a><?= $contentname ?> Image</a>
                                                            <img  height="100" width="100" id="varietyimage" name="varietyimage">
                                                        </li>
                                                        <li> 
                                                            <fieldset class="form-style-9 field-style field-full">
                                                                <a>Upload Image</a>
                                                                <input type="file" id="myFile" name="upload_image" class="btn-facebook" value="Upload" />
                                                                <div id="imagePreview"> </div>
                                                            </fieldset>
                                                        </li>
                                                    </ul>

                                                    <center> 
                                                        <input type="button" class="btn-cancel" onclick="backbtn('<?php echo $prevpagename ?>');" value="Cancel"/>
                                                        <input type="button" class="btn-facebook" onclick="updateVariety();" value="Update"/>
                                                    </center>
                                                </div> 
                                            </section>
                                        </div>
                                    </div>

                                    <!-- Edit variety details popup-->
                                    <div id="popup1" class="overlay">
                                        <div class="popup">
                                            <div class="popup_header_new">Edit <?= $contentname ?> Details<a id="sss" class="closepopup" href="#">&times;</a></div>
                                            <section id="unseen">
                                                <div class="form-style-9" style="overflow-y: scroll;height: 300px;">
                                                    <ul> 
                                                        <li>                                                
                                                            <a><?= $contentname ?> Name <span class="mandatory_red">*</span></a>
                                                            <input type="text" class="field-style field-full" name="editcolname" id="editcolname"/>
                                                        </li>
                                                        <li>                                                
                                                            <a>Description <span class="mandatory_red">*</span></a>
                                                            <input type="text" class="field-style field-full" name="editcoldescription" id="editcoldescription"/>
                                                        </li>
                                                    </ul>
                                                    <center> <input type="button" class="btn-facebook" onclick="updateVarietyDetail();" value="Update"/></center>
                                                </div> 
                                            </section>
                                        </div>
                                    </div>
                                    <!-- Edit packing details popup -->
                                    <div id="popup2" class="overlay">
                                        <div class="popup">
                                            <div class="popup_header_new">Edit Packing Details<a id="sss2" class="closepopup" href="#">&times;</a></div>
                                            <section id="unseen">
                                                <div class="form-style-9" style="overflow-y: scroll;height: 300px;">
                                                    <ul> 
                                                        <li>                                                
                                                            <a>Quantity per Bag (Kg/Ltr)<span class="mandatory_red">*</span></a>
                                                            <input type="number" class="field-style field-full" name="editqtyperbag" id="editqtyperbag"/>
                                                        </li>
                                                        <li>                                                
                                                            <a>No of Bags/Container <span class="mandatory_red">*</span></a>
                                                            <input type="number" class="field-style field-full" name="editnoofbags" id="editnoofbags"/>
                                                        </li>
                                                        <li>                                                
                                                            <a>Price per Bag (INR)<span class="mandatory_red">*</span></a>
                                                            <input type="number" class="field-style field-full" name="editpriceperbag" id="editpriceperbag"/>
                                                        </li>
                                                    </ul>
                                                    <center> <input type="button" class="btn-facebook" onclick="updatePackingDetails();" value="Update"/></center>
                                                </div> 
                                            </section>
                                        </div>
                                    </div>
                                </div><!-- /content-panel -->
                                <input type="hidden" name="packingid" id="packingid"/>
                                <input type="hidden" name="varietydetailid" id="varietydetailid"/>
                                <input type="hidden" name="method"/>
                                <input type="hidden" name="imageid" value="<?php echo $imageid ?>"/>
                                <input type="hidden" name="index" id="index"/>
                                <input type="hidden" name="indexvariety" id="indexvariety"/>
                                <input type="hidden" name="contentname" id="contentname" value="<?= $contentname ?>"/>
                                <input type="hidden" name="mesdesc" id="mesdesc"/>
                                <input type="hidden" name="ispackingvalue" id="ispackingvalue"/>
                                <input type="hidden" name="isnewvalue" id="isnewvalue"/>
                            </form>
                        </div><!-- /col-md-12 -->
                    </div><!-- /row -->
                </section><! --/wrapper -->
            </section><!-- /MAIN CONTENT -->
            <!--main content end-->
            <!--footer start-->
            <?php include './common/footer2.php'; ?>
            <!--footer end-->
        </section>
        <script>
            var app_crop = "";
            var aff_logo = "";
            bodyOnLoad();

            function bodyOnLoad() {
                getVarietyDetails();

                document.getElementById('myFile').value = "";
                $('#minitable2').DataTable();
            }

            function gethomepage(obj) {
         
                if (obj.checked) {
                    $('#isnewvalue').val('1');
                } else {
                    $('#isnewvalue').val('0');
                }
            }

            function ispacking(obj) {
                var hhh = obj.checked;
                if (hhh) {
                    $('#packingdiv').show();
                    $('#packingPlusBtn').show();
                    $('#ispackingvalue').val('Y');
                } else {
                    $('#packingdiv').hide();
                    $('#packingPlusBtn').hide();
                    $('#ispackingvalue').val('N');
                }
            }


            function opennew(idd) {
                $('#' + idd).show();
            }

//variety details array 
            function constructArray() {
                var list_target_id = 'addColumnGrid'; //first select list ID
                if (checkMandatoryFormFields(new Array("colname~Column Name", "coldescription~Column Description"))) {
                    $('#addVarBtn').hide();
                    $('#addVarBtnDis').show();
                    var colname = $('#colname').val();
                    var coldescription = $('#coldescription').val();
                    $.ajax({url: 'action/adminAction.php?method=constructVarityArray&colname=' + colname + '&coldescription=' + coldescription,
                        success: function (output) {
                            $('#addVarBtn').show();
                            $('#addVarBtnDis').hide();
                            $('#colname').val('');
                            $('#coldescription').val('');
                            $('#' + list_target_id).html(output);
                            $('#minitable').DataTable();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});

                }
            }

            function cancelArray(arrname, idname) {
                var list_target_id = 'addColumnGrid'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=cancelArrayList&arrName=' + arrname,
                    success: function (output) {
                        $('#' + idname).hide();
                        if (idname == 'minitable2') {
                            $('#colname').val('');
                            $('#coldescription').val('');
                        } else if (idname == 'minitable3') {
                            $('#qtyperbag').val('');
                            $('#noofbags').val('');
                            $('#priceperbag').val('');
                        }
                        $('#' + list_target_id).html(output);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function updateVarietyDetail() {
                if (checkMandatoryFormFields(new Array("editcolname~Name", "editcoldescription~Description", "varietydetailid~Somwthing went wrong please referesh page"))) {
                    var list_target_id = 'columnGrid'; //first select list ID
                    var varietydetailid = $('#varietydetailid').val();
                    var varietyid = $('#varietyid').val();
                    var editcolname = $('#editcolname').val();
                    var editcoldescription = $('#editcoldescription').val();
                    $.ajax({url: 'action/adminAction.php?method=updateVarietyDetails&varietydetailid=' + varietydetailid + '&varietyid=' + varietyid + '&editcolname=' + editcolname + '&editcoldescription=' + editcoldescription,
                        success: function (output) {
                            $('#sss').click();
                            setTimeout(function () {
                                window.location.href = "#";
                            }, 60);
                            $('#' + list_target_id).html(output);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});
                }
            }

            function removeItem(varietydetailid, varflag, list_target_id) {
                var r = confirm("Are you sure want to confirm?");
                if (r) {
                    var varietyid = $('#varietyid').val();

                    $.ajax({url: 'action/adminAction.php?method=removeVarietyDetails&varietydetailid=' + varietydetailid + '&varietyid=' + varietyid + '&varflag=' + varflag,
                        success: function (output) {
                            $('#sss').click();
                            setTimeout(function () {
                                window.location.href = "#";
                            }, 60);
                            $('#' + list_target_id).html(output);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});
                }
            }


            function mofifyVarietyValues(idname, countindex, colname, coldescription) {
                $('#colname').val(colname);
                $('#coldescription').val(coldescription);

                var lineno = idname + countindex;
                $('#indexvariety').val(countindex);
                $('#addVarBtn').hide();
                $('#updateVarBtn').show();
                document.getElementById(lineno).style.backgroundColor = '#ffcbaf';

            }

            function modifyVarietyArray() {
                var list_target_id = 'addColumnGrid'; //first select list ID
                if (checkMandatoryFormFields(new Array("colname~Column Name", "coldescription~Column Description"))) {
                    $('#updateVarBtn').hide();
                    $('#updateVarBtnDis').show();
                    var colname = $('#colname').val();
                    var coldescription = $('#coldescription').val();
                    var indexvariety = $('#indexvariety').val();
                    $.ajax({url: 'action/adminAction.php?method=modifyVarietyArray&colname=' + colname + '&coldescription=' + coldescription + '&indexvariety=' + indexvariety,
                        success: function (output) {
                            $('#updateVarBtnDis').hide();
                            $('#colname').val('');
                            $('#coldescription').val('');
                            $('#' + list_target_id).html(output);
                            $('#minitable').DataTable();
                            $('#addVarBtn').show();
                            $('#updateVarBtn').hide();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});

                }
            }

            //packing array 
            function constructPackingArray() {
                var list_target_id = 'addPackingGrid'; //first select list ID
                if (checkMandatoryFormFields(new Array("qtyperbag~Quantity per Bag", "noofbags~No of Bags", "priceperbag~Price per Bag"))) {
                    $('#addPackBtn').hide();
                    $('#addPackBtnDis').show();

                    var noofbags = $('#noofbags').val();
                    var qtyperbag = $('#qtyperbag').val();
                    var priceperbag = $('#priceperbag').val();
                    var mesdesc = $('#mesdesc').val();

                    $.ajax({url: 'action/adminAction.php?method=constructPackingArray&noofbags=' + noofbags + '&qtyperbag=' + qtyperbag + '&priceperbag=' + priceperbag + '&mesdesc=' + mesdesc,
                        success: function (output) {
                            $('#addPackBtn').show();
                            $('#addPackBtnDis').hide();
                            $('#noofbags').val('');
                            $('#qtyperbag').val('');
                            $('#priceperbag').val('');
                            $('#' + list_target_id).html(output);
                            $('#minitable').DataTable();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});

                }
            }

            function modifyValues(idname, countindex, noofbags, qtyperbag, priceperbag) {

                $('#noofbags').val(noofbags);
                $('#qtyperbag').val(qtyperbag);
                $('#priceperbag').val(priceperbag);

                var lineno = idname + countindex;
                $('#index').val(countindex);
                $('#addPackBtn').hide();
                $('#updatePackBtn').show();
                document.getElementById(lineno).style.backgroundColor = '#ffcbaf';

            }

            function constructModifyPackingArray() {
                var list_target_id = 'addPackingGrid'; //first select list ID
                if (checkMandatoryFormFields(new Array("qtyperbag~Quantity per Bag", "noofbags~No of Bags", "priceperbag~Price per Bag"))) {
                    $('#updatePackBtn').hide();
                    $('#updatePackBtnDis').show();
                    var noofbags = $('#noofbags').val();
                    var qtyperbag = $('#qtyperbag').val();
                    var priceperbag = $('#priceperbag').val();
                    var mesdesc = $('#mesdesc').val();
                    var index = $('#index').val();
                    $.ajax({url: 'action/adminAction.php?method=constructModifyPackingArray&noofbags=' + noofbags + '&qtyperbag=' + qtyperbag + '&priceperbag=' + priceperbag + '&mesdesc=' + mesdesc + '&index=' + index,
                        success: function (output) {
                            $('#updatePackBtnDis').hide();
                            $('#noofbags').val('');
                            $('#qtyperbag').val('');
                            $('#priceperbag').val('');
                            $('#' + list_target_id).html(output);
                            $('#minitable').DataTable();
                            $('#addPackBtn').show();
                            $('#updatePackBtn').hide();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});

                }
            }

            function updatePackingDetails() {
                if (checkMandatoryFormFields(new Array("editqtyperbag~Quantity per Bag (Kg)", "editnoofbags~No of Bags", "editpriceperbag~Price per Bag (INR)", "packingid~Somwthing went wrong please referesh page"))) {
                    var list_target_id = 'packingGrid'; //first select list ID
                    var packingid = $('#packingid').val();
                    var varietyid = $('#varietyid').val();
                    var editqtyperbag = $('#editqtyperbag').val();
                    var editnoofbags = $('#editnoofbags').val();
                    var editpriceperbag = $('#editpriceperbag').val();
                    $.ajax({url: 'action/adminAction.php?method=updatePackingDetails&packingid=' + packingid + '&varietyid=' + varietyid + '&editqtyperbag=' + editqtyperbag + '&editnoofbags=' + editnoofbags + '&editpriceperbag=' + editpriceperbag,
                        success: function (output) {
                            $('#sss2').click();
                            setTimeout(function () {
                                window.location.href = "#";
                            }, 60);
                            $('#' + list_target_id).html(output);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});
                }
            }

            function updateVariety() {
                var validation = new Array("cropid~Category Name", "varietyname~Name of the Product", "offers~Product Offers", "displayvolume~Display Volume per Unit", "displayprice~Display Price per Unit", "affiliatelogo~Affiliate Logo", "varietystatus~Status");
                if (checkMandatoryFormFields(new Array("cropid~Category Name", "varietyname~Name of the Product", "displayvolume~Display Volume per Unit", "displayprice~Display Price per Unit", "varietystatus~Status"))) {
                    var r = confirm("Are you sure want to Confirm!");
                    if (r)
                    {
                        startloader();
                        document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                        document.forms[0].method.value = 'editVariety';
                        document.forms[0].submit();
                    }
                }
            }

            //fetch crop master details
            function getVarietyDetails() {
                let varietyid = '<?php echo $varietyid ?>';
                startloader();
                $.ajax({url: 'action/adminAction.php?method=getVarietyDetails&varietyid=' + varietyid,
                    success: function (output) {
                        var splitdata = output.split('~');

                        var cropid = splitdata[1];
                        var imageid = splitdata[2];
                        var variety = splitdata[3];
                        var offers = splitdata[4];
                        var varietystatus = splitdata[5];
                        var imagepath = splitdata[6];
                        var crop = splitdata[7];
                        var measurementtype = splitdata[8];
                        var mesdesc = splitdata[9];
                        var hyperlink = splitdata[10];
                        var productdescription = splitdata[11];
                        var displayprice = splitdata[12];
                        var displayvolume = splitdata[13];
                        var affiliatelogo = splitdata[14];
                        var ispackingvalue = splitdata[15];
                        var ishomepage = splitdata[16];

                        if (ishomepage == '1') {
                            document.getElementById('isnew').checked = true;
                            $('#isnewvalue').val('1');
                        } else {
                            document.getElementById('isnew').checked = false;
                            $('#isnewvalue').val('0');
                        }
                        if (ispackingvalue == 'Y') {
                            document.getElementById('packingckbox').checked = true;
                            $('#packingdiv').show();
                            $('#packingPlusBtn').show();
                        } else {
                            document.getElementById('packingckbox').checked = false;
                            $('#packingdiv').hide();
                            $('#packingPlusBtn').hide();
                        }


                        $('#varietyid').val(varietyid);
                        app_crop = cropid;
                        $('#varietyname').val(variety);
                        $('#offers').val(offers);
                        document.getElementById("varietyimage").src = '..' + imagepath;
                        $('#varietystatus').val(varietystatus);
                        $('#mesdesc').val(mesdesc);
                        $('#mesurementid').html(mesdesc);
                        $('#mesid').html(mesdesc);
                        $('#hyperlink').val(hyperlink);
                        $('#productdescription').val(productdescription);
                        $('#displayprice').val(displayprice);
                        $('#displayvolume').val(displayvolume);
                        $('#ispackingvalue').val(ispackingvalue);


                        aff_logo = affiliatelogo;

                        getAffiliateLogo();
                        getCropList();

                        getVarietyDetailsList(varietyid);
                        getVarietyPackingList(varietyid, mesdesc);

                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function getVarietyDetailsList(varietyid) {
                var list_target_id = 'columnGrid'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=getVarietyDetailsList&varietyid=' + varietyid,
                    success: function (output) {
                        $('#' + list_target_id).html(output);

                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }


            function getVarietyPackingList(varietyid, mesdesc) {
                var list_target_id = 'packingGrid'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=getVarietyPackingList&varietyid=' + varietyid + '&mesdesc=' + mesdesc,
                    success: function (output) {
                        $('#' + list_target_id).html(output);

                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function  getCropList() {
                startloader();
                var list_target_id = 'cropid'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=getCropList',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        if (app_crop) {
                            document.getElementById(list_target_id).value = app_crop;
                            document.getElementById(list_target_id).onchange();
                        }
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function getAffiliateLogo() {
                startloader();
                var list_target_id = 'affiliatelogo'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=getAffiliateLogo',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        if (aff_logo) {
                            document.getElementById(list_target_id).value = aff_logo;
                            dropdownlist();
                            document.getElementById(list_target_id).onchange();
                        }
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function dropdownlist() {
                //affliate dropdown script
                $(document).ready(function (e) {
                    //no use
                    try {
                        var pages = $("#pages").msDropdown({on: {change: function (data, ui) {
                                    var val = data.value;
                                    if (val != "")
                                        window.location = val;
                                }}}).data("dd");

                        var pagename = document.location.pathname.toString();
                        pagename = pagename.split("/");
                        pages.setIndexByValue(pagename[pagename.length - 1]);
                        $("#ver").html(msBeautify.version.msDropdown);
                    } catch (e) {
                        //console.log(e);	
                    }

                    $("#ver").html(msBeautify.version.msDropdown);

                    //convert
                    $("#affiliatelogo").msDropdown({roundedBorder: false});
                    createByJson();
                    $("#tech").data("dd");
                });

                function showValue(h) {
                    console.log(h.name, h.value);
                }
                $("#tech").change(function () {
                    console.log("by jquery: ", this.value);
                })
            }
        </script>
    </body>
</html>