/* This JS is used for handling any user actions*/

function getXmlHttpRequestObject() {
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        alert("Your Browser Sucks!");
    }
}

var xmlhttpReq = getXmlHttpRequestObject();

function showModals(modal_name, title) {
    $("#" + modal_name).modal("show");
    $("#" + modal_name + "Label").html(title);
}

// general javascript command code
$(document).ready(function () {
    //Disable cut copy paste
    $('body').bind('cut copy', function (e) {
        e.preventDefault();
    });

    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});

$(document).ready(function(){
    $('#availableStaffTable').DataTable({
        "bPaginate": false,
        "bSort": false,
        "scrollX": true
    });

    $('#registeredStaffTable').DataTable({
        "bPaginate": false,
        "bSort": false,
        "scrollX": true
    });

    $('#longDataTable').DataTable({
        "bPaginate": false,
        "bSort": false,
        "scrollX": true
    });

    $('#shortDataTable').DataTable({
        "bPaginate": false,
        "bSort": false,
        "scrollX": false
    });
});

$('#enrollBtn').click(function() {
    xmlhttpReq.open("GET", "/authentification/sessionCheck/isLogin", true);
    xmlhttpReq.send(null);
    xmlhttpReq.onreadystatechange = function () {
        if (xmlhttpReq.readyState == 4) {
            var str = xmlhttpReq.responseText.split("&nbsp;");
            if (str[0] == '1') {
                idCourseClass = $('#idCourseClassEnroll').val();
                userEnrollCourseClass(idCourseClass);
            } else if(str[0] == '0'){
                window.location = "/login";
            } else {
                $("#myWarningModalsBody").html(str[0]);
                showModals('myWarningModals', 'Warning');
            }
        }
    }
});

function userEnrollCourseClass(idCourseClass){
    xmlhttpReq.open("GET", "/userAction/enrollClass/"+idCourseClass, true);
    xmlhttpReq.send(null);
    xmlhttpReq.onreadystatechange = function () {
        if (xmlhttpReq.readyState == 4) {
            var str = xmlhttpReq.responseText.split("&nbsp;");
            if (str[0] == "success"){
                $("#myWarningModalsBody").html("You have been enrolled to this course successfully. Please go to your Dashboard to access this Class.");
                showModals('myWarningModals', 'Information');
            }else {
                $("#myWarningModalsBody").html(str[0]);
                showModals('myWarningModals', 'Warning');
            }
        }
    }
}

function addUnregisteredLecturer(idUserLogin){
    xmlhttpReq.open("GET", "/manageMember/lecturer/addLecturer/addMember/"+idUserLogin, true);
    xmlhttpReq.send(null);
    xmlhttpReq.onreadystatechange = function () {
        if (xmlhttpReq.readyState == 4) {
            var str = xmlhttpReq.responseText.split("&nbsp;");
            if (str[0] == 'success') {
                location.reload();
            } else {
                $("#myWarningModalsBody").html(str[0]);
                showModals('myWarningModals', 'Warning');
            }
        }
    }
}

    $('#btnRemovePublicUser').click( function(){
        return confirm("Do you want to delete this member?");
    });

    function loadDateTimePicker(id,dateFormat){
        return $('#'+id).datetimepicker({
            format: dateFormat,
            autoclose: true,
            todayBtn: true,
            startDate: "2013-02-14 10:00",
            minuteStep: 5
        });
    }