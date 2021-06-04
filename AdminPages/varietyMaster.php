<?php
require_once '../common/ApplicationConstant.php';
require_once '../connection/DBConnection.php';
$DBConnection = new DBConnection();
$loader_Path = constant("LOADER");
$locationurl = SITE_URL_ADMIN;
$adminname = ADMINNAME;
$message = "";
$contentname = "Product";

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
    <body>
        <section id="container">
            <!--sidebar start-->
            <?php include './common/leftnavigationbar.php'; ?>
            <section id="main-content">
                <section class="wrapper">
                    <div class="row mt">                       
                        <div class="col-md-12">
                            <div class="header_bg content-panel">
                                <div class="header_bg">
                                    <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i><a> Masters </a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Product Master <a id="delAllBtn" class="btn-cancel" style="float: right;margin-right: 10px;padding: 5px;" onclick="delAllVariety();">Trash All</a><a href="#popup1" class="btn-facebook" style="float: right;margin-right: 10px;padding: 5px;"> Add New Product</a><a id="delBtn" class="btn-cancel" style="float: right;margin-right: 10px;padding: 5px;display: none;" onclick="delVariety();">Trash</a></h4>                                    
                                </div>
                            </div>
                            <div class="content-panel">
                                <?php
                                if ($message) {
                                    echo '<div class="success">' . $message . '</div>';
                                }
                                ?>
                                <div class="modalloder" id="loader" style="display: none">
                                    <div class="center">
                                        <img width="100px" height="100px" alt="" src="<?php echo $loader_Path ?>" />
                                    </div>
                                </div>                                
                                <fieldset class="fieldsetstyle2"><div id="varietyGrid"></div></fieldset>

                            </div><!-- /content-panel -->
                        </div><!-- /col-md-12 -->
                    </div><!-- /row -->
                </section><! --/wrapper -->

                <!--           new popup alert-->
                <div id="popup1" class="overlay">
                    <div class="popup">
                        <div class="popup_header_new">Add New <?= $contentname ?><a class="closepopup" href="#">&times;</a></div>
                        <section id="unseen">
                            <form class="form-style-9" style="overflow-y: scroll;height: 500px;" enctype="multipart/form-data" method="post" name="Form" action="action/adminAction.php" onsubmit="return validateForm(this)">
                                <ul> 

                                    <fieldset class="fieldsetstyle2"><legend>Affiliate Marketing Details </legend>
                                        <li>                                  
                                            <a>Affiliate Logo <span class="mandatory_red"></span></a>
                                            <select class="field-style field-split" name="affiliatelogo" id="affiliatelogo"></select>
                                            <input type="button" class="btn-facebook" onclick="openNewPage('affiliateIconMaster');" value="Add New Logo"/>
                                        </li>

                                        <li>
                                            <a>Affiliate Marketing Link <span class="mandatory_red"></span></a>
                                            <textarea class="field-style field-full" id="hyperlink" name="hyperlink" placeholder="write upto 500 chars maximum..." maxlength="500"></textarea>
                                        </li>
                                    </fieldset>

                                    <li>                                                
                                        <a>Choose Category <span class="mandatory_red">*</span></a>
                                        <select class="field-style field-split" name="cropid" id="cropid"></select>
                                        <input type="button" class="btn-facebook" onclick="openNewPage('cropMaster');" value="Add Category"/>
                                    </li>
                                    <li>                                                
                                        <a><?= $contentname ?> Name <span class="mandatory_red">*</span></a>
                                        <input type="text" class="field-style field-full" name="varietyname" id="varietyname"/>
                                    </li>
                                    <li>                                                
                                        <a><?= $contentname ?> Offers <span class="mandatory_red"></span></a>
                                        <input type="text" class="field-style field-full" name="offers" id="offers"/>
                                    </li>

                                    <fieldset class="fieldsetstyle2"><legend><?= $contentname ?> Details</legend>
                                        <li>                                                
                                            <a>Column Name<span class="mandatory_red"></span></a>
                                            <input type="text" class="field-style field-full" name="colname" id="colname"/>
                                        </li>
                                        <li>                                                
                                            <a>Column Description<span class="mandatory_red"></span></a>
                                            <input type="text" class="field-style field-full" name="coldescription" id="coldescription"/>
                                        </li>

                                        <center> 
                                            <input id="addVarBtn" type="button" class="btn-facebook" onclick="constructArray();" value="Add"/>
                                            <input id="addVarBtnDis" type="button"  value="Add" style="display: none;"/>
                                            <input id="updateVarBtn" type="button" class="btn-facebook" onclick="modifyVarietyArray();" value="Update" style="display: none;"/>
                                            <input id="updateVarBtnDis" type="button"  value="Update" style="display: none;"/>
                                        </center>

                                        <div id="columnGrid"></div>
                                    </fieldset>
                                    <fieldset class="fieldsetstyle2"><legend>Packing Details </legend>

                                        <fieldset class="fieldsetstyle3"><legend>A) Choose Measurement</legend>
                                            <li>                                                
                                                <a>Measurement Type <span class="mandatory_red"></span></a>
                                                <select class="field-style field-split" name="measurementtype" id="measurementtype" onchange="setIdMeasurement(this)"></select>
                                                <a id="measdiv" style="display: none;">
                                                    <input type="text" class="field-style field-split" name="measurementothers" id="measurementothers" style="width: 18%;"/>
                                                    <input type="button" class="btn-facebook" onclick="addMeasurementType();" value="Add" style="width: 10%;"/>
                                                </a>
                                            </li>
                                            <div id="displaydiv">
                                                <li>                                                
                                                    <a>Display Volume per unit <span class="mandatory_blue" id="mesid"></span><span class="mandatory_red">*</span></a>
                                                    <input type="number" class="field-style field-full" name="displayvolume" id="displayvolume" value="1"/>
                                                </li>
                                                <li>                                                
                                                    <a>Deal Price per unit <span class="mandatory_blue">(INR)</span><span class="mandatory_red">*</span></a>
                                                    <input type="number" class="field-style field-full"  name="displayprice" id="displayprice"/>
                                                </li>
                                                <li>                                                
                                                    <a>Actual Price per unit <span class="mandatory_blue">(INR)</span><span class="mandatory_red">*</span></a>
                                                    <input type="number" class="field-style field-full"  name="actualprice" id="actualprice"/>
                                                </li>
                                            </div>
                                        </fieldset>

                                        <fieldset class="fieldsetstyle3"><legend>B) Packing Details <input type="checkbox" onclick="ispacking(this)"></legend>
                                            <div id="packingdiv" style="display: none;">
                                                <li>                                                
                                                    <a>Quantity per Bag/Container <span class="mandatory_blue" id="mesid2"></span><span class="mandatory_red"> *</span></a>
                                                    <input type="number" class="field-style field-full" name="qtyperbag" id="qtyperbag"/>
                                                </li>
                                                <li>                                                
                                                    <a>No of Bag(s)/Container(s)<span class="mandatory_blue">(Nos)</span><span class="mandatory_red"> *</span></a>
                                                    <input type="number" class="field-style field-full" name="noofbags" id="noofbags"/>
                                                </li>
                                                <li>                                                
                                                    <a>Price per Bag/Container<span class="mandatory_blue">(INR)</span><span class="mandatory_red"> *</span></a>
                                                    <input type="number" class="field-style field-full" name="priceperbag" id="priceperbag"/>
                                                </li>
                                                <center> 
                                                    <input id="addbtn" type="button" class="btn-facebook" onclick="constructPackingArray();" value="Add"/>
                                                    <input id="addbtnmodify" type="button" class="btn-facebook" onclick="constructModifyPackingArray();" value="Update" style="display: none;"/>
                                                    <input id="addbtndis" type="button" value="Add" style="display: none;"/>
                                                    <input id="addbtnmodifydis" type="button"  value="Update" style="display: none;"/>
                                                </center>
                                                <div id="packingGrid"></div>
                                            </div>
                                        </fieldset>
                                    </fieldset>

                                    <li>
                                        <a>About Product <span class="mandatory_red"></span></a>
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
                                        <input type="checkbox" name="isnew" id="isnew" value="1" checked/>
                                    </li>
                                    <fieldset class="fieldsetstyle2">
                                        <a>Upload Image <span class="mandatory_red">*</span></a>
                                        <input type="file" id="myFile" name="upload_image" class="btn-facebook" value="Upload" />
                                        <div id="imagePreview"> </div>
                                    </fieldset>

                                </ul>

                                <center> <input type="button" class="btn-facebook" onclick="saveVariety();" value="Submit"/></center>

                                <input type="hidden" name="crpid"/>
                                <input type="hidden" name="varietyid"/>
                                <input type="hidden" name="imageid"/>
                                <input type="hidden" name="method"/>
                                <input type="hidden" name="index" id="index"/>
                                <input type="hidden" name="indexvariety" id="indexvariety"/>
                                <input type="hidden" name="contentname" id="contentname" value="<?= $contentname ?>"/>
                                <input type="hidden" name="mesdesc" id="mesdesc"/>
                                <input type="hidden" name="filename"/>
                                <input type="hidden" name="id"/>
                                <input type="hidden" name="productdetailsvalue" id="productdetailsvalue"/>
                                <input type="hidden" name="packingdetailsvalue" id="packingdetailsvalue"/>
                                <input type="hidden" name="ispackingvalue" id="ispackingvalue" value="N"/>
                                <input type="hidden" name="iddArray" id="iddArray"/>
                                <input type="hidden" name="flag" id="flag"/>
                            </form> 
                        </section>
                    </div>

                </div>
            </section><!-- /MAIN CONTENT -->
            <!--main content end-->
            <!--footer start-->
            <?php include './common/footer2.php'; ?>
            <!--footer end-->
        </section>
        <script>
            var idd = [];
            bodyOnLoad();

            function bodyOnLoad() {
                getCropList();
                getAffiliateLogo();
                getVarietyGrid();
            }

            function openNewPage(pagename) {
                document.forms[0].action = "<?php echo $locationurl; ?>/" + pagename + ".php";
                document.forms[0].submit();
            }

            function ispacking(obj) {
                var hhh = obj.checked;
                if (hhh) {
                    $('#packingdiv').show();
                    $('#ispackingvalue').val('Y');
                } else {
                    $('#packingdiv').hide();
                    $('#ispackingvalue').val('N');
                }
            }

            function constructArray() {
                var list_target_id = 'columnGrid'; //first select list ID
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
                            $('#productdetailsvalue').val('1');
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
                var list_target_id = 'columnGrid'; //first select list ID
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


            function constructPackingArray() {
                var list_target_id = 'packingGrid'; //first select list ID
                if (checkMandatoryFormFields(new Array("measurementtype~Measurement Type", "qtyperbag~Quantity per Bag", "noofbags~No of Bags", "priceperbag~Price per Bag"))) {
                    $('#addbtn').hide();
                    $('#addbtndis').show();

                    var noofbags = $('#noofbags').val();
                    var qtyperbag = $('#qtyperbag').val();
                    var priceperbag = $('#priceperbag').val();
                    var mesdesc = $('#mesdesc').val();

                    $.ajax({url: 'action/adminAction.php?method=constructPackingArray&noofbags=' + noofbags + '&qtyperbag=' + qtyperbag + '&priceperbag=' + priceperbag + '&mesdesc=' + mesdesc,
                        success: function (output) {
                            $('#addbtn').show();
                            $('#addbtndis').hide();
                            $('#noofbags').val('');
                            $('#qtyperbag').val('');
                            $('#priceperbag').val('');
                            $('#' + list_target_id).html(output);
                            $('#minitable').DataTable();
                            $('#packingdetailsvalue').val('1');
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
                $('#addbtn').hide();
                $('#addbtnmodify').show();
                document.getElementById(lineno).style.backgroundColor = '#ffcbaf';

            }

            function constructModifyPackingArray() {
                var list_target_id = 'packingGrid'; //first select list ID
                if (checkMandatoryFormFields(new Array("measurementtype~Measurement Type", "qtyperbag~Quantity per Bag", "noofbags~No of Bags", "priceperbag~Price per Bag"))) {
                    $('#addbtnmodify').hide();
                    $('#addbtnmodifydis').show();
                    var noofbags = $('#noofbags').val();
                    var qtyperbag = $('#qtyperbag').val();
                    var priceperbag = $('#priceperbag').val();
                    var mesdesc = $('#mesdesc').val();
                    var index = $('#index').val();
                    $.ajax({url: 'action/adminAction.php?method=constructModifyPackingArray&noofbags=' + noofbags + '&qtyperbag=' + qtyperbag + '&priceperbag=' + priceperbag + '&mesdesc=' + mesdesc + '&index=' + index,
                        success: function (output) {
                            $('#addbtnmodifydis').hide();
                            $('#noofbags').val('');
                            $('#qtyperbag').val('');
                            $('#priceperbag').val('');
                            $('#' + list_target_id).html(output);
                            $('#minitable').DataTable();
                            $('#addbtn').show();
                            $('#addbtnmodify').hide();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});

                }
            }

//            $('input[type="button"]').click(function (e) {
//                $(this).closest('tr').remove()
//            })

            function getCropList() {
                startloader();
                var list_target_id = 'cropid'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=getCropList',
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        measurementtype();
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
                        dropdownlist();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }


            function measurementtype() {
                startloader();
                var list_target_id = 'measurementtype'; //first select list ID
                $.ajax({url: 'action/adminAction.php?method=getReferencecodes&parentcode=5',
                    success: function (output) {
                        stoploader();
                        $('#' + list_target_id).html(output);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function setIdMeasurement(obj) {

                if (obj.value == '100') {
                    $('#measdiv').show();
                    $('#displaydiv').hide();
                } else {
                    $('#measdiv').hide();
                    $('#displaydiv').show();
                    var list_target_id = 'mesid'; //first select list ID
                    var referencecode = obj.value;
                    startloader();
                    $.ajax({url: 'action/adminAction.php?method=getReferencecodeswithname&parentcode=5&referencecode=' + referencecode,
                        success: function (output) {

                            stoploader();
                            $('#' + list_target_id).html(output);
                            $('#mesid2').html(output);

                            $('#mesdesc').val(output);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});
                }
            }

            function addMeasurementType() {
                if (checkMandatoryFormFields(new Array("measurementothers~New Measurement Type"))) {
                    startloader();
                    var measurementothers = $('#measurementothers').val();
                    $.ajax({url: 'action/adminAction.php?method=saveReferenceCodes&parentcode=5&description=' + measurementothers,
                        success: function (output) {
                            measurementtype();
                            $('#measurementothers').val('');
                            $('#displaydiv').show();
                            stoploader();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + " " + thrownError);
                        }});
                }
            }

            //fetch crop master details
            function getVarietyGrid() {

                var contentname = $('#contentname').val();
                var list_target_id = 'varietyGrid'; //first select list ID
                startloader();
                $.ajax({url: 'action/adminAction.php?method=varietyGrid&contentname=' + contentname,
                    success: function (output) {
                        $('#' + list_target_id).html(output);
                        $('#minitable').DataTable();
                        stoploader();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //alert(xhr.status + " "+ thrownError);
                    }});
            }

            function saveVariety() {
                var contentname = '<?php echo $contentname ?>';
                var validation;
                var ispackingdiv = $('#ispackingvalue').val();
//                if (ispackingdiv == 'Y') {
//                    validation = new Array("cropid~Name of the Category", "offers~Product Offers", "productdetailsvalue~Product Details", "displayvolume~Display Volume", "displayprice~Deal Price", "actualprice~Actual Price", "packingdetailsvalue~Packing Details", "affiliatelogo~Affiliate Logo", "hyperlink~Affiliate Marketing Link", "productdescription~About " + contentname, "varietystatus~" + contentname + " Status");
//                } else {
//                    validation = new Array("cropid~Name of the Category", "offers~Product Offers", "productdetailsvalue~Product Details", "displayvolume~Display Volume", "displayprice~Deal Price", "actualprice~Actual Price", "affiliatelogo~Affiliate Logo", "hyperlink~Affiliate Marketing Link", "productdescription~About " + contentname, "varietystatus~" + contentname + " Status");
//                }
                if (ispackingdiv == 'Y') {
                    validation = new Array("cropid~Name of the Category", "displayvolume~Display Volume", "displayprice~Deal Price", "actualprice~Actual Price", "packingdetailsvalue~Packing Details", "varietystatus~" + contentname + " Status");
                } else {
                    validation = new Array("cropid~Name of the Category", "displayvolume~Display Volume", "displayprice~Deal Price", "actualprice~Actual Price", "varietystatus~" + contentname + " Status");
                }
                var varname = $('#varietyname').val();
                if (varname == '') {
                    alert("Product Name should be fill!");
                    document.getElementById('varietyname').focus();
                } else {
                    if (checkMandatoryFormFields(validation)) {
                        var img1 = $('#myFile').val();
                        if (img1 == '') {
                            alert("Upload " + contentname + " Image ! ");
                            return false;
                        }

                        var r = confirm("Are you sure want to Confirm?");
                        if (r) {
                            startloader();
                            document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                            document.forms[0].method.value = 'addNewVariety';
                            document.forms[0].submit();
                        }
                    }
                }
            }

            function editVariety(varietyid, cropid, imageid) {
                document.forms[0].action = "<?php echo $locationurl; ?>/editVarietyMaster.php";
                document.forms[0].varietyid.value = varietyid;
                document.forms[0].crpid.value = cropid;
                document.forms[0].imageid.value = imageid;
                document.forms[0].submit();
            }


            function getCheckboxpage(idvalue, id, filename) {
                if (idvalue.checked) {
                    idd.push(id);
                } else {
                    idd.pop(id);
                }

                if (idd.length > 0) {
                    $('#delBtn').show();
                } else {
                    $('#delBtn').hide();
                    document.getElementById('ckbCheckAll').checked = false;
                }
            }

            function SelectandDeselectalltags(Obj) {

                $('#filename').val('varietyMaster');
                var totalcount = document.getElementById("totalcount").value;

                if (Obj.checked) {
                    for (var i = 0; i <= totalcount; i++) {
                        var idname = "Checkbox" + i;
                        var checkboxid = "Checkbox" + i;
                        if (document.getElementById(checkboxid) != null)
                        {
                            var id = document.getElementById(idname).value;
                            idd.push(id);
                            document.getElementById(idname).value = "Y";
                            document.getElementById(checkboxid).checked = true;
                            $('#delBtn').show();
                        }
                    }

                } else {
                    for (var i = 0; i <= 20; i++) {
                        var idname = "Checkbox" + i;
                        var checkboxid = "Checkbox" + i;
                        if (document.getElementById(checkboxid) != null)
                        {
                            var id = document.getElementById(idname).value;
                            idd.pop(id);
                            document.getElementById(idname).value = "N";
                            document.getElementById(checkboxid).checked = false;
                            $('#delBtn').hide();
                        }
                    }
                }
            }

            function delVariety() {
                var sampleNumberStr = "";
                for (var count = 0; count < idd.length; count++) {
                    sampleNumberStr = sampleNumberStr + "'" + idd[count] + "'";
                    if (count != (idd.length - 1)) {
                        sampleNumberStr = sampleNumberStr + ",";
                    }
                }
                var r = confirm("Are you sure want to delete?");
                if (r) {
                    startloader();
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].method.value = 'saveTrash';
                    document.forms[0].filename.value = 'varietyMaster';
                    document.forms[0].iddArray.value = sampleNumberStr;
                    document.forms[0].flag.value = 'S';
                    document.forms[0].submit();
                }
            }

            function delAllVariety() {
                var r = confirm("Are you sure want to delete?");
                if (r) {
                    startloader();
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].method.value = 'saveTrash';
                    document.forms[0].filename.value = 'varietyMaster';
                    document.forms[0].flag.value = 'A';
                    document.forms[0].submit();
                }
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