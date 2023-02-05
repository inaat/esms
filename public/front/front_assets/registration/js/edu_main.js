$(document).ready(function() {

    initializeReadyFunctions();

})

/////////////////// Anas Code start ///////////////////

$(document).ready(function() {

    $(".numbervalue").bind("keypress", function (e) {

        var keyCode = e.which ? e.which : e.keyCode

             

        if (!(keyCode >= 48 && keyCode <= 57)) {

          $(".error2").css("display", "inline");

          return false;

        }else{

          $(".error2").css("display", "none");

        }

    });


    $('.propercase').on('keypress keyup change',function(){
        var str = this.id;
   
        var val2 = $('#'+str).val(); 
       var valarr = val2.split(' ');
       var allval='';
       for(i=0;i<valarr.length;i++){
           valarr[i] = valarr[i].toString();
           if(i!=0){
           allval = allval+' '+valarr[i].charAt(0).toUpperCase() + valarr[i].substr(1).toLowerCase();
           } else
           {
               allval = allval+valarr[i].charAt(0).toUpperCase() + valarr[i].substr(1).toLowerCase();
           }
       }
       $('#'+str).val(allval);
   
   
       });

  });

/////////////////// Anas Code End ///////////////////







function initializeReadyFunctions() {

    $(".floatvalue").focusout(function() {

        if ($(this).val()) {

            $(this).val(parseFloat($(this).val()).toFixed(2));

        }

    });

    $(".floatvalue").keydown(function(e) {

        allowNumbersOnly(e, true);

    });

    $(".textonly").keydown(function(e) {

        allowTextOnly(e);

    });

    $(".numbervalue").keydown(function(e) {

        allowNumbersOnly(e, false);

    });

    $

}



function allowNumbersOnly(e, allowDot) {

    var codeArray = [46, 8, 9, 27, 13, 190];

    if (allowDot) {

        codeArray.push(110)

    };

    if ($.inArray(e.keyCode, codeArray) !== -1 || ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) || (e.keyCode >= 35 && e.keyCode <= 40)) {

        return;

    }

    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {

        e.preventDefault();

    }

}



function allowTextOnly(e) {

    var codeArray = [46, 8, 9, 27, 13, 190, 110, 32];

    if ($.inArray(e.keyCode, codeArray) !== -1 || (e.keyCode == 65 && (e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 40))) {

        return;

    }

    if ((e.keyCode >= 65 && e.keyCode <= 90)) {

        return;

    } else {

        e.preventDefault();

    }

}



function edu__disablebuttonbyjq(name) {

    $("." + name).each(function() {

        $(this).prop("disabled", true);

    });

}



function edu__showErrorMessageDiv(message) {

    document.getElementById('errormessagediv').style.display = "block";

    document.getElementById("errormessages").innerHTML = message;

    edu__enablebuttonbyjq('savebtn');

    $("#errormessagediv").focus();

}



