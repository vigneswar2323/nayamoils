<?php
require_once '../common/ApplicationConstant.php';
$loader_Path = constant("LOADER");
$locationurl = SITE_URL_ADMIN;
$adminname = ADMINNAME;
$message = "";
$contentname = 'Category';

session_start();
if (isset($_SESSION['message'])) {
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
    unset($_SESSION['message']);
}

if ($_SESSION['userdetails']) {
    $userdetails = $_SESSION["userdetails"];
} else {
    header("location:$locationurl/adminLogin.php");
}
?>
<!DOCTYPE html>

<html lang="en">
    <?php include './common/header.php'; ?>
    <body>
        <section id="container" >
            <?php include './common/leftnavigationbar.php'; ?>
            <section id="main-content">
                <section class="wrapper">
                    <form method="post">
                        <div class="row mt">                       
                            <div class="col-md-12">
                                <div class="header_bg content-panel">
                                    <div class="header_bg">
                                        <h4><i class="fa fa-angle-right"></i> <a href="home.php"> Dashboard</a> &nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i> Trash <a  style="float: right;margin-right: 10px;padding: 5px;"><label for="cars">Choose Removed Item:</label>
                                                <select name="trash" id="trash" onchange="getFilter(this)">
                                                    <option value="0">Select One</option>
                                                    <option value="1">Category</option>
                                                    <option value="2">Product</option>
                                                    <option value="3">Blog</option>
                                                    <option value="4">Gallery</option>
                                                    <option value="5">Affiliate Logo</option>
                                                </select></a>
                                            <a id="delBtn" class="btn-cancel" style="float: right;margin-right: 10px;padding: 5px;display: none;" onclick="deleteTrash();">Delete</a>
                                        </h4>        
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
                                    <fieldset class="fieldsetstyle2"><div id="trashGrid"></div></fieldset>
                                </div><!-- /content-panel -->
                            </div><!-- /col-md-12 -->
                        </div><!-- /row -->
                        <input type="hidden" name="method" id="method"/>
                        <input type="hidden" name="iddArray" id="iddArray"/>
                    </form>
                </section><! --/wrapper -->     
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
                getCropGrid();
            }

            function deleteTrash() {
                var sampleNumberStr = "";
                for (var count = 0; count < idd.length; count++) {
                    sampleNumberStr = sampleNumberStr + "'" + idd[count] + "'";
                    if (count != (idd.length - 1)) {
                        sampleNumberStr = sampleNumberStr + ",";
                    }
                }
                var r = confirm("Are you sure want to delete permenantly?");
                if (r) {
                    startloader();
                    document.forms[0].action = "<?php echo $locationurl; ?>/action/adminAction.php";
                    document.forms[0].method.value = 'delTrash';
                    document.forms[0].iddArray.value = sampleNumberStr;
                    document.forms[0].submit();
                }
            }

            //fetch crop master details
            function getFilter(obj) {
                var trashid = obj.value;
                if (trashid != 0) {
                    var contentname = $('#contentname').val();
                    var list_target_id = 'trashGrid'; //first select list ID
                    startloader();
                    $.ajax({url: 'action/adminAction.php?method=trashGrid&trashid=' + trashid + '&contentname=' + contentname,
                        success: function (output) {
                            $('#' + list_target_id).html(output);
                            $('#minitable').DataTable();
                            stoploader();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //alert(xhr.status + " "+ thrownError);
                        }});
                }
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
                $('#filename').val(filename);
            }

            function SelectandDeselectalltags(Obj) {
                var filename = document.getElementById("filename").value;
                $('#filename').val(filename);
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

        </script>
    </body>
</html>
