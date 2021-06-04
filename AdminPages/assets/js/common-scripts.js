/*---LEFT BAR ACCORDION----*/
$(function () {
    $('#nav-accordion').dcAccordion({
        eventType: 'click',
        autoClose: true,
        saveState: true,
        disableLink: true,
        speed: 'slow',
        showCount: false,
        autoExpand: true,
//        cookie: 'dcjq-accordion-1',
        classExpand: 'dcjq-current-parent'
    });
});

var Script = function () {


//    sidebar dropdown menu auto scrolling

    jQuery('#sidebar .sub-menu > a').click(function () {
        var o = ($(this).offset());
        diff = 250 - o.top;
        if (diff > 0)
            $("#sidebar").scrollTo("-=" + Math.abs(diff), 500);
        else
            $("#sidebar").scrollTo("+=" + Math.abs(diff), 500);
    });


//    sidebar toggle

    $(function () {
        function responsiveView() {
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('#container').addClass('sidebar-close');
                $('#sidebar > ul').hide();
            }

            if (wSize > 768) {
                $('#container').removeClass('sidebar-close');
                $('#sidebar > ul').show();
            }
        }
        $(window).on('load', responsiveView);
        $(window).on('resize', responsiveView);
    });

    $('.fa-bars').click(function () {
        if ($('#sidebar > ul').is(":visible") === true) {
            $('#main-content').css({
                'margin-left': '0px'
            });
            $('#sidebar').css({
                'margin-left': '-210px'
            });
            $('#sidebar > ul').hide();
            $("#container").addClass("sidebar-closed");
        } else {
            $('#main-content').css({
                'margin-left': '210px'
            });
            $('#sidebar > ul').show();
            $('#sidebar').css({
                'margin-left': '0'
            });
            $("#container").removeClass("sidebar-closed");
        }
    });

// custom scrollbar
    $("#sidebar").niceScroll({styler: "fb", cursorcolor: "#4ECDC4", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled: false, cursorborder: ''});

    $("html").niceScroll({styler: "fb", cursorcolor: "#4ECDC4", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled: false, cursorborder: '', zindex: '1000'});

// widget tools

    jQuery('.panel .tools .fa-chevron-down').click(function () {
        var el = jQuery(this).parents(".panel").children(".panel-body");
        if (jQuery(this).hasClass("fa-chevron-down")) {
            jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            el.slideUp(200);
        } else {
            jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            el.slideDown(200);
        }
    });

    jQuery('.panel .tools .fa-times').click(function () {
        jQuery(this).parents(".panel").parent().remove();
    });


//    tool tips

    $('.tooltips').tooltip();

//    popovers

    $('.popovers').popover();



// custom bar chart

    if ($(".custom-bar-chart")) {
        $(".bar").each(function () {
            var i = $(this).find(".value").html();
            $(this).find(".value").html("");
            $(this).find(".value").animate({
                height: i
            }, 2000)
        })
    }


}();
var iChars = "~`!%^&*+={}[]\\\';|\":<>?@#$";

function specialCharNotAllowed2(fieldValue, fieldId) {
    for (var i = 0; i < fieldValue.length; i++) {
        if (iChars.indexOf(fieldValue.charAt(i)) >= 0) {
            alert("Special characters except @ - . , _ ( ) / are not allowed");
            fieldValue = "";
            document.getElementById(fieldId).focus();
            document.getElementById(fieldId).value = '';
            return false;
        } else {
            return true;
        }
    }
}

function validateEmailid(txtBoxObj) {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    var address = txtBoxObj.value;
    if (address != null && address != "" && reg.test(address) == false) {
        alert('Invalid email id');
        txtBoxObj.focus();
        txtBoxObj.value = "";
        return false;
    }
    return true;
}

function elementToPassParameters(elementsToPass) {
    for (var i = 0; i < elementsToPass.length; i++) {
        var split = elementsToPass[i].split('~');
        var fieldId = split[0];
        var fieldValue = split[1];
        $('#' + fieldId).val(fieldValue);
    }
}

//validating input fields
function checkMandatoryFormFields(elementsToValidate) {


    for (var i = 0; i < elementsToValidate.length; i++) {
        var split = elementsToValidate[i].split('~');
        var fieldId = split[0];
        var fieldDescription = split[1];


        var fieldValue = document.getElementById(fieldId).value;
        fieldValue = fieldValue.replace(/^\s*|\s*$/g, '');

        if (fieldValue == null || fieldValue == "" || fieldValue == "0" || fieldValue.length <= 0) {
            alert("Please enter " + fieldDescription);
            document.getElementById(fieldId).focus();
            return false;
        } else {
            if (specialCharNotAllowed2(fieldValue, fieldId)) {

            } else {
                return false;
            }
        }
    }

    if (specialCharNotAllowed2(fieldValue, fieldId)) {
        return true;
    } else {
        return false;
    }

}

//progress function
function startloader() {
    document.getElementById("loader").style.display = "";
}
function stoploader() {
    document.getElementById("loader").style.display = "none";
}


