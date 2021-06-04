<?php
$SITE_TITLE = constant('SITE_TITLE');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<!-- js placed at the end of the document so the pages load faster -->
<!--    <script src="assets/js/jquery.js"></script>-->
<!--    <script src="assets/js/jquery-1.8.3.min.js"></script>-->
<script src="assets/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="assets/js/jquery.sparkline.js"></script>


<!--common script for all pages-->
<script src="assets/js/common-scripts.js"></script>

<script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="assets/js/gritter-conf.js"></script>

<!--script for this page-->
<script src="assets/js/sparkline-chart.js"></script>    
<script src="assets/js/zabuto_calendar.js"></script>	
<footer class="site-footer">
    <div class="text-center">
        2021 - <?= $SITE_TITLE ?>
        <a href="#" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>
<script>
//for upload image preview and validataion
    $(function () {
        $("#myFile").change(function () {
            if (typeof (FileReader) != "undefined") {
                var dvPreview = $("#imagePreview");
                dvPreview.html("");
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                $($(this)[0].files).each(function () {
                    var file = $(this);
                    if (regex.test(file[0].name.toLowerCase())) {
                        var size = 350000;
                        var file_size = document.getElementById('myFile').files[0].size;
                        if (file_size >= size) {
                            alert('File too large - Maximum Allowable size is 350kb only !');
                            document.getElementById("myFile").value = "";

                            return false;
                        }
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var img = $("<img />");
                            img.attr("style", "height:100px;width: 100px");
                            img.attr("src", e.target.result);
                            dvPreview.append(img);
                        }
                        reader.readAsDataURL(file[0]);
                    } else {
                        alert(file[0].name + " is not a valid image file.");
                        document.getElementById("myFile").value = "";
                        dvPreview.html("");
                        return false;
                    }
                });
            } else {
                alert("This browser does not support HTML5 FileReader.");
            }
        });
    });

</script>
<!-- js placed at the end of the document so the pages load faster -->