function edu__validateFormValue() {

    edu__disablebuttonbyjq('savebtn');

    var validate = true;

    //$('input,select').prop('disabled', false);

    $("select.mandatoryvalue").each(function() {

        var value = $(this).val();

        if (value == null || value == 'null' || value == '' || value == -1) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = "Kindly select the " + title + ".";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $("input.mandatoryvalue").each(function() {

        var value = $(this).val().trim();

        if (value == '') {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = "Kindly insert the " + title + ".";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $("textarea.mandatoryvalue").each(function() {

        var value = $(this).val().trim();

        if (value == '') {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = "Kindly insert the " + title + ".";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $(".filesize").each(function() {

        var limitsize = $(this).prop('title');

        if (limitsize == undefined || limitsize == '') {

            limitsize = $(this).prop('name');

        }

        var file = $(this).prop('files')[0];

        if (file == NaN || file == undefined) {} else {

            var iFileSize = file.size;

            var iConvert = (iFileSize / 1024).toFixed(2);

            if (parseFloat(iConvert) > parseFloat(limitsize)) {

                var message = "Attachment size is " + iConvert + " KB . Attachment size should be less than " + limitsize + " KB";

                edu__showErrorMessageDiv(message);

                validate = false;

                return false;

            }

        }

    });

    $(".file_size").each(function() {

        var limitsize = $(this).prop('filesize');

        if (limitsize == undefined) {

            limitsize = $(this).attr('filesize');

        }

        var title = $(this).prop('title');

        title = title && title.length > 0 ? title : 'Attachment';

        var file = $(this).prop('files')[0];

        if (file == NaN || file == undefined) {} else {

            var iFileSize = file.size;

            var iConvert = (iFileSize / 1024).toFixed(2);

            if (parseFloat(iConvert) > parseFloat(limitsize)) {

                var message = "The attached file size of the " + title + " is of " + iConvert + " KB, reduce the file size to " + limitsize + " KB before uploading."

                edu__showErrorMessageDiv(message);

                validate = false;

                return false;

            }

        }

    });

    $("input.edu_range").each(function() {

        var value = $(this).val().trim();

        if (value != '') {

            var min = $(this).prop('min');

            var max = $(this).prop('max');

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            if (parseInt(min) > value || parseInt(max) < value) {

                $(this).focus();

                var message = "Kindly insert the value of '" + title + "' between " + min + " to " + max + ". ";

                edu__showErrorMessageDiv(message);

                validate = false;

                return false;

            }

        }

    });

    $(".fileext").each(function() {

        var file = $(this).prop('files')[0];

        if (file == NaN || file == undefined) {} else {

            var extenstion = $(this).attr('ext');

            var res = extenstion.split(",");

            var ext = $(this).val().split('.').pop().toLowerCase();

            if ($.inArray(ext, res) == -1) {

                var message = "The Attachment should be in " + extenstion + " format.";

                edu__showErrorMessageDiv(message);

                $(this).focus();

                validate = false;

                return false;

            }

        }

    });

    $(".date").each(function() {

        var value = $(this).val();

        var re = /^\d{1,2}(\-|\/)\d{1,2}(\-|\/)\d{4}$/;

        if (value.trim() != '' && !value.match(re)) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            $(this).focus();

            var message = "Kindly select the correct " + title + ". (dd-MM-yyyy)";

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $(".datetime").each(function() {

        var value = $(this).val();

        var re = /^\d{1,2}(\-|\/)\d{1,2}(\-|\/)\d{4}\s*?\d{2}[- :.]\d{2}$/;

        if (value.trim() != '' && !value.match(re)) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = "Kindly select the correct " + title + ".";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $(".numbervalue").each(function() {

        var value = $(this).val();

        if (value != '' && parseInt(value) != value) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = "Kindly fill the Number value in '" + title + "'.";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $(".floatvalue").each(function() {

        var value = $(this).val();

        if (value != '' && parseFloat(value) != value) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = "Kindly fill the Decimal or Number value in '" + title + "'.";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $(".time").each(function() {

        var value = $(this).val();

        var regex = /^(?:0?[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;

        if (value != '') {

            if (regex.test(value)) {} else {

                $(this).focus();

                edu__showErrorMessageDiv("Invalid Time Format : " + value);

                validate = false;

                return false;

            }

        }

    });

    $(".pincode").each(function() {

        var value = $(this).val().trim();

        if (value.length > 0 && !(edu__validatePincode(value))) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = title + " should be 6 digits";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $(".mobileno").each(function() {

        var value = $(this).val().trim();

        if (value.length > 0 && !edu__validateMobileNo(value)) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = title + " should be 10 digits";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $(".panno").each(function() {

        var value = $(this).val().trim();

        if (value!='' && value.length < 10) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = title + " should be 10 digits";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $(".emailid").each(function() {

        var value = $(this).val().trim();

        if (value.length > 0 && !edu__validateEmail(value)) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = title + " is not valid";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    $(".aadhar").each(function() {

        var value = $(this).val().trim();

        if (value.length > 0 && !edu__validateAadharNo(value)) {

            var title = $(this).prop('title');

            if (!title || title.length == 0) {

                title = $(this).prop('name');

            }

            var message = title + " is not valid";

            $(this).focus();

            edu__showErrorMessageDiv(message);

            validate = false;

            return false;

        }

    });

    

	$(".text_upper").each(function() {

        var value = $(this).val();

        if (value) {

            $(this).val(value.toUpperCase());

        }

    });

	

	$(".text_proper").each(function() {

        var value = $(this).val();

        if (value) {

            $(this).val(toTitleCase(value));

        }

    });

	

	

    $(".text_lower").each(function() {

        var value = $(this).val();

        if (value) {

            $(this).val(value.toLowerCase());

        }

    });

    if (!validate) {

        edu__enablebuttonbyjq("savebtn")

        return false;

    } else {

        return true;

    }

}





function toTitleCase(str) {

    return str.replace(/(?:^|\s)\w/g, function(match) {

        return match.toUpperCase();

    });

}

function blockSpecialChar(e) {

    var k = e.keyCode;

    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8   || (k >= 48 && k <= 57));

}



function edu__enablebuttonbyjq(name) {

    $("." + name).each(function() {

        $(this).removeProp("disabled")

    });

}



function edu__validateMobileNo(mobileNo) {

    var filter = /^\d{10}$/;

    if (filter.test(mobileNo)) {

        return true;

    } else {

        return false;

    }

}

$(document).on('keypress', '.inputTextBox', function(event) {

    var regex = new RegExp("^[a-zA-Z ]+$");

    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

    if (!regex.test(key)) {

        event.preventDefault();

        return false;

    }

});



function edu__validatePincode(pincode) {

    var filter = /^\d{6}$/;

    if (filter.test(pincode)) {

        return true;

    } else {

        return false;

    }

}



function edu__validateAadharNo(aadharNo) {

    var filter = /^\d{12}$/;

    if (filter.test(aadharNo)) {

        return true;

    } else {

        return false;

    }

}



function edu__validateEmail(emailAddress) {

    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if (filter.test(emailAddress)) {

        return true;

    } else {

        return false;

    }

}



function edu__getstudent_data(schoolurl, filter) {

    var url = schoolurl + "/appsibling?" + filter;

    $.ajax({

        url: url,

        type: 'GET',

        crossdomain: true,

        datatype: 'jsonp',

        success: function(data, textStatus, errorThrown) {

            getdata(data);

        },

        error: function(data, textStatus, errorThrown) {}

    });

}



function edu__application_form_validation(schoolurl, filter) {

    var url = schoolurl + "/mobapps/ValidateApplicationForm?" + filter;

    $.ajax({

        url: url,

        type: 'GET',

        crossdomain: true,

        datatype: 'jsonp',

        success: function(data, textStatus, errorThrown) {

            application_form_validation_response(data);

        },

        error: function(data, textStatus, errorThrown) {}

    });

}





//-------------------------upload image validation lessthen 1MB start---------------------------

function document_ext(str)

{

    var a = document.getElementById(str).value;

    var size2 = $('#'+str)[0].files[0].size; 

    var size = size2/1024;



        if(size>2048)

        {

            alert('File should be less than 2 MB');

            //$("#savebtn").hide();

           document.getElementById(str).value=''; 

        }

}

//-------------------------upload image validation lessthen 1MB end---------------------------

function detectIE(){
     
    try {
 
        var isIE = /*@cc_on!@*/false || !!document.documentMode;
 
        if ( ! isIE ) {
            // Fallback to UserAgent detection for IE
            if ( navigator.userAgent.indexOf("MSIE") > 0 ) {
                return true;    
            } else {
                return false;
            }
        }
 
        //return true;
        alert('Please use newer version of browser');
        $("#myform").removeAttr('action');
        $("#myform").hide();

 
    } catch(e) {
 
        var error = e.toString();
 
        console.log(error);
    }
}